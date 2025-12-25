<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Appointment as AppointmentService;
use think\Response;

class Appointment extends Base
{
    protected $service;

    public function __construct(\think\App $app, AppointmentService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 创建预约
     * @return Response
     */
    public function create(): Response
    {
        $params = $this->request->only([
            'service_id',
            'hospital_id',
            'department',
            'expected_time',
            'patient_name',
            'patient_gender',
            'patient_phone',
            'address',
            'escort_gender_preference',
            'requirements'
        ]);

        // 验证必填项
        if (empty($params['hospital_id']) || empty($params['service_id']) || empty($params['patient_name']) || empty($params['patient_phone']) || empty($params['expected_time'])) {
            return $this->error('缺少必要参数');
        }

        $res = $this->service->create($this->getUid(), $params);
        return $this->success($res);
    }
}
