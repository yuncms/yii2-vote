<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace yuncms\vote\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\MethodNotAllowedHttpException;
use yuncms\vote\models\Rating;

/**
 * Class VoteAction
 * @package yuncms\vote\actions
 */
class VoteAction extends Action
{
    public function run()
    {
        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (null === $model = Yii::$app->request->post('model')) {
                return ['content' => Yii::t('vote', 'modelId has not been sent')];
            }
            if (null === $modelId = (int)Yii::$app->request->post('modelId')) {
                return ['content' => Yii::t('vote', 'The purpose is not defined')];
            }
            $act = Yii::$app->request->post('act');
            if (!in_array($act, ['like', 'dislike'], true)) {
                return ['content' => Yii::t('vote', 'Wrong action')];
            }
            $value = $act === 'like' ? Rating::VOTE_LIKE : Rating::VOTE_DISLIKE;
            $userId = Yii::$app->user->getId();
            if ($userId === null && !Rating::getIsAllowGuests($model)) {
                return ['content' => Yii::t('vote', 'Guests are not allowed to vote')];
            }
            if (!$userIp = Yii::$app->request->getUserIP()) {
                return ['content' => Yii::t('vote', 'The user is not recognized')];
            }
            if (Rating::getIsAllowGuests($model)) {
                $isVoted = Rating::findOne(['model' => $model, 'model_id' => $modelId, 'user_ip' => $userIp]);
            } else {
                $isVoted = Rating::findOne(['model' => $model, 'model_id' => $modelId, 'user_id' => $userId]);
            }
            if (is_null($isVoted)) {
                $newVote = new Rating;
                $newVote->model = $model;
                $newVote->model_id = $modelId;
                $newVote->user_id = $userId;
                $newVote->user_ip = $userIp;
                $newVote->value = $value;
                if ($newVote->save()) {
                    if ($value === Rating::VOTE_LIKE) {
                        return ['content' => Yii::t('vote', 'Your vote is accepted. Thanks!'), 'success' => true];
                    } else {
                        return ['content' => Yii::t('vote', 'Thanks for your opinion'), 'success' => true];
                    }
                } else {
                    return ['content' => Yii::t('vote', 'Validation error')];
                }
            } else {
                if ($isVoted->value !== $value && Rating::getIsAllowChangeVote($model)) {
                    $isVoted->value = $value;
                    if ($isVoted->save()) {
                        return ['content' => Yii::t('vote', 'Your vote has been changed. Thanks!'), 'success' => true, 'changed' => true];
                    } else {
                        return ['content' => Yii::t('vote', 'Validation error')];
                    }
                }
                return ['content' => Yii::t('vote', 'You have already voted!')];
            }
        } else {
            throw new MethodNotAllowedHttpException(Yii::t('vote', 'Forbidden method'), 405);
        }
    }

}
