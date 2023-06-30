<?php

namespace mvbsoft;

use Yii;

class CreateAppForm extends Model {

    public mixed $appName;

    public mixed $appType;

    public const APP_TYPE_API = 'api';

    public const APP_TYPE_WEB = 'web';

    public const APP_TYPE_CONSOLE = 'console';

    public function rules(): array
    {
        return [
            [['appName', 'appType'], 'required'],
            [['appName'], 'string', 'max' => 20],
            [['appType'], 'in', 'range' => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE]],
            [['appName'], 'validateAppName']
        ];
    }

    public function create(): bool|static
    {
        if(!$this->validate()){
            return $this;
        }

        $appPath = "/var/www/apps/$this->appName";

        $this->_createAppFolder($appPath);

        $this->_updateAliases();

        $this->_createAppStruct($this->_struct(), $this->_getAppPath());

        return true;
    }
    private function _updateAliases(){
        $filePath = "/var/www/aliases.php";

        $fileContent = file_get_contents("/var/www/aliases.php");

        $fileContent .= "\nYii::setAlias('@dev', __DIR__ . '/apps/$this->appName');";

        file_put_contents($filePath, $fileContent);
    }
    private function  _struct(): array
    {
        return [
            [
                "type" => "file",
                "name" => $this->appName,
                "path" => '/var/www',
                "appType" => [self::APP_TYPE_CONSOLE],
                "generator" => "generateConsoleExecutableFile"
            ],
            [
                "type" => "file",
                "name" => "Configurator.php",
                "appType" => [self::APP_TYPE_CONSOLE],
                "generator" => "generateConsoleConfigurator"
            ],
            [
                "type" => "file",
                "name" => "Configurator.php",
                "appType" => [self::APP_TYPE_WEB],
                "generator" => "generateWebConfigurator"
            ],
            [
                "type" => "file",
                "name" => "Configurator.php",
                "appType" => [self::APP_TYPE_API],
                "generator" => "generateApiConfigurator"
            ],
            [
                "type" => "dir",
                "name" => "web",
                "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB],
                "attachments" => [
                    [
                        "type" => "dir",
                        "name" => "assets",
                        "appType" => ["web"],
                        "attachments" => [
                            [
                                "type" => "file",
                                "name" => ".gitignore",
                                "appType" => ["web"],
                                "generator" => "generateGitignore"
                            ],
                            [
                                "type" => "file",
                                "name" => "index.php",
                                "appType" => [self::APP_TYPE_API, "web"],
                                "generator" => "generateWebIndex"
                            ]
                        ]
                    ]
                ]
            ],
            [
                "type" => "dir",
                "name" => "views",
                "appType" => [self::APP_TYPE_WEB],
                "attachments" => [
                    [
                        "type" => "dir",
                        "name" => "default",
                        "appType" => [self::APP_TYPE_WEB],
                        "attachments" => [
                            [
                                "type" => "file",
                                "name" => "default.php",
                                "appType" => [self::APP_TYPE_WEB],
                                "generator" => "generateViewDefault"
                            ]
                        ]
                    ]
                ]
            ],
            [
                "type" => "dir",
                "name" => "runtime",
                "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                "attachments" => [
                    [
                        "type" => "file",
                        "name" => ".gitignore",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                        "generator" => "generateGitignore"
                    ]
                ]
            ],
            [
                "type" => "dir",
                "name" => "modules",
                "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                "attachments" => [
                    [
                        "type" => "file",
                        "name" => "DebugModule.php",
                        "appType" => [self::APP_TYPE_WEB],
                        "generator" => "generateDebugModule"
                    ],
                    [
                        "type" => "file",
                        "name" => "GiiModule.php",
                        "appType" => [self::APP_TYPE_WEB],
                        "generator" => "generateGiiModule"
                    ]
                ]
            ],
            [
                "type" => "dir",
                "name" => "models",
                "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                "attachments" => [
                    [
                        "type" => "file",
                        "name" => ".gitkeepmarker",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                        "generator" => "generateGitkeepmarker"
                    ]
                ]
            ],
            [
                "type" => "dir",
                "name" => "layouts",
                "appType" => [self::APP_TYPE_WEB],
                "attachments" => [
                    [
                        "type" => "file",
                        "name" => "main.php",
                        "appType" => [self::APP_TYPE_WEB],
                        "generator" => "generateLayout"
                    ]
                ]
            ],
            [
                "type" => "dir",
                "name" => "controllers",
                "appType" => [self::APP_TYPE_WEB],
                "attachments" => [
                    [
                        "type" => "file",
                        "name" => "DefaultController.php",
                        "appType" => [self::APP_TYPE_WEB],
                        "generator" => "generateDefaultWebController"
                    ],
                    [
                        "type" => "file",
                        "name" => "DefaultController.php",
                        "appType" => [self::APP_TYPE_API],
                        "generator" => "generateDefaultApiController"
                    ],
                    [
                        "type" => "file",
                        "name" => "DefaultController.php",
                        "appType" => [self::APP_TYPE_CONSOLE],
                        "generator" => "generateDefaultConsoleController"
                    ]
                ]
            ],
            [
                "type" => "dir",
                "name" => "components",
                "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                "attachments" => [
                    [
                        "type" => "file",
                        "name" => "CacheComponent.php",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                        "generator" => "generateCacheComponent"
                    ],
                    [
                        "type" => "file",
                        "name" => "DbComponent.php",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                        "generator" => "generateDbComponent"
                    ],
                    [
                        "type" => "file",
                        "name" => "LogComponent.php",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                        "generator" => "generateLogComponent"
                    ],
                    [
                        "type" => "file",
                        "name" => "MongodbComponent.php",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                        "generator" => "generateMongodbComponent"
                    ],
                    [
                        "type" => "file",
                        "name" => "QueueComponent.php",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB, self::APP_TYPE_CONSOLE],
                        "generator" => "generateQueueComponent"
                    ],
                    [
                        "type" => "file",
                        "name" => "RequestComponent.php",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB],
                        "generator" => "generateRequestComponent"
                    ],
                    [
                        "type" => "file",
                        "name" => "SessionComponent.php",
                        "appType" => ["web"],
                        "generator" => "generateSessionComponent"
                    ],
                    [
                        "type" => "file",
                        "name" => "UrlManagerComponent.php",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB],
                        "generator" => "generateUrlManagerComponent"
                    ],
                    [
                        "type" => "file",
                        "name" => "UserComponent.php",
                        "appType" => [self::APP_TYPE_API, self::APP_TYPE_WEB],
                        "generator" => "generateUserComponent"
                    ]
                ]
            ]
        ];
    }

    private function _createFile($path, $generator)
    {
        if (method_exists($this, $generator)) {
            $content = $this->$generator();

            file_put_contents($path, $content);
        }
    }

    private function _createDirectory($path, $name)
    {
        $dirPath = $path . '/' . $name;

        mkdir($dirPath);
    }

    private function generateConsoleExecutableFile(): string
    {
        return '#!/usr/bin/env php
<?php

use '.$this->appName.'\Configurator;

$basePath = __DIR__;

require $basePath . \'/constants.php\';
require $basePath . \'/vendor/autoload.php\';
require $basePath . \'/vendor/yiisoft/yii2/Yii.php\';
require $basePath . \'/aliases.php\';

$configs = Configurator::build();

$application = new yii\console\Application($configs);

$exitCode = $application->run();

exit($exitCode);';
    }

    private function generateViewDefault(): string
    {
        return 'Default';
    }

    private function generateGitkeepmarker(): string
    {
        return '';
    }

    private function generateApiConfigurator(): string
    {
        return '<?php

namespace '.$this->appName.';
class Configurator extends \mvbsoft\Configurator {
    public string $id = \'app-'.$this->appName.'\';
    public string $controllerNamespace = \'apps\\'.$this->appName.'\controllers\';
    public function keyBootstrap(): array
    {
        return [\'log\'];
    }
    public function keyParams(): array
    {
        return [];
    }

}';
    }


    private function generateConsoleConfigurator(): string
    {
        return '<?php

namespace '.$this->appName.';
class Configurator extends \mvbsoft\Configurator {
    public string $id = \'app-'.$this->appName.'\';
    public string $controllerNamespace = \'apps\\'.$this->appName.'\controllers\';
    public function keyBootstrap(): array
    {
        return [\'log\'];
    }
    public function keyParams(): array
    {
        return [];
    }

}';
    }

    private function generateWebConfigurator(): string
    {
        return '<?php

namespace '.$this->appName.';
class Configurator extends \mvbsoft\Configurator {
    public string $id = \'app-'.$this->appName.'\';
    public string $controllerNamespace = \'apps\\'.$this->appName.'\controllers\';
    public array $aliases = [
        \'@bower\' => \'@vendor/bower-asset\',
        \'@npm\'   => \'@vendor/npm-asset\'
    ];
    public function keyLayoutPath(): string
    {
        return $this->keyBasePath() . \'/layouts\';
    }
    public function keyBootstrap(): array
    {
        return [\'log\', \'debug\', \'gii\'];
    }
    public function keyParams(): array
    {
        return [];
    }

}';
    }

    private function generateLayout(): string
    {
        return '<?php

/* @var $this View */
/* @var $content string */

use yii\helpers\Html;
use yii\web\View;

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body>
    <?php $this->beginBody() ?>

    <?= $content ?>

    <?php $this->endBody() ?>
    </body>

</html>

<?php $this->endPage() ?>';
    }

    private function generateDefaultConsoleController(): string
    {
        return '<?php

namespace '.$this->appName.'\controllers;

use mvbsoft\ConsoleController;

class DefaultController extends ConsoleController {
    public function actionDefault() : array {
        return [];
    }

}';
    }

    private function generateDefaultApiController(): string
    {
        return '<?php

namespace '.$this->appName.'\controllers;

use mvbsoft\ApiController;

class DefaultController extends ApiController {
    public function actionDefault() : array {
        return [];
    }

}';
    }

    private function generateDefaultWebController(): string
    {
        return '<?php

namespace '.$this->appName.'\controllers;

use mvbsoft\WebController;

class DefaultController extends WebController {
    public function actionDefault() : string {
        return $this->render(\'default\');
    }

}';
    }

    private function generateGiiModule(): string
    {
        return '<?php

namespace '.$this->appName.'\modules;

use Yii;
use yii\gii\Module;
class GiiModule extends Module {
    public $allowedIPs = [\'*\'];
    public function getViewPath(): bool|string
    {
        return Yii::getAlias(\'@vendor/yiisoft/yii2-gii/src/views\');
    }
    
}';
    }

    private function generateDebugModule(): string
    {
        return '<?php

namespace '.$this->appName.'\modules;

use yii\debug\Module;
class DebugModule extends Module {
    public $allowedIPs = [\'*\'];
    
}';
    }

    private function generateWebIndex(): string
    {
        return '<?php

use '.$this->appName.'\Configurator;

$basePath = dirname(__DIR__, 3);

require $basePath . \'/constants.php\';
require $basePath . \'/vendor/autoload.php\';
require $basePath . \'/vendor/yiisoft/yii2/Yii.php\';
require $basePath . \'/aliases.php\';

$configs = Configurator::build();

(new yii\web\Application($configs))->run();
';
    }

    private function generateGitignore(): string
    {
        return '*
!.gitignore';
    }

    private function generateCacheComponent(): string
    {
        return '<?php

namespace '.$this->appName.'\components;

use yii\redis\Cache;
class CacheComponent extends Cache {
    public $redis = [
        \'class\' => \'yii\redis\Connection\',
        \'hostname\' => \'redis\',
        \'port\' => 6379,
        \'retries\' => 1,
        \'password\' => \'ReD1S_%%_sErVeR_&at*(#Ion\',
        \'database\' => 2
    ];
    
}';
    }

    private function generateDbComponent(): string
    {
        return '<?php

namespace '.$this->appName.'\components;

use yii\db\Connection;
class DbComponent extends Connection {
    public $dsn = \'pgsql:host=postgres;dbname=mvbsoft\';
    public $username = \'mvbsoft\';
    public $password = \'mvbsoft_postgres\';
    public $charset = \'utf8\';
    public $enableSchemaCache =  true;
    public $schemaCacheDuration = 3600;
    public $enableQueryCache = true;
    public $queryCacheDuration = 3600;
    
}';
    }

    private function generateLogComponent(): string
    {
        return '<?php

namespace '.$this->appName.'\components;

use yii\log\Dispatcher;
class LogComponent extends Dispatcher {
    public $traceLevel = YII_DEBUG ? 3 : 0;
    public $targets = [
        [
            \'class\' => \'yii\log\FileTarget\',
            \'levels\' => [\'error\', \'warning\']
        ]
    ];
    
}';
    }

    private function generateMongodbComponent(): string
    {
        return '<?php

namespace '.$this->appName.'\components;

use yii\mongodb\Connection;
class MongodbComponent extends Connection {
    public $dsn = \'mongodb://mvbsoft:mvbsoft_mongo@mongo/mvbsoft\';
    
}';
    }

    private function generateQueueComponent(): string
    {
        return '<?php

namespace '.$this->appName.'\components;

use yii\queue\redis\Queue;
class QueueComponent extends Queue {
    public $redis = [
        \'class\' => \'yii\redis\Connection\',
        \'hostname\' => \'redis\',
        \'port\' => 6379,
        \'retries\' => 1,
        \'password\' => \'ReD1S_%%_sErVeR_&at*(#Ion\',
        \'database\' => 1
    ];
    
}';
    }

    private function generateRequestComponent(): string
    {
        return '<?php

namespace '.$this->appName.'\components;

use yii\web\Request;
class RequestComponent extends Request {
    public $csrfParam = \'csrf-app-dev\';
    public $cookieValidationKey = \'cookie_validation_key_dev\';
    
}';
    }

    private function generateSessionComponent(): string
    {
        return '<?php

namespace ' . $this->appName . '\components;

use yii\web\Session;
class SessionComponent extends Session {
    public $name = \'session-app-dev\';
    
}';
    }

    private function generateUrlManagerComponent(): string
    {
        return '<?php

namespace '.$this->appName.'\components;

use yii\web\UrlManager;
class UrlManagerComponent extends UrlManager {
    public $enablePrettyUrl = true;
    public $enableStrictParsing = true;
    public $showScriptName = false;
    public $rules = [
        \'GET /\' => \'default/default\'
    ];
    
}';
    }

    private function generateUserComponent(): string
    {
        return '<?php

namespace '.$this->appName.'\components;

use yii\web\User;
class UserComponent extends User {
    public $identityClass = \'identityClass\';
    public $enableAutoLogin = true;
    public $identityCookie = [\'name\' => \'_identity-app-dev\', \'httpOnly\' => true];
    
}';
    }

    private function _createAppStruct($struct, $path)
    {
        foreach ($struct as $item) {
            $type    = $item['type']    ?? null;
            $name    = $item['name']    ?? null;
            $path    = $item['path']    ?? $path;
            $appType = $item['appType'] ?? [];

            if ($type === 'dir' && in_array($this->appType, $appType)) {
                $this->_createDirectory($path, $name);

                $attachments = $item['attachments'] ?? [];

                if(!empty($attachments)){
                    $this->_createAppStruct($attachments, $path . '/' . $name);
                }
            }

            if ($type === 'file' && in_array($this->appType, $appType)) {
                $generator = $item['generator'];
                $this->_createFile($path . '/' . $name, $generator);
            }
        }
    }

    private function _getAppPath(): string
    {
        return "/var/www/apps/$this->appName";
    }

    private function _createAppFolder($path){
        mkdir($path);
    }

    public function validateAppName($attribute){
        $appName = $this->$attribute;

        if(is_dir(Yii::getAlias("@apps/$appName"))){
            $this->addError($attribute, 'An app with this name already exists');
        }
    }

}