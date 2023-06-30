<?php

namespace mvbsoft;

use yii\rest\Controller;

class ApiController extends Controller
{
    public $serializer = [
        'class' => '\mvbsoft\Serializer',
        'collectionEnvelope' => 'items'
    ];

    public $enableCsrfValidation = false;

}
