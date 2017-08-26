<?php

?>


<div class="container">
    <h2><?=$model->name;?></h2><br/>
    <div class="col-md-offset-1"><h6><?=date('Y-m-d H:i:s',$model->create_time)?></h6><br/></div>
    <div class="col-md-12">
        <span><?=$model_detail->content?></span>
    </div>


    <div class="col-md-offset-10" style="margin-top: 100px"><a href="<?= \yii\helpers\Url::to(['essay/index'])?>" class="btn btn-info">返回列表</a></div>
</div>

