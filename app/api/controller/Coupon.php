<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Coupon as CouponService;
use think\Response;

class Coupon extends Base
{
    protected $service;

    public function __construct(\think\App $app, CouponService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 优惠券列表
     * @return Response
     */
    public function list(): Response
    {
        $userId = $this->getUid();
        // $status = $this->request->get('status/d', 0);
        $page = $this->request->get('page/d', 1);
        $pageSize = $this->request->get('page_size/d', 10);
        
        try {
            // $this->validate([
            //     'status'    => $status,
            //     'page'      => $page,
            //     'page_size' => $pageSize
            // ], \app\api\validate\Common::class . '.status');
            $this->validate([
                'page'      => $page,
                'page_size' => $pageSize
            ], \app\api\validate\Common::class . '.paging');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        // $data = $this->service->getCouponList($userId, $status, $page, $pageSize);
        $data = $this->service->getCouponList($userId, $page, $pageSize);
        return $this->success($data);
    }
}
