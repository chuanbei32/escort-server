define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'order/index',
        add_url: 'order/add',
        edit_url: 'order/edit',
        delete_url: 'order/delete',
        export_url: 'order/export',
        modify_url: 'order/modify',
        recycle_url: 'order/recycle',

        refund_url: 'order/refund',
    };

    return {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh', 'delete', 'export'],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', search: false},
                    {field: 'order_sn', title: '订单号', search: true, searchOp: '='},
                    {field: 'user.nickname', fieldAlias: 'user_nickname', title: '用户名称'},
                    {field: 'service.name', fieldAlias: 'service_name', title: '服务名称'},
                    {field: 'total_fee', title: '订单金额', search: false},
                    {field: 'status', search: 'select', selectList: notes?.status || {}, title: '订单状态', search: false},
                    {field: 'pay_time', title: '支付时间', search: false},
                    {field: 'total_count', title: '总次数', search: false},
                    {field: 'used_count', title: '已使用次数', search: false},
                    {field: 'is_coupon', search: 'select', selectList: notes?.is_coupon || {}, title: '是否使用优惠券', search: false},
                    {field: 'create_time', title: '创建时间', search: false},
                    {field: 'update_time', title: '更新时间', search: false},
                    {width: 250, title: '操作', templet: ea.table.tool, operat: [[{
            text: '退款',
            title: '确认退款？',
            extra:'确认退款？',
            url: init.refund_url,
            method: 'request',
            auth: 'refund',
            class: 'layui-btn layui-btn-xs layui-btn-normal'
        }], 'edit', 'delete']},
                ]],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
        recycle: function () {
            init.index_url = init.recycle_url;
            ea.table.render({
                init: init,
                toolbar: ['refresh',
                    [{
                        class: 'layui-btn layui-btn-sm',
                        method: 'get',
                        field: 'id',
                        icon: 'fa fa-refresh',
                        text: '全部恢复',
                        title: '确定恢复？',
                        auth: 'recycle',
                        url: init.recycle_url + '?type=restore',
                        checkbox: true
                    }, {
                        class: 'layui-btn layui-btn-danger layui-btn-sm',
                        method: 'get',
                        field: 'id',
                        icon: 'fa fa-delete',
                        text: '彻底删除',
                        title: '确定彻底删除？',
                        auth: 'recycle',
                        url: init.recycle_url + '?type=delete',
                        checkbox: true
                    }], 'export',
                ],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', search: 'select', selectList: notes?.id || {}, title: 'id'},
                    {field: 'order_sn', title: '订单号'},
                    {field: 'user_id', title: '用户ID'},
                    {field: 'service_id', title: '服务ID'},
                    {field: 'type', title: '类型：1-单次，2-套餐'},
                    {field: 'total_fee', title: '订单金额'},
                    {field: 'pay_type', title: '支付方式：1-微信支付'},
                    {field: 'status', search: 'select', selectList: notes?.status || {}, title: '订单状态：0-待支付，1-已支付，2-已退款3-已完成'},
                    {field: 'total_count', title: '总次数'},
                    {field: 'used_count', title: '已使用次数'},
                    {field: 'create_time', title: '创建时间'},
                    {field: 'pay_time', title: '支付时间'},
                    {field: 'transaction_id', title: '支付平台交易流水号'},
                    {field: 'expire_time', title: '过期时间'},
                    {field: 'coupon_id', search: 'select', selectList: notes?.coupon_id || {}, title: '优惠券ID'},
                    {
                        width: 250,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            [{
                                title: '确认恢复？',
                                text: '恢复数据',
                                filed: 'id',
                                url: init.recycle_url + '?type=restore',
                                method: 'get',
                                auth: 'recycle',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                            }, {
                                title: '想好了吗？',
                                text: '彻底删除',
                                filed: 'id',
                                method: 'get',
                                url: init.recycle_url + '?type=delete',
                                auth: 'recycle',
                                class: 'layui-btn layui-btn-xs layui-btn-normal layui-bg-red',
                            }]]
                    }
                ]],
            });

            ea.listen();
        },
    };
});