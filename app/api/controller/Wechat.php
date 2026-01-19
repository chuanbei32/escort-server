<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Wechat as WechatService;
use think\App;
use think\facade\Log;
use think\Response;

class Wechat extends Base
{
    /**
     * @var WechatService
     */
    protected $service;

    public function __construct(App $app, WechatService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 微信小程序服务器回调 (用于客服消息等)
     * @return Response
     */
    public function server(): Response
    {
        $request = request();
        
        // 记录详细请求日志 (生产环境建议调高日志级别或减少记录)
        Log::info(sprintf('收到小程序回调 [%s] %s', $request->method(), $request->url(true)));
        
        if ($request->isPost()) {
            Log::debug('请求参数：' . json_encode($request->param(), JSON_UNESCAPED_UNICODE));
            Log::debug('请求头：' . json_encode($request->header(), JSON_UNESCAPED_UNICODE));
            Log::debug('请求内容：' . $request->getInput());
        }

        try {
            return $this->service->handleServer();
        } catch (\Exception $e) {
            Log::error('微信回调处理失败：' . $e->getMessage());
            return response($e->getMessage(), 500);
        }
    }
}
