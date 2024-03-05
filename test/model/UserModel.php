<?php

declare(strict_types=1);

namespace test\Model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class UserModel extends Model
{
    // protected $table = '{%tableName%}';

    use SoftDelete;
    protected $deleteTime = 'delete_time';
    private $where = [];

    protected $hidden = [
        'update_time',
        'delete_time'
    ];

    /**
     * 获取列表
     * @param $with     关联条件
     * @param $page     页数
     * @param $limit    条数
     * @param $desc     排序
     * @return boolean||Array
     */
    public function getList($with, $page = 1, $limit = 10, $desc = 'id desc')
    {
        try {
            if (count($this->where) > 1) {
                $whereFunctionName = 'whereOr';
            } else {
                $whereFunctionName = 'where';
            }
            $data = $this->with($with)
                ->$whereFunctionName($this->where)
                ->orderRaw($desc)
                ->paginate(['page' => $page, 'list_rows' => $limit])->toArray();
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
            return false;
        }

        return $data;
    }

    /**
     * 设置条件
     * @param bool $isAuto  是否自适应条件（有筛）
     * @param array $data   数据 ["name"=>'1,2']   添加逗号会有whereOr生成
     * @param array $screen 筛选的字段条件（["name"=>"like"]）
     * @return object
     */
    public function setWhere(bool $isAuto = true, array $data = [], array $screen = [])
    {
        if ($isAuto) {
            $whereOr = [];
            $where = [];
            foreach ($screen as $k => $v) {
                if (isset($data[$k]) && !empty($data[$k])) {
                    if ($k == 'goods_type') {
                        $fieldName = 'GoodsModel.' . $k;
                    } else {
                        $fieldName = $k;
                    }
                    if ($v == 'like') {
                        $where[] = [$fieldName, $v, "%$data[$k]%"];
                    } else if ($v == 'find in set') {
                        // 处理find in set 搜索方法
                        is_array($data[$k]) ?: $data[$k] = explode(',', $data[$k]);
                        // 是否只有一个条件
                        if (count($data[$k]) == 1) {
                            $where[] = [$fieldName, $v, implode(',', $data[$k])];
                        } else {
                            foreach ($data[$k] as $fv) {
                                $whereOr[] = [$fieldName, $v, $fv];
                            }
                        }
                    } else {
                        $where[] = [$fieldName, $v, $data[$k]];
                    }
                }
            }

            if ($whereOr != []) {
                foreach ($whereOr as $ov) {
                    $this->where[] = array_merge($where, [$ov]);
                }
            } else {
                $this->where = [$where];
            }
        } else {
            $this->where = [$data];
        }
        return $this;
    }
}
