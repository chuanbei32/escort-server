<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class Order extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "order",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
];

    

}