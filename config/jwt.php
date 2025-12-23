<?php

return [
    'secret'      => env('JWT_SECRET', 'your_secret_key'),
    //  有效期 (秒) 默认 1小时
    'ttl'         => env('JWT_TTL', 3600),
    //  刷新有效期 (秒) 默认 2周
    'refresh_ttl' => env('JWT_REFRESH_TTL', 1209600),
    //  算法
    'algo'        => 'HS256',
];
