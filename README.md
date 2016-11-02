# Yii2-Vote 自用

修改自 [hauntd/yii2-vote](https://github.com/hauntd/yii2-vote),如果你想使用，请使用源码。谢谢！

[![Latest Version](https://img.shields.io/packagist/v/hauntd/yii2-vote.svg)](https://packagist.org/packages/hauntd/yii2-vote) [![License](https://poser.pugx.org/hauntd/yii2-vote/license.svg)](LICENSE.md) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/hauntd/yii2-vote/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/hauntd/yii2-vote/?branch=master) [![Code Climate](https://codeclimate.com/github/hauntd/yii2-vote/badges/gpa.svg)](https://codeclimate.com/github/hauntd/yii2-vote)

This module allows you to attach vote widgets, like/favorite buttons to your models.

![Demo](https://raw.githubusercontent.com/hauntd/resources/master/yii2-vote/output.gif)

- Attach as many widgets to model as you need
- Customization (action, events, views)
- Useful widgets included (Favorite button, Like button, Rating "up/down")

## 安装

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yuncms/yii2-vote "1.0.*"
```

or add

```
"yuncms/yii2-vote": "*"
```

to the require section of your `composer.json` file.

## 配置

添加模块配置到你的应用配置文件 (`config/main.php`).

Entity names should be in camelCase like `itemVote`, `itemVoteGuests`, `itemLike` and `itemFavorite`.

```php
return [
  'modules' => [
    'vote' => [
      'class' => 'yuncms\vote\Module',
        'guestTimeLimit' => 3600,
        'entities' => [
          // Entity -> Settings
          'itemVote' => app\models\Item::class, // your model
          'itemVoteGuests' => [
              'modelName' => app\models\Item::class, // your model
              'allowGuests' => true,
          ],
          'itemLike' => [
              'modelName' => app\models\Item::class, // your model
              'type' => hauntd\vote\Module::TYPE_TOGGLE, // like/favorite button
          ],
          'itemFavorite' => [
              'modelName' => app\models\Item::class, // your model
              'type' => hauntd\vote\Module::TYPE_TOGGLE, // like/favorite button
          ],
      ],
    ],
  ],
  'components' => [
    ...
  ]
];
```

下载并配置后 `yuncms/yii2-vote`, 您最后需要做的是通过应用迁移来更新数据库:

```
php yii migrate/up --migrationPath=@vendor/yuncms/yii2-vote/migrations/
```

## 使用

Vote widget:

```php
<?= \yuncms\vote\widgets\Vote::widget([
  'entity' => 'itemVote',
  'model' => $model,
  'options' => ['class' => 'vote vote-visible-buttons']
]); ?>
```

Like/Favorite widgets:

```php
<?= \hauntd\vote\widgets\Favorite::widget([
    'entity' => 'itemFavorite',
    'model' => $model,
]); ?>

<?= \hauntd\vote\widgets\Like::widget([
    'entity' => 'itemLike',
    'model' => $model,
]); ?>
```

## 更改日志

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## 文档

* [Vote behaviors](docs/behaviors.md)
* [Action events](docs/action-events.md)
* [Customization](docs/customization.md)

## License

BSD 3-Clause License. Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/hauntd/yii2-vote.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/hauntd/yii2-vote.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/hauntd/yii2-vote
[link-downloads]: https://packagist.org/packages/hauntd/yii2-vote
[link-author]: https://github.com/hauntd
