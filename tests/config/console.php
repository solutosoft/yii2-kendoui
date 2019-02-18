<?php

return  [
    'class' => 'yii\console\Application',
    'id' => 'yii2-kendoui-console-test',
    'basePath' => dirname(__DIR__),
    'vendorPath' => __DIR__ . '/../../vendor',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite::memory:'
        ],
    ],
];
