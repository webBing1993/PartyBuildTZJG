<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/9/13
 * Time: 15:14
 */

namespace app\home\model;


use think\Model;

class Incorrupt extends Model {
    protected $insert = [
        'views' => 0,
        'likes' => 0,
        'comments' => 0,
        'create_time' => NOW_TIME,
        'status' => 0
    ];

    /**
     * @param $type
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * 获取type1,3列表
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
     * @param $length
     * @param $type
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * 加载更多
     */
    public function getListMore($length,$type) {
        $map = array(
            'status' => 1,
            'type' => $type,
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