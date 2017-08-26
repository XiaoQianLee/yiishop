<?php
/* @var $this yii\web\View */
$this->registerCssFile('@web/DateTables/css/jquery.dataTables.min.css');
$this->registerJsFile('@web/DateTables/js/jquery.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('@web/DateTables/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>
<div class="row">
    <div class="col-md-2"><h1>角色列表</h1></div>
</div>



<table class="table display" id="table_id_example">

    <thead>
    <tr>
        <td>角色名称</td>
        <td>角色描述</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($roles as $v):?>
        <tr data-name="<?=$v->name;?>">
            <td><?=$v->name;?></td>
            <td><?=$v->description;?></td>
            <td>
                <?php if (Yii::$app->user->can('rbac/role-edit')){?>
                <a href="<?=\yii\helpers\Url::to(['rbac/role-edit','name'=>$v->name])?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-edit">&ensp;</span>编辑</a>
                <?php }?>
                <?php if (Yii::$app->user->can('rbac/role-del')){?>
                <button class="btn btn-danger btn-sm btn-del"><span class="glyphicon glyphicon-trash">&ensp;</span>删除</button><?php }?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php
$url = \yii\helpers\Url::to(['rbac/role-del']);
$this->registerJs(
    <<<JS
    $(document).ready( function () {
        $('#table_id_example').DataTable({});
    });

 $("#table_id_example").on("click",'.btn-del',function(data) {
            if (confirm('确认是否删除该角色？！')) {
                var tr = $(this).closest('tr');
                var name = tr.attr('data-name');
                //发送ajax请求
                $.post("{$url}",{name:"name"},function(data) {
                  if (data === 'success') {
                      tr.remove();//删除图片所有在tr
                  }else{
                      console.log(data);
                  }
                });
            }
        })
JS

);
?>
