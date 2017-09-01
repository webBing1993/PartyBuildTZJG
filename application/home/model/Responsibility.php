<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/30
 * Time: 10:58
 */

namespace app\home\model;


use think\Model;

class Responsibility extends Model {
    /**
     * 获取主页列表数据
     */
    public function getIndex($type) {
        $map = array(
            'type' => $type,
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit(8)->select();
        return $res;
    }

    /**
     * 获取type=3数据
     */
    public function getBranchIndex($type) {
        $map1 = array(
            'type' => $type,
            'class' => 1,
            'status' => 1
        );
        $res1 = $this->where($map1)->order('create_time desc')->limit(8)->select();
        
        $map2 = array(
            'type' => $type,
            'class' => 2,
            'status' => 1
        );
        $res2 = $this->where($map2)->order('create_time desc')->limit(8)->select();
        $res = array(
            'one' => $res1,
            'two' => $res2,
        );
        return $res;
    }
}