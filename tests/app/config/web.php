<?php

return [
    'id' => 'test-app',
    'basePath' => dirname(__DIR__),
    'container' => [
        'definitions' => [
            'foo' => 'bar'
        ],
    ],
    'components' => [
        'urlManager' => [
            'rules' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
        ],
    ],
];
