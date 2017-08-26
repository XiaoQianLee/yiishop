<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170816_031944_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户账号'),
            'auth_key' => $this->string(32)->notNull()->comment('cookie身份验证码'),
            'password_hash' => $this->string(255)->notNull()->comment('用户密码'),
            'password_reset_token' => $this->string()->unique()->comment('重置密码口令'),
            'email' => $this->string()->notNull()->unique()->comment('用户邮箱'),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'last_login_time' =>$this->integer()->comment('最后登录时间'),
            'last_login_ip' =>$this->integer() -> comment('最后登录ip'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
