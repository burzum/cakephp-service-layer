<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('ROOT', dirname(__DIR__));
define('APP_DIR', 'src');
// Point app constants to the test app.
define('TEST_ROOT', ROOT . DS . 'tests' . DS . 'test_app' . DS);
define('APP', TEST_ROOT . APP_DIR . DS);
define('TEST_FILES', ROOT . DS . 'tests' . DS . 'test_files' . DS);
define('TMP', ROOT . DS . 'tmp' . DS);
if (!is_dir(TMP)) {
    mkdir(TMP, 0770, true);
}
define('CONFIG', ROOT . DS . 'config' . DS);
define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT . '/vendor/cakephp/cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . APP_DIR . DS);

require dirname(__DIR__) . '/vendor/autoload.php';
require CORE_PATH . 'config/bootstrap.php';

Configure::write('App', [
    'namespace' => 'App',
]);
Configure::write('debug', true);

$cache = [
    'default' => [
        'engine' => 'File',
    ],
    '_cake_core_' => [
        'className' => 'File',
        'prefix' => 'crud_myapp_cake_core_',
        'path' => CACHE . 'persistent/',
        'serialize' => true,
        'duration' => '+10 seconds',
    ],
    '_cake_model_' => [
        'className' => 'File',
        'prefix' => 'crud_my_app_cake_model_',
        'path' => CACHE . 'models/',
        'serialize' => 'File',
        'duration' => '+10 seconds',
    ],
];
Cache::setConfig($cache);

// Ensure default test connection is defined
if (!getenv('db_class')) {
    putenv('db_class=Cake\Database\Driver\Sqlite');
    putenv('db_dsn=sqlite::memory:');
}

Cake\Datasource\ConnectionManager::setConfig('test', [
    'className' => 'Cake\Database\Connection',
    'driver' => getenv('db_class') ?: null,
    'dsn' => getenv('db_dsn') ?: null,
    'timezone' => 'UTC',
    'quoteIdentifiers' => true,
    'cacheMetadata' => true,
]);
