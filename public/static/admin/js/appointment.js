define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'appointment/index',
        add_url: 'appointment/add',
        edit_url: 'appointment/edit',
        delete_url: 'appointment/delete',
        export_url: 'appointment/export',
        modify_url: 'appointment/modify',
        recycle_url: 'appointment/recycle',
    };

    return {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh', 'delete', 'export'],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', search: false},
                    {field: 'status', search: 'select', selectList: notes?.status || {}, title: '预约状态'},
                    {field: 'service.name', fieldAlias: 'service_name', title: '服务名称'},
                    {field: 'hospital.name', fieldAlias: 'hospital_name', title: '医院名称'},
                    {field: 'department', title: '科室', search: false},
                    {field: 'expected_time', title: '期望就诊时间', search: false},
                    {field: 'patient_name', title: '就诊人姓名'},
                    {field: 'patient_gender', search: 'select', selectList: notes?.gender || {}, title: '就诊人性别', search: false},
                    {field: 'patient_phone', title: '就诊人电话', search: false},
                    {field: 'escort.name', fieldAlias: 'escort_name', title: '陪诊师姓名'},
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
                    {field: 'order_id', title: '订单ID'},
                    {field: 'user_id', title: '用户ID'},
                    {field: 'service_id', title: '服务ID'},
                    {field: 'type', title: '类型：1-单次，2-套餐'},
                    {field: 'hospital_id', title: '医院ID'},
                    {field: 'escort_id', title: '陪诊师ID（关联ea8_escort_application表id）'},
                    {field: 'department', title: '科室'},
                    {field: 'expected_time', title: '期望就诊时间'},
                    {field: 'patient_name', title: '就诊人姓名'},
                    {field: 'patient_gender', title: '就诊人性别：1-男，0-女'},
                    {field: 'patient_phone', title: '就诊人电话'},
                    {field: 'address', title: '详细地址'},
                    {field: 'escort_gender_preference', title: '陪诊师性别偏好：0-不限，1-男，2-女'},
                    {field: 'status', title: '预约状态：0-待预约，1-使用中，2-已取消'},
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