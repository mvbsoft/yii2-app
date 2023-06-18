<?php

namespace dev\components;

use yii\web\User;

class UserComponent extends User {

    public $identityClass = 'identityClass';

    public $enableAutoLogin = true;

    public $identityCookie = ['name' => '_identity-app-dev', 'httpOnly' => true];

}