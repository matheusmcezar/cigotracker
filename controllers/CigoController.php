<?php

namespace app\controllers;

use app\Geocoder\Geocoder;
use Yii;
use yii\web\Controller;
use app\models\Order;
use app\models\OrderStatus;
use app\models\OrderType;
use app\models\Country;

class CigoController extends Controller {
    private $apiKey = "e8807c9ce09c25d628766d852d0d627dd7e9225";

    public function actionIndex() {
        $order = new Order;

        if ($postOrder = Yii::$app->request->post()) {
            $order->attributes = $postOrder['Order'];

            $status = OrderStatus::find()
                        ->where(['textid' => 'PE'])
                        ->one();

            $order->orderStatus = $status->id;

            $order->getCoordinates($this->apiKey);

            $order->orderValue = str_replace(",", "", $order->orderValue);

            if ($order->latitude != null || $order->longitude != null && $order->validate()) {
                $order->save();
            }

            // clear the fields
            $order = new Order;
        }

        $orderTypes = OrderType::find()->all();
        $countries = Country::find()->all();

        $otView = array();
        foreach ($orderTypes as $ot) {
            $otView[$ot->id] = $ot->description;
        }
        $cView = array();
        foreach ($countries as $c) {
            $cView[$c->id] = $c->country;
        }


        return $this->render('index', ['order'=>$order, 'orderTypes'=>$otView, 'countries'=>$cView]);
    }

    public function actionGetOrders() {
        $orders = Order::find()
                    ->joinWith("joinStatus")
                    ->joinWith("joinType")
                    ->all();

        $ordersRet = array();

        foreach ($orders as $o) {
            $orderRet = $o->getAttributes();
            $related = $o->getRelatedRecords();
            $orderRet['orderStatus'] = $related['joinStatus']->getAttributes();
            $orderRet['orderType'] = $related['joinType']->getAttributes();
            $ordersRet[] = $orderRet;
        }
        return json_encode($ordersRet);
    }

    public function actionGetOrderStatus() {
        $status = OrderStatus::find()
                    ->all();

        $statusRet = array();
        foreach ($status as $s) {
            $statusRet[] = $s->getAttributes();
        }
        return json_encode($statusRet);
    }

    function actionDeleteOrder() {
        $order = Order::find()
                    ->where(['id' => Yii::$app->request->get("id")])
                    ->one();

        $order->delete();

        $this->goHome();
    }

    function actionUpdateOrderStatus() {
        $order = Order::find()
                    ->where(['id' => Yii::$app->request->get("orderid")])
                    ->one();

        $order->orderStatus = Yii::$app->request->get("statusid");

        $order->update();

        $this->goHome();
    }

    function actionGetOrder() {
        $order = Order::find()
                    ->where(['id' => Yii::$app->request->get("orderid")])
                    ->one();

        $order = $order->getAttributes();
        return json_encode($order);
    }

    function actionGetLatlng() {
        $order = new Order();

        $order->streetAddress = Yii::$app->request->get("streetAddress");
        $order->city = Yii::$app->request->get("city");
        $order->state = Yii::$app->request->get("state");
        $order->country = Yii::$app->request->get("country");
        $order->postalCode = Yii::$app->request->get("postalCode");

        $order->getCoordinates($this->apiKey);

        return json_encode($order->getAttributes());
    }
}
