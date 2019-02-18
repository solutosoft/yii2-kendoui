<?php

$console = require(__DIR__ . '/console.php');

return  yii\helpers\ArrayHelper::merge($console, [
    'class' => 'yii\web\Application',
    'id' => 'yii2-kendoui-web-test',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'TPweYCnTCow7EwZlCkjOYsSL',
            'scriptFile' => __DIR__ .'/index.php',
            'scriptUrl' => '/index.php',
        ]
    ],
]);
