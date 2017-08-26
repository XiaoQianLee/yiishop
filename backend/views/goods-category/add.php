<?php
/**
 * @var $this \yii\web\View
 */

$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name');//分类名称
//echo $form->field($model,'parent_id')->dropDownList($model->category);//父级分类
echo $form->field($model,'parent_id')->hiddenInput();
echo '<div>
        <ul id="treeDemo" class="ztree"></ul>
       </div>';

echo $form->field($model,'intro')->textarea();//分类简介

echo \yii\bootstrap\Html::submitButton('提交/保存',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();


$ztree = \backend\models\GoodsCategory::getZtree();
//加载zTree静态资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');//加载css
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);//依赖于jquery
//加载js代码
$this->registerJs(new \yii\web\JsExpression(
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
        //获取选中的对象的id 写入上级分类中
        callback:{
            onClick:function(event, treeId, treeNode){
                //console.log(treeNode.id);
                //赋值给parent_id
                $("#goodscategory-parent_id").val(treeNode.id);
            }
        }
    };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$ztree};
   
   zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
   zTreeObj.expandAll(true);//默认展开
   


JS
));
