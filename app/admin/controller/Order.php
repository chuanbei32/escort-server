<?php

namespace app\admin\controller;

use app\common\controller\AdminController;
use app\admin\service\annotation\ControllerAnnotation;
use app\admin\service\annotation\NodeAnnotation;
use think\App;
use think\response\Json;
use app\Request;
use app\admin\model\User;
use app\admin\model\Service;


#[ControllerAnnotation(title: 'order')]
class Order extends AdminController
{

    private array $notes;

    public function __construct(App $app)
    {
        parent::__construct($app);
        self::$model = new \app\admin\model\Order();
        $notes = self::$model::$notes;
        
        $this->notes =$notes;
        $this->assign(compact('notes'));
    }

    #[NodeAnnotation(title: '列表', auth: true)]
    public function index(Request $request): Json|string
    {
        if ($request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParams();

            $newWhere = [];
            foreach ($where as $key => $value) {
                switch ($value[0]) {
                    case 'order_sn':
                        $newWhere['order_sn'] = $value[2];
                        break;
                    case 'status':
                        $newWhere['status'] = $value[2];
                        break;
                    case 'user_nickname':
                        $newWhere['user_id'] = User::where('nickname', 'like', "%{$value[2]}%")->value('id');
                        break;
                    case 'service_name':
                        $newWhere['service_id'] = Service::where('name', 'like', "%{$value[2]}%")->value('id');
                        break;
                }
            }
            $count = self::$model::with(['user', 'service', 'userCoupon'])->where($newWhere)->count();
            $list  = self::$model::with(['user', 'service', 'userCoupon'])->withAttr('is_coupon')->where($newWhere)->page($page, $limit)->order($this->sort)->select()->toArray();
            $data  = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }

    #[NodeAnnotation(title: '退款', auth: true)]
    public function refund(Request $request): Json|string
    {
        $id = $request->param('id', []);
        $this->checkPostRequest();
        $row = self::$model::whereIn('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
        try {
            // 调用支付宝退款接口
            $save = $row->refund();
        }catch (\Exception $e) {
            $this->error('退款失败');
        }
        $save ? $this->success('退款成功') : $this->error('退款失败');
    }
}