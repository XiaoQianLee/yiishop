<?php
/* @var $this yii\web\View */
?>
<h1>品牌分类列表</h1>
<table class="table" id="brand-table">

    <tr>
        <th>品牌编号</th>
        <th>品牌名称</th>
        <th>品牌简介</th>
        <th>品牌logo</th>
        <th>品牌排序</th>
        <th>操作品牌</th>

    </tr>

    <?php foreach ($brand as $v):?>
        <tr data-id="<?=$v->id;?>">
            <td><?=$v->id;?></td>
            <td><?=$v->name;?></td>
            <td><?=$v->intro;?></td>
            <td><img src="<?=$v->logo;?>" width="100px" height="50px"/></td>
            <td><?=$v->sort;?></td>
            <td>
                <?php if (Yii::$app->user->can('brand/edit')){?>
                <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$v->id])?>" class="btn btn-warning">
                    <span class="glyphicon glyphicon-edit">&ensp;</span>编辑</a><?php }?>
                <?php if (Yii::$app->user->can('brand/edit')){?>
                <button class="btn btn-danger btn-del">
                    <span class="glyphicon glyphicon-trash">&ensp;</span>删除</button><?php }?>
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
$url = \yii\helpers\Url::to(['brand/del']);
$this->registerJs(new \yii\web\JsExpression(
    <<<js
$("#brand-table").on('click','.btn-del',function(data) {
     if (confirm('是否确认删除该品牌？')) {
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