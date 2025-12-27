<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;

class Appointment extends Validate
{
    protected $rule = [
        'service_id'    => 'require|number',
        'hospital_id'   => 'require|number',
        'expected_time' => 'require',
        'patient_name'  => 'require|max:20',
        'patient_phone' => 'require|mobile',
    ];

    protected $message = [
        'service_id.require'    => '请选择服务',
        'hospital_id.require'   => '请选择医院',
        'expected_time.require' => '请选择预约时间',
        'patient_name.require'  => '请输入就诊人姓名',
        'patient_phone.require' => '请输入联系电话',
        'patient_phone.mobile'  => '联系电话格式不正确',
    ];

    protected $scene = [
        'create' => ['service_id', 'hospital_id', 'expected_time', 'patient_name', 'patient_phone'],
    ];
}
