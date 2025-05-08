<?php

declare(strict_types=1);

$config = [
    'includes' => [
        __DIR__ . '/vendor/phpstan/phpstan-phpunit/extension.neon',
        __DIR__ . '/vendor/phpstan/phpstan-phpunit/rules.neon',
        __DIR__ . '/vendor/phpstan/phpstan-symfony/extension.neon',
        __DIR__ . '/phpstan-baseline.neon',
    ],
    'parameters' => [
        'phpVersion' => PHP_VERSION_ID,
        'level' => 6,
        'bootstrapFiles' => [
            __DIR__ . '/vendor/yiisoft/yii2/Yii.php',
        ],
    ],
];

if (PHP_VERSION_ID >= 80000) {
    $config['includes'][] = __DIR__ . '/phpstan-baseline-gt-80.neon';
}

return $config;
