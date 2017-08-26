<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "pay".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 */
class Pay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '支付方式名称',
            'intro' => '支付方式介绍',
        ];
    }
}
