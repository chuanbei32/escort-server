<?php

return [
    'mini_program' => [
        'app_id' => env('WECHAT_MINIPROGRAM_APPID', ''),
        'secret' => env('WECHAT_MINIPROGRAM_SECRET', ''),
    ],
    'payment'      => [
        'app_id'               => env('WECHAT_PAY_APPID', ''),
        'mch_id'               => env('WECHAT_PAY_MCH_ID', ''),
        'certificate'          => root_path() . env('WECHAT_PAY_CERTIFICATE', ''), // 商户证书路径
        'private_key'          => root_path() . env('WECHAT_PAY_PRIVATE_KEY', ''), // 商户私钥路径
        'v2_secret_key'               => env('WECHAT_PAY_V2_KEY', ''),      // APIv2 密钥
        // 'platform_certs'       => [root_path() . env('WECHAT_PAY_PLATFORM_CERT', '')], // 微信支付公钥路径
        'notify_url'           => env('WECHAT_PAY_NOTIFY_URL', ''),  // 通知地址
        'http'                 => [
            'throw'  => true, // 抛出异常
            'timeout' => 5.0,
        ],
    ],
];
