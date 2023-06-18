<?php

namespace dev\components;

use yii\db\Connection;

class DbComponent extends Connection {

    public $dsn = 'pgsql:host=postgres;dbname=mvbsoft';
    public $username = 'mvbsoft';
    public $password = 'mvbsoft@postgres';

    public $charset = 'utf8';
    public $enableSchemaCache =  true;
    public $schemaCacheDuration = 3600;
    public $enableQueryCache = true;
    public $queryCacheDuration = 3600;

}