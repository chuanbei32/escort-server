<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class RecruitApplication extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "recruit_application",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
  'gender' => [
    1 => '男',
    2 => '女',
  ],
];

    

}