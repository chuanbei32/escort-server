<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\model\User as UserModel;
use EasyWeChat\Pay\Application;
use think\facade\Config;
use think\facade\Log;
use think\facade\Request;

class Payment
{
    /**
     * 微信支付
     * @param int   $userId
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function wxpay(int $userId, array $data): array
    {
        $orderId = $data['order_id'];
        
        // 1. 获取订单详情
        $order = OrderModel::with(['appointments.serviceInfo'])->where('id', $orderId)
            ->where('user_id', $userId)
            ->find();
            
        if (!$order) {
            throw new \Exception('订单不存在');
        }
        
        if ($order->status != 0) {
            throw new \Exception('订单状态异常，无法支付');
        }
        
        // 2. 获取用户 openid
        $user = UserModel::find($userId);
        if (!$user || empty($user->openid)) {
            throw new \Exception('用户未绑定微信');
        }
        
        // 3. 初始化微信支付
        $config = Config::get('wechat.payment');
        $miniProgramConfig = Config::get('wechat.mini_program');
        $app = new Application($config);
        
        // 4. 下单
        $response = $app->getClient()->post('pay/unifiedorder', [
            'xml' => [
                'appid'            => (string)$miniProgramConfig['app_id'],
                'mch_id'           => (string)$config['mch_id'],
                'out_trade_no'     => (string)$order->order_sn,
                'body'             => '陪诊服务',
                'notify_url'       => $config['notify_url'],
                'total_fee'        => (int)($order->total_fee * 100), // 转为分
                'trade_type'       => 'JSAPI',
                'openid'           => $user->openid,
                'spbill_create_ip' => Request::ip(),
            ],
        ]);
        
        $result = $response->toArray();
        
        if (!isset($result['prepay_id'])) {
            Log::error('微信支付下单失败：' . json_encode($result, JSON_UNESCAPED_UNICODE));
            throw new \Exception('支付下单失败');
        }
        
        // 5. 生成支付参数
        $utils = $app->getUtils();
        $params = $utils->buildBridgeConfig($result['prepay_id'], $miniProgramConfig['app_id'], 'MD5');
        
        return $params;
    }

    /**
     * 微信支付回调
     * @return mixed
     */
    public function notify()
    {
        $config = Config::get('wechat.payment');
        $app = new Application($config);
        $server = $app->getServer();

        $server->with(function ($message, $next) {
            Log::info('微信支付回调：' . json_encode($message, JSON_UNESCAPED_UNICODE));

            if ($message['return_code'] === 'SUCCESS' && $message['result_code'] === 'SUCCESS') {
                $orderId = $message['out_trade_no'];
                $order = OrderModel::where('order_sn', $orderId)->find();
                
                if ($order && $order->status == 0) {
                    $order->status = 1; // 已支付
                    $order->pay_time = date('Y-m-d H:i:s');
                    $order->save();
                    
                    Log::info('订单支付成功：' . $orderId);
                }
            }
            
            return $next($message);
        });

        return $server->serve();
    }
}
