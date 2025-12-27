<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class Service extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "service",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
];

    

}