<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/6/14
 * Time: 10:19
 */

namespace app\home\model;
use think\Model;

class Push extends Model {
    /*获取 push 表列表 */
    public function get_list($status=0,$len=0,$opt=false){
        $map = [
            'status' => ['egt',$status]
        ];
        $list = $this->where($map)->order('create_time desc')->limit($len,10)->select();
        if (empty($list)){
            return null;
        }
        foreach($list as $value){
            switch($value['class']){  // 根据 class 值找对应表
                case 1:
                    // 获取 主图文 详情
                    
                    break;
                case 2:
                    // 获取 主图文 详情
                    
                    break;
                case 3;
                    // 获取 主图文 详情
                    
                    break;

            }
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