define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'coupon/index',
        add_url: 'coupon/add',
        edit_url: 'coupon/edit',
        delete_url: 'coupon/delete',
        export_url: 'coupon/export',
        modify_url: 'coupon/modify',
        recycle_url: 'coupon/recycle',
    };

    return {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', search: false},
                    {field: 'name', title: '优惠券名称'},
                    {field: 'amount', title: '面额', search: false},
                    {field: 'min_amount', title: '最低使用金额', search: false},
                    {field: 'start_time', title: '有效期开始时间', search: false},
                    {field: 'end_time', title: '有效期结束时间', search: false},
                    {field: 'create_time', title: '创建时间', search: false},
                    {field: 'update_time', title: '更新时间', search: false},
                    {width: 250, title: '操作', templet: ea.table.tool},
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
                    {field: 'name', title: '优惠券名称'},
                    {field: 'amount', title: '面额'},
                    {field: 'min_amount', title: '最低使用金额'},
                    {field: 'start_time', title: '有效期开始时间'},
                    {field: 'end_time', title: '有效期结束时间'},
                    {field: 'create_time', title: '创建时间'},
                    {field: 'update_time', title: '更新时间'},
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