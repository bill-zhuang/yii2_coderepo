<?php

$config = [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ],

        // ... other components
    ],
];

return $config;