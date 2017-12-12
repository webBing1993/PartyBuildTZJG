<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/29
 * Time: 15:51
 */

namespace app\admin\model;


class Incorrupt extends Base {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 0
    ];
    // 获取数据列表
    public function get_list($status){
        $map = array(
            'status' => $status
        );
        $list = $this->where($map)->order('create_time','desc')->select();
        return $list;
    }
}