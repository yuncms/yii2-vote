<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace yuncms\vote\controllers;

use Yii;
use yii\web\Controller;

/**
 * Class DefaultController
 * @package yuncms\vote\controllers
 */
class DefaultController extends Controller
{
    public $defaultAction = 'vote';

    public function actions()
    {
        return [
            'vote' => [
                'class' => 'yuncms\vote\actions\VoteAction',
            ]
        ];
    }
}
