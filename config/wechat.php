<?php

return [
    'mini_program' => [
        'app_id' => env('WECHAT_MINIPROGRAM_APPID', ''),
        'secret' => env('WECHAT_MINIPROGRAM_SECRET', ''),
    ],
    'payment'      => [
        'mch_id'               => env('WECHAT_PAY_MCH_ID', ''),
        'certificate'          => env('WECHAT_PAY_CERTIFICATE', ''), // 商户证书路径
        'private_key'          => env('WECHAT_PAY_PRIVATE_KEY', ''), // 商户私钥路径
        'v3_key'               => env('WECHAT_PAY_V3_KEY', ''),      // APIv3 密钥
        'notify_url'           => env('WECHAT_PAY_NOTIFY_URL', ''),  // 通知地址
        'http'                 => [
            'throw'  => true, // 抛出异常
            'timeout' => 5.0,
        ],
    ],
];
