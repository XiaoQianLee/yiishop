<?php
/* @var $this yii\web\View */
?>
<h2>文章管理列表</h2>

<table class="table" id="essay-table">

    <tr>
        <th>文章编号</th>
        <th>文章标题</th>
        <th>文章简介</th>
        <th>文章分类</th>
        <th>文章排序</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>

    <?php foreach ($essay as $v):?>

        <tr>
            <td><?=$v->id;?></td>
            <td><?=$v->name;?></td>
            <td><?=$v->intro;?></td>
            <td><?=$v->category->name;?></td>
            <td><?=$v->sort;?></td>
            <td><?=date('Y-m-d H:i;s',$v->create_time)?></td>
            <td data-id="<?=$v->id;?>">
                <?php if (Yii::$app->user->can('essay/look')){?>
                <a href="<?=\yii\helpers\Url::to(['essay/look','id'=>$v->id])?>" class="btn btn-info"><span class="glyphicon glyphicon-hand-up">&ensp;</span>查看</a><?php }?>
                <?php if (Yii::$app->user->can('essay/edit')){?>
                <a href="<?=\yii\helpers\Url::to(['essay/edit','id'=>$v->id])?>" class="btn btn-warning"><span class="glyphicon glyphicon-edit">&ensp;</span>编辑</a><?php }?>
                <?php if (Yii::$app->user->can('essay/del')){?>
                    <button class="btn btn-danger btn-del"><span class="glyphicon glyphicon-trash">&ensp;</span>删除</button><?php }?>
            </td>
        </tr>

    <?php endforeach;?>

</table>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
    'maxButtonCount' => 5,
    'hideOnSinglePage' => false
])?>

<?php
$url = \yii\helpers\Url::to(['essay/del']);
$this->registerJs(new \yii\web\JsExpression(
    <<<js
$("#essay-table").on('click','.btn-del',function(data) {
     if (confirm('是否确认删除该文章？')) {
         var tr = $(this).closest('tr');
         var id = tr.attr('data-id');
         //发起ajax请求
          $.post("{$url}",{id:id},function(data){
                if(data=='success'){
                    tr.remove();//移除文章分类所在的tr
                }else{
                    console.log(data);
                }
           });
     }   
});
js

));
?>
