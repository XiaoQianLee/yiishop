<?php

$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'label');//菜单名称
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getParentMenu());//上级菜单
echo $form->field($model,'url')->dropDownList(\backend\models\Menu::getPermissionUrl());//菜单地址
echo $form->field($model,'sort');//菜单排序

echo \yii\bootstrap\Html::submitButton('提交/保存',['class'=>'btn btn-info']);



\yii\bootstrap\ActiveForm::end();