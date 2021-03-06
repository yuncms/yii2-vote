# Vote for Yii2
这个仓库 来自 https://github.com/Chiliec/yii2-vote

[![Latest Stable Version](https://poser.pugx.org/yuncms/yii2-vote/v/stable.svg)](https://packagist.org/packages/yuncms/yii2-vote) 
[![Total Downloads](https://poser.pugx.org/yuncms/yii2-vote/downloads.svg)](https://packagist.org/packages/yuncms/yii2-vote) 
[![Build Status](https://travis-ci.org/yuncms/yii2-vote.svg?branch=master)](https://travis-ci.org/yuncms/yii2-vote) 
[![Test Coverage](https://codeclimate.com/github/yuncms/yii2-vote/badges/coverage.svg)](https://codeclimate.com/github/yuncms/yii2-vote/coverage)
[![Code Climate](https://codeclimate.com/github/yuncms/yii2-vote/badges/gpa.svg)](https://codeclimate.com/github/yuncms/yii2-vote) 
[![License](https://poser.pugx.org/yuncms/yii2-vote/license.svg)](https://packagist.org/packages/yuncms/yii2-vote)
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

![How yii2-vote works](https://raw.githubusercontent.com/Chiliec/yii2-vote/master/docs/showcase.gif)


## Installation

Next steps will guide you through the process of installing yii2-vote using **composer**. Installation is a quick and easy three-step process.

### Step 1: Install component via composer

Run command

```
php composer.phar require --prefer-dist yuncms/yii2-vote "~1.0.0"
```

or add

```
"yuncms/yii2-vote": "~1.0.0"
```

to the require section of your `composer.json` file.


### Step 2: Configuring your application

Add following lines to your main configuration file:

```php
'bootstrap' => [
    'yuncms\vote\components\VoteBootstrap',
],
'modules' => [
    'vote' => [
        'class' => 'yuncms\vote\Module',
        // show messages in popover
        'popOverEnabled' => true,
        // global values for all models
        // 'allowGuests' => true,
        // 'allowChangeVote' => true,
        'models' => [
        	// example declaration of models
            // 'Post'=>\common\models\Post::className(),
            // 'Post1'=>'backend\models\Post',
            // '2' => 'frontend\models\Story',
            // '3' => [
            //     'model' => \backend\models\Mail::className(),
            //     you can rewrite global values for specific model
            //     'allowGuests' => false,
            //     'allowChangeVote' => false,
            // ],
        ],      
    ],
],
```

And add widget in view:

```php
<?php echo \yuncms\vote\widgets\Vote::widget([
    'model' => $model,
    // optional fields
    // 'showAggregateRating' => true,
]); ?>
```

Also you can add widget for display top rated models:

```php
<?php echo \yuncms\vote\widgets\TopRated::widget([
    'modelName' => \common\models\Post::className(),
    'title' => 'Top rated models',
    'path' => 'site/view',
    'limit' => 10,
    'titleField' => 'title',
]) ?>
```

### Step 3: Updating database schema

After you downloaded and configured Yii2-vote, the last thing you need to do is updating your database schema by applying the migrations:

```bash
$ php yii migrate/up --migrationPath=@vendor/yuncms/yii2-vote/migrations
```

## Documentation

Extended information about configuration of this module see in [docs/README.md](https://github.com/yuncms/yii2-vote/blob/master/docs/README.md). There you can find:
* [Manually add behavior in models](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#manually-add-behavior-in-models)
* [Sorting by rating in data provider](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#sorting-by-rating-in-data-provider)
* [Overriding views](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#overriding-views)
* [Customizing JS-events](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#customizing-js-events)
* [Rich snippet in search engines](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#rich-snippet-in-search-engines)

## License

yii2-vote is released under the BSD 3-Clause License. See the bundled [LICENSE.md](https://github.com/Chiliec/yii2-vote/blob/master/LICENSE.md) for details.

## List of contributors

* [Chiliec](https://github.com/Chiliec) - Maintainer
* [loveorigami](https://github.com/loveorigami) - Ideological inspirer
* [fourclub](https://github.com/fourclub) - PK name fix in behavior
* [yurkinx](https://github.com/yurkinx) - Duplication js render fix
* [n1k88](https://github.com/n1k88) - German translation
* [teranchristian](https://github.com/teranchristian) - Add popover to display messages

## How to contribute 

See [CONTRIBUTING.md](https://github.com/Chiliec/yii2-vote/blob/master/CONTRIBUTING.md) for details.

Enjoy and don't hesitate to send issues and pull requests :)
