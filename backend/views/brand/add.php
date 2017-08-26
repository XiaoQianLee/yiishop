<?php


use yii\web\JsExpression;

//表单开始
$form = \yii\bootstrap\ActiveForm::begin();

//字段
echo $form->field($model,'name');//品牌名称
echo $form->field($model,'intro')->textarea();//品牌简介
echo $form->field($model,'logo')->hiddenInput();


//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],//传递数据
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
        console.log(data.fileUrl);
        //图片回显
        $("#img").attr("src",data.fileUrl);
        //将图片地址写入到logo隐藏输入框
        $("#brand-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);


echo \yii\bootstrap\Html::img($model->logo,['width'=>'30%','height'=>'10%','id'=>'img']);



echo $form->field($model,'sort');//品牌排序
echo $form->field($model,'status')->radioList([0=>'隐藏',1=>'正常']);//品牌状态

echo \yii\bootstrap\Html::submitButton('添加\提交',['class'=>'btn btn-info']);

//表单结束
\yii\bootstrap\ActiveForm::end();