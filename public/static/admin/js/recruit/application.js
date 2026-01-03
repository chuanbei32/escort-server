define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'recruit.application/index',
        add_url: 'recruit.application/add',
        edit_url: 'recruit.application/edit',
        delete_url: 'recruit.application/delete',
        export_url: 'recruit.application/export',
        modify_url: 'recruit.application/modify',
        recycle_url: 'recruit.application/recycle',
    };

    return {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', search: false},
                    {field: 'name', title: '姓名'},
                    {field: 'gender', search: 'select', selectList: notes?.gender || {}, title: '性别：1-男，0-女'},
                    {field: 'birth_date', title: '出生日期', search: false},
                    {field: 'phone', title: '联系电话', search: false},
                    {field: 'education', title: '学历'},
                    {field: 'region', title: '所在地区', search: false},
                    {field: 'hospital', title: '常住医院', search: false},
                    {field: 'specialty', title: '擅长科室', search: false},
                    {field: 'resume_url', title: '简历', templet: ea.table.image, search: false},
                    {field: 'certificate_url', title: '资质证书', templet: ea.table.image, search: false},
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
                    {field: 'name', title: '姓名'},
                    {field: 'gender', search: 'select', selectList: notes?.gender || {}, title: '性别：1-男，0-女'},
                    {field: 'birth_date', title: '出生日期'},
                    {field: 'phone', title: '联系电话'},
                    {field: 'education', title: '学历'},
                    {field: 'region', title: '所在地区'},
                    {field: 'hospital', title: '常住医院'},
                    {field: 'specialty', title: '擅长科室'},
                    {field: 'resume_url', title: '简历', templet: ea.table.image},
                    {field: 'certificate_url', title: '资质证书', templet: ea.table.image},
                    {field: 'agree_protocol', title: '是否同意协议：1-是，0-否'},
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