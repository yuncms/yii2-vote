# Action 事件

If you need to add or extend functionality (before or after vote action) you can attach action events.

1. 通过添加您的投票控制器来修改模块配置 (`controllerMap`):

```php
 'modules' => [
    'vote' => [
        class' => 'yuncms\vote\Module',
            'controllerMap' => [
                'default' => 'app\controllers\MyVoteController', // here
            ],
            'entities' => [
                'itemLike' => [
                    'modelName' => app\models\Item::className(),
                    'type' => hauntd\vote\Module::TYPE_TOGGLE,
                ],
                'itemFavorite' => [
                    'modelName' => app\models\Item::className(),
                    'type' => hauntd\vote\Module::TYPE_TOGGLE,
                ],
            ],
        ],
    ],
```

2. 添加 Action `yuncms\vote\actions\VoteAction` 到你的控制器:

```php
<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yuncms\vote\actions\VoteAction;
use yuncms\vote\events\VoteActionEvent;

class MyVoteController extends Controller
{
    public function actions()
    {
        return [
            'vote' => [
                'class' => VoteAction::className(),
                'on ' . VoteAction::EVENT_BEFORE_VOTE => function(VoteActionEvent $event) {
                    $event->responseData['before'] = microtime(true);
                    if (Yii::$app->request->userIP == '192.168.0.23') {
                        throw new ForbiddenHttpException('You have no power here.');
                    }
                },
                'on ' . VoteAction::EVENT_AFTER_VOTE => function(VoteActionEvent $event) {
                    $event->responseData['after'] = microtime(true);
                    if ($event->voteForm->validate()) {
                        Yii::$app->cache->delete('someCache');
                    }
                },
            ],
        ];
    }
}
```
