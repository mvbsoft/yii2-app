<?php

namespace extenders;

use yii\base\Arrayable;
use yii\base\Model;
use yii\data\DataProviderInterface;

class Serializer extends \yii\rest\Serializer
{
    public function serialize($data)
    {
        if($data instanceof Model && $data->hasErrors())
        {
            return $this->serializeModelErrors($data);
        }
        elseif ($data instanceof Arrayable)
        {
            return $this->serializeModel($data);
        }
        elseif ($data instanceof DataProviderInterface)
        {
            $data = $this->serializeDataProvider($data);

            if(array_key_exists('_links', $data)){
                unset($data['_links']);
            }

            return $data;
        }
        elseif(is_array($data)){
            return $this->_serializeDeep($data);
        }
        else{
            return $data;
        }
    }

    private function _serializeDeep($data): ?array
    {
        foreach ($data as $key => $value) {
            if($value instanceof Model && $value->hasErrors())
            {
                $data[$key] = $this->serializeModelErrors($value);
            }
            elseif ($value instanceof Arrayable)
            {
                $data[$key] = $this->serializeModel($value);
            }
            elseif ($value instanceof DataProviderInterface)
            {
                $result = $this->serializeDataProvider($value);

                if(array_key_exists('_links', $result)){
                    unset($result['_links']);
                }

                $data[$key] = $result;
            }
            elseif(is_array($value)){
                $data[$key] = $this->_serializeDeep($value);
            }
        }

        return $data;
    }
}
