<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property integer $parent_id
 * @property string $url
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'parent_id'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label', 'url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '菜单名称',
            'parent_id' => '上级菜单',
            'url' => '路由/地址',
            'sort' => '菜单排序',
        ];
    }

    /*
     * 获取上级菜单
     * */
    public static function getParentMenu()
    {
        return ArrayHelper::merge(
            [0=>'顶级菜单'],
            ArrayHelper::map(Menu::find()->where(['parent_id'=>0])->all(),'id','label')
        );
    }

    /*
     * 获得所有路由权限
     * */
    public static function getPermissionUrl(){
        return ArrayHelper::merge(
            [''=>'=请选择路由地址='],
            ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description')
        );
    }
}
