<?php

namespace yuncms\vote\traits;

use Yii;
use yuncms\vote\Module;
use yii\base\InvalidConfigException;

/**
 * Trait ModuleTrait
 * @package yuncms\vote\traits
 */
trait ModuleTrait
{
    /**
     * @return \yuncms\vote\Module
     * @throws InvalidConfigException
     */
    public function getModule()
    {
        if (Yii::$app->hasModule('vote') && ($module = Yii::$app->getModule('vote')) instanceof Module) {
            return $module;
        }

        throw new InvalidConfigException('Module "vote" is not set.');
    }
}
