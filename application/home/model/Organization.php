<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/9/12
 * Time: 15:21
 */

namespace app\home\model;


use think\Model;

class Organization extends Model {
    protected $insert = [
        'views' => 0,
        'likes' => 0,
        'comments' => 0,
        'create_time' => NOW_TIME,
        'status' => 0
    ];

    /**
     * 规范建设
     */
    public function getStandard() {
        $map = array(
            'type' => 1,
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit(8)->select();
        foreach ($res as $value) {
            switch ($value['class']) {
                case 1:
                    $value['class_text'] = "[阵地建设]";
                    break;
                case 2:
                    $value['class_text'] = "[按期换届]";
                    break;
                case 3:
                    $value['class_text'] = "[主题党日]";
                    break;
                default:
                    $value['class_text'] = "[无]";
                    break;
            }
        }
        return $res;
    }

    /**
     * 获取离退休
     */
    public function getRetirement() {
        $map = array(
            'type' => 2,
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit(8)->select();
        return $res;
    }

    /**
     * 获取党费收缴
     */
    public function getFee() {
        $map = array(
            'type' => 3,
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit(8)->select();
        return $res;
    }

    /**
     * 获取信息录用
     */
    public function getInformation(){
        $map = array(
            'type' => 4,
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit(8)->select();
        return $res;
    }

    /**
     * 加载更多
     * $length 长度
     * $type 类别
     */
    public function getListMore($length,$type) {
        $map = array(
            'type' => $type,
            'status' => 1
        );
        $res = $this->where($map)->order('create_time desc')->limit($length,8)->select();
        foreach ($res as $value) {
            $value['time'] = date("Y-m-d",$value['create_time']);
            switch ($value['class']) {
                case 1:
                    $value['class_text'] = "[阵地建设]";
                    break;
                case 2:
                    $value['class_text'] = "[按期换届]";
                    break;
                case 3:
                    $value['class_text'] = "[主题党日]";
                    break;
                default:
                    $value['class_text'] = "[无]";
                    break;
            }
        }
        return $res;
    }

}