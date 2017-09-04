<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/6/14
 * Time: 10:19
 */

namespace app\home\model;
use think\Db;
use think\Model;

class Push extends Model {
    /*获取 push 表列表 */
    public function get_list($status=0,$len=0,$opt=false){
        $map = [
            'status' => ['egt',$status]
        ];
        $list = $this->where($map)->order('create_time desc')->limit($len,7)->select();
        if (empty($list)){
            return null;
        }
        foreach($list as $value){
            switch ($value['class']) {    //根据类别获取表明
                case 1:
                    $table = "responsibility";
                    $pre = "[党建责任]";
                    break;
                case 2:
                    $table = "learn";
                    $pre = "[两学一做]";
                    break;
                case 3:
                    $table = "organization";
                    $pre = "[组织建设]";
                    break;
                case 4:
                    $table = "special";
                    $pre = "[特色创新]";
                    break;
                case 5:
                    $table = "style";
                    $pre = "[作风建设]";
                    break;
                case 6:
                    $table = "volunteer";
                    $pre = "[志愿服务]";
                    break;
                case 7:
                    $table = "incorrupt";
                    $pre = "[党风廉政]";
                    break;
                default:
                    return $this->error("无该数据表");
                    break;
            }
            $map = array(
                'id' => $value['focus_main'],
                'status' => ['egt',0],
            );
            $info = Db::name($table)->where($map)->find();
            $value['title'] = $info['title'];
            $value['pre'] = $pre;
            $value['publisher'] = $info['publisher'];
            $value['create_time'] = date('Y-m-d',$info['create_time']);
            $Pic = Picture::where(['id' => $info['front_cover']])->find();
            $value['front_cover'] = $Pic['path'];
            // 是否 获取审核人信息
            if ($opt){
                // 是
                $review = PushReview::where(['push_id' => $value['focus_main'] , 'status' => ['egt',-1]])->select();
                foreach($review as $val){
                    $val['create_time'] = date('Y-m-d',$val['create_time']);
                }
                $value['review'] = $review;
            }
        }
        return $list;
    }
}