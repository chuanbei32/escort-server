<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class EscortApplication extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "escort_application",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
];

    

}