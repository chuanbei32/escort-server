<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Appointment as AppointmentModel;

class Appointment
{
    /**
     * 创建预约
     * @param int   $userId
     * @param array $data
     * @return AppointmentModel
     */
    public function create(int $userId, array $data): AppointmentModel
    {
        $data['user_id'] = $userId;
        // 逻辑：保存预约信息
        return AppointmentModel::create($data);
    }
}
