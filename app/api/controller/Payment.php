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
        
        try {
            $this->validate(['order_id' => $orderId], \app\api\validate\Payment::class . '.wxpay');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $data = $this->service->wxpay($this->getUid(), ['order_id' => $orderId]);
        return $this->success($data);
    }
}
