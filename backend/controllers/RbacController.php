<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;

class RbacController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'rbac' => RbacFilter::className(),
        ];
    }

    /*
     * 添加权限
     * */
    public function actionPermissionAdd()
    {
        //创建新的权限表单模型
        $model = new PermissionForm();
        //接收数据 验证数据
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //处理数据
            if ($model->sava()) {
                \Yii::$app->session->setFlash('success','添加权限成功！');
                $this->redirect(['rbac/permission-index']);
            }
        }
        //显示视图
        return $this->render('permission-add',['model'=>$model]);
    }

    /*
     * 显示权限列表
     * */
    public function actionPermissionIndex()
    {
        //获取所有权限
        $permissions = \Yii::$app->authManager->getPermissions();
        return $this->render('permission-index',['permissions'=>$permissions]);
    }

    /*
     * 修改权限
     * */
    public function actionPermissionEdit($name)
    {
        //创建新的权限表单模型
        $model = new PermissionForm();
        $model -> name = \Yii::$app->authManager->getPermission($name)->name;
        $model -> description = \Yii::$app->authManager->getPermission($name) -> description;
        //接收数据 验证数据
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //处理数据
            if ($model->savaEdit($name)) {
                \Yii::$app->session->setFlash('success','修改权限成功！');
                $this->redirect(['rbac/permission-index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //显示视图
        return $this->render('permission-add',['model'=>$model]);
    }

    /*
     * 删除权限
     * */
    public function actionPermissionDel()
    {
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission(\Yii::$app->request->post('name'));//获得权限对象
        if ($permission) {
            $authManager->remove($permission);//删除权限对象
            return 'success';
        }
        return 'false';
    }

    /*
     * 添加角色
     * */
    public function actionRoleAdd()
    {
        //创建新的角色模型
        $model = new RoleForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model -> sava()) {
                \Yii::$app->session->setFlash('success','添加角色成功！');
                $this -> redirect(['rbac/role-index']);
            }
        }
        //显示视图
        return $this->render('role-add',['model'=>$model]);
    }

    /*
     * 显示角色
     * */
    public function actionRoleIndex()
    {
        //获取所有角色
        $roles = \Yii::$app->authManager->getRoles();
        //显示视图
        return $this->render('role-index',['roles'=>$roles]);
    }

    /*
     * 编辑角色
     * */
    public function actionRoleEdit($name)
    {
        //创建新的角色模型
        $model = new RoleForm();
        $model -> name = \Yii::$app->authManager->getRole($name)->name;
        $model -> description = \Yii::$app->authManager->getRole($name) -> description;
        $model -> permissions =ArrayHelper::getColumn(\Yii::$app->authManager->getPermissionsByRole($name),'name');
        //var_dump($model -> permissions);exit;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model -> savaEdit($name)) {
                \Yii::$app->session->setFlash('success','修改角色成功！');
                $this -> redirect(['rbac/role-index']);
            }
        }
        //显示视图
        return $this->render('role-add',['model'=>$model]);
    }

    /*
     * 删除角色
     * */
    public function actionRoleDel()
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole(\Yii::$app->request->post('name'));//获取角色对象
        if ($role) {
            $authManager->remove($role);//删除角色对象
            return 'success';
        }
        return 'false';
    }

}
