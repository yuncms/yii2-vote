<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace yuncms\vote\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%vote_aggregate_rating}}".
 *
 * @property integer $id
 * @property integer $model_id
 * @property integer $target_id
 * @property integer $likes
 * @property integer $dislikes
 * @property double $rating
 */
class AggregateRating extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vote_aggregate_rating}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'model_id', 'likes', 'dislikes', 'rating'], 'required'],
            [['model', 'model_id', 'likes', 'dislikes'], 'integer'],
            [['rating'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vote', 'ID'),
            'model' => Yii::t('vote', 'Model'),
            'model_id' => Yii::t('vote', 'Model ID'),
            'likes' => Yii::t('vote', 'Likes'),
            'dislikes' => Yii::t('vote', 'Dislikes'),
            'rating' => Yii::t('vote', 'Rating'),
        ];
    }
}
