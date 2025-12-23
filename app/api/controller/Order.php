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
        // 假设当前用户ID为1
        $userId = 1;
        $page = $this->request->get('page/d', 1);
        $limit = $this->request->get('page_size/d', 10);
        
        $data = $this->service->getOrderList($userId, $page, $limit);
        return $this->success($data);
    }

    /**
     * 订单详情
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        // 假设当前用户ID为1
        $userId = 1;
        $info = $this->service->getOrderDetail($id, $userId);
        if (!$info) {
            return $this->error('订单不存在');
        }
        return $this->success($info);
    }
}
