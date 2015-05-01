<?php
return [
    'id'                    => 'staticactiverecord-benchmark',
    'basePath'              => dirname(__DIR__),
    'controllerNamespace'   => 'app\commands',
    'components' => [
        'db' => [
            'class'     => 'yii\db\Connection',
            'dsn'       => 'sqlite:data/benchmark.db',
            'charset'   => 'utf8',
        ]
    ]
];
