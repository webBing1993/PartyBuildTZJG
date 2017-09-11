<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/10/19
 * Time: 19:09
 */

namespace app\home\controller;
use app\home\model\WechatUser;
use app\home\model\WechatUserTag;
use think\Controller;
use think\Db;

/**
 * Class Rank
 * 排行榜
 */
class Rank extends Base {
    /**
     * 个人中心 支部排行榜
     */
    public function department(){
        $this->anonymous();
        // 获取考核人员列表
        $list = WechatUserTag::where('tagid',1)->select();
        foreach($list as $value){
            $User = WechatUser::where('userid',$value['userid'])->find();
            $score1 = $User['score_efficiency'];  // 机关效能积分
            $score2 = $User['score_form'];  // 四种形态积分
            $score3 = $User['score_satisfaction'];  // 满意度测评积分
            $Arr = Db::name('score')->where('userid',$value['userid'])->whereTime('create_time','y')->select();
            $score4 = 0;
            foreach($Arr as $val){
                $score4 += ($val['score_up'] / $val['score_down']);
            }
            $value['score'] = $score1 + $score2 + $score3 + $score4;
        }
        // 冒泡排序
        for($i = 0;$i < count($list); $i++){
            if ($list[$i] < $list[$i+1]){
                $temp = $list[$i+1];
                $list[$i] = $temp;
                $list[$i+1] = $list[$i];
             }
        }
        $this->assign('all',$list);
        return $this->fetch();
    }

}