<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170820_025133_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'label' => $this->string(50)->notNull()->comment('菜单名称'),
            'parent_id' => $this->integer()->notNull()->comment('上级菜单'),
            'url' => $this->string(50)->comment('路由/地址'),
            'sort'=>$this->integer()->comment('菜单排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
