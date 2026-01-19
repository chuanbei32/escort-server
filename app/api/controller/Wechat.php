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
        $thinkRequest = request();
        
        // 获取请求原始内容
        $content = $thinkRequest->getInput();
        
        // 记录详细请求日志
        Log::info(sprintf('收到小程序回调 [%s] %s', $thinkRequest->method(), $thinkRequest->url(true)));
        Log::debug('请求参数：' . json_encode($thinkRequest->param(), JSON_UNESCAPED_UNICODE));
        Log::debug('请求头：' . json_encode($thinkRequest->header(), JSON_UNESCAPED_UNICODE));
        Log::debug('请求内容：' . $content);

        $config = Config::get('wechat.mini_program');
        if (empty($config['app_id'])) {
            Log::error('微信小程序配置缺失');
            return response('Configuration missing', 400);
        }

        $app = new Application($config);

        // 如果是 GET 请求且包含 echostr，通常是微信服务器的 URL 验证
        if ($thinkRequest->isGet() && $thinkRequest->has('echostr')) {
            Log::info('处理微信服务器 URL 验证');
            // 创建 Symfony Request 对象供 EasyWeChat 使用
            $symfonyRequest = new SymfonyRequest(
                $_GET,
                $_POST,
                [],
                $_COOKIE,
                $_FILES,
                $_SERVER,
                $content
            );
            $app->setRequestFromSymfonyRequest($symfonyRequest);
            $server = $app->getServer();
            return $server->serve();
        }

        // 如果是 POST 请求但内容为空，EasyWeChat 会抛出异常
        if ($thinkRequest->isPost() && empty($content)) {
            Log::warning('收到空的 POST 请求内容');
            return response('success'); // 返回 success 避免微信重试
        }
        $server = $app->getServer();

        // 处理小程序消息和事件
        $server->with(function ($message, $next) {
            Log::info('解析到小程序消息：' . json_encode($message, JSON_UNESCAPED_UNICODE));

            $msgType = $message['MsgType'] ?? '';
            $event = $message['Event'] ?? '';

            // 1. 处理用户打开客服会话事件
            if ($msgType === 'event' && $event === 'user_enter_tempsession') {
                return '您好！欢迎使用我们的陪诊服务。请问有什么可以帮您？您可以直接在此输入您的问题，我们的客服人员会尽快为您解答。';
            }

            return $next($message);
        });

        try {
            $response = $server->serve();
            // EasyWeChat 6.x 使用 Symfony Response，通过 getContent() 获取内容
            return response($response->getContent(), $response->getStatusCode(), $response->headers->all());
        } catch (\Exception $e) {
            Log::error('小程序服务器响应异常：' . $e->getMessage());
            // 如果解析失败（可能是非微信请求），返回 success 或 fail
            return response('success'); 
        }
    }
}
