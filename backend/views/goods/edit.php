<?php
/* @var $this \yii\web\View */
use yii\web\JsExpression;
//表单开始
$form = \yii\bootstrap\ActiveForm::begin();
//商品名称
echo $form->field($model, 'name');

//LOGO图片
echo $form->field($model, 'logo')->hiddenInput();
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'logo']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'logo',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 100,
        'height' => 40,
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
        console.log(data.fileUrl);
        $("#logo_show").attr("src",data.fileUrl);
        //将返回的地址写入logo字段中
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo '<img id="logo_show"/>';


//商品分类
echo $form->field($model, 'goods_category_id')->hiddenInput();
echo '<div>
        <ul id="treeDemo" class="ztree"></ul>
       </div>';

//获得商品分类信息
$category_table = \backend\models\Goods::getCategoryTab();
////加载zTree的静态文件
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');//zTreeStyle/zTreeStyle.css
$this->registerJsFile('@web/zTree/js/jquery-1.4.4.min.js');//jquery-1.4.2.js
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);//jquery.ztree.core-3.x.js
////写入zTree js代码
$this->registerJs(
    <<<JS
    var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {
       data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }     
       },
       callback: {
		    onClick: function(event, treeId, treeNode) {
		           $("#goods-goods_category_id").val(treeNode.id);
		    }
        }   
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$category_table};
   $(document).ready(function(){
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
      zTreeObj.expandAll(true);
      //修改功能   根据当前分类的parent_id选中节点
      var node = zTreeObj.getNodeByParam("id", "{$model->goods_category_id}", null);//根据id获取节点
      zTreeObj.selectNode(node);
   });
JS
);
//品牌分类
echo $form->field($model, 'brand_id')->dropDownList(\backend\models\Goods::getBrandTab());
//市场价格
echo $form->field($model, 'market_price');
//商品价格
echo $form->field($model, 'shop_price');
//库存
echo $form->field($model, 'stock');
//是否在售
echo $form->field($model, 'is_on_sale')->radioList([0 => '下架', 1 => '在售']);
//排序
echo $form->field($model, 'sort');

//商品描述
echo $form->field($model_intro, 'content')->widget('kucha\ueditor\UEditor',[]);

//提交按钮
echo \yii\bootstrap\Html::submitButton('提交/保存', ['class' => 'btn btn-info']);

//表单结束
\yii\bootstrap\ActiveForm::end();