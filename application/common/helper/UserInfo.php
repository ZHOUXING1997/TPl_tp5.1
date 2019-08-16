<?php

namespace app\common\helper;

use think\Model;

class UserInfo extends Model {

    // 表名
    const TABLE_NAME = 'user_info';
    protected $table = self::TABLE_NAME;
    // 是否更新
    protected $isUpdate = false;

    // 主键
    protected $pk = 'id';
    // 第三方
    protected $thirdK = 'openid';
    // 只读字段
    protected $readonly = ['id', 'openid'];

    // 默认数据
    protected function defaultData () {
        if ($this->isUpdate) {
            // 已存在的用户更新ip,登录次数
            return [
                'login_times' => $this->raw('login_times + 1'),
                'last_login_ip' => get_client_ip(0, true, true),
            ];
        } else {
            // 新用户生成主键，ip
            return [
                // 生成一个新的snowflakeid
                'id' => app('Snowflake')->nextId(),
                'last_login_ip' => get_client_ip(0, true, true),
            ];
        }
    }

    public function saveUser (array $data, array $upWhere = []) {
        if (null == $this->checkUserExists($upWhere)) {
            // TODO 增加自定义更新条件字段限制
            // 如果自定义更新条件，必须可以查询出来
            return false;
        }
        // 存在主键视为已存在
        if ((isset($data[$this->pk]) && $data[$this->pk]) || !empty($upWhere)) {
            $this->isUpdate = true;
            if (empty($upWhere)) {
                $upWhere = [
                    $this->pk => $data[$this->pk],
                ];
            }
        } else {
            $this->isUpdate = false;
        }

        // 合并数据
        $data = array_merge($this->defaultData(), $data);

        // 更新数据
        $res = $this->readonly($this->readonly)->allowField(true)->isUpdate($this->isUpdate)->save($data, empty($upWhere) ? [] : $upWhere);

        // 成功返回主键
        if ($res) {
            return $data[$this->pk];
        } else {
            return false;
        }
    }

    public function checkUserExists (array $where) {
        return $this->where($where)->find();
    }
}
