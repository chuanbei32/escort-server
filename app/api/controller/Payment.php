<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Payment as PaymentService;
use think\Response;

class Payment extends Base
{
    protected $service;

    public function __construct(\think\App $app, PaymentService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 微信支付
     * @return Response
     */
    public function wxpay(): Response
    {
        $orderId = $this->request->post('order_id/d');
        if (!$orderId) {
            return $this->error('订单ID不能为空');
        }

        $data = $this->service->wxpay(['order_id' => $orderId]);
        return $this->success($data);
    }
}
