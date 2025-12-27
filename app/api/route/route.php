<?php
declare (strict_types = 1);

use think\facade\Route;

Route::group('v1', function () {
    // --- 公开接口 ---
    
    // 登录模块
    Route::group('login', function () {
        Route::post('wechat', 'Login/wechat');
    });

    // 首页/Banner模块
    Route::group('banner', function () {
        Route::get('list', 'Banner/list');
    });

    // 医院模块
    Route::group('hospital', function () {
        Route::get('list', 'Hospital/list');
        Route::get('detail/:id', 'Hospital/detail');
    });

    // 服务套餐模块
    Route::group('package', function () {
        Route::get('list', 'Package/list');
    });

    // 服务模块
    Route::group('service', function () {
        Route::get('list', 'Service/list');
        Route::get('detail/:id/:type', 'Service/detail');
    });

    // 支付回调
    Route::group('payment', function () {
        Route::any('notify', 'Payment/notify');
    });

    // --- 需授权接口 ---
    Route::group('', function () {
        // 预约模块
        Route::group('appointment', function () {
            Route::post('create', 'Appointment/create');
        });

        // 支付模块
        Route::group('payment', function () {
            Route::post('wxpay', 'Payment/wxpay');
        });

        // 订单模块
        Route::group('order', function () {
            Route::get('list', 'Order/list');
            Route::get('detail/:id', 'Order/detail');
        });

        // 个人中心/用户模块
        Route::group('user', function () {
            Route::get('info', 'User/info');
            Route::post('update', 'User/update');
        });

        // 陪诊师申请
        Route::post('escort/apply', 'EscortApply/apply');

        // 招聘陪诊师申请
        Route::post('recruit/apply', 'EscortApply/recruitApply');

        // 优惠券模块
        Route::group('coupon', function () {
            Route::get('list', 'Coupon/list');
        });

        // 文件上传
        Route::group('upload', function () {
            Route::post('file', 'Upload/file');
        });
    })->middleware(\app\api\middleware\Auth::class);
});
