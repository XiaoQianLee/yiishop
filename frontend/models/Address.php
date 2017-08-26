<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $addressName
 * @property string $province
 * @property string $city
 * @property string $town
 * @property string $detailed_address
 * @property integer $tel
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    public $default;//是否默认地址
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'addressName', 'province', 'area', 'town', 'detailed_address', 'tel'], 'required'],
            [['member_id', 'tel', 'status'], 'integer'],
            [['addressName'], 'string', 'max' => 30],
            [['province', 'area', 'town', 'detailed_address'], 'string', 'max' => 255],
            [['default'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '所属账号',
            'addressName' => '收货人姓名',
            'province' => '所属省份',
            'area' => '所属市区',
            'town' => '所属县镇',
            'detailed_address' => '详细地址',
            'tel' => '联系号码',
            'status' => '地址状态',
        ];
    }
}
