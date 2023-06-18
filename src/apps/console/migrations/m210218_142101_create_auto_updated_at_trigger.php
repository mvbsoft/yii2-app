<?php

use yii\db\Migration;

/**
 * Class m210218_222101_create_auto_updated_at_trigger
 */
class m210218_142101_create_auto_updated_at_trigger extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): bool
    {
        $this->execute("
            CREATE OR REPLACE FUNCTION auto_updated_at()   
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.updated_at = CURRENT_TIMESTAMP;
                RETURN NEW;   
            END;
            $$ language 'plpgsql';
        ");

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): bool
    {
        return true;
    }

}
