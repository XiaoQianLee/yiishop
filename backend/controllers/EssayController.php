<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\Essay;
use backend\models\EssayDetail;
use yii\data\Pagination;

class EssayController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'rbac' => [
                'class'=>RbacFilter::className(),
            ]
        ];
    }

    /*
     * 查询所有文章
     * */
    public function actionIndex()
    {
        //查询所有文章列表
        $model = Essay::find();
        //创建分页模型
        $page = new Pagination([
            'totalCount' => $model->count(),//总页数
            'defaultPageSize' => 5,//默认显示条数
        ]);

        //查询数据
        $rows = $model -> where(['status'=> [0,1]])
            ->offset($page->offset)
            ->limit($page->limit)
            ->all();

        return $this->render('index',['essay'=>$rows,'page'=>$page]);
    }

    /*
     * 添加文章
     * */
    public function actionAdd(){
        //创建新的模型
        $model_essay = new Essay();
        $model_detail = new EssayDetail();
        //创建文章分类查询器
        $query = Article::find();
        //查询文章分类
        $models = $query->where(['status'=>[0,1]])->all();
        //数据请求
        if (\Yii::$app->request->isPost) {
            //接收数据
            $model_essay->load(\Yii::$app->request->post());
            $model_detail->load(\Yii::$app->request->post());
            //验证数据
            if ($model_essay->validate() && $model_detail->validate()) {
                $model_essay->create_time = time();
                //写入数据库
                $model_essay->save();
                $model_detail->save();
                //跳转
                $this -> redirect(['essay/index']);
            }
        }
        //显示视图
        return $this -> render('add',['model_essay'=>$model_essay,'model_detail'=>$model_detail,'models'=>$models]);
    }

    /*
     * 查看文章详情
     * */
    public function actionLook($id){
        //根据id查找
        $model = Essay::findOne(['id'=>$id]);
        $model_detail = EssayDetail::findOne(['id'=>$id]);

        return $this->render('look',['model'=>$model,'model_detail'=>$model_detail]);
    }

    /*
     * 编辑文章
     * */
    public function actionEdit($id){
        //创建新的模型
        $model_essay = Essay::findOne(['id'=>$id]);
        $model_detail = EssayDetail::findOne(['id'=>$id]);
        //创建文章分类查询器
        $query = Article::find();
        //查询文章分类
        $models = $query->where(['status'=>[0,1]])->all();
        //数据请求
        if (\Yii::$app->request->isPost) {
            //接收数据
            $model_essay->load(\Yii::$app->request->post());
            $model_detail->load(\Yii::$app->request->post());
            //验证数据
            if ($model_essay->validate() && $model_detail->validate()) {
                //写入数据库
                $model_essay->save();
                $model_detail->save();
                //跳转
                $this -> redirect(['essay/index']);
            }
        }
        //显示视图
        return $this -> render('add',['model_essay'=>$model_essay,'model_detail'=>$model_detail,'models'=>$models]);
    }

    /*
     * 删除文章
     * */
    public function actionDel(){
        //创建查找器
        $model = Essay::findOne(['id'=>\Yii::$app->request->post('id')]);
        if ($model) {
            $model -> status = -1;
            $model -> save();
            return 'success';
        }
        return 'false';
    }

}
