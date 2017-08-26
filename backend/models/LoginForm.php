<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17
 * Time: 15:02
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;//登录用户名
    public $password;//登录密码
    public $code;//登录验证码
    public $remember;//是否自动登录

    //验证规则
    public function rules()
    {
        return [
            [['username','password',],'required'],
            [['remember'],'safe'],
            [['code'],'captcha','captchaAction' => 'admin/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'登录用户名',
            'password' => '登录密码',
            'code' => '验证码',
            'remember' => '是否保存自动登录'
       ];
    }
}