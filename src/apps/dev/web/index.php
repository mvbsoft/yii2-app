<?php

use dev\Configurator;

$basePath = dirname(__DIR__, 3);

require $basePath . '/constants.php';
require $basePath . '/vendor/autoload.php';
require $basePath . '/vendor/yiisoft/yii2/Yii.php';
require $basePath . '/aliases.php';

$configs = Configurator::build();

(new yii\web\Application($configs))->run();