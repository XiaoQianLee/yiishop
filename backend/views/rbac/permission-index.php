<?php
/* @var $this yii\web\View */
$this->registerCssFile('@web/DateTables/css/jquery.dataTables.css');
$this->registerJsFile('@web/DateTables/js/jquery.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('@web/DateTables/js/jquery.dataTables.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>


<div class="row">
    <div class="col-md-2"><h1>权限列表</h1></div>
</div>

<table class="display table" id="table_id_example">
    <thead>
    <tr>
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($permissions as $v):?>
        <tr data-name="<?=$v->name;?>">
            <td><?=$v->name;?></td>
            <td><?=$v->description;?></td>
            <td>
                <?php if (Yii::$app->user->can('rbac/permission-edit')){?>
                <a href="<?=\yii\helpers\Url::to(['rbac/permission-edit','name'=>$v->name])?>" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-edit">&ensp;</span>编辑</a><?php }?>
                <?php if (Yii::$app->user->can('rbac/permission-del')){?>
                <button class="btn btn-danger btn-sm btn-del"><span class="glyphicon glyphicon-trash">&ensp;</span>删除</button><?php }?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php
$url = \yii\helpers\Url::to(['rbac/permission-del']);
$this->registerJs(
        <<<JS
    $(document).ready( function () {
        $('#table_id_example').DataTable();
    });
    $("#table_id_example").on("click",'.btn-del',function(data) {
            if (confirm('确认是否删除该权限？！')) {
                var tr = $(this).closest('tr');
                var name = tr.attr('data-name');
                //发送ajax请求
                $.post("{$url}",{name:name},function(data) {
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
