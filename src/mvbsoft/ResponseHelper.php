<?php

namespace mvbsoft;

use Yii;
use yii\base\Event;
use yii\web\Response;

class ResponseHelper {

    public static function generate(Event $event): Response
    {
        /** @var Response $response */
        $response = $event->sender;

        $response->headers->set('Access-Control-Allow-Origin', ['*']);
        $response->headers->set('Access-Control-Request-Method', ['PUT', 'GET', 'HEAD', 'OPTIONS', 'DELETE', 'POST', 'PATCH']);
        $response->headers->set('Access-Control-Request-Headers', ['*']);
        $response->headers->set('Access-Control-Allow-Credentials', true);
        $response->headers->set('Access-Control-Max-Age', 3600);

        if ($response->data !== null && !$response->isSuccessful && isset($response->data['status'])) {
            $response->statusCode = $response->data['status'];
            $response->data = self::responseTemplate([
                'success' => false,
                'code' => Yii::$app->response->statusCode,
                'errors' => $response->data['message']
            ]);
        }
        elseif (Yii::$app->response->statusCode == 422 || Yii::$app->response->statusCode == 401){
            $response->data = self::responseTemplate([
                'success' => false,
                'code' => Yii::$app->response->statusCode,
                'errors' => $response->data
            ]);
        }
        else{
            $response->data = self::responseTemplate([
                'success' => true,
                'code' => Yii::$app->response->statusCode,
                'result' => $response->data
            ]);
        }

        return $response;
    }

    public static function responseTemplate($params)
    {
        if(in_array(Yii::$app->response->format, [Response::FORMAT_RAW, Response::FORMAT_HTML])){
            return $params['result'] ?? '';
        }

        return [
            'success'   => boolval($params['success'] ?? false),
            'code'      => $params['code']    ?? null,
            'result'    => $params['result']  ?? null,
            'errors'    => $params['errors']  ?? null
        ];
    }
}