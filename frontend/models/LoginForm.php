<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 15:57
 */

namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;//登录名
    public $password;//登录密码
//    public $code;//验证码
    public $remember;//保存登录信息

    public function rules()
    {
        return [
            [['username','password'],'required'],
            [['remember'],'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '登录用户名',
            'password' => '登录密码',
//            'code' => '验证码'
        ];
    }

}