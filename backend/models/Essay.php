<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "essay".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Essay extends \yii\db\ActiveRecord
{
    /*
     * 建立文章与文章分类之间的关系
     * */
    public function getCategory()
    {
        return $this->hasOne(Article::className(),['id'=>'article_id']);
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'essay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'article_id', 'sort'], 'required'],
            [['intro'], 'string'],
            [['article_id', 'sort', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '文章标题',
            'intro' => '文章简介',
            'article_id' => '文章分类',
            'sort' => '文章排序',
            'status' => '文章状态',
            'create_time' => '创建时间',
        ];
    }
}
