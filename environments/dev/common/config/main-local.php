<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.1.22;dbname=chepartner',
            'username' => 'chepartner',
            'password' => 'chepartner',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'keyPrefix' => 'E2B:'
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '192.168.1.16',
            'port' => 6379,
            'database' => 2
        ],
    ],
];
