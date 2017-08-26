<?php

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;

class MemberController extends \yii\web\Controller
{
    /*
     *用户注册
     * */
    public function actionRegister()
    {
        $model = new Member();
        if ($model->load(\Yii::$app->request->post(),'') && $model->validate()) {
            $model -> save();
            $this->redirect(['member/index']);
        }
        //显示视图
        return $this->render('regist');
    }

    /*
     * 用户登录
     * */
    public function actionLogin()
    {
        $model = new LoginForm();
        $user = \Yii::$app->user;
        if ($model->load(\Yii::$app->request->post(),'') && $model->validate()) {
            $member = Member::findOne(['username'=>$model->username]);//查找当前用户
            if ($member) {//存在当前用户
                if (\Yii::$app->security->validatePassword($model->password,$member->password_hash)) {//密码正确
                    if ($model->remember) {
                        $user->login($member,24*3600);
                        $member->LastLogin();
                        $member->getCookieCarts();
                        $this->redirect(['member/address']);
                    }else{
                        $user->login($member);
                        $member->LastLogin();
                        $member->getCookieCarts();
                        $this->redirect(['member/address']);
                    }
                }else{
                    echo '用户密码错误';
                }
            }else{
                echo '用户名输入错误！';
            }
        }
        //显示视图
        return $this->render('login');
    }

    /*
     * 添加收货地址
     * */
    public function actionAddress(){
        $model = new Address();
        if ($model->load(\Yii::$app->request->post(),'') && $model->validate()) {
            if ($model->default) {
                $model -> status = 1;
                $model->save();
            }else{
                $model->save();
            }
            return $this->refresh();
        }
        //查询该用户所有收货地址
        $models = Address::find()->where(['member_id'=>\Yii::$app->user->identity->getId()])->all();
        //显示视图
        return $this->render('address',['models'=>$models]);
    }

    /*
     * 设置地址默认收货地址
     * */
    public function actionAddressDefault($id)
    {
        $model = Address::findOne(['id'=>$id]);
        $model -> status = 1;
        $model -> save();
        return $this->refresh();
    }

    /*
     * 删除收货地址
     * */
    public function actionDel()
    {
        $model = Address::findOne(['id'=>\Yii::$app->request->post('id')]);
        if ($model) {
            $model -> delete();
            return 'success';
        }
        return 'false';
    }

    /*
     * 修改收货地址
     * */
    public function actionEdit($id){
        $model = Address::findOne(['id'=>$id]);
        if ($model->load(\Yii::$app->request->post(),'') && $model->validate()) {
            if ($model->default) {
                $model -> status = 1;
            }else{
                $model -> status = 0;
            }
            $model->save();
            return $this->refresh();
        }
        //显示视图
        return $this->render('edit',['model'=>$model]);
    }


    /*
     * 退出登录
     * */
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        $this->redirect(['home/index']);
    }



    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class'=> CaptchaAction::className(),
                'maxLength' => 4,
                'minLength' => 4,
                'foreColor' => 'black'
            ],
        ];
    }

}
