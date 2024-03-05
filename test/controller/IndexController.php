<?php
declare (strict_types = 1);

namespace test\controller;

use  as ThisModel;
use think\Request;

class IndexController extends Controller 
{
    /**
     * 列表
     */
    public function list()
    {
        try {
            $page = (int)input('page') ?: 1;
            $limit = (int)input('limit') ?: 10;
            $screen = [];
            $inputData = array_merge(input('get.'), input('post.'));
            $thisModel = new ThisModel();
            $data = $thisModel->setWhere(true, $inputData, $screen)->getList([], $page, $limit,'sort asc,id desc');
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }

        return $this->success('操作成功', $data);
    }

    /**
     * 编辑
     */
    public function edit()
    {
        if (request()->isPost()) {
            try {
                $data = request()->post();
                if (request()->post('id')) {
                    ThisModel::update($data, ['id' => (int)(request()->post('id'))]);
                } else {
                    ThisModel::create($data);
                }
            } catch (\Throwable $th) {
                return $this->fail($th->getMessage());
            }

            return $this->success('操作成功');
        }
    }

    /**
     * 详情
     */
    public function info()
    {
        try {
            $id = input('id');
            $data = ThisModel::where(['id' => $id])->append(['avatar_path'])->findOrFail()->toArray();
        } catch (\think\db\exception\ModelNotFoundException $e) {
            return $this->fail('信息不存在');
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }

        return $this->success('操作成功', $data);
    }

    /**
     * 删除
     */
    public function del()
    {
        if (request()->isPost()) {
            try {
                $id = (int)(request()->post('id'));
                ThisModel::destroy($id);
            } catch (\Throwable $th) {
                return $this->fail($th->getMessage());
            }

            return $this->success('操作成功');
        }
    }
}
