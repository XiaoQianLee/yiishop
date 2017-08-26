<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $area
 * @property string $town
 * @property string $address
 * @property integer $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property string $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'tel', 'delivery_id', 'payment_id', 'status', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['province', 'area', 'town'], 'string', 'max' => 20],
            [['address', 'delivery_name', 'payment_name', 'trade_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'province' => '省',
            'area' => '市',
            'town' => '县',
            'address' => '详细地址',
            'tel' => '电话号码',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'payment_id' => '支付方式id',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }

    public function getAddress($address,$delivery,$pay)
    {
        $this-> name = $address-> addressName;//收货人
        $this-> province = $address-> province;//省
        $this-> area = $address -> area;//市
        $this-> town = $address-> town;//县
        $this-> address = $address-> detailed_address;//详细地址
        $this-> tel = $address-> tel;//详细地址
        $this-> delivery_id = $delivery -> id;//配送方式id
        $this-> delivery_name = $delivery -> name;//配送方式名称
        $this-> delivery_price = $delivery -> price;//配送方式价格
        $this-> payment_id = $pay -> id;
        $this ->payment_name = $pay -> name;
        $this-> status = 1;//订单状态待付款

    }

    public function getOrders()
    {
        return $this->hasMany(OrderGoods::className(),['order_id'=>'id']);
    }
}
