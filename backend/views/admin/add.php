<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');//管理员账号
echo $form->field($model,'password')->passwordInput();//管理员密码
echo $form->field($model,'email')->textInput(['type'=>'email']);
echo $form->field($model,'status',['inline' => 'true'])->radioList([1=>'正常',0=>'禁用']);
echo $form->field($model,'role',['inline'=>'true'])->checkboxList(\backend\models\Admin::getRole());

echo \yii\bootstrap\Html::submitButton('提交/保存',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();