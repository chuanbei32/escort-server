<?php

namespace app\admin\controller;

use app\common\controller\AdminController;
use app\admin\service\annotation\ControllerAnnotation;
use app\admin\service\annotation\NodeAnnotation;
use think\App;
use think\response\Json;
use think\facade\Config;
use think\facade\Db;
use app\Request;
use app\admin\model\User;
use app\admin\model\Service;
use app\admin\model\UserCoupon;
use app\admin\model\Appointment;
use EasyWeChat\Pay\Application;


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

        Db::startTrans();
        try {
            $orders = self::$model::whereIn('id', $id)->lock(true)->select();
            $orders->isEmpty() && $this->error('数据不存在');

            $config = Config::get('wechat.payment');
            $miniProgramConfig = Config::get('wechat.mini_program');

            // 初始化 EasyWeChat 支付应用
            $app = new Application($config);

            $successCount = 0;
            $failCount = 0;
            $lastError = '';

            foreach ($orders as $order) {
                // 只有已支付的订单才能退款
                if ($order->status != 1) {
                    $failCount++;
                    $lastError = "订单 {$order->order_sn} 状态不正确，无法退款";
                    continue;
                }

                try {
                    // 生成退款单号
                    $outRefundNo = 'REF' . date('YmdHis') . str_pad((string)$order->id, 6, '0', STR_PAD_LEFT);

                    // 调用微信退款接口 (v2) - 注意：v2 退款接口需要使用 secapi 路径并携带证书
                    $response = $app->getClient()->post('secapi/pay/refund', [
                        'xml' => [
                            'appid'         => $miniProgramConfig['app_id'],
                            'mch_id'        => $config['mch_id'],
                            'out_trade_no'  => $order->order_sn,
                            'out_refund_no' => $outRefundNo,
                            'total_fee'     => (int)($order->total_fee * 100), // 总金额，单位分
                            'refund_fee'    => (int)($order->total_fee * 100), // 退款金额，单位分
                        ],
                        'local_cert' => $config['certificate'],
                        'local_pk'   => $config['private_key'],
                    ]);

                    $result = $response->toArray();

                    if (isset($result['return_code']) && $result['return_code'] === 'SUCCESS' &&
                        isset($result['result_code']) && $result['result_code'] === 'SUCCESS') {

                        // 退款成功，更新订单状态
                        $order->status = 2; // 已退款
                        $order->refund_sn = $outRefundNo;
                        $order->refund_time = date('Y-m-d H:i:s');
                        $order->save();

                        // 1. 退回优惠券 (状态改为未使用: 0)
                        if (!empty($order->coupon_id)) {
                            UserCoupon::where('id', $order->coupon_id)->update([
                                'status'    => 0,
                                'used_time' => null,
                                'order_id'  => 0,
                            ]);
                        }

                        // 2. 取消关联的预约 (状态改为已取消: 3)
                        Appointment::where('order_id', $order->id)->update([
                            'status' => 3,
                        ]);

                        $successCount++;
                    } else {
                        $failCount++;
                        $lastError = $result['err_code_des'] ?? ($result['return_msg'] ?? '退款接口调用失败');
                    }
                } catch (\Exception $e) {
                    $failCount++;
                    $lastError = $e->getMessage();
                }
            }

            if ($failCount > 0 && $successCount == 0) {
                throw new \Exception($lastError);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return $this->error("退款失败：" . $e->getMessage());
        }

        if ($successCount > 0 && $failCount == 0) {
            return $this->success("成功退款 {$successCount} 笔订单");
        } else {
            return $this->success("部分退款成功：{$successCount} 笔成功，{$failCount} 笔失败。最后一次错误：{$lastError}");
        }
    }
}