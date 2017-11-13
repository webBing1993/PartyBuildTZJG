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
        $search = input('search');
        // 获取考核人员列表
        $map = array(
            'tagid' => 1,

        );
        if ($search != '') {
            $where['name'] = ['like','%'.$search.'%'];
            $arr = WechatUser::where($where)->column('userid');
            $map = array(
                'tagid' => 1,
                'userid' => ['in',$arr]
            );
        }
        $list = WechatUserTag::where($map)->select();
        foreach($list as $key => $value){
            $User = WechatUser::where('userid',$value->userid)->find();
            if (!empty($User)){
                $score1 = $User['score_party'];  // 党风廉政 4
                $score2 = $User['score_satisfaction'];  // 满意度测评积分 40
                $score3 = $User['score_work'];  // 两新党建 2
                $Arr = Db::name('score')->where('userid',$value->userid)->whereTime('create_time','y')->select();
                $score4 = 0;
                foreach($Arr as $val){
                    $score4 += ($val['score_up'] / $val['score_down']);
                }
                $value['score'] = $score1 + $score2 + $score3 + $score4;
                $value['name'] = $User['name'];
                $value['party'] = $score1;
                $value['satisfaction'] = $score2;
                $value['work'] = $score3;
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
        $search = input('search');
        $where = array();
        if ($search != '') {
            $map['name'] = ['like','%'.$search.'%'];
            $arr = WechatUser::where($map)->column('userid');
            $where = array(
                'userid' => ['in',$arr]
            );
        }
        $list =  $this->lists('Handle',$where);
        foreach($list as $value){
            $name = WechatUser::where('userid',$value['userid'])->value('name');
            switch ($value['class']){
                case 1:
                    $pre = "党风廉政";
                    $num = 1;
                    break;
                case 2:
                    $pre = "满意度测评积分";
                    $num = 1;
                    break;
                case 3:
                    $pre = "两新党建";
                    $num = 1;
                    break;
            }
            if ($value['type'] == 1){
                $ps = "减去";
            }else{
                $ps = "增加";
            }
            $value['content'] = "对用户：【".$name."】的【".$pre.'】【'.$ps."】".$num." 分";
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
        $class = $data['class'];  // 1 党风廉政 4分 2 满意度测评 3 两新党建
        $type = $data['type'];  // 1 减  2 加
        $User = WechatUser::where('userid',$userid)->find();
        if (empty($User)){
            return $this->error('操作失败');
        }
        switch ($class){
            case 1:
                $field = 'score_party';
                $num = 1;
                break;
            case 2:
                $field = 'score_satisfaction';
                $num = 1;
                break;
            case 3:
                $field = 'score_work';
                $num = 1;
                break;
        }
        $create_user = $_SESSION['think']['user_auth']['id'];
        if ($type == 1){
            // 减分
            $res = WechatUser::where('userid',$userid)->setDec($field,$num);
        }else{
            // 加分
            $res = WechatUser::where('userid',$userid)->setInc($field,$num);
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