<?php
/* @var $this yii\web\View */
?>
<h1>商品分类列表</h1>
<table class="table" id="goodsCategory-table">

    <tr>
        <td>分类编号</td>
        <td>分类名称</td>
        <td>分类简介</td>
        <td>操作</td>
    </tr>

    <?php foreach ($models as $v):?>

        <tr data-id="<?=$v->id;?>">
            <td><?=$v->id;?></td>
            <td><?php for ($i=0;$i<$v->depth;$i++){echo '——';} echo $v->name;?></td>
            <td><?=$v->intro;?></td>
            <td>
                <?php if (Yii::$app->user->can('goods-category/edit')){?>
                <a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$v->id])?>" class="btn btn-warning"><span class="glyphicon glyphicon-edit">&ensp;</span>编辑</a><?php }?>
         <?php if (Yii::$app->user->can('goods-category/del')){?>
        <button class="btn btn-danger btn-del"><span class="glyphicon glyphicon-trash">&ensp;</span>删除</button><?php }?>
            </td>
        </tr>

    <?php endforeach;?>
</table>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $pager,
    'maxButtonCount' => 5,
    'hideOnSinglePage' => false
])?>

<?php
$url = \yii\helpers\Url::to(['goods-category/del']);
$this->registerJs(new \yii\web\JsExpression(
    <<<js
$("#goodsCategory-table").on('click','.btn-del',function(data) {
     if (confirm('是否确认删除该商品分类？')) {
         var tr = $(this).closest('tr');
         var id = tr.attr('data-id');
         //发起ajax请求
          $.post("{$url}",{id:id},function(data){
                if(data=='success'){
                    tr.remove();//移除文章分类所在的tr
                }else{
                    alert('操作失败！或许这个分类下还有子分类！');
                }
           });
     }   
});
js

));
?>
