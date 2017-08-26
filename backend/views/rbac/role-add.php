<?php

$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name');//角色名称
echo $form->field($model,'description');//角色描述
echo $form->field($model,'permissions',['inline' => 'true'])->checkboxList(\backend\models\RoleForm::getPermissions());
echo \yii\helpers\Html::submitButton('提交/保存',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();