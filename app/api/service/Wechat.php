<?php
declare (strict_types = 1);

namespace app\api\service;

use EasyWeChat\MiniApp\Application;
use think\facade\Config;
use think\facade\Log;
use think\Response;

class Wechat
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $config;

    public function __construct()
    {
        $this->config = Config::get('wechat.mini_program');
        if (empty($this->config['app_id'])) {
            throw new \Exception('微信小程序配置缺失');
        }
        $this->app = new Application($this->config);
    }

    /**
     * 获取微信小程序实例
     * @return Application
     */
    public function getApp(): Application
    {
        return $this->app;
    }

    /**
     * 处理微信服务器请求
     * @return Response
     */
    public function handleServer(): Response
    {
        $request = request();
        $server = $this->app->getServer();

        // 1. 处理 URL 验证 (GET echostr)
        if ($request->isGet() && $request->has('echostr')) {
            Log::info('处理微信服务器 URL 验证');
            $psrResponse = $server->serve();
            
            // 清除之前的输出缓冲区，防止干扰
            if (ob_get_length()) ob_clean();
            
            return $this->psr7ToThinkResponse($psrResponse);
        }

        // 2. 处理 POST 消息
        if ($request->isPost()) {
            $content = $request->getInput();
            if (empty($content)) {
                Log::warning('收到空的 POST 请求内容');
                return response('success');
            }

            // 注册消息处理器
            $server->with(function ($message, $next) {
                return $this->handleMessage($message, $next);
            });

            try {
                $psrResponse = $server->serve();
                
                // 清除之前的输出缓冲区
                if (ob_get_length()) ob_clean();
                
                return $this->psr7ToThinkResponse($psrResponse);
            } catch (\Exception $e) {
                Log::error('小程序服务器响应异常：' . $e->getMessage());
                return response('success');
            }
        }

        return response('Invalid request', 400);
    }

    /**
     * 处理小程序消息和事件
     * @param mixed $message
     * @param callable $next
     * @return mixed
     */
    protected function handleMessage($message, $next)
    {
        Log::info('收到微信消息: ' . json_encode($message, JSON_UNESCAPED_UNICODE));

        $msgType = $message['MsgType'] ?? '';
        
        switch ($msgType) {
            case 'event':
                return $this->handleEvent($message, $next);
            case 'text':
                return $this->handleText($message, $next);
            default:
                Log::debug('未处理的消息类型: ' . $msgType);
        }

        return $next($message);
    }

    /**
     * 处理事件消息
     * @param array $message
     * @param callable $next
     * @return mixed
     */
    protected function handleEvent($message, $next)
    {
        $event = $message['Event'] ?? '';
        $openid = $message['FromUserName'] ?? '';
        Log::info(sprintf('收到微信事件: %s, 来自: %s', $event, $openid));

        switch ($event) {
            case 'user_enter_tempsession':
                $content = '您好！欢迎使用我们的陪诊服务。请问有什么可以帮您？您可以直接在此输入您的问题，我们的客服人员会尽快为您解答。';
                
                Log::info('用户进入客服会话，尝试通过两种方式回复');

                // 1. 客服消息场景 (异步推送，JSON 结构)
                // 这种方式最可靠，特别是在小程序场景下
                try {
                    $this->sendCustomMessage($openid, $content);
                } catch (\Exception $e) {
                    Log::error('发送客服消息失败: ' . $e->getMessage());
                }

                // 2. 服务端被动回复场景 (同步返回，XML 结构)
                // 根据 EasyWeChat 6.x 文档，直接返回字符串，框架会自动转为 XML 文本消息
                // 如果返回 'success'，则不进行任何回复。这里我们尝试返回内容以增加成功率。
                return $content;
                
            default:
                Log::debug('未处理的事件类型: ' . $event);
        }

        return $next($message);
    }

    /**
     * 处理文本消息
     * @param array $message
     * @param callable $next
     * @return mixed
     */
    protected function handleText($message, $next)
    {
        $content = $message['Content'] ?? '';
        $openid = $message['FromUserName'] ?? '';
        Log::info(sprintf('收到用户文本消息: %s, 来自: %s', $content, $openid));
        
        // 可以在这里添加简单的关键词回复逻辑
        if ($content === '客服' || $content === '人工') {
            $replyContent = '正在为您转接人工客服，请稍候...';

            Log::info('匹配到关键词，尝试通过两种方式回复');

            // 1. 客服消息场景 (异步推送)
            try {
                $this->sendCustomMessage($openid, $replyContent);
            } catch (\Exception $e) {
                Log::error('发送客服消息失败: ' . $e->getMessage());
            }

            // 2. 服务端被动回复场景 (同步返回)
            return $replyContent;
        }

        return $next($message);
    }

    /**
     * 将 PSR-7 响应转换为 ThinkPHP 响应
     * @param \Psr\Http\Message\ResponseInterface $psrResponse
     * @return Response
     */
    protected function psr7ToThinkResponse($psrResponse): Response
    {
        $content = (string)$psrResponse->getBody();
        $statusCode = $psrResponse->getStatusCode();
        $headers = [];
        
        foreach ($psrResponse->getHeaders() as $name => $values) {
            $headers[$name] = implode(', ', $values);
        }

        // 默认返回 text/plain 如果没有指定 content-type 且是验证请求
        if (!isset($headers['Content-Type']) && request()->has('echostr')) {
            $headers['Content-Type'] = 'text/plain';
        }

        return response($content, $statusCode, $headers);
    }

    /**
     * 发送客服消息 (异步 JSON 结构)
     * @param string $openid
     * @param string $content
     * @return void
     */
    protected function sendCustomMessage(string $openid, string $content)
    {
        if (empty($openid)) return;

        $client = $this->app->getClient();
        $response = $client->post('cgi-bin/message/custom/send', [
            'json' => [
                'touser' => $openid,
                'msgtype' => 'text',
                'text' => [
                    'content' => $content,
                ],
            ],
        ]);

        Log::info('发送客服消息结果: ' . $response->getContent());
    }
}
