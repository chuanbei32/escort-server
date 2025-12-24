<?php

return [
    'secret'      => env('JWT_SECRET', 'f4d8e2c9a1b3c5e7d9f0a2b4c6e8d0f1a3b5c7e9d1f2a4b6c8e0d2f3a5b7c9e1'),
    //  有效期 (秒) 默认 1小时
    'ttl'         => env('JWT_TTL', 3600),
    //  刷新有效期 (秒) 默认 2周
    'refresh_ttl' => env('JWT_REFRESH_TTL', 1209600),
    //  算法
    'algo'        => 'HS256',
];
