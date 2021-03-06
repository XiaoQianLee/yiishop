<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170824_081710_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'amount' => $this->integer()->comment('商品数量'),
            'member_id' => $this->integer()->comment('所属用户id')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
