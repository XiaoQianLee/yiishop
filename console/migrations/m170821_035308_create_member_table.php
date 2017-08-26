<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170821_035308_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->unique()->notNull()->comment('用户名'),
            'auth_key' => $this->string(32),
            'password_hash'=>$this->string(100)->comment('用户密码(密文)'),
            'email' => $this->string(100)->notNull()->unique()->comment('用户邮箱'),
            'tel' => $this->integer(11)->notNull()->comment('电话号码'),
            'last_login_time' => $this->integer()->comment('最后登录时间'),
            'last_login_ip' => $this->integer()->comment('最后登录ip'),
            'status' => $this->integer(1)->defaultValue(1)->comment('账号状态'),
            'created_at' => $this->integer()->comment('添加时间'),
            'updated_at' => $this -> integer() -> comment('修改时间')


        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
