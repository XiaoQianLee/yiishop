<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170814_033644_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_day_count', [
            'day' => $this->date()->comment('日期'),
            'count' => $this->integer(6)->comment('商品数量'),
        ]);
        $this->addPrimaryKey('day','goods_day_count','day');
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->notNull()->comment('商品名称'),
            'sn'=>$this->string(20)->notNull()->comment('商品货号'),
            'logo'=>$this->string(255)->notNull()->comment('LOGO图片'),
            'goods_category_id'=>$this->integer(11)->notNull()->comment('商品分类'),
            'brand_id'=>$this->integer(11)->notNull()->comment('品牌分类'),
            'market_price'=>$this->decimal(10,2)->comment('市场价格'),
            'shop_price'=>$this->decimal(10,2)->notNull()->comment('商品价格'),
            'stock'=>$this->integer(11)->notNull()->comment('商品库存'),
            'is_on_sale'=>$this->integer(1)->notNull()->comment('是否上架出售'),
            'status'=>$this->integer(1)->notNull()->comment('商品状态'),
            'sort'=>$this->integer(11)->notNull()->comment('商品排序'),
            'create_time'=>$this->integer(11)->comment('添加时间'),
            'view_times'=>$this->integer(11)->comment('浏览次数')
        ]);
        $this->createTable('goods_intro', [
            'goods_id' => $this->integer(11)->comment('商品id'),
            'content'=>$this->text()->comment('商品描述')
        ]);
        $this->createTable('goods_gallery', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer(11)->comment('商品id'),
            'path'=>$this->string(255)->comment('图片地址')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_day_count');
        $this->dropTable('goods');
        $this->dropTable('goods_intro');
        $this->dropTable('goods_gallery');
    }
}
