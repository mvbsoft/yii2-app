<?php

namespace dev;

class Configurator extends \extenders\Configurator {

    public string $id = 'app-dev';
    public string $timeZone = 'UTC';

    public string $controllerNamespace = 'apps\dev\controllers';

    public array $aliases = [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
    ];

    public function keyLayoutPath(): string
    {
        return $this->keyBasePath() . '/layouts';
    }

    public function keyBootstrap(): array
    {
        return ['log', 'debug', 'gii'];
    }

    public function keyParams(): array
    {
        return [];
    }

}