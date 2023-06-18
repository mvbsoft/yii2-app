<?php

/**
 * Initializes Extensions for PostgreSQL tables.
 *
 * @author Vitaliy Malinovsyi <malina.mvb@gmail.com>2.0
 */
class m100000_100000_install_extensions extends extenders\Migration
{
    public function up()
    {
        if($this->db->driverName === 'pgsql'){
            $this->execute('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        }
    }
    public function down(): bool
    {
        return true;
    }
}
