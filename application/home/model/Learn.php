<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/9/6
 * Time: 15:37
 */

namespace app\home\model;


use think\Model;

class Learn extends Model {
    protected $insert = [
        'views' => 0,
        'likes' => 0,
        'comments' => 0,
        'create_time' => NOW_TIME,
        'status' => 0
    ];

    /**
     * 获取方案部署详情
     */
    public function getProgram() {
        $map = array(
            'type' => 1,
            'status' => 1
        );
        $res = $this->where($map)->order("create_time desc")->limit(8)->select();
        return $res;
    }
    
    /**
     * 获取三会一课
     */
    public function getLesson() {
        $map = array(
            'type' => 2,
            'status' => 1
        );
        $all = $this->where($map)->order("create_time desc")->limit(8)->select();

        $map1 = array(
            'type' => 2,
            'status' => 1,
            'class' => 1,
        );
        $one = $this->where($map1)->order("create_time desc")->limit(8)->select();

        $map2 = array(
            'type' => 2,
            'status' => 1,
            'class' => 2,
        );
        $two = $this->where($map2)->order("create_time desc")->limit(8)->select();

        $map3 = array(
            'type' => 2,
            'status' => 1,
            'class' => 3,
        );
        $three = $this->where($map3)->order("create_time desc")->limit(8)->select();

        $map4 = array(
            'type' => 2,
            'status' => 1,
            'class' => 4,
        );
        $four = $this->where($map4)->order("create_time desc")->limit(8)->select();

        $data = array(
            'all' => $all,
            'one' => $one,
            'two' => $two,
            'three' => $three,
            'four' => $four
        );
        return $data;
    }

    /**
     * 年度计划
     */
    public function getPlan() {
        $map1 = array(
            'type' => 3,
            'status' => 1,
            'class' => 1
        );
        $one = $this->where($map1)->order("create_time desc")->limit(8)->select();

        $map2 = array(
            'type' => 3,
            'status' => 1,
            'class' => 2
        );
        $two = $this->where($map2)->order("create_time desc")->limit(8)->select();

        $data = array(
            'one' => $one,
            'two' => $two
        );
        return $data;
    }

    /**
     * 主题党日
     */
    public function getTheme() {
        $map = array(
            'type' => 4,
            'status' => 1
        );
        $res = $this->where($map)->order("create_time desc")->limit(8)->select();
        return $res;
    }

    /**
     * 加载更多
     * $type 类型
     * $class 列别
     * $length 长度
     */
    public function getMoreList($length,$type,$class = 0) {
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