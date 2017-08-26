<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class GoodsController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'rbac' => [
                'class'=> RbacFilter::className(),
                'except'=> ['s-upload','upload'],
            ]
        ];
    }
    /*
     * 商品列表
     * */
    public function actionIndex()
    {
        //商品数据查询器
        $query = Goods::find()->where(['status'=>1]);
        $name = \Yii::$app->request->get('good_name');
        //判断是否有搜索
        if ($name) {
            $query->andWhere(['like','name',$name]);
        }
        //创建分页模型
        $pager = new Pagination([
            'totalCount' => $query -> count(),
            'defaultPageSize' => 5
        ]);
        $models = $query->offset($pager->offset)
            ->limit($pager->limit)
            ->all();
        return $this->render('index',['models' => $models,'pager' => $pager]);
    }

    /*
     * 添加商品数据
     * */
    public function actionAdd(){
        //创建新的商品基本信息模型
        $model = new Goods();
        //创建新的商品描述模型
        $model_intro = new GoodsIntro();
        //获取信息并验证
        if ($model->load(\Yii::$app->request->post()) && $model_intro->load(\Yii::$app->request->post()) && $model->validate() && $model_intro->validate()) {
            if (GoodsCategory::findOne(['id'=>$model->goods_category_id])->depth != 2) {
                \Yii::$app->session->setFlash('error','请选择具体商品分类');
                return $this->refresh();
            }
            //写入数据库
            $model->save();
            //获取id 写入商品描诉
            $model_intro->goods_id = $model->id;
            //写入数据库
            $model_intro->save();
            //提示信息
            \Yii::$app->session->setFlash('sueecss','添加商品成功');
            //跳转
            $this->redirect(['goods/index']);
        }
        //显示视图
        return $this->render('add',['model'=>$model,'model_intro'=>$model_intro]);
    }

    /*
     * 删除商品数据 逻辑删除
     * */
    public function actionDel($id)
    {
        //通过id获得模型对象
        $model = Goods::findOne(['id'=>\Yii::$app->request->post('id')]);
        if ($model) {
            //修改商品状态 放入回收站
            $model -> status = 0;
            //将数据保存到数据库
            $model -> save();
            return 'success';
        }
        return 'false';
    }

    /*
     * 编辑商品信息
     * */
    public function actionEdit($id){
        //创建新的商品基本信息模型
        $model = Goods::findOne(['id'=>$id]);
        //创建新的商品描述模型
        $model_intro = GoodsIntro::findOne(['goods_id'=>$id]);
        //获取信息并验证
        if ($model->load(\Yii::$app->request->post()) && $model_intro->load(\Yii::$app->request->post()) && $model->validate() && $model_intro->validate()) {
            //写入数据库
            $model->save();
            $model_intro->save();
            //提示信息
            \Yii::$app->session->setFlash('sueecss','修改商品信息成功');
            //跳转
            $this->redirect(['goods/index']);
        }
        //显示视图
        return $this->render('edit',['model'=>$model,'model_intro'=>$model_intro]);
    }

    /*
     * 相册展示相片功能
     * */
    public function actionGallery($id)
    {
        //查询所有该商品图片信息
        $models = GoodsGallery::find()->where(['goods_id'=>$id])->all();

        //显示视图
        return $this->render('gallery',['models'=>$models,'goods_id'=>$id]);

    }

    public function actionImgDel()
    {
        //通过id获得图片模型
        $model = GoodsGallery::findOne(['id'=>\Yii::$app->request->post('id')]);
        if($model){
            $model->delete();
            return 'sueecss';
        }
        return 'false';

    }



    public function actions()
    {
        return [
             's-upload' => [
                    'class' => UploadAction::className(),
                    'basePath' => '@webroot/upload/images',
                    'baseUrl' => '@web/upload/images',
                    'enableCsrf' => true, // default
                    'postFieldName' => 'Filedata', // defaul
             'overwriteIfExist' => true,
            'format' => function (UploadAction $action) {
                $fileext = $action->uploadfile->getExtension();
                $filehash = sha1(uniqid() . time());
                $p1 = substr($filehash, 0, 2);
                $p2 = substr($filehash, 2, 2);
                return "{$p1}/{$p2}/{$filehash}.{$fileext}";
            },
            //END CLOSURE BY TIME
            'validateOptions' => [
                'extensions' => ['jpg', 'png'],
                'maxSize' => 1 * 1024 * 1024, //file size
            ],
            'beforeValidate' => function (UploadAction $action) {
                //throw new Exception('test error');
            },
            'afterValidate' => function (UploadAction $action) {},
            'beforeSave' => function (UploadAction $action) {},
            'afterSave' => function (UploadAction $action) {
    //                    $action->output['fileUrl'] = $action->getWebUrl();
    //                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
    //                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
    //                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                $goods_id = \Yii::$app->request->post('goods_id');
                $config = [
                    'accessKey'=>'wyc6ULNVVwHxFsBRI9_h4f6q1T2zntL0PJGQ8T1T',//AK
                    'secretKey'=>'uPm7PMrdQ3dryCwOdEhnKwd2CXdCSWLRbzxfArsE',//SK
                    'domain'=>'http://oukaup91e.bkt.clouddn.com',//测试域名
                    'bucket'=>'yiishop',//存储空间
                    'area'=>Qiniu::AREA_HUADONG//区域
                ];
                $qiniu = new Qiniu($config);
                $key = $action->getWebUrl();//文件名
                $file = $action->getSavePath();
                $qiniu->uploadFile($file,$key);
                $url = $qiniu->getLink($key);//上传的地址
                if ($goods_id) {
                    $model = new GoodsGallery();
                    $model -> goods_id = $goods_id;
                    $model -> path = $url;
                    $model -> save();
                    $action->output['fileUrl'] = $url;
                    $action->output['id'] = $model->id;
                }else{
                    $action->output['fileUrl'] = $url;
                }

            },
         ],
            //富文本框
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];

    }


}
