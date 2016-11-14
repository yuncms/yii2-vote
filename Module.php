<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace yuncms\vote;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Class Module
 * @package yuncms\vote
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'yuncms\vote\controllers';

    /**
     * Is allow vote for guests
     * @var bool
     */
    public $allowGuests = true;

    /**
     * Is enable pop over
     * @var bool
     */
    public $popOverEnabled = false;

    /**
     * Is allow change votes
     * @var bool
     */
    public $allowChangeVote = true;


    public function init()
    {
        parent::init();
        if (!isset($this->models)) {
            throw new InvalidConfigException('models not configurated');
        }
        if (empty(Yii::$app->i18n->translations['vote'])) {
            Yii::$app->i18n->translations['vote'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }
}
