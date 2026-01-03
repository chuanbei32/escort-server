define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'user.coupon/index',
        add_url: 'user.coupon/add',
        edit_url: 'user.coupon/edit',
        delete_url: 'user.coupon/delete',
        export_url: 'user.coupon/export',
        modify_url: 'user.coupon/modify',
        recycle_url: 'user.coupon/recycle',
    };

    return {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh', 'delete', 'export', 'recycle'],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', search: false},
                    {field: 'user_id', title: '用户ID', search: false, hide: true},
                    {field: 'user.nickname', fieldAlias: 'user_nickname', title: '用户名称', search: true,},
                    {field: 'coupon_id', title: '优惠券ID', search: false, hide: true},
                    {field: 'coupon.name', fieldAlias: 'coupon_name', title: '优惠券名称', search: false,},
                    {field: 'coupon.amount', fieldAlias: 'coupon_amount', title: '优惠券金额', search: false,},
                    {field: 'coupon.min_amount', fieldAlias: 'coupon_mini_amount', title: '优惠券最小金额', search: false,},
                    {field: 'coupon.start_time', fieldAlias: 'coupon_start_time', title: '优惠券开始时间', search: false,},
                    {field: 'coupon.end_time', fieldAlias: 'coupon_end_time', title: '优惠券结束时间', search: false,},
                    {field: 'status', search: 'select', selectList: notes?.status || {}, title: '状态：0-未使用，1-已使用，2-已过期', search: false},
                    {field: 'used_time', title: '使用时间', search: false},
                    {field: 'create_time', title: '创建时间', search: false},
                    {field: 'update_time', title: '更新时间', search: false},
                    {width: 250, title: '操作', templet: ea.table.tool, operat: ['delete']},
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
                    {field: 'id', title: 'id'},
                    {field: 'user_id', title: '用户ID'},
                    {field: 'coupon_id', title: '优惠券ID'},
                    {field: 'status', search: 'select', selectList: notes?.status || {}, title: '状态：0-未使用，1-已使用，2-已过期'},
                    {field: 'used_time', title: '使用时间'},
                    {field: 'create_time', title: '创建时间'},
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