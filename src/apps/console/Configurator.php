<?php

namespace console;

class Configurator extends \extenders\Configurator {

    public string $id = 'app-console';
    public string $timeZone = 'UTC';

    public string $controllerNamespace = 'apps\console\controllers';

    public function keyBootstrap(): array
    {
        return ['log'];
    }

    public function keyParams(): array
    {
        return [];
    }

}