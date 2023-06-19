<?php

namespace console\components;

use yii\mongodb\Connection;

class MongodbComponent extends Connection {

    public $dsn = 'mongodb://mvbsoft:mvbsoft_mongo@mongo/mvbsoft';

}