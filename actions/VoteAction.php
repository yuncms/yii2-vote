<?php

namespace hauntd\vote\actions;

use Yii;
use hauntd\vote\Module;
use hauntd\vote\events\VoteActionEvent;
use hauntd\vote\models\Vote;
use hauntd\vote\models\VoteAggregate;
use hauntd\vote\models\VoteForm;
use hauntd\vote\traits\ModuleTrait;
use yii\base\Action;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;

/**
 * Class VoteAction
 * @package hauntd\vote\actions
 */
class VoteAction extends Action
{
    use ModuleTrait;

    const EVENT_BEFORE_VOTE = 'beforeVote';
    const EVENT_AFTER_VOTE = 'afterVote';

    /**
     * @return array
     * @throws MethodNotAllowedHttpException
     */
    public function run()
    {
        if (!Yii::$app->request->getIsAjax() || !Yii::$app->request->getIsPost()) {
            throw new MethodNotAllowedHttpException(Yii::t('vote', 'Forbidden method'), 405);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $module = $this->getModule();
        $form = new VoteForm();
        $form->load(Yii::$app->request->post());
        $this->trigger(self::EVENT_BEFORE_VOTE, $event = $this->createEvent($form, $response = []));

        if ($form->validate()) {
            $settings = $module->getSettingsForEntity($form->entity);
            if ($settings['type'] == Module::TYPE_VOTING) {
                $response = $this->processVote($form);
            } else {
                $response = $this->processToggle($form);
            }
            $response = array_merge($event->responseData, $response);
            $response['aggregate'] = VoteAggregate::findOne([
                'entity' => $module->encodeEntity($form->entity),
                'target_id' => $form->targetId
            ]);
        } else {
            $response = ['success' => false, 'errors' => $form->errors];
        }

        $this->trigger(self::EVENT_AFTER_VOTE, $event = $this->createEvent($form, $response));

        return $event->responseData;
    }

    /**
     * Processes a vote (+/-) request.
     *
     * @param VoteForm $form
     * @return array
     * @throws \Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function processVote(VoteForm $form)
    {
        /* @var $vote Vote */
        $module = $this->getModule();
        $response = ['success' => false];
        $searchParams = ['entity' => $module->encodeEntity($form->entity), 'target_id' => $form->targetId];

        if (Yii::$app->user->isGuest) {
            $vote = Vote::find()
                ->where($searchParams)
                ->andWhere(['user_ip' => Yii::$app->request->userIP])
                ->andWhere('UNIX_TIMESTAMP() - created_at < :limit', [':limit' => $module->guestTimeLimit])
                ->one();
        } else {
            $vote = Vote::findOne(array_merge($searchParams, ['user_id' => Yii::$app->user->id]));
        }

        if ($vote == null) {
            $response = $this->createVote($module->encodeEntity($form->entity), $form->targetId, $form->getValue());
        } else {
            if ($vote->value !== $form->getValue()) {
                $vote->value = $form->getValue();
                if ($vote->save()) {
                    $response = ['success' => true, 'changed' => true];
                }
            }
        }

        return $response;
    }

    /**
     * Processes a vote toggle request (like/favorite etc).
     *
     * @param VoteForm $form
     * @return array
     * @throws \Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function processToggle(VoteForm $form)
    {
        /* @var $vote Vote */
        $module = $this->getModule();
        $vote = Vote::findOne([
            'entity' => $module->encodeEntity($form->entity),
            'target_id' => $form->targetId,
            'user_id' => Yii::$app->user->id
        ]);

        if ($vote == null) {
            $response = $this->createVote($module->encodeEntity($form->entity), $form->targetId, $form->getValue());
            $response['toggleValue'] = 1;
        } else {
            $vote->delete();
            $response = ['success' => true, 'toggleValue' => 0];
        }

        return $response;
    }

    /**
     * Creates new vote entry and returns response data.
     *
     * @param string $entity
     * @param integer $targetId
     * @param integer $value
     * @return array
     */
    protected function createVote($entity, $targetId, $value)
    {
        $vote = new Vote();
        $vote->entity = $entity;
        $vote->target_id = $targetId;
        $vote->value = $value;

        if ($vote->save()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => $vote->errors];
        }
    }

    /**
     * @param VoteForm $voteForm
     * @param array $responseData
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    protected function createEvent(VoteForm $voteForm, array $responseData)
    {
        return Yii::createObject([
            'class' => VoteActionEvent::className(),
            'voteForm' => $voteForm,
            'responseData' => $responseData
        ]);
    }
}
