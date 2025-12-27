<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class Hospital extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "hospital",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
];

    

}