<?php
/* @var $this yii\web\View */
?>
<h1>菜单列表</h1>

<table class="table" id="menu-table">
    <tr>
        <td>菜单编号</td>
        <td>菜单名称</td>
        <td>菜单地址</td>
        <td>菜单排序</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $v):?>
        <tr data-id="<?=$v->id?>">
            <td><?=$v->id;?></td>
            <td><?=$v->parent_id == 0 ? $v->label : "——".$v->label;?></td>
            <td><?=$v->url;?></td>
            <td><?=$v->sort;?></td>
            <td>
                <?php if (Yii::$app->user->can('menu/edit')) {?>
                <a href="<?=\yii\helpers\Url::to(['menu/edit','id'=>$v->id])?>" class="btn btn-warning"><span class="glyphicon glyphicon-edit">&ensp;</span>编辑</a><?php }?>
                <?php if (Yii::$app->user->can('menu/del')) {?>
                <button class="btn btn-danger btn-del"><span class="glyphicon glyphicon-trash">&ensp;</span>删除</button><?php }?>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?= yii\widgets\LinkPager::widget([
    'pagination' => $pager,
])?>

<?php
$url = \yii\helpers\Url::to(['menu/del']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        //表格下删除按钮委派时间
        $("#menu-table").on("click",'.btn-del',function(data) {
            if (confirm('确认是否删除该菜单？！')) {
                var tr = $(this).closest('tr');
                var id = tr.attr('data-id');
                //发送ajax请求
                $.post("{$url}",{id:id},function(data) {
                  if (data === 'success') {
                      tr.remove();//删除图片所有在tr
                  }else{
                      alert("该菜单下还有子菜单，暂不能删除！");
                  }
                });
            }
        })
JS

));
?>

