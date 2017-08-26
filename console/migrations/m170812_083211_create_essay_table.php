<?php

use yii\db\Migration;

/**
 * Handles the creation of table `essay`.
 */
class m170812_083211_create_essay_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('essay', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('文章标题'),
            'intro'=>$this->text()->notNull()->comment('文章简介'),
            'article_id'=>$this->integer(11)->notNull()->comment('文章分类'),
            'sort'=>$this->integer(11)->notNull()->comment('文章排序'),
            'status'=>$this->smallInteger(2)->notNull()->comment('文章状态'),
            'create_time'=>$this->integer(11)->defaultValue(time())->comment('创建时间')
        ]);

        $this->createTable('essay_detail', [
            'id' => $this->primaryKey(),
            'content' => $this->text()->notNull()->comment('文章内容')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('essay');
        $this->dropTable('essay_detail');
    }
}
