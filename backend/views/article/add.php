<?php

//表单开始
$form = \yii\bootstrap\ActiveForm::begin();

//添加的字段
echo $form->field($model,'name');//文章标题
echo $form->field($model,'intro')->textarea();//文章简介
echo $form->field($model,'sort');//文章排序
echo $form->field($model,'status')->radioList([0=>'隐藏',1=>'正常']);//文章状态

echo \yii\bootstrap\Html::submitButton('添加\提交',['class'=>'btn btn-info']);

//表单结束
\yii\bootstrap\ActiveForm::end();
