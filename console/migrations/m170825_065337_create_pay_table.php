<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pay`.
 */
class m170825_065337_create_pay_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('pay', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('支付方式名称'),
            'intro' => $this->string(255)->comment('支付方式介绍'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('pay');
    }
}
