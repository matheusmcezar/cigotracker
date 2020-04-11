<?php

namespace app\models;

use Exception;
use yii\db\ActiveRecord;

class Order extends ActiveRecord {

    public function rules() {
        return [
            [['firstName',
            'lastName',
            'email',
            'phoneNumber',
            'orderType',            
            'orderValue',
            'orderStatus',
            'scheduledDate',
            'streetAddress',
            'city',
            'state',
            'country',
            'postalCode'], 'safe'],

            [['firstName',
            'phoneNumber',
            'orderType',            
            'scheduledDate',
            'streetAddress',
            'city',
            'state',
            'country'], 'required'],

            ['email', 'email']
        ];
    }

    public static function tableName() {
        return "orders";
    }

    public function attributeLabels() {
        return [
            'email' => 'E-mail'
        ];
    }

    public function getJoinStatus() {
        return $this->hasOne(OrderStatus::className(), ['id' => 'orderStatus']);
    }

    public function getJoinType() {
        return $this->hasOne(OrderType::className(), ['id' => 'orderType']);
    }

    public function getCoordinates($apiKey) {
        $address = $this->streetAddress . ' ' .
            $this->city . ' ' .
            $this->state . ' ' .
            $this->country;

        try {
            $response = json_decode(file_get_contents("https://api.geocod.io/v1.4/geocode?q=".str_replace(" ", "+", $address)."&api_key=".$apiKey));

            if (count($response->results) > 0) {
                $this->latitude = $response->results[0]->location->lat;
                $this->longitude = $response->results[0]->location->lng;
            }
        } catch (Exception $e) {
            $this->latitude = null;
            $this->longitude = null;
        }
        

        
    }
}
