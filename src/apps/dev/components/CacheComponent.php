<?php

namespace dev\components;

use yii\queue\redis\Queue;

class CacheComponent extends Queue {

    public $redis = [
        'class' => 'yii\redis\Connection',
        'hostname' => 'redis',
        'port' => 6379,
        'retries' => 1,
        'password' => 'ReD1S_%%_sErVeR_&at*(#Ion',
        'database' => 2
    ];

}