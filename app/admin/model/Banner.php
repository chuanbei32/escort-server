<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class Banner extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "banner",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
];

    

}