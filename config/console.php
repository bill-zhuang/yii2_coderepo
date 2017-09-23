<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii', 'raven'],
    'controllerNamespace' => 'app\console\controllers',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'raven' => [
            'class' => 'e96\sentry\ErrorHandler',
            'dsn' => 'https://b56f336f488d4aba884a7ad2175c6a30:a071d1f27634436196a126b0fd50dcc3@sentry.io/218061', // Sentry DSN
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'e96\sentry\Target',
                    'levels' => ['error', 'warning'],
                    'dsn' => 'https://b56f336f488d4aba884a7ad2175c6a30:a071d1f27634436196a126b0fd50dcc3@sentry.io/218061', // Sentry DSN
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
    ],
    'params' => $params,
];
