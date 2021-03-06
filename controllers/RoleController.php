<?php

namespace controllers;

use models\Privilege;
use models\Role;

class RoleController
{
    /**
     * 列表页
     */
    public function index()
    {
        $model = new Role;
        $data = $model->findAll([
            'fields' => 'a.id,a.role_name,group_concat(c.pri_name) pri_list',
            'join' => ' a left join role_privilege b on a.id=b.role_id left join privilege c on b.pri_id = c.id',
            'groupby' => 'group by a.id',
        ]);
        view('role.index', $data);
    }

    /**
     * 显示添加的表单
     */
    public function create()
    {
        $model = new Privilege;
        $data = $model->tree();
        view('role.create', $data);
    }

    /**
     * 处理添加的表单
     */
    public function insert()
    {
        $model = new Role;
        $model->fill($_POST);
        $model->insert();
        redirect('/role/index');
    }

    /**
     * 显示修改的表单
     */
    public function edit()
    {
        $model = new Role;
        $data = $model->findOne($_GET['id']);

        $priModel = new Privilege;
        $priData = $priModel->tree();

        $priIds = $model -> getPriIds($_GET['id']);

        view("/role/edit", [
            'data' => $data,
            'priData' => $priData,
            'priIds' => $priIds,
        ]);
    }

    /**
     * 修改表单的方法
     */
    public function update()
    {
        $model = new Role;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/role/index');
    }

    /**
     * 删除
     */
    public function delete()
    {
        $model = new Role;
        $model->delete($_GET['id']);
        redirect("/role/index");
    }
}
