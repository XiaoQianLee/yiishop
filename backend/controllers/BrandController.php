<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
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
     *显示品牌列表
     */
    public function actionIndex()
    {
        //创建品牌模型
        $model = Brand::find();
        //创建分页模型
        $page = new Pagination([
            'totalCount'=>$model->count(),//总页数
            'defaultPageSize' => 10,//默认显示条数
            'pageSizeLimit' => [1,20],//页面大小
        ]);

        $rows = $model ->where(['status'=> [0,1]])//条件
            ->offset($page->offset)//偏移多少条
            ->limit($page->limit)//每页显示多少条
            -> all();//查找状态为未删除的品牌显示

        return $this->render('index',['brand' => $rows,'page'=>$page]);
    }

    /*
     * 添加品牌
     */
    public function actionAdd()
    {
        //实例化模型
        $model = new Brand();
        //判断请求方式
        if (\Yii::$app->request->isPost) {//接收数据，入库
            //接收数据
            $model ->load(\yii::$app->request->post());
            //处理上传的图片
            //$model->brand_logo=UploadedFile::getInstance($model,'brand_logo');
            //验证数据
            if ($model->validate()) {//验证成功
                //保存上传文件
//                $filepath='/upload/'.uniqid().".".$model->brand_logo->extension;//文件保存路径和文件名
//                if ($model -> brand_logo ->saveAs(\Yii::getAlias('@webroot').$filepath,false)) {
//                    $model -> logo =$filepath;
//                }
                //写入数据库
                $model -> save();
                //跳转页面
                $this -> redirect(['brand/index']);
            }//else{//验证失败
//                return $this->render('add',['model' => $model]);
//            }
        }//else{//显示添加页面
            //实例化模型
            //$model = new Brand();
            //显示视图
            return $this->render('add',['model' => $model]);
        //}
    }

    /*
     * 编辑品牌信息
     * */
    public function actionEdit($id)
    {
        //实例化模型
        $model = Brand::findOne(['id'=>$id]);
        //判断请求方式
        if (\Yii::$app->request->isPost) {//接收数据，入库
            //接收数据
            $model ->load(\yii::$app->request->post());
            //处理上传的图片
            //$model->brand_logo=UploadedFile::getInstance($model,'brand_logo');
            //验证数据
            if ($model->validate()) {//验证成功
                //保存上传文件
                //$filepath='/upload/'.uniqid().".".$model->brand_logo->extension;//文件保存路径和文件名
//                if ($model -> brand_logo ->saveAs(\Yii::getAlias('@webroot').$filepath,false)) {
//                    $model -> logo =$filepath;
//                }
                //写入数据库
                $model -> save();
                //跳转页面
                $this -> redirect(['brand/index']);
            }//else{//验证失败
//                return $this->render('add',['model' => $model]);
//            }
        }//else{//显示添加页面
        //实例化模型
        //$model = new Brand();
        //显示视图
        return $this->render('add',['model' => $model]);
        //}
    }

    /*
     * 删除品牌信息
     * */

    public function actionDel(){
        //实例化模型
        $model = Brand::findOne(['id'=>\Yii::$app->request->post('id')]);
        if ($model) {
            //逻辑删除 状态改为-1
            $model->status=-1;
            //写入数据库
            $model->save();
            return 'success';
        }
        return 'false';
    }

    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default

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
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl'] = $url;
                },
            ],
        ];
    }


}
