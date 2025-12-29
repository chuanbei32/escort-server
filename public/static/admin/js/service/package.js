define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'service.package/index',
        add_url: 'service.package/add',
        edit_url: 'service.package/edit',
        delete_url: 'service.package/delete',
        export_url: 'service.package/export',
        modify_url: 'service.package/modify',
        recycle_url: 'service.package/recycle',
    };

    var form = layui.form; // 获取 Layui 表单对象
    console.log(form);

    return {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', search: false},
                    {field: 'name', title: '套餐名称'},
                    {field: 'price', title: '套餐价格', search: false},
                    {field: 'total_count', title: '总次数', search: false},
                    {field: 'image_url', title: '套餐图片', search: false, templet: ea.table.image},
                    {field: 'create_time', title: '创建时间', search: false},
                    {field: 'update_time', title: '更新时间', search: false},
                    {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

            ea.listen();
        },
        add: function () {

            // 注册表单验证规则
            form.verify({
                len50: [/^.{1,50}$/, '内容长度必须在 1 到 50 个字符之间']
            });

            ea.listen();
        },
        edit: function () {

            // 注册表单验证规则
            form.verify({
                len50: [/^.{1,50}$/, '内容长度必须在 1 到 50 个字符之间']
            });

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
                    {field: 'name', title: '套餐名称'},
                    {field: 'price', title: '套餐价格'},
                    {field: 'total_count', title: '总次数'},
                    {field: 'image_url', title: '套餐图片', templet: ea.table.image},
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