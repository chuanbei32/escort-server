<?php
declare (strict_types = 1);

namespace app\api\controller;

use EasyWeChat\MiniApp\Application;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use think\facade\Config;
use think\facade\Log;

class Wechat extends Base
{
    /**
     * 微信小程序服务器回调 (用于客服消息等)
     * @return \think\Response
     */
    public function server()
    {
        $config = Config::get('wechat.mini_program');
        $app = new Application($config);
        $app->setRequestFromSymfonyRequest(SymfonyRequest::createFromGlobals());
        $server = $app->getServer();

        // 处理小程序消息和事件
        $server->with(function ($message, $next) {
            Log::info('收到小程序消息：' . json_encode($message, JSON_UNESCAPED_UNICODE));

            $msgType = $message['MsgType'] ?? '';
            $event = $message['Event'] ?? '';

            // 1. 处理用户打开客服会话事件
            // 当用户在小程序内点击客服按钮进入会话时触发
            if ($msgType === 'event' && $event === 'user_enter_tempsession') {
                return '您好！欢迎使用我们的陪诊服务。请问有什么可以帮您？您可以直接在此输入您的问题，我们的客服人员会尽快为您解答。';
            }

            // 2. 处理普通文本消息
            // 如果需要对用户发送的每一条消息都做自动回复，可以开启以下逻辑
            // if ($msgType === 'text') {
            //     return '收到您的消息，请稍候，客服人员正在接入...';
            // }

            return $next($message);
        });

        try {
            $response = $server->serve();
            return response($response->getBody()->getContents());
        } catch (\Exception $e) {
            Log::error('小程序服务器响应异常：' . $e->getMessage());
            return response('fail');
        }
    }
}
