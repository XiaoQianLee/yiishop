<?php

//表单开始
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model_essay,'name');//文章标题
echo $form->field($model_essay,'intro');//文章简介
echo $form->field($model_detail,'content')->textarea();//文章内容
echo $form->field($model_essay,'article_id')->dropDownList(\yii\helpers\ArrayHelper::map($models,'id','name'));//文章分类
echo $form->field($model_essay,'sort');
echo $form->field($model_essay,'status')->radioList([0=>'隐藏',1=>'正常']);

echo \yii\bootstrap\Html::submitButton('提交/保存',['class'=>'btn btn-info']);


//表单结束
\yii\bootstrap\ActiveForm::end();