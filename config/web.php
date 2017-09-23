<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '123456',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => $params['auth_time_out'],
            'loginUrl' => ['login/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => 'your@mail.com',
                'password' => 'password',
                'port' => 'port',
            ],
        ],
        'raven' => [
            'class' => 'e96\sentry\ErrorHandler',
            'dsn' => 'https://b56f336f488d4aba884a7ad2175c6a30:a071d1f27634436196a126b0fd50dcc3@sentry.io/218061', // Sentry DSN
        ],
        'log' => [
            //'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'e96\sentry\Target',
                    'levels' => ['error', 'warning'],
                    'dsn' => 'https://b56f336f488d4aba884a7ad2175c6a30:a071d1f27634436196a126b0fd50dcc3@sentry.io/218061', // Sentry DSN
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => [
                        'position' => \yii\web\View::POS_HEAD
                    ],
                ],
            ],
            'appendTimestamp' => true,
        ],
    ],
    'params' => $params,
    'timeZone' => 'Asia/Shanghai',
    'defaultRoute' => 'index',
    'layout' => 'layout',
    'modules' => [
        'person' => [
            'class' => 'app\modules\person\PersonModule',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
