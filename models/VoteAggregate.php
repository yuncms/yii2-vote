<?php

namespace yuncms\vote\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "vote_aggregate".
 *
 * @author Alexander Kononenko <contact@hauntd.me>
 * @package hauntd\vote\models
 * @property integer $id
 * @property integer $entity
 * @property integer $target_id
 * @property integer $positive
 * @property integer $negative
 * @property float $rating
 */
class VoteAggregate extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%vote_aggregate}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['entity', 'target_id', 'positive', 'negative', 'rating'], 'required'],
            [['entity', 'target_id', 'positive', 'negative'], 'integer'],
            [['rating'], 'number']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vote', 'ID'),
            'entity' => Yii::t('vote', 'Entity'),
            'target_id' => Yii::t('vote', 'Target Model ID'),
            'positive' => Yii::t('vote', 'Positive'),
            'negative' => Yii::t('vote', 'Negative'),
            'rating' => Yii::t('vote', 'Rating'),
        ];
    }
}
