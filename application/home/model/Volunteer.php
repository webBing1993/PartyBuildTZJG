<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/9/13
 * Time: 9:39
 */

namespace app\home\model;


use think\Model;

class Volunteer extends Model {
    protected $insert = [
        'views' => 0,
        'likes' => 0,
        'comments' => 0,
        'create_time' => NOW_TIME,
        'status' => 0
    ];
    /**
     * 获取主页列表数据
     */
    public function getIndex() {
        $map = array(
            'type' => 1,
            'status' => 1
        );
        $one = $this->where($map)->order('create_time desc')->limit(8)->select();

        $map2 = array(
            'type' => 2,
            'status' => 1
        );
        $two = $this->where($map2)->order('create_time desc')->limit(8)->select();

        $map3 = array(
            'type' => 3,
            'status' => 1,
        );
        $three = $this->where($map3)->order('create_time desc')->limit(8)->select();
        $data = array(
            'one' => $one,
            'two' => $two,
            'three' => $three
        );
        return $data;
    }

    /**
     * @param $length
     * @param $type
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * 加载更多
     */
    public function getListMore($length,$type) {
        $map = array(
            'type' => $type,
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit($length,8)->select();
        foreach ($res as $value) {
            $value['time'] = date("Y-m-d",$value['create_time']);
            $path = Picture::get($value['front_cover']);
            $value['path'] = $path['path'];
        }
        return $res;
    }
}