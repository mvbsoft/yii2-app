<?php

namespace extenders;

/**
 *
 * @property-read string $rawSql
 * @property-read string $sqlHashesCacheKey
 * @property-read string $sqlHash
 */
abstract class ActiveQuery extends \yii\db\ActiveQuery {

    public string $tableName;

    /**
     * ActiveQuery constructor.
     * @param string $modelClass
     * @param array $config
     */
    public function __construct(string $modelClass, $config = [])
    {
        parent::__construct($modelClass, $config);

        /** @var ActiveRecord $modelClass */
        $this->tableName  = $modelClass::tableName();
    }

}