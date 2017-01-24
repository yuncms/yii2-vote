<div class="text-center" id="vote-<?=$model?>-<?=$modelId?>" data-placement="top" data-container="body" data-toggle="popover">
    <span id="vote-up-<?=$model?>-<?=$modelId?>" class="glyphicon glyphicon-thumbs-up" onclick="vote('<?=$model?>', <?=$modelId?>, 'like'); return false;" style="cursor: pointer;"><?=$likes?></span>
    <span id="vote-down-<?=$model?>-<?=$modelId?>" class="glyphicon glyphicon-thumbs-down" onclick="vote('<?=$model?>', <?=$modelId?>, 'dislike'); return false;" style="cursor: pointer;"><?=$dislikes?></span>
    <div id="vote-response-<?=$model?>-<?=$modelId?>">
        <?php if ($showAggregateRating) { ?>
            <?=Yii::t('vote', 'Aggregate rating')?>: <?=$rating?>
        <?php } ?>
    </div>

</div>
<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
    <meta itemprop="interactionCount" content="UserLikes:<?=$likes?>"/>
    <meta itemprop="interactionCount" content="UserDislikes:<?=$dislikes?>"/>
    <meta itemprop="ratingValue" content="<?=$rating?>"/>
    <meta itemprop="ratingCount" content="<?=$likes+$dislikes?>"/>
    <meta itemprop="bestRating" content="10"/>
    <meta itemprop="worstRating" content="0"/>
</div>

