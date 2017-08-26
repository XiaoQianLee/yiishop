<?php
/* @var $this yii\web\View */
use yii\web\JsExpression;
?>
<h1>管理员列表</h1>

<table class="table" id="admin-table">

    <tr>
        <th>管理员编号</th>
        <th>管理员账号</th>
        <th>绑定邮箱</th>
        <th>添加时间</th>
        <th>账号状态</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>

    <?php foreach ($models as $v):?>
        <tr data-id="<?=$v->id?>">
            <td><?=$v->id;?></td>
            <td><?=$v->username;?></td>
            <td><?=$v->email;?></td>
            <td><?=date('Y/m/d H:i:s',$v->created_at);?></td>
            <td><?=$v->status == 1 ? "正常" : "禁用"?></td>
            <td><?=$v->last_login_time ? date('Y/m/d H:i:s',$v->last_login_time) : ""?></td>
            <td><?=$v->last_login_ip ? long2ip($v->last_login_ip) : ""?></td>
            <td>
                <?php if (Yii::$app->user->can('admin/edit')){?>
                    <a href="<?=\yii\helpers\Url::to(['admin/edit','id'=>$v->id])?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil">&ensp;</span>修改</a>
                <?php }?>
                <?php if (Yii::$app->user->can('admin/del')){?>
                    <button class="btn btn-danger btn-del"><span class="glyphicon glyphicon-trash">&ensp;</span>删除</button>
                <?php }?>
            </td>
        </tr>
    <?php endforeach;?>

</table>

<?= yii\widgets\LinkPager::widget([
        'pagination' => $pager,
])?>

<?php
$url = \yii\helpers\Url::to(['admin/del']);
$this->registerJs(new JsExpression(
        <<<js
$("#admin-table").on('click','.btn-del',function(data) {
     if (confirm('是否确认删除该管理员？')) {
         var tr = $(this).closest('tr');
         var id = tr.attr('data-id');
         //发起ajax请求
          $.post("{$url}",{id:id},function(data){
                if(data=='success'){
                    tr.remove();//移除图片所在tr
                }else{
                    console.log(data);
                }
           });
     }   
});
js

));
?>

