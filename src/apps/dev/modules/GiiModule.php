<?php

namespace dev\modules;

use Yii;
use yii\gii\Module;

class GiiModule extends Module {

    public $allowedIPs = ['*'];

    public function getViewPath(): bool|string
    {
        return Yii::getAlias('@vendor/yiisoft/yii2-gii/src/views');
    }

}