<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class ServicePackage extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "service_package",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
];

    

}