<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Order as OrderService;
use think\Response;

class Order extends Base
{
    protected $service;

    public function __construct(\think\App $app, OrderService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 订单列表
     * @return Response
     */
    public function list(): Response
    {
        $userId = $this->getUid();
        $page = $this->request->get('page/d', 1);
        $limit = $this->request->get('page_size/d', 10);
        $status = $this->request->get('status/d', -1); // -1:全部 0:待支付 1:已支付 2:已退款 3:已完成
        
        try {
            $this->validate([
                'page'      => $page,
                'page_size' => $limit,
                'status'    => $status
            ], \app\api\validate\Order::class . '.list');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $data = $this->service->getOrderList($userId, $page, $limit, $status);
        return $this->success($data);
    }

    /**
     * 订单详情
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        try {
            $this->validate(['id' => $id], \app\api\validate\Common::class . '.id');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $userId = $this->getUid();
        $info = $this->service->getOrderDetail($id, $userId);
        if (!$info) {
            return $this->error('订单不存在');
        }
        return $this->success($info);
    }
}
