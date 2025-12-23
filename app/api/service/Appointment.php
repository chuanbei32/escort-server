<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Appointment as AppointmentModel;

class Appointment
{
    /**
     * 创建预约
     * @param array $data
     * @return AppointmentModel
     */
    public function create(array $data): AppointmentModel
    {
        // 逻辑：保存预约信息
        return AppointmentModel::create($data);
    }
}
