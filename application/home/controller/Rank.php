<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/10/19
 * Time: 19:09
 */

namespace app\home\controller;
use app\home\model\WechatDepartment;
use app\home\model\WechatDepartmentUser;
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
        $userId = session('userId');
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
            $value['name'] = $User['name'];
        }
        // 冒泡排序  大数向前排列
        for($i = 1;$i < count($list); $i++){
            for ($k = 0;$k < count($list)-$i;$k++){
                if ($list[$k]['score'] < $list[$k+1]['score']){
                    $temp = $list[$k+1];
                    $list[$k+1] = $list[$k];
                    $list[$k] = $temp;
                }
            }
        }
        foreach($list as $key => $value){
            if ($key >= 10){
                unset($list[$key]);
            }
        }
        $Depart = WechatUser::where('userid',$userId)->field('department')->find();
        $depart = json_decode($Depart['department']);
        if (is_array($depart)){
            $depart_id = $depart[0];  // 两个部门 只获取第一个
        }else{
            $depart_id = $depart;
        }
        $users = WechatDepartmentUser::where('departmentid',$depart_id)->field('userid')->select();
        $score = 0;
        foreach($users as $value){  // 获取自己所在部门的管理员积分
            $res = WechatUserTag::where(['tagid' => 1,'userid' => $value['userid']])->find();
            if (!empty($res)){
                $User = WechatUser::where('userid',$value['userid'])->find();
                $score1 = $User['score_efficiency'];  // 机关效能积分
                $score2 = $User['score_form'];  // 四种形态积分
                $score3 = $User['score_satisfaction'];  // 满意度测评积分
                $Arr = Db::name('score')->where('userid',$value['userid'])->whereTime('create_time','y')->select();
                $score4 = 0;  // 发布所得分数
                foreach($Arr as $val){
                    $score4 += ($val['score_up'] / $val['score_down']);
                }
                $score = $score1 + $score2 + $score3 + $score4;
                break;
            }

        }
        $name = WechatDepartment::where('id',$depart_id)->value('name');
        $this->assign('name',$name);
        $this->assign('score',$score);
        $this->assign('all',$list);
        return $this->fetch();
    }

}