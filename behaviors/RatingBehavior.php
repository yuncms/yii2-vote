<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace yuncms\vote\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\base\InvalidConfigException;
use yuncms\vote\models\Rating;
use yuncms\vote\models\AggregateRating;

/**
 * Class RatingBehavior
 * @package yuncms\vote\behaviors
 */
class RatingBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        if (!$owner instanceof ActiveRecord) {
            throw new InvalidConfigException(Yii::t('vote', 'Please attach this behavior to the instance of the ActiveRecord class'));
        }
        parent::attach($owner);
    }

    /**
     * @inheritdoc
     */
    public function getAggregate()
    {
        return $this->owner
            ->hasOne(AggregateRating::className(), [
                'model_id' => $this->owner->primaryKey()[0],
            ])
            ->onCondition([
                'model' => Rating::getNameByModel($this->owner->className())
            ]);
    }
}
