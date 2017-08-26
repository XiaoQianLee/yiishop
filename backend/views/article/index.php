<?php
/* @var $this yii\web\View */
?>
<h1>文章分类列表</h1>
<table class="table" id="article-table">

    <tr>
        <th>文章分类编号</th>
        <th>文章分类名称</th>
        <th>文章分类简介</th>
        <th>文章分类排序</th>
        <th>操作文章分类</th>

    </tr>

    <?php foreach ($article as $v):?>

        <tr data-id="<?=$v->id;?>">
            <td><?=$v->id;?></td>
            <td><?=$v->name;?></td>
            <td><?=$v->intro;?></td>
            <td><?=$v->sort;?></td>
            <td>
                <?php if (Yii::$app->user->can('article/edit')){?>
                <a href="<?= \yii\helpers\Url::to(['article/edit','id'=>$v->id])?>" class="btn btn-warning"><span class="glyphicon glyphicon-edit">&ensp;</span>编辑</a>
                <?php }?>
                <?php if (Yii::$app->user->can('article/del')){?>
                <button class="btn btn-danger btn-del"><span class="glyphicon glyphicon-trash">&ensp;</span>删除</button>
                <?php }?>
            </td>
        </tr>

    <?php endforeach;?>

</table>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
    'maxButtonCount' => 5,//最大显示
    'hideOnSinglePage' => false
])?>

<?php
$url = \yii\helpers\Url::to(['article/del']);
$this->registerJs(new \yii\web\JsExpression(
    <<<js
$("#article-table").on('click','.btn-del',function(data) {
     if (confirm('是否确认删除该文章分类？')) {
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
