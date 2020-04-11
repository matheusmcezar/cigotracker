<?php

namespace app\models;

use yii\db\ActiveRecord;

class OrderType extends ActiveRecord {

    public static function tableName() {
        return "ordertypes";
    }
    
    public function rules() {
        return [
            [[
            'id',
            'description'
            ], 'safe']
        ];
    }
}
