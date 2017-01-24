<?php
return [
    'id' => 'vote-test-app',
    'class' => 'yii\console\Application',
    'basePath' => \Yii::getAlias('@tests'),
    'runtimePath' => \Yii::getAlias('@tests/_output'),
    'bootstrap' => [
        'yuncms\vote\components\VoteBootstrap',
    ],
    'components' => [
        'db' => [
            'class' => '\yii\db\Connection',
            'dsn' => 'sqlite:'.\Yii::getAlias('@tests/_output/temp.db'),
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
                255 => \tests\unit\mocks\FakeModel::className(),
                256 => [
                    'modelName' => \tests\unit\mocks\FakeModel2::className(), 
                    'allowGuests' => true,
                    'allowChangeVote' => true,
                ],
                [
                    'modelName' => \tests\unit\mocks\FakeModel3::className(),
                    'allowGuests' => false,
                    'allowChangeVote' => false,
                ]
            ],      
        ],
    ],
];
