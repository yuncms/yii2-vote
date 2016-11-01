<?php

namespace yuncms\vote\controllers;

use yuncms\vote\actions\VoteAction;
use Yii;
use yii\web\Controller;

/**
 * @author Alexander Kononenko <contact@hauntd.me>
 * @package yuncms\vote\controllers
 */
class DefaultController extends Controller
{
    /**
     * @var string
     */
    public $defaultAction = 'vote';

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'vote' => [
                'class' => VoteAction::className(),
            ]
        ];
    }
}
