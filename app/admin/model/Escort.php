<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class Escort extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "escort",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
        'gender' => [
            0 => '未知',
            1 => '男',
            2 => '女',
        ],
];

    

}