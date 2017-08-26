<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170810_070909_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('品牌名称'),
            'intro' => $this->text()->notNull()->notNull()->comment('品牌简介'),
            'logo' => $this->string(255)->comment('品牌logo图片'),
            'sort' => $this->integer(11)->defaultValue(0)->comment('品牌排序'),
            'status' => $this->smallInteger(2)->notNull()->comment('品牌状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
