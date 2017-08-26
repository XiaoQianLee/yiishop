<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Pay;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\Cookie;

class HomeController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    /*
     * 显示商场首页
     * */
    public function actionIndex()
    {
        $models = GoodsCategory::find()->where(['parent_id'=>0])->all();

        return $this->render('index',['models'=>$models]);
    }

    /*
     * 显示商品列表页
     * */
    public function actionList($id)
    {
        $goods_catagory = GoodsCategory::findOne(['id'=>$id]);

        if ($goods_catagory-> depth == 2) {//三级分类
            $query = Goods::find();
            $pager = new Pagination([
                'totalCount' => $query->count(),
                'defaultPageSize' => 8
            ]);
            $models = $query->where(['goods_category_id'=>$id])->andWhere(['is_on_sale'=>1])->andWhere(['status'=>1])->offset($pager->offset)->limit($pager->limit)->all();

        }else{//一级分类，二级分类
            $query = Goods::find();
            $pager = new Pagination([
                'totalCount' => $query->count(),
                'defaultPageSize' => 8
            ]);
            $cates = GoodsCategory::find()->where("depth=2 AND lft>$goods_catagory->lft AND rgt<$goods_catagory->rgt AND tree=$goods_catagory->tree")->all();
            $ids = [];
            foreach ($cates as $c){
                $ids[] = $c->id;
            }
            $models = $query->where(['is_on_sale'=>1])->andWhere(['in','goods_category_id',$ids])->all();
        }
        //显示视图
        return $this->render('list',['models'=>$models,'pager'=>$pager]);
    }

    /*
     * 商品页
     * */
    public function actionGoods($id)
    {
        $goods_categorys = GoodsCategory::find()->where(['parent_id'=>0])->all();
        //根据id查询该商品信息
        $model = Goods::findOne(['id'=>$id]);
        $good = GoodsIntro::findOne(['goods_id'=>$id]);
        $good_gallery = GoodsGallery::findAll(['goods_id'=>$id]);
        //显示视图
        return $this->render('goods',['model'=>$model,'good'=>$good,'good_gallery'=>$good_gallery,'goods_categorys'=>$goods_categorys]);
    }

    /*
     * 加入购物车
     * */
    public function actionAddCart($goods_id,$amount)
    {
        //判断是否登录
        if (\Yii::$app->user->isGuest) {//未登录，保存到cookie中
            //1.查看是否有cookie中是否有carts
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if ($carts == null) {
                $carts = [];//不存在就创建购物车
            }else{
                $carts = unserialize($carts);//存在，反序列化购物车数据
            }
            if (array_key_exists($goods_id,$carts)) {//判断购物车中是否存在该商品
                $carts[$goods_id] += $amount;//存在 增加该商品数量
            }else{
                $carts[$goods_id] = $amount;//不存在，保存该商品id和数量
            }
            //将购物车数据保存到cookie中
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name' => 'carts',//键名
                'value' => serialize($carts),//键值
                'expire' => time()+24*3600,//过期时间
            ]);
            $cookies->add($cookie);
        }else{//已经登录,保存到数据库中
            $member_id = \Yii::$app->user->identity->getId();//获取当前登录用户的id
            $model = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);//查看购物车是否有该商品
            if ($model) {//如果有
                $model->amount = $model->amount+$amount;//原数量+添加的数量
            }else{//如果没有就新添加
                $model = new Cart();
                $model -> goods_id = $goods_id;
                $model -> amount = $amount;
                $model -> member_id = $member_id;
            }
            $model -> save();
        }
        //跳转购物车
        $this->redirect(['home/cart']);
    }

    public function actionCart(){
        if (\Yii::$app->user->isGuest) {//未登录
            $cookies = \Yii::$app->request->cookies;
            //查看cookie中是否有cart
            $carts = $cookies->getValue('carts');
            if ($carts == null) {
                $carts = [];
            }else{
                $carts = unserialize($carts);
            }
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();//取出所有cookie购物车中的商品信息
        }else{
            $cart = Cart::find()->where(['member_id'=>\Yii::$app->user->identity->getId()])->all();
            $carts = [];
            foreach ($cart as $v) {
                $carts[$v->goods_id] = $v->amount;
            }
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
        }
        return $this->render('cart',['models'=>$models,'carts'=>$carts]);
    }

    /*
     * 修改商品数量
     * */
    public function actionEditCart()
    {
        //接收数据
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        //判断用户是否登录
        if (\Yii::$app->user->isGuest) {//未登录
            //查看cookie中是否有购物车
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');//获得cookie中carts数据
            if ($carts == null) {//如果为空，cookie中没有购物车信息
                return '购物车内暂时没有商品，请添加后重试！';
            }else{
                $carts = unserialize($carts);//反序列化取得购物车中的商品信息
            }
            //查询该商品是否在购物车内
            if (array_key_exists($goods_id,$carts)) {//该商品存在
                if ($amount == 0) {//如果数量为0，
                    unset($carts[$goods_id]);//删除该商品
                }else{
                    $carts[$goods_id] = $amount;//修改商品数量
                }
                //写入cookie中
                $cookies = \Yii::$app->response->cookies;
                $cookie = new Cookie([
                    'name' => 'carts',
                    'value' => serialize($carts),
                    'expire' => time()+24*3600,
                ]);
                $cookies->add($cookie);
                return 'success';
            }else{//该商品不存在
                return '该商品不存在，请刷新后重试';
            }
        }else{//已登录
            //查看该用户的该商品
            $good = Cart::findOne(['member_id'=>\Yii::$app->user->identity->getId(),'goods_id'=>$goods_id]);
            if ($amount == 0) {//如果数量为0
                $good->delete();//删除该商品对象
                return 'success';
            }else{
                $good->amount = $amount;//修改商品数量
                $good->save();
                return 'success';
            }

        }
    }

    /*
     * 购物车结算订单
     * */
    public function actionOrder()
    {
        if (\Yii::$app->user->isGuest) {//未登录
            //跳转到登录页面进行登录
            $this->redirect(['member/login']);
        }else{//已登录
            //获得已登录用户的id
            $member_id = \Yii::$app->user->identity->getId();
            //获得该用户的所有购物地址
            $address = Address::findAll(['member_id'=>$member_id]);
            //获取所有的快递方式
            $delivery = Delivery::find()->all();
            //获得所有支付方式
            $pay = Pay::find()->all();
            //获得该用户所有购物车商品
            $carts = Cart::find()->where(['member_id'=>$member_id])->all();
            $ids = [];
            foreach ($carts as $cart) {
                $ids[] = $cart->goods_id;
            }
            $goods = Goods::find()->where(['in','id',$ids])->all();

            return $this->render('order',['address'=>$address,'delivery'=>$delivery,'pay'=>$pay,'goods'=>$goods]);
        }
    }

    /*
     * 提交订单
     * */
    public function actionSubmitOrder()
    {
        //接收数据
        $address_id = \Yii::$app->request->post('address_id');
        $delivery_id = \Yii::$app->request->post('delivery_id');
        $pay_id = \Yii::$app->request->post('pay_id');
        $total = \Yii::$app->request->post('total');
        //开启事物
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            //创建订单
            $order = new Order();
            $order -> member_id = \Yii::$app->user->identity->getId();
            //根据id查询收货具体信息
            $address = Address::findOne(['id'=>$address_id]);
            //根据id查询具体配送方式
            $delivery = Delivery::findOne(['id'=>$delivery_id]);
            //根据id查询具体支付方式
            $pay = Pay::findOne(['id'=>$pay_id]);
            $order->getAddress($address,$delivery,$pay);
            $order -> total = $total;
            if ($order -> validate()) {
                $order -> save();
            }else{
                //提示错误信息
                var_dump($order->getErrors());exit;
            }
            //依次检查购物车的库存
            $carts = Cart::findAll(['member_id'=>\Yii::$app->user->identity->getId()]);
            foreach ($carts as $cart) {
                $goods = Goods::findOne(['id'=>$cart->goods_id]);
                if($goods->stock < $cart->amount){//判断商品库存是否充足
                    //抛出异常
                    throw new Exception($goods->name.'商品库存不足，请返回购物车修改');
                }
                //添加订单商品表
                $order_goods = new OrderGoods();
                $order_goods -> order_id = $order->id;//订单号
                $order_goods -> goods_id = $goods->id;//商品id号
                $order_goods -> goods_name = $goods->name;
                $order_goods -> logo = $goods->logo;
                $order_goods -> price = $goods -> shop_price;
                $order_goods -> amount = $cart -> amount;
                $order_goods -> total = $goods -> shop_price*$cart -> amount;
                $order_goods->save();
                //扣减库存
                Goods::updateAllCounters(['sotck'=>-$cart->amount],['goods_id'=>$cart->goods_id]);

            }
            foreach ($carts as $cart) {
                $cart->delete();
            }
            //提交事务
            $transaction->commit();
            //跳转
            $this->redirect(['home/win-order']);

        }catch (Exception $e) {
            //回滚事物
            $transaction->rollBack();
        }
    }

    public function actionWinOrder()
    {
        return $this->render('sub-order');
    }

    /*
     * 查看我的订单
     * */
    public function actionMyOrder()
    {
        if (\Yii::$app->user->isGuest) {//未登录
            $this->redirect(['member/login']);
        }else{//已登录
            //获得该用户所有订单
            $orders = Order::find()->where(['member_id'=>\Yii::$app->user->identity->getId()])->all();
                $ids = [];
                foreach ($orders as $v) {
                    $ids[] = $v->id;
                }
                $goods = OrderGoods::find()->where(['in','order_id',$ids])->all();


            return $this->render('my-order',['goods'=>$goods]);
        }
    }


}
