<?php

namespace dev\components;

use yii\log\Dispatcher;

class LogComponent extends Dispatcher {

    public $traceLevel = YII_DEBUG ? 3 : 0;

    public $targets = [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning']
        ]
    ];
}