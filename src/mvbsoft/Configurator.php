<?php

namespace mvbsoft;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

class Configurator {

    public string $id = 'app';
    public string $timeZone = 'UTC';

    public string $controllerNamespace = '';

    public function keyVendorPath(): string
    {
        return dirname(__DIR__) . '/vendor';
    }

    public function keyBasePath(): string
    {
        return dirname($this->getFileName());
    }

    protected function getAppFolderName() : string {
        $classFile = (new ReflectionClass(static::class))->getFileName();

        return basename(dirname($classFile));
    }

    protected function getFileName(): bool|string
    {
        return (new ReflectionClass(static::class))->getFileName();
    }

    private function _getClasses(string $type, string $folderPath, string $namespace): array
    {
        $results = [];

        if(is_dir($folderPath)){
            $files = scandir($folderPath);

            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $filePath = $folderPath . '/' . $file;

                if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
                    $results[] = [
                        'path' => $filePath,
                        'name' => lcfirst(str_replace("$type.php", '', $file)),
                        'namespace' => str_replace('.php', '', $namespace . '\\' . $file)
                    ];
                }
            }

        }

        return $results;
    }

    /**
     * @throws ReflectionException
     */
    private function _prepareConfigs(string $type, string $path, string $name): array
    {
        $configs = [];

        $configFiles = self::_getClasses($type, $path, $name);

        foreach ($configFiles as $configFile) {
            if (file_exists($configFile['path'])) {
                require_once $configFile['path'];
            }

            $classes = get_declared_classes();

            foreach ($classes as $class) {
                if (str_starts_with($class, $configFile['namespace'])) {
                    $configs[$configFile['name']]['class'] = $configFile['namespace'];

                    $allVariables = get_class_vars($class);

                    $reflection = new ReflectionClass($class);

                    $publicVariables = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

                    foreach ($publicVariables as $variable) {
                        $varName = $variable->getName();
                        $varClass = $variable->getDeclaringClass()->getName();

                        if ($varClass === $class) {
                            $configs[$configFile['name']][$varName] = $allVariables[$varName];
                        }
                    }
                }
            }
        }

        return $configs;
    }

    /**
     * @throws ReflectionException
     */
    private function _prepareBaseConfigs(self $obj): array
    {
        $configs = [];

        $reflectionClass = new ReflectionClass($obj);

        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyValue = $property->getValue($obj);
            $configs[$propertyName] = $propertyValue;
        }

        foreach ($methods as $method) {
            if(str_starts_with($method->name, 'key') && $method->getReturnType() != 'void'){
                $methodName = $method->getName();
                $methodValue = $method->invoke($obj);
                $key = lcfirst(str_replace('key', '', $methodName));
                $configs[$key] = $methodValue;
            }
        }

        return $configs;
    }

    private static function generateHash(string $appFolderName, string $configuratorFile, string $moduleConfigFolder, string $componentConfigFolder): string
    {
        $configuratorFileHash = md5($configuratorFile);
        $moduleConfigFolderHash = md5(implode('', array_map('md5_file', glob("$moduleConfigFolder/*"))));
        $componentConfigFolderHash = md5(implode('', array_map('md5_file', glob("$componentConfigFolder/*"))));

        return md5($appFolderName . $configuratorFileHash . $moduleConfigFolderHash . $componentConfigFolderHash);
    }

    private static function formatArray($array, $indent = 0): string
    {
        $result = "[\n";

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = self::formatArray($value, $indent + 1);
            } else {
                $value = var_export($value, true);
            }

            $result .= str_repeat('    ', $indent + 1) . "'$key' => $value,\n";
        }

        $result .= str_repeat('    ', $indent) . "]";

        return $result;
    }

    public static function build(): array
    {
        $configurator = new static();
        $appFolderName = $configurator->getAppFolderName();

        $configuratorFile = "/var/www/apps/$appFolderName/Configurator.php";
        $moduleConfigFolder = "/var/www/apps/$appFolderName/modules";
        $componentConfigFolder = "/var/www/apps/$appFolderName/components";

        $currentVersion = self::generateHash($appFolderName, $configuratorFile, $moduleConfigFolder, $componentConfigFolder);

        $configFilename = "/var/www/apps/$appFolderName/configs/$currentVersion.php";

        var_dump($currentVersion);

        if (file_exists($configFilename)) {
            $configs = include $configFilename;
        } else {
            try {
                $configs = $configurator->_prepareBaseConfigs($configurator);

                $appFolderName = $configurator->getAppFolderName();

                $configs['modules'] = $configurator->_prepareConfigs('Module', $moduleConfigFolder, "$appFolderName\\modules");

                $configs['components'] = $configurator->_prepareConfigs('Component', $componentConfigFolder, "$appFolderName\\components");

                $configDir = dirname($configFilename);

                if (!is_dir($configDir)) {
                    mkdir($configDir, 0755, true);
                }

                file_put_contents($configFilename, '<?php return ' . self::formatArray($configs) . ';');
            } catch (ReflectionException $e) {
                exit("Invalid configuration: " . $e->getMessage());
            }
        }

        return $configs;
    }

}