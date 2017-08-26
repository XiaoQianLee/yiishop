<?php
echo '<h1>修改密码</h1>';

$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'username')->textInput(['readonly'=>'true']);
echo $form->field($model,'old_password')->passwordInput();
echo $form->field($model,'new_password')->passwordInput();
echo $form->field($model,'new_password2')->passwordInput();

echo \yii\bootstrap\Html::submitButton('保存/提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();