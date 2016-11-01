<?php

namespace yuncms\vote\events;

use yuncms\vote\models\VoteForm;
use yii\base\Event;

/**
 * Class VoteActionEvent
 * @package yuncms\vote\events
 */
class VoteActionEvent extends Event
{
    /**
     * @var VoteForm
     */
    public $voteForm;

    /**
     * @var array
     */
    public $responseData;
}
