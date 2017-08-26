<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 14:08
 */

namespace backend\models;


use yii\base\Model;

class PermissionForm extends Model
{
    public $name;//权限名称/路由
    public $description;//描述

    /*
     * 验证规则
     * */
    public function rules()
    {
        return [
            [['name','description'],'required'],
        ];
    }

    /*
     * 处理数据
     * */
    public function sava(){
        $authManager = \Yii::$app->authManager;
        //判断权限是否已存在
        if ($authManager->getPermission($this->name)) {
            $this->addError('name','当前添加的权限已存在！');
            return false;
        }else{
            $permission = $authManager->createPermission($this->name);//添加一个权限
            $permission-> description = $this->description;//描述
            $authManager -> add($permission);//保存到数据库
            return true;
        }
    }

    /*
     * 修改权限
     * */
    public function savaEdit($name){
        $authManager = \Yii::$app->authManager;
        $permission = $authManager ->getPermission($name);//获得当前修改的权限对象
        //判断name是否有修改
        if ($this->name == $name) {//没有修改name
            $permission -> description = $this -> description;//只修改描述
            $authManager->update($name,$permission);
            return true;
        }else{//修改了name
            if ($authManager ->getPermission($this->name)) {
                $this->addError('name','当前权限名称已存在！');
                return false;
            }else{
                $permission -> name = $this->name;
                $permission -> description = $this -> description;//只修改描述
                $authManager->update($name,$permission);
                return true;
            }
        }

    }


    /*
     *对应中文命名
     * */
    public function attributeLabels()
    {
        return [
            'name' => '权限名称/路由',
            'description' => '权限描述',
        ];
    }
}