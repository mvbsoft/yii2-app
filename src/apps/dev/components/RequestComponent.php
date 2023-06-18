<?php

namespace dev\components;

use yii\web\Request;

class RequestComponent extends Request {

    public $csrfParam = 'csrf-app-dev';

    public $cookieValidationKey = 'cookie_validation_key_dev';

}