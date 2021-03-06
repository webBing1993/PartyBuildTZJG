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
    public function getIndex($type) {
        $map = array(
            'type' => $type,
            'status' => 1,
            'class' => 0
        );
        $res = $this->where($map)->order('create_time desc')->limit(8)->select();
        return $res;
    }

    /**
     * 获取type:2,3数据
     */
    public function getTypeIndex($type) {
        $map = array(
            'type' => $type,
            'status' => 1
        );
        $map['class'] = 1;
        $res1 = $this->where($map)->order('create_time desc')->limit(8)->select();

        $map['class'] = 2;
        $res2 = $this->where($map)->order('create_time desc')->limit(8)->select();
        $res = array(
            'one' => $res1,
            'two' => $res2,
        );
        return $res;
    }
    
    /**
     * 加载更多
     * $length 长度
     * $type 类别
     * $class type=3时分类，默认为0
     */
    public function getListMore($length,$type,$class = 0) {
        $map = array(
            'type' => $type,
            'class' => $class,
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit($length,8)->select();
        foreach ($res as $value) {
            $value['time'] = date("Y-m-d",$value['create_time']);
        }
        return $res;
    }
}