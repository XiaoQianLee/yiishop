<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use yii\base\ActionFilter;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'rbac' => [
                'class'=>RbacFilter::className(),
                'except'=> ['login','logout','captcha','s-upload'],
            ],
        ];
    }

    /*
     * 显示文章列表
     * */
    public function actionIndex()
    {
        //创建文章模型
        $model = Article::find();
        //创建分页模型
        $page = new Pagination([
            'totalCount'=>$model->count(),//总条数
            'defaultPageSize' => 10,//每页显示多少条
        ]);

        //查询数据
        $rows = $model -> where(['status'=>[0,1]])
            ->offset($page->offset)//偏移
            ->limit($page->limit)//查询条数
            ->all();

        return $this->render('index',['article'=>$rows,'page'=>$page]);
    }

    /*
     * 添加文章
     * */
    public function actionAdd(){
        //实例化模型
        $model = new Article();
        //判断请求方式
        if (\Yii::$app->request->isPost) {//添加新文章 入库
            //接收数据
            $model -> load(\Yii::$app->request->post());
            //验证数据 判断是否验证成功
            if ($model->validate()) {
                //写入数据库
                $model -> save();
                //跳转回列表页面
                $this->redirect(['article/index']);
            }
        }
        //显示添加页面
       return $this -> render('add',['model'=>$model]);

    }

    /*
     * 编辑文章
     * */
    public function actionEdit($id)
    {
        //获得该数据模型
        $model = Article::findOne(['id'=>$id]);
        //判断请求方式
        if (\Yii::$app->request->isPost) {//添加新文章 入库
            //接收数据
            $model -> load(\Yii::$app->request->post());
            //验证数据 判断是否验证成功
            if ($model->validate()) {
                //写入数据库
                $model -> save();
                //跳转回列表页面
                $this->redirect(['article/index']);
            }
        }
        //显示添加页面
        return $this -> render('add',['model'=>$model]);
    }

    /*
     * 删除文章
     * */
    public function actionDel()
    {
        //获得该模型
        $model = Article::findOne(['id'=>\Yii::$app->request->post('id')]);
        if ($model) {
            //修改文章状态
            $model -> status = -1;
            //写入数据库
            $model -> save();
            return 'success';
        }
        return 'false';
    }




}
