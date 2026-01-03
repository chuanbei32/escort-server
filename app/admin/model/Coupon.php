<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class Coupon extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "coupon",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
];

    

}