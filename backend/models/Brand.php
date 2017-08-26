<?php

namespace backend\models;

use Yii;


/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    public $brand_logo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'status'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '品牌名称',
            'intro' => '品牌简介',
            'logo' => '品牌logo图片',
            'sort' => '品牌排序',
            'status' => '品牌状态',
            'brand_logo' => '品牌Logo图片',
        ];
    }

    /*
     * 关联商品id所属品牌
     * */
    public function getBrand(){
        return $this->hasOne(Goods::className(),['goods_category_id'=>'id']);
    }


}
