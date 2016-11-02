# Vote 行为

In case when app renders a lot of some items (models) and you need to attach vote module to each it's better to add vote behaviors to your model.

With this behaviors you'll decrease the count of sql queries dramatically.

For this you have to include these behaviors:

- *VoteBehavior*: 允许您获得投票数据（投票计数，用户投票状态）。
- *VoteQueryBehavior*: 允许您在您的查询中包括投票搜索条件。


## 配置

Imagine that you have model **Item** (`app\models\Item.php`):

```php
<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yuncms\vote\behaviors\VoteBehavior;

class Item extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%item}}';
    }

    public function behaviors()
    {
        return [
            VoteBehavior::className(), // add VoteBehavior class to your model
        ];
    }

    public static function find()
    {
        return new ItemQuery(get_called_class()); // override find() method
    }
}
```

如果你没有 `ItemQuery` 类 (or other query class for you model) - create new one and attach **VoteQueryBehavior**:

```php
<?php

namespace app\models;

use yii\db\ActiveQuery;
use yuncms\vote\behaviors\VoteQueryBehavior;

class ItemQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            VoteQueryBehavior::className(),
        ];
    }
}
```

之后你可以使用 `withVoteAggregate($entity)` 和 `withUserVote($entity)` 查询方法

## 离职

`app/controllers/ItemsController.php`:

```php
<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\Item;

class ItemsController extends Controller
{
    public function actionIndex()
    {
        $query = Item::find();

        foreach (['itemVote', 'itemFavorite'] as $entity) {
            $query->withVoteAggregate($entity); // include votes and favorites
            $query->withUserVote($entity); // include user vote status
        }

        /**
         * After attaching behaviors, you'll get access to new attributes - positive, negative and rating
         * So, if you have 'itemVote' entity, you should use 'itemVotePositive', 'itemVoteNegative' and
         * 'itemVoteRating' attributes.
         *
         * For example:
         */
        $query->orderBy('itemVoteRating desc');
        // or
        $query->orderBy('itemFavoritePositive desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ]
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
}
```

`app/views/items/index.php`:

```php
<?= \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items} {pager}',
    'itemView' => '_item',
]) ?>
```

`app/views/items/_item.php`:

```php
<div class="item">
    <div class="item-content">
        <?= \yii\helpers\Html::encode($model->content) ?>
    </div>
    <div class="item-buttons'>
        <?= \yuncms\vote\widgets\Vote::widget([
            'entity' => 'itemVote',
            'model' => $model,
        ]); ?>
        <?= \yuncms\vote\widgets\Favorite::widget([
            'entity' => 'itemFavorite',
            'model' => $model,
        ]); ?>
    </div>
</div>
```
