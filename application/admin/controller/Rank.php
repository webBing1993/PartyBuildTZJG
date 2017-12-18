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
    /**
     * 积分详情
     */
    public function detail($id){
        $res = WechatUserTag::where(['tagid' => 1,'userid' => $id])->find();
        if (empty($res)){
            return $this->error('非考核人员 , 暂无权限');
        }
        $responsibility = array(
            0 => array(
                'type' => 1,
                'str' => '党建责任-专题研究 ( 2分 ) '
            ),
            1 => array(
                'type' => 5,
                'str' => '党建责任-书记述职 ( 1分 ) '
            ),
            2 => array(
                'type' => 6,
                'str' => '党建责任-支部书记述职 ( 1分 ) '
            ),
            3 => array(
                'type' => 7,
                'str' => '党建责任-工作要点 ( 1分 ) '
            ),
            4 => array(
                'type' => 8,
                'str' => '党建责任-责任清单 ( 1分 ) '
            ),
        );
        foreach($responsibility as $key => $value){
            $msg = Db::name('score')->where(['userid' => $id , 'class' => 1 , 'type' => $value['type']])->order('id desc')->select();
            $score = 0;
            $content = '';
            foreach($msg as $val){
                $score += $val['score_up'] / $val['score_down'];
                $title = Db::name("responsibility")->where(['id' => $val['aid']])->value('title');
                $content .= $title.' ( '.$val['score_up'] / $val['score_down'].' ) ';
            }
            $responsibility[$key]['score'] = $score;
            $responsibility[$key]['content'] = $content;
        }
        dump($responsibility);
        $msg = Db::name('score')->where(['userid' => $id])->order('id desc')->select();
        foreach($msg as $key => $value){
            $msg[$key]['score'] = $value['score_up'] / $value['score_down'];
            $msg[$key]['create_time'] = date('Y-m-d',$value['create_time']);
            switch ($value['class']){
                case 1: // 党建责任
                    $table = "responsibility";
                    $info = Db::name($table)->where(['id' => $value['aid']])->find();
                    $msg[$key]['title'] = $info['title'];
                    switch ($value['type']){
                        case 1:
                            $msg[$key]['str'] = "党建责任-专题研究";
                            break;
                        case 5:
                            $msg[$key]['str'] = "党建责任-书记述职";
                            break;
                        case 6:
                            $msg[$key]['str'] = "党建责任-支部书记述职";
                            break;
                        case 7:
                            $msg[$key]['str'] = "党建责任-工作要点";
                            break;
                        case 8:
                            $msg[$key]['str'] = "党建责任-责任清单";
                            break;
                        default:
                            $msg[$key]['str'] = "暂无数据";
                    }
                    break;
                case 2: // 两学一做
                    $table = "learn";
                    if ($value['aid'] == 0){
                        $msg[$key]['title'] = "无党小组会数据";
                    }else{
                        $info = Db::name($table)->where(['id' => $value['aid']])->find();
                        $msg[$key]['title'] = $info['title'];
                    }
                    switch ($value['type']){
                        case 1:
                            $msg[$key]['str'] = "两学一做-方案部署";
                            break;
                        case 4:
                            $msg[$key]['str'] = "两学一做-支部活动";
                            break;
                        case 5:
                            $msg[$key]['str'] = "两学一做-培训计划";
                            break;
                        case 6:
                            $msg[$key]['str'] = "两学一做-理论研究";
                            break;
                        case 7:
                            $msg[$key]['str'] = "两学一做-支部党员大会";
                            break;
                        case 8:
                            $msg[$key]['str'] = "两学一做-党支部委员会";
                            break;
                        case 9:
                            $msg[$key]['str'] = "两学一做-党小组会";
                            break;
                        case 10:
                            $msg[$key]['str'] = "两学一做-党课";
                            break;
                        default:
                            $msg[$key]['str'] = "暂无数据";
                    }
                    break;
                case 3: // 组织建设
                    $table = "organization";
                    $info = Db::name($table)->where(['id' => $value['aid']])->find();
                    $msg[$key]['title'] = $info['title'];
                    switch ($value['type']){
                        case 1:
                            $msg[$key]['str'] = "组织建设-规范性建设";
                            break;
                        case 2:
                            $msg[$key]['str'] = "组织建设-离退休党员台账资料";
                            break;
                        case 3:
                            $msg[$key]['str'] = "组织建设-党费收缴";
                            break;
                        case 4:
                            $msg[$key]['str'] = "组织建设-信息录用";
                            break;
                        case 5:
                            $msg[$key]['str'] = "组织建设-阵地建设";
                            break;
                        case 6:
                            $msg[$key]['str'] = "组织建设-按期换届";
                            break;
                        case 7:
                            $msg[$key]['str'] = "组织建设-主题党日";
                            break;
                        default:
                            $msg[$key]['str'] = "暂无数据";
                    }
                    break;
                case 4: // 特色创新
                    $table = "special";
                    $info = Db::name($table)->where(['id' => $value['aid']])->find();
                    $msg[$key]['title'] = $info['title'];
                    $msg[$key]['str'] = "特色创新";
                    break;
                case 5: // 作风建设
                    $table = "style";
                    $info = Db::name($table)->where(['id' => $value['aid']])->find();
                    $msg[$key]['title'] = $info['title'];
                    switch ($value['type']){
                        case 1:
                            $msg[$key]['str'] = "作风建设-方案部署";
                            break;
                        case 2:
                            $msg[$key]['str'] = "作风建设-金点子";
                            break;
                        case 3:
                            $msg[$key]['str'] = "作风建设-培树典型";
                            break;
                        case 4:
                            $msg[$key]['str'] = "作风建设-党员清单";
                            break;
                        default:
                            $msg[$key]['str'] = "暂无数据";
                    }
                    break;
                case 6: // 志愿服务
                    $table = "volunteer";
                    $info = Db::name($table)->where(['id' => $value['aid']])->find();
                    $msg[$key]['title'] = $info['title'];
                    switch ($value['type']){
                        case 1:
                            $msg[$key]['str'] = "志愿服务-四跑志愿服务";
                            break;
                        case 2:
                            $msg[$key]['str'] = "志愿服务-一条街三走进";
                            break;
                        case 3:
                            $msg[$key]['str'] = "志愿服务-最多跑一次";
                            break;
                        default:
                            $msg[$key]['str'] = "暂无数据";
                    }
                    break;
                default:  // 党风廉政
                    $table = "incorrupt";
                    $info = Db::name($table)->where(['id' => $value['aid']])->find();
                    $msg[$key]['title'] = $info['title'];
                    switch ($value['type']){
                        case 1:
                            $msg[$key]['str'] = "党风廉政-廉政责任";
                            break;
                        case 2:
                            $msg[$key]['str'] = "党风廉政-廉政教育";
                            break;
                        case 3:
                            $msg[$key]['str'] = "党风廉政-纪检报告";
                            break;
                        default:
                            $msg[$key]['str'] = "暂无数据";
                    }

            }
        }
        $this->assign('list',$msg);
        return $this->fetch();
    }
}