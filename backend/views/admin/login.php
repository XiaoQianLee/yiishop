<?php
/* @var  $this \yii\web\View */
echo '<h1>管理员登录</h1>';

echo '<div class="row">';
echo '<div class="col-md-4">';
//表单开始
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($login,'username');//用户登录名
echo $form->field($login,'password')->passwordInput();//登录密码
echo $form->field($login,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction' => 'admin/captcha',
    'template'=>'<div class="row"><div class="col-lg-9">{input}</div><div class="col-lg-1">{image}</div></div>'
]);
echo $form->field($login,'remember')->checkbox();
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
//表单结束
echo '</div>';
echo '<div class="col-md-offset-5">';
echo '<img src="\upload\0f\01\0f01c3c0173afab8287cd3916dbb9b7a88e9dafa.jpg">';
echo '</div>';
echo '</div>';
?>


