<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\data\Pagination;

class MenuController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'rbac' => [
                'class'=> RbacFilter::className(),
            ]
        ];
    }
    /*
     * 添加菜单
     * */
    public function actionAdd(){
        //创建新的菜单模型
        $model = new Menu();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            //提示
            \Yii::$app->session->setFlash('success','添加菜单成功！');
            //跳转
            $this->redirect(['menu/index']);
        }
        //显示视图
        return $this->render('add',['model'=>$model]);
    }


    public function actionIndex()
    {
        $query = Menu::find();
        //创建分页模型
        $pager = new Pagination([
            'totalCount' => $query -> count(),
            'defaultPageSize' => 10
        ]);
        //查询所有菜单
        $models = $query->offset($pager->offset)
            ->limit($pager->limit)
            ->orderBy('id,parent_id')
            ->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    /*
     * 编辑菜单信息
     * */
    public function actionEdit($id){
        //创建新的菜单模型
        $model = Menu::findOne(['id'=>$id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            //提示
            \Yii::$app->session->setFlash('success','修改菜单成功！');
            //跳转
            $this->redirect(['menu/index']);
        }
        //显示视图
        return $this->render('add',['model'=>$model]);
    }

    /*
     * 删除菜单
     * */
    public function actionDel(){
        //通过id获得模型
        $model = Menu::findOne(['id'=>\Yii::$app->request->post('id')]);
        if ($model->parent_id === 0) {
            $count = Menu::find()->where(['parent_id'=>\Yii::$app->request->post('id')])->count();
            if ($count > 0) {
                return 'false';
            }else{
                $model->delete();
                return 'success';
            }
        }

    }

}
