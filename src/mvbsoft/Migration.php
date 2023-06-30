<?php

namespace mvbsoft;

use yii\base\NotSupportedException;
use yii\db\ColumnSchemaBuilder;

class Migration extends \yii\db\Migration
{
    public string $tableName;

    public array $foreignKeys = [];

    public array $indexes = [];

    /**
     * @return ColumnSchemaBuilder
     * @throws NotSupportedException
     */
    public function jsonb(): ColumnSchemaBuilder
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('jsonb');
    }

    /**
     * @return ColumnSchemaBuilder
     * @throws NotSupportedException
     */
    public function inet(): ColumnSchemaBuilder
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('inet');
    }

    /**
     * @return ColumnSchemaBuilder
     * @throws NotSupportedException
     */
    public function uuid(): ColumnSchemaBuilder
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid');
    }
    /**
     * @return ColumnSchemaBuilder
     * @throws NotSupportedException
     */
    public function box(): ColumnSchemaBuilder
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('box');
    }

    public function createForeignKeys()
    {
        if(isset($this->foreignKeys)) foreach ($this->foreignKeys as $fk)
        {
            $this->addForeignKey(
                $fk['name'],
                $this->tableName,
                $fk['columns'],
                $fk['refTable'],
                $fk['refColumns'],
                $fk['delete'],
                $fk['update']
            );
        }
    }

    public function createIndexes()
    {
        if(isset($this->indexes)) foreach ($this->indexes as $index)
        {
            $this->createIndex("idx-".self::cTableName($this->tableName)."-$index", $this->tableName, $index);
        }
    }

    public function dropForeignKeys()
    {
        if(isset($this->foreignKeys)) foreach ($this->foreignKeys as $fk)
        {
            $this->dropForeignKey($fk['name'], $this->tableName);
        }
    }

    public function dropIndexes()
    {
        if(isset($this->indexes)) foreach ($this->indexes as $index)
        {
            $this->dropIndex("idx-".self::cTableName($this->tableName)."-$index", $this->tableName);
        }
    }

    protected static function cTableName($tableName): string
    {
        return str_replace(['{','%','}'], '', str_replace('.', '--', $tableName));
    }

    public function createTable($table, $columns, $options = null)
    {
        parent::createTable($table, $columns, $options);

        $this->createForeignKeys();
        $this->createIndexes();

        if(array_key_exists('updated_at', $columns)){
            $this->execute("CREATE TRIGGER auto_updated_at BEFORE UPDATE ON $table FOR EACH ROW EXECUTE PROCEDURE auto_updated_at();");
        }

    }

    public function dropTable($table)
    {
        $this->dropForeignKeys();
        $this->dropIndexes();

        parent::dropTable($table);
    }

    public function endCommand($time)
    {
        parent::endCommand($time);
    }

}
