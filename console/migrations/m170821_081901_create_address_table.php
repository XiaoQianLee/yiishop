<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170821_081901_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull()->comment('所属账号'),
            'addressName' => $this->string(30)->notNull()->comment('收货人姓名'),
            'province' => $this->string(255)->notNull()->comment('所属省份'),
            'city' => $this->string(255)->notNull()->comment('所属市区'),
            'town' => $this->string(255)->notNull()->comment('所属县镇'),
            'detailed_address' => $this->string(255)->notNull()->comment('详细地址'),
            'tel' => $this->integer(11)->notNull()->comment('联系号码'),
            'status' =>$this->integer(1)->defaultValue(0)->comment('地址状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
