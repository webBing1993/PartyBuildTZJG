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
            'tagid' => 1,
            
        );
        $list = WechatUserTag::where($map)->select();
        foreach($list as $key => $value){
            $User = WechatUser::where('userid',$value->userid)->find();
            if (!empty($User)){
                $score1 = $User['score_efficiency'];  // 机关效能积分
                $score2 = $User['score_form'];  // 四种形态积分
                $score3 = $User['score_satisfaction'];  // 满意度测评积分
                $Arr = Db::name('score')->where('userid',$value->userid)->whereTime('create_time','y')->select();
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
            }else{
               unset($list[$key]);
            }
        }
        $arr = array();
        foreach($list as $key => $value){
            $arr[] = $value;
        }
        // 冒泡排序  大数向前排列
        for($i = 1;$i < count($arr); $i++){
            for ($k = 0;$k < count($arr)-$i;$k++){
                if ($arr[$k]['score'] < $arr[$k+1]['score']){
                    $temp = $arr[$k+1];
                    $arr[$k+1] = $arr[$k];
                    $arr[$k] = $temp;
                }
            }
        }
        $this->assign('list',$arr);
        return $this->fetch();
    }
    /**
     * 操作日志
     */
    public function book(){
        $list =  $this->lists('Handle');
        foreach($list as $value){
            $name = WechatUser::where('userid',$value['userid'])->value('name');
            switch ($value['class']){
                case 1:
                    $pre = "机关效能积分";
                    break;
                case 2:
                    $pre = "四种形态积分";
                    break;
                default :
                    $pre = "满意度测评积分";
            }
            if ($value['type'] == 1){
                $ps = "减去";
            }else{
                $ps = "增加";
            }
            $value['content'] = "对用户：【".$name."】的【".$pre.'】【'.$ps."】1分";
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 人工操作积分
     */
    public function handle(){
        $data = input('post.');
        $userid = $data['userid'];
        $class = $data['class'];  // 1 机关效能 2 四种形态 3 满意度测评
        $type = $data['type'];  // 1 减  2 加
        $User = WechatUser::where('userid',$userid)->find();
        if (empty($User)){
            return $this->error('操作失败');
        }
        switch ($class){
            case 1:
                $field = 'score_efficiency';
                break;
            case 2:
                $field = 'score_form';
                break;
            default :
                $field = 'score_satisfaction';
        }
        $create_user = $_SESSION['think']['user_auth']['id'];
        if ($type == 1){
            // 减分
            $res = WechatUser::where('userid',$userid)->setDec($field);
        }else{
            // 加分
            $res = WechatUser::where('userid',$userid)->setInc($field);
        }
        if ($res){
            // 存日志
            Db::name('handle')->insert(['userid' => $userid,'class' => $class,'type' => $type,'create_time' => time(),'create_user' => $create_user]);
            return  $this->success('操作成功');
        }else{
            return $this->error('操作失败');
        }
    }
}