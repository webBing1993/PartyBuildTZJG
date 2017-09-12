<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/9/12
 * Time: 17:54
 */

namespace app\home\model;


use think\Model;

class Special extends Model {
    protected $insert = [
        'views' => 0,
        'likes' => 0,
        'comments' => 0,
        'create_time' => NOW_TIME,
        'status' => 0
    ];

    /**
     * 规范化建设
     */
    public function getIndex() {
        $map = array(
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit(8)->select();
        return $res;
    }

    /**
     * @param $length
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * 加载更多
     */
    public function getListMore($length) {
        $map = array(
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit($length,8)->select();
        foreach ($res as $value) {
            $value['time'] = date("Y-m-d",$value['create_time']);
        }
        return $res;
    }
}