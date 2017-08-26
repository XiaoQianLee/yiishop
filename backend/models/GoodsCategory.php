<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    //插件
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['name', 'parent_id', 'intro'], 'required'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
            ['parent_id','validateParentId'],
        ];
    }
    //自定义验证规则
    public function validateParentId(){
        //验证parent_id 不能和id相同
        //只处理不符合验证规则的情况
        if($this->parent_id == $this->id){
            //设置错误信息
            $this->addError('parent_id','父分类不能是自己');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => '树id',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '商品分类名称',
            'parent_id' => '上级分类',
            'intro' => '分类简介',
        ];
    }

    //声明静态方法返回所有分类信息
    public static function getCategory(){

        $items1 = [0=>'顶级分类'];
        $items2 = ArrayHelper::map(GoodsCategory::find()->all(),'id','name');
        return array_merge($items1,$items2);
    }

    //调用方法返回所有数据 ztree商品分类
    public static function getZtree(){
       return Json::encode(
           ArrayHelper::merge(
               [['id'=>0,'name'=>'顶级分类','parent_id'=>0]],
               self::find()->select(['id','name','parent_id'])->all()
           )
       );
    }

    /*
     * 前台显示商品分类逻辑关联
     * */
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }


}
