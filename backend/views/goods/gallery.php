<?php
/* @var $this \yii\web\View */
use yii\web\JsExpression;
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['goods_id' => $goods_id],
        'width' => 80,
        'height' => 30,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        //上传成功后
        $("#gallery").append('<tr data-id="'+data.id+'"><td><img src="'+data.fileUrl+'"/></td><td><button class="btn btn-danger del-btn">删除</button></td></tr>');
    }
}
EOF
        ),
    ]
]);
echo "<br/>";
?>


<!--显示页面-->
<table class="table" id="gallery">
    <tr>
        <td colspan="2"><a href="<?=\yii\helpers\Url::to(['goods/index'])?>" class="btn btn-default">返回商品列表</a></td>
    </tr>
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $v):?>
        <tr data-id="<?=$v->id;?>">
            <td><img src="<?=$v->path;?>" /></td>
            <?php if (Yii::$app->user->can('goods/img-del')){?>
            <td><button class="btn btn-danger del-btn">删除</button></td>
            <?php }?>
        </tr>
    <?php endforeach;?>

</table>

<?php
$url = \yii\helpers\Url::to(['goods/img-del']);
$this->registerJs(new JsExpression(
        <<<JS
        
        //委派事件
        $("#gallery").on('click','.del-btn',function(data) {
                 if (confirm('是否确认删除该图片？')) {
                     var tr = $(this).closest('tr');
                     var id = tr.attr('data-id');
                     //发起ajax请求
                      $.post("{$url}",{id:id},function(data){
                            if(data=='sueecss'){
                                tr.remove();//移除图片所在tr
                            }else{
                                console.log(data);
                            }
                       });
                 }
                
               
        });
JS
));
?>