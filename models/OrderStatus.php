<?php

namespace app\models;

use yii\db\ActiveRecord;

class OrderStatus extends ActiveRecord {

    public static function tableName() {
        return "orderstatus";
    }
    
    public function rules() {
        return [
            [[
            'id',
            'description',
            'textid'
            ], 'safe']
        ];
    }
}
