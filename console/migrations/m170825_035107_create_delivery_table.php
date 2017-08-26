<?php

use yii\db\Migration;

/**
 * Handles the creation of table `distribution`.
 */
class m170825_035107_create_delivery_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('delivery', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('配送方式名称'),
            'price'=>$this->decimal(10,2)->comment('配送方式价格'),
            'intro' => $this->string()->comment('配送方式描述'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('delivery');
    }
}
