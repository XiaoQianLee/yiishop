<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17
 * Time: 16:39
 */

namespace backend\models;


use yii\base\Model;

class ChangePass extends Model
{
    public $username;//账户用户名
    public $old_password;//旧密码
    public $new_password;//新密码
    public $new_password2;//确认新密码

    //验证规则
    public function rules()
    {
        return [
            [['username','old_password','new_password','new_password2'],'required'],
            ['new_password2','compare','compareAttribute'=>'new_password','on' => 'admin/modify']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'old_password' => '旧密码',
            'new_password' => '新密码',
            'new_password2' => '确认输入密码',
        ];
    }
}