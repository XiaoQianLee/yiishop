<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\GoodsCategory;
use yii\base\NotSupportedException;
use yii\data\Pagination;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class GoodsCategoryController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'rbac' => [
                'class' => RbacFilter::className(),
            ]
        ];
    }

    /*
     * 显示商品分类列表
     * */
    public function actionIndex()
    {
        //利用查询器查询所有分类信息
        $query = GoodsCategory::find();
        //创建分页模型
        $pager = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 10
        ]);

        //获得所有分类信息
        $models = $query->orderBy('tree,lft')
            ->offset($pager->offset)
            ->limit($pager->limit)
            ->all();

        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    /*
     * 添加商品分类
     * */
    public function actionAdd(){
        //创建模型
        $model = new GoodsCategory();
        //接收数据且验证成功
        if ($model -> load(\Yii::$app->request->post()) && $model->validate()) {
            //验证是否是顶级分类
            if ($model->parent_id) {//子分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);
            }else{//顶级分类
                $model->makeRoot();
            }
            //提示信息
            \Yii::$app->session->setFlash('success','添加分类成功');
            //跳转页面
            return $this->refresh();//刷新页面
        }
        //显示视图
        return $this->render('add',['model'=>$model]);
    }

    /*
     * 删除商品分类
     * */
    public function actionDel($id){
        //$sum = GoodsCategory::find()->where(['parent_id'=>$id])->count()->all();
        try{
            //根据id查找对象
            $model = GoodsCategory::findOne(['id'=>\Yii::$app->request->post('id')]);
            if ($model) {
                //删除对象
                $model -> delete();
                //跳转
                $this->redirect(['goods-category/index']);
                return 'success';
            }
        }catch (NotSupportedException $e){
            return 'false';
        }


    }

    /*
     * 编辑商品分类
     * */
    public function actionEdit($id){
        //创建模型
        $model = GoodsCategory::findOne(['id'=>$id]);
        //接收数据且验证成功
        if ($model -> load(\Yii::$app->request->post()) && $model->validate()) {
            //验证是否是顶级分类
            if($model->parent_id){
                //添加子分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                //创建子分类
                $model->prependTo($parent);
            }else{
                //判定修改前是否是根节点
                if($model->getOldAttribute('parent_id')){
                    //如果修改前不是根节点
                    $model->makeRoot();
                }else{
                    //如果修改前是根节点
                    $model->save();
                }
                //添加顶级分类
                $model->makeRoot();
            }
            //提示信息
            \Yii::$app->session->setFlash('success', '修改分类成功');
            //跳转页面
            return $this->refresh();//刷新页面
        }
        //显示视图
        return $this->render('edit',['model'=>$model]);
    }

}
