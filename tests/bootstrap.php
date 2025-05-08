<?php

require dirname(__DIR__) . '/vendor/autoload.php';

// Disable Yii2 error handler
define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_ENV', 'test');
define('TEST_DIR', __DIR__);
define('TEST_APP_DIR', __DIR__ . '/app');
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';
