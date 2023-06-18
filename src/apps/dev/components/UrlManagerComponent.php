<?php

namespace dev\components;

use yii\web\UrlManager;

class UrlManagerComponent extends UrlManager {

    public $enablePrettyUrl = true;

    public $enableStrictParsing = true;

    public $showScriptName = false;

    public $rules = [
        'GET /' => 'index/index'
    ];

}