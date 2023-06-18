<?php

namespace dev\components;

use yii\mongodb\Connection;

class MongodbComponent extends Connection {

    public $dsn = 'mongodb://evaluation:films@mongo/films';

}