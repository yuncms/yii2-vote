<?php
return [
    'id' => 'vote-test-app',
    'class' => 'yii\console\Application',
    'basePath' => \Yii::getAlias('@tests'),
    'runtimePath' => \Yii::getAlias('@tests/_output'),
    'bootstrap' => [
        'yuncms\vote\components\VoteBootstrap',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            //自动应答
            'interactive' => 0,
            //命名空间
            'migrationNamespaces' => [

                'yuncms\vote\migrations',

            ],
            // 完全禁用非命名空间迁移
            //'migrationPath' => null,
        ],
        'components' => [
            'db' => [
                'class' => '\yii\db\Connection',
                'dsn' => 'sqlite:' . \Yii::getAlias('@tests/_output/temp.db'),
                'username' => '',
                'password' => '',
            ],
            'cache' => [
                'class' => 'yii\caching\DummyCache',
            ],
        ],
        'modules' => [
            'vote' => [
                'class' => 'yuncms\vote\Module',
                'allowGuests' => true,
                'allowChangeVote' => true,
                'models' => [
                    '255' => \tests\unit\mocks\FakeModel::className(),
                    '256' => [
                        'model' => \tests\unit\mocks\FakeModel2::className(),
                        'allowGuests' => true,
                        'allowChangeVote' => true,
                    ],
                    '257' => [
                        'model' => \tests\unit\mocks\FakeModel3::className(),
                        'allowGuests' => false,
                        'allowChangeVote' => false,
                    ]
                ],
            ],
        ],
    ];
