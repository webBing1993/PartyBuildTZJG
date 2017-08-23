<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/8/23
 * Time: 13:38
 */

namespace app\home\model;
use think\Model;

class Notice extends Model
{
    // 获取列表数据
    public function get_list($where,$len=0,$num=10){
        $list = $this->where($where)->order('id desc')->limit($len,$num)->field('id,front_cover,title,publisher,create_time')->select();
        foreach($list as $value){
            $value['create_time'] = date("Y-m-d",$value['create_time']);
            $Pic = Picture::where('id',$value['front_cover'])->field('path')->find();
            $value['front_cover'] = $Pic['path'];
        }
        return $list;
    }
}