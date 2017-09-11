<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/9/11
 * Time: 16:14
 */

namespace app\admin\controller;
use app\admin\model\WechatUserTag;
use app\admin\model\WechatUser;
use think\Db;
/**
 * Class Rank
 * @package app\admin\controller  排行榜
 */
class Rank extends Admin
{
    /**
     * 首页
     */
    public function index(){
        // 获取考核人员列表
        $map = array(
            'tagid' => 1
        );
        $list = $this->lists('WechatUserTag',$map);
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
            $value['efficiency'] = $score1;
            $value['form'] = $score2;
            $value['satisfaction'] = $score3;
            $value['push'] = $score4;
        }
        // 冒泡排序  大数向前排列
        for($i = 1;$i < count($list); $i++){
            for ($k = 0;$k < count($list)-$i;$k++){
                if ($list[$k]['score'] < $list[$k+1]['score']){
                    $temp = $list[$k+1];
                    $list[$k] = $temp;
                    $list[$k+1] = $list[$k];
                }
            }
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 操作日志
     */
    public function book(){
        return $this->fetch();
    }
}