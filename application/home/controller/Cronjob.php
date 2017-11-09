<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/2/17
 * Time: 16:28
 */

namespace app\home\controller;
use app\home\model\WechatUser;
use think\Controller;
use app\home\model\Notice;
use app\home\model\WechatUserTag;
use app\home\model\Browse;
use app\home\model\NoticeMinus;

/**
 * Class Cronjob
 * @package  定时执行任务
 */
class Cronjob extends Controller {
    /**
     * 通知公告  截止时间  未读 自动扣分
     */
    public function down_score(){
        $list = Notice::where(['end_time' => ['egt',time()],'status' => ['egt',0] ])->select(); // 已经截止的 列表
        foreach($list as $value){
            $res = NoticeMinus::where(['notice_id' => $value['id']])->find();
            if (!$res){  // 未执行过扣分 
                // 获取  需要考核的人员
                $People = WechatUserTag::where(['tagid' => 1])->select();
                // 未读人员  名单
                foreach($People as $val){
                    $read = Browse::where(['type' => 1,'aid' => $value['id'],'uid' => $val['userid']])->find();
                    if (!$read){
                        // 未读
                        $data = [
                            'notice_id' => $value['id'],
                            'userid' => $val['userid'],
                            'create_time' => time()
                        ];
                        NoticeMinus::create($data);
                    }
                }
            }
        }
    }
}