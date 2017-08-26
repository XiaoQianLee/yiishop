<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170810_074900_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('文章名称'),
            'intro' => $this->text()->notNull()->comment('文章简介'),
            'sort' => $this->integer(11)->defaultValue(0)->comment('文章排序'),
            'status' => $this->smallInteger(2)->notNull()->comment('文章状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
