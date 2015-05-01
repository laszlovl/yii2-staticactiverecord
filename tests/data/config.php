<?php
$config = [
    'databases' => [
        'mysql' => [
            'dsn' => 'mysql:host=127.0.0.1;dbname=yiitest',
            'username' => 'travis',
            'password' => '',
            'fixture' => __DIR__ . '/../../vendor/yiisoft/yii2-dev/tests/unit/data/mysql.sql',
        ]
    ],
];

if (is_file(__DIR__ . '/config.local.php')) {
    include(__DIR__ . '/config.local.php');
}
return $config;
