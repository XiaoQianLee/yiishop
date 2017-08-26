<?php
/* @var $this yii\web\View */
?>

<h1>商品管理列表</h1>


    <form class="form-inline" action="<?= \yii\helpers\Url::to(['goods/index'])?>" method="get">
        <div class="form-group">
            <label for="exampleInputName2">商品名称：</label>
            <input type="text" class="form-control" id="exampleInputName2" name="good_name" placeholder="请输入商品名称关键字">
        </div>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search">&ensp;</span>搜索</button>
    </form>



<table class="table" id="goods-table">

    <tr>
        <td>商品名称</td>
        <td>商品货号</td>
        <td>商品LOGO</td>
        <td>商品分类</td>
        <td>品牌分类</td>
        <td>商品价格</td>
        <td>商品库存</td>
        <td>是否在售</td>
        <td>添加时间</td>
        <td>操作</td>
    </tr>

    <?php foreach ($models as $v):?>
        <tr data-id="<?=$v->id;?>">
            <td><?=$v->name;?></td>
            <td><?=$v->sn;?></td>
            <td><img src="<?=$v->logo;?>" width="100px" height="50px"></td>
            <td><?=$v->category->name;?></td>
            <td><?=$v->brand->name;?></td>
            <td><?=$v->shop_price;?></td>
            <td><?=$v->stock;?></td>
            <td><?=$v->is_on_sale == 1? "在售" : "下架"?></td>
            <td><?=date('Y-m-d',$v->create_time);?></td>
            <td>
                <?php if (Yii::$app->user->can('goods/gallery')){?>
                <a href="<?=\yii\helpers\Url::to(['goods/gallery','id'=>$v->id])?>" class="btn btn-default">
                    <span class="glyphicon glyphicon-picture"></span>相册</a><?php }?>
                <?php if (Yii::$app->user->can('goods/edit')){?>
                <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$v->id])?>" class="btn btn-warning">
                    <span class="glyphicon glyphicon-edit">&ensp;</span>编辑</a><?php }?>
                <?php if (Yii::$app->user->can('goods/del')){?>
                <button class="btn btn-danger btn-del"><span class="glyphicon glyphicon-trash">&ensp;</span>删除</button><?php }?>
            </td>
        </tr>

    <?php endforeach;?>

</table>

<?= yii\widgets\LinkPager::widget([
    'pagination' => $pager,
])?>

<?php
$url = \yii\helpers\Url::to(['goods/del']);
$this->registerJs(new \yii\web\JsExpression(
    <<<js
$("#goods-table").on('click','.btn-del',function(data) {
     if (confirm('是否确认删除该商品？')) {
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
