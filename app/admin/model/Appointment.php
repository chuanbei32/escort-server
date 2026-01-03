<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class Appointment extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "appointment",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
        'type' => [
            1 => '单次',
            2 => '套餐',
        ],
        'gender' => [
            1 => '男',
            0 => '女',
        ],
        'gender_preference' => [
            0 => '不限',
            1 => '男',
            2 => '女',
        ],
        'status' => [
            0 => '待预约',
            1 => '使用中',
            2 => '已取消',
        ],
    ];

    /**
     * 关联订单
     * @return \think\model\relation\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * 关联用户
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 关联服务
     * @return \think\model\relation\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    /**
     * 关联医院
     * @return \think\model\relation\BelongsTo
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id', 'id');
    }

    /**
     * 关联陪诊师
     * @return \think\model\relation\BelongsTo
     */
    public function escort()
    {
        return $this->belongsTo(EscortApplication::class, 'escort_id', 'id');
    }

    

}