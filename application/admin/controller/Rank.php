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
            if (!empty($arr)){
                $map = array(
                    'tagid' => 1,
                    'userid' => ['in',$arr]
                );
            }
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
    /**
     * 积分详情
     */
    public function detail($id){
        $res = WechatUserTag::where(['tagid' => 1,'userid' => $id])->find();
        if (empty($res)){
            return $this->error('非考核人员 , 暂无权限');
        }
        $name = WechatUser::where('userid',$id)->value('name');
        $data = array(
            0 => array(
                'table' => 'responsibility',
                'class' => 1,
                'type' => 1,
                'str' => '【 党建责任--专题研究 】 （ 总：2分 ） '
            ),
            1 => array(
                'table' => 'responsibility',
                'class' => 1,
                'type' => 5,
                'str' => '【 党建责任--书记述职 】 （ 总：1分 ） '
            ),
            2 => array(
                'table' => 'responsibility',
                'class' => 1,
                'type' => 6,
                'str' => '【 党建责任--支部书记述职 】 （ 总：1分 ） '
            ),
            3 => array(
                'table' => 'responsibility',
                'class' => 1,
                'type' => 7,
                'str' => '【 党建责任--工作要点 】 （ 总：1分 ） '
            ),
            4 => array(
                'table' => 'responsibility',
                'class' => 1,
                'type' => 8,
                'str' => '【 党建责任--责任清单 】 （ 总：1分 ） '
            ),
            5 => array(
                'table' => 'learn',
                'class' => 2,
                'type' => 1,
                'str' => '【 两学一做--方案部署 】 （ 总：2分 ） '
            ),
            6 => array(
                'table' => 'learn',
                'class' => 2,
                'type' => 4,
                'str' => '【 两学一做--支部活动 】 （ 总：2分 ） '
            ),
            7 => array(
                'table' => 'learn',
                'class' => 2,
                'type' => 5,
                'str' => '【 两学一做--培训计划 】 （ 总：1分 ） '
            ),
            8 => array(
                'table' => 'learn',
                'class' => 2,
                'type' => 6,
                'str' => '【 两学一做--理论研究 】 （ 总：1分 ） '
            ),
            9 => array(
                'table' => 'learn',
                'class' => 2,
                'type' => 7,
                'str' => '【 两学一做--支部党员大会 】 （ 总：1分 ） '
            ),
            10 => array(
                'table' => 'learn',
                'class' => 2,
                'type' => 8,
                'str' => '【 两学一做--党支部委员会 】 （ 总：1分 ） '
            ),
            11 => array(
                'table' => 'learn',
                'class' => 2,
                'type' => 9,
                'str' => '【 两学一做--党小组会 】 （ 总：1分 ） '
            ),
            12 => array(
                'table' => 'learn',
                'class' => 2,
                'type' => 10,
                'str' => '【 两学一做--党课 】 （ 总：1分 ） '
            ),
            13 => array(
                'table' => 'organization',
                'class' => 3,
                'type' => 2,
                'str' => '【 组织建设--离退休党员台账资料 】 （ 总：3分 ） '
            ),
            14 => array(
                'table' => 'organization',
                'class' => 3,
                'type' => 3,
                'str' => '【 组织建设--党费收缴 】 （ 总：1分 ） '
            ),
            15 => array(
                'table' => 'organization',
                'class' => 3,
                'type' => 4,
                'str' => '【 组织建设--信息录用 】 （ 总：2分 ） '
            ),
            16 => array(
                'table' => 'organization',
                'class' => 3,
                'type' => 5,
                'str' => '【 组织建设--阵地建设 】 （ 总：0.5分 ） '
            ),
            17 => array(
                'table' => 'organization',
                'class' => 3,
                'type' => 6,
                'str' => '【 组织建设--按期换届 】 （ 总：0.5分 ） '
            ),
            18 => array(
                'table' => 'organization',
                'class' => 3,
                'type' => 7,
                'str' => '【 组织建设--主题党日 】 （ 总：1分 ） '
            ),
            19 => array(
                'table' => 'special',
                'class' => 4,
                'type' => 0,
                'str' => '【 特色创新 】 （ 总：10分 ） '
            ),
            20 => array(
                'table' => 'style',
                'class' => 5,
                'type' => 1,
                'str' => '【 作风建设--方案部署 】 （ 总：2分 ） '
            ),
            21 => array(
                'table' => 'style',
                'class' => 5,
                'type' => 2,
                'str' => '【 作风建设--金点子 】 （ 总：2分 ） '
            ),
            22 => array(
                'table' => 'style',
                'class' => 5,
                'type' => 3,
                'str' => '【 作风建设--培树典型 】 （ 总：2分 ） '
            ),
            23 => array(
                'table' => 'style',
                'class' => 5,
                'type' => 4,
                'str' => '【 作风建设--党员清单 】 （ 总：2分 ) '
            ),
            24 => array(
                'table' => 'volunteer',
                'class' => 6,
                'type' => 1,
                'str' => '【 志愿服务--四跑志愿服务 】 （ 总：2分 ) '
            ),
            25 => array(
                'table' => 'volunteer',
                'class' => 6,
                'type' => 2,
                'str' => '【 志愿服务--一条街三走进 】 （ 总：2分 ) '
            ),
            26 => array(
                'table' => 'volunteer',
                'class' => 6,
                'type' => 3,
                'str' => '【 志愿服务--最多跑一次 】 （ 总：2分 ) '
            ),
            27 => array(
                'table' => 'incorrupt',
                'class' => 7,
                'type' => 1,
                'str' => '【 党风廉政--廉政责任 】 （ 总：2分 ) '
            ),
            28 => array(
                'table' => 'incorrupt',
                'class' => 7,
                'type' => 2,
                'str' => '【 党风廉政--廉政教育 】 （ 总：2分 ) '
            ),
            29 => array(
                'table' => 'incorrupt',
                'class' => 7,
                'type' => 3,
                'str' => '【 党风廉政--纪检报告 】 （ 总：2分 ) '
            ),
        );
        $sum = 0;
        foreach($data as $key => $value){
            $msg = Db::name('score')->where(['userid' => $id , 'class' => $value['class'] , 'type' => $value['type']])->whereTime('create_time','y')->order('id desc')->select();
            $score = 0;
            $content = '';
            foreach($msg as $val){
                $score += $val['score_up'] / $val['score_down'];
                if ($value['class'] == 2  && $val['aid'] == 0){
                    $title = '无党小组会数据';
                }elseif($value['class'] == 3  && $val['aid'] == 0){
                    $title = '无离退休党员台账资料数据';
                }else{
                    $count = Db::name($value['table'])->where(['id' => ['>=',$val['aid']],'status' => ['egt',0]])->count();
                    // 具体该条数据在第几页
                    $p = ceil(($count/10));
                    $url = "http://".$_SERVER['SERVER_NAME']."/admin/".$value['table']."/index/id/".$val['aid']."/p/".$p;
                    $str = Db::name($value['table'])->where(['id' => $val['aid'] , 'status' => ['egt',0]])->value('title');
                    if (empty($str)){
                        // 该条数据已经被删除 需要删掉该条记录积分
                        $title = '';
                        Db::name('score')->where(['userid' => $id , 'id' => $val['id']])->delete();
                        $this->redirect("http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
                    }else{
                        $title = '<a href="'.$url.'">'.$str.'</a>';
                    }
                }
                $content .= $title.' （ '.$val['score_up'] / $val['score_down'].' 分 ） ， ';
            }
            $data[$key]['score'] = $score;
            $data[$key]['content'] = $content;
            $sum += $score;
        }
        $this->assign('list',$data);
        $this->assign('sum',$sum);
        $this->assign('name',$name);
        return $this->fetch();
    }
}