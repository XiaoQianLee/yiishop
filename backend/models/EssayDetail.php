<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "essay_detail".
 *
 * @property integer $id
 * @property string $content
 */
class EssayDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'essay_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '文章内容',
        ];
    }
}
