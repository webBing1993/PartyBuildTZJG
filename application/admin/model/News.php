<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/8/22
 * Time: 15:13
 */

namespace app\admin\model;
use think\Model;
use think\Db;
class News extends Model
{
    public $insert = [
        'likes' => 0,
        'views' => 0,
        'comments' => 0,
        'create_user' => UID,
        'create_time' => NOW_TIME,
    ];
    // 获取内容
    public function get_content($id){
        if (empty($id)){
            $list = null;
        }else{
            $list = $this->where(['id' => $id])->find();
            if($list['file']) {
                $temp = json_decode($list['file']);
                $arr[] = [];
                foreach($temp as $key => $value){
                    $arr[$key]['id'] = $value;
                    $arr[$key]['name'] = Db::name('file')->where('id',$value)->value('name');
                }
                $list['files'] = $arr;
            }else{
                $list['files'] = '';
            }
        }
        return $list;
    }
    //获取后台用户名称
    public function user(){
        return $this->hasOne('Member','id','create_user');
    }
}