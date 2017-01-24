<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace yuncms\vote\components;

use Yii;
use yii\base\Event;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yuncms\vote\behaviors\RatingBehavior;

class VoteBootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\web\Application $app the application currently running
     * @throws InvalidConfigException
     */
    public function bootstrap($app)
    {
        $models = Yii::$app->getModule('vote')->models;
        foreach ($models as $value) {
            if (is_array($value)) {
                $modelName = $value['model'];
            } else if (is_string($value)) {
                $modelName = $value;
            } else {
                throw new InvalidConfigException('models configure error.');
            }
            Event::on($modelName::className(), $modelName::EVENT_INIT, function ($event) {
                if (null === $event->sender->getBehavior('rating')) {
                    $event->sender->attachBehavior('rating', [
                        'class' => RatingBehavior::className(),
                    ]);
                }
            });
        }
    }
}
