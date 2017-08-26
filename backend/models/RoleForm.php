<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 16:23
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class RoleForm extends Model
{
    public $name;//角色名称
    public $description;//角色描述
    public $permissions;//权限

    /*
     * 验证规则
     * */
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
        ];
    }

    /*
     * 对应意思
     * */
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'角色描述',
            'permissions' => '角色权限',
        ];
    }

    /*
     * 获取所有权限信息
     * */
    public static function getPermissions()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','description');
    }

    /*
     * 添加角色
     * */
    public function sava(){
        $authManager = \Yii::$app->authManager;
        if ($authManager->getRole($this->name)) {
            $this->addError('name','该角色名已存在！');
            return false;
        }else{
            $role = $authManager -> createRole($this->name);//创建角色
            $role -> description = $this->description;//添加角色描述
            $authManager->add($role);//保存到数据库
            //给角色关联权限
            if (is_array($this->permissions)) {
                foreach ($this->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);//角色，权限
                }
            }
            return true;
        }
    }

    /*
     * 修改角色
     * */
    public function savaEdit($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager ->getRole($name);//获得当前修改的权限对象
        if ($this->name == $name) {//不修改角色名
            $role -> description = $this->description;//修改描述信息
            if (is_array($this->permissions)) {
                $authManager->removeChildren($role);
                foreach ($this->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);//角色，权限
                }
            }
            $authManager->update($name,$role);
            return true;
        }else{//修改角色名
            if ($authManager->getRole($name)) {//存在改角色名
                $this->addError('name','该角色名已存在！');
                return false;
            }else{
                $role->name= $this->name;
                $role->description = $this->description;//修改描述信息
                if (is_array($this->permissions)) {
                    $authManager->removeChildren($role);
                    foreach ($this->permissions as $permissionName){
                        $permission = $authManager->getPermission($permissionName);
                        $authManager->addChild($role,$permission);//角色，权限
                    }
                }
                $authManager->update($name,$role);
                return true;
            }
        }
    }



}