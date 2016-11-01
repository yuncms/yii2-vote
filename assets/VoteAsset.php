<?php

namespace yuncms\vote\assets;

use yii\web\AssetBundle;

/**
 * @author Alexander Kononenko <contact@hauntd.me>
 * @package yuncms\vote\assets
 */
class VoteAsset extends AssetBundle
{
    public $sourcePath = '@yuncms/vote/assets/static';
    public $css = [
        'vote.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
