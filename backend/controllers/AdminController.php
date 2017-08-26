<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\ChangePass;
use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class AdminController extends \yii\web\Controller
{
    /*
     * 配置行为
     * */
    public function behaviors()
    {
        return [
            'rbac' => [
                'class'=>RbacFilter::className(),
                'except'=> ['login','logout','captcha','s-upload','modify','upload'],
            ],
        ];
    }

    /*
     * 显示管理员列表
     * */
    public function actionIndex()
    {
        //声明查询器
        $query = Admin::find();
        //创建分页模型
        $pager = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => 5
        ]);
        //查询所有管理员
        $models = $query->offset($pager->offset)
            ->limit($pager->limit)
            ->where(['status'=>[0,1]])
            ->all();
        //显示视图
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    /*
     * 添加管理员
     * */
    public function actionAdd()
    {
        //创建新的管理员模型
        $model = new Admin();
        $model->scenario = Admin::SCENARIO_ADD;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model -> save();
            $id = $model->id;
            $model -> PermissionRole($id);
            //提示信息
            \Yii::$app->session->setFlash('success','添加管理员成功！');
            //跳转页面
            $this -> redirect(['admin/index']);
        }
        //显示视图
        return $this->render('add',['model'=>$model]);
    }

    /*
     * 编辑管理员信息
     * */
    public function actionEdit($id)
    {
        //创建新的管理员模型
        $model = Admin::findOne(['id'=>$id]);
        $model -> role =ArrayHelper::getColumn(\Yii::$app->authManager->getRolesByUser($id),'name');
        //var_dump($model -> role);exit;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model -> save();
            $model->EditRole($id);
            //提示信息
            \Yii::$app->session->setFlash('success','修改管理员信息成功！');
            //跳转页面
            $this -> redirect(['admin/index']);
        }
        //显示视图
        return $this->render('add',['model'=>$model]);
    }

    /*
     * 删除管理员
     * */
    public function actionDel()
    {
        //通过id获得管理员模型
        $model = Admin::findOne(['id'=>\Yii::$app->request->post('id')]);
        if ($model) {
            //删除模型
            $model -> status = -1;
            $model -> save();
            return 'success';
        }
        return 'false';
    }

    /*
     * 管理员登录
     * */
    public function actionLogin(){
        //创建user组件
        $model = \Yii::$app->user;
       //创建登录表单模型
        $login = new LoginForm();
        if ($login->load(\Yii::$app->request->post()) && $login->validate()) {
            $admin = Admin::findOne(['username'=>$login->username]);
           //验证是否有此管理员
            if ($admin) {//管理员存在
                //验证密码
                if (\Yii::$app->security->validatePassword($login->password,$admin->password_hash)) {
                    if ($admin->status === 0) {
                        \Yii::$app->session->setFlash('danger','此账号已经被禁用！');
                        return $this->refresh();//刷新页面
                    }else{
                        if ($login->remember) {
                            $model->login($admin,3*24*3600);
                        }else{
                            $model->login($admin);
                        }
                        $admin->getLastLogin();
                        $admin->save();
                        \Yii::$app->session->setFlash('success','登录成功！');
                        $this->redirect(['admin/index']);
                    }
                }else{
                    \Yii::$app->session->setFlash('danger','用户名不存在或密码错误！');
                    return $this->refresh();//刷新页面
                }
            }else{
                \Yii::$app->session->setFlash('danger','用户名不存在或密码错误！');
                return $this->refresh();//刷新页面
            }
        }
        //显示视图
        return $this->render('login',['login'=>$login]);
    }

    /*
     * 退出登录
     * */
    public function actionLogout()
    {
        //用户退出登陆操作
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','退出登录成功！');
        $this->redirect(['admin/login']);
    }

    /*
     * 修改密码
     * */
    public function actionModify(){
        if ($identity = \Yii::$app->user->identity) {//当前认证用户
            //创建一个表单组件
            $model = new ChangePass();
            $model -> username = $identity->username;
            if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                $admin = Admin::findOne(['id'=>$identity->id]);
                if (\Yii::$app->security->validatePassword($model->old_password,$admin->password_hash)) {//旧密码匹配
                    //hash加密 写入数据库
                    $admin -> password_hash =\Yii::$app->security->generatePasswordHash($model->new_password);
                    $admin->save();
                    \Yii::$app->session->setFlash('success','修改密码成功！');
                    $this -> redirect(['admin/index']);
                }else{
                    \Yii::$app->session->setFlash('danger','密码输入错误，请确认后重试！');
                    return $this->refresh();
                }
            }else{
                //var_dump($model->getErrors());exit;
            }
            return $this->render('change',['model'=>$model]);

        }else{
            \Yii::$app->session->setFlash('danger','您还没有登录！请登录后再试');
            $this->redirect(['admin/login']);
        }
    }

    /*
     * 验证码
     * */
    public function actions()
    {
        return [
            'captcha' => [
                'class'=>CaptchaAction::className(),
                'maxLength' => 4,
                'minLength' => 4,
                'foreColor' => 'black'
            ],
        ];
    }

}
