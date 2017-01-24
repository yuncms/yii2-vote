<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace yuncms\vote\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%vote_rating}}".
 *
 * @property integer $id
 * @property integer $model
 * @property integer $model_id
 * @property integer $user_id
 * @property string $user_ip
 * @property integer $value
 * @property integer $date
 */
class Rating extends ActiveRecord
{
    const VOTE_LIKE = 1;
    const VOTE_DISLIKE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vote_rating}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => 'date',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'model_id', 'user_ip', 'value'], 'required'],
            [['model_id', 'user_id', 'value'], 'integer'],
            [['user_ip'], 'string', 'max' => 39]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model ID',
            'model_id' => 'Target ID',
            'user_id' => 'User ID',
            'user_ip' => 'User IP',
            'value' => 'Value',
            'date' => 'Date',
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        static::updateRating($this->attributes['model'], $this->attributes['model_id']);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     *
     * @param string $model Name of model
     * @return integer|false Id corresponding model or false if matches not found
     */
    public static function getNameByModel($model)
    {
        if (null !== $models = Yii::$app->getModule('vote')->models) {
            foreach ($models as $key => $value) {
                if (is_string($value) && $value == $model) {
                    return $key;
                } else if ((is_array($value) && isset($value['model'])) && $value['model'] == $model) {
                    return $key;
                }
            }
        }
        return false;
    }

    /**
     * @param integer $modelId Id of model
     * @return string|false Model name or false if matches not found
     */
    public static function getModelNameById($modelId)
    {
        if (null !== $models = Yii::$app->getModule('vote')->models) {
            if (isset($models[$modelId])) {
                if (is_array($models[$modelId])) {
                    return $models[$modelId]['modelName'];
                } else {
                    return $models[$modelId];
                }
            }
        }
        return false;
    }

    /**
     * 是否允许游客投票
     * @param string $model Id of model
     * @return boolean Checks exists permission for guest voting in model params or return global value
     */
    public static function getIsAllowGuests($model)
    {
        $models = Yii::$app->getModule('vote')->models;
        if (isset($models[$model]['allowGuests'])) {
            return $models[$model]['allowGuests'];
        }
        return Yii::$app->getModule('vote')->allowGuests;
    }

    /**
     * 是否允许更改投票
     * @param string $modelId Id of model
     * @return boolean Checks exists permission for change vote in model params or return global value
     */
    public static function getIsAllowChangeVote($model)
    {
        $models = Yii::$app->getModule('vote')->models;
        if (isset($models[$model]['allowChangeVote'])) {
            return $models[$model]['allowChangeVote'];
        }
        return Yii::$app->getModule('vote')->allowChangeVote;
    }

    /**
     * @param string $model Id of model
     * @param integer $modelId Current value of primary key
     */
    public static function updateRating($model, $modelId)
    {
        $likes = static::find()->where(['model' => $model, 'model_id' => $modelId, 'value' => self::VOTE_LIKE])->count();
        $dislikes = static::find()->where(['model' => $model, 'model_id' => $modelId, 'value' => self::VOTE_DISLIKE])->count();
        if ($likes + $dislikes !== 0) {
            // Рейтинг = Нижняя граница доверительного интервала Вильсона (Wilson) для параметра Бернулли
            // http://habrahabr.ru/company/darudar/blog/143188/
            $rating = (($likes + 1.9208) / ($likes + $dislikes) - 1.96 * sqrt(($likes * $dislikes)
                        / ($likes + $dislikes) + 0.9604) / ($likes + $dislikes)) / (1 + 3.8416 / ($likes + $dislikes));
        } else {
            $rating = 0;
        }
        $rating = round($rating * 10, 2);
        $aggregateModel = AggregateRating::findOne([
            'model' => $model,
            'model_id' => $modelId,
        ]);
        if (null === $aggregateModel) {
            $aggregateModel = new AggregateRating;
            $aggregateModel->model = $model;
            $aggregateModel->model_id = $modelId;
        }
        $aggregateModel->likes = $likes;
        $aggregateModel->dislikes = $dislikes;
        $aggregateModel->rating = $rating;
        $aggregateModel->save();
    }
}
