<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/4/20
 * Time: 15:34
 */

namespace app\home\controller;
use app\home\model\WechatDepartment;
use app\home\model\Picture;
use app\home\model\WechatUser;
use app\home\model\WechatUserTag;
use think\Controller;
use think\Db;
use think\Request;

/**
 * Class User
 * 用户个人中心
 */
class User extends Base {
    /**
     * 个人中心主页
     */
    public function index(){
        //游客判断
        $this->anonymous();
        $userId = session('userId');
        $user = WechatUser::where('userid',$userId)->find();
        // 积分明细 权限
        $info = WechatUserTag::where(['userid' => $userId,'tagid' => 1])->find();
        if (empty($info)){
            // 非考核 无权限
            $is = 0;
        }else{
            // 考核对象  
            $is = 1;
        }
        $this->assign('is',$is);
        $this->assign('user',$user);
        return $this->fetch();
    }

    /**
     * 个人信息页
     */
    public function personal(){
        $userId = session('userId');
        $user = WechatUser::where('userid',$userId)->find();
        $Department = WechatDepartment::where(['id'=>$user['department']])->find();
        $user['department'] = $Department['name'];
        switch ($user['gender']) {
            case 0:
                $user['sex'] = "未定义";
                break;
            case 1:
                $user['sex'] = "男";
                break;
            case 2:
                $user['sex'] = "女";
                break;
            default:
                break;
        }
        $this->assign('user',$user);
        $request = Request::instance() ->domain();
        $this ->assign('request',$request);
        return $this->fetch();
    }

    /**
     * 设置头像
     */
    public function setHeader(){
        $userId = session('userId');
        $header = input('header');
        $map = array(
            'header' => $header,
        );
        $info = WechatUser::where('userid',$userId)->update($map);
        if($info){
            return $this->success("修改成功");
        }else{
            return $this->error("修改失败");
        }
    }
    /**
     * 我的草稿  status : 3
     */
    public function mynotes(){
        $len = array('responsibility' => 0,'learn' => 0,'organization' => 0,'special' => 0,'style' => 0,'volunteer' => 0,'incorrupt' => 0);
        $list = $this ->getDataList($len,3);  //  草稿
        $this->assign('list',$list['data']);
        return $this->fetch();
    }
    /**
     * 草稿 查看详情  模板1
     */
    public function detail(){
        $class = input('get.class');
        $id = input('get.id');
        $info = $this->get_detail($class,$id);
        $this->assign('info',$info);
        return  $this->fetch();
    }
    /**
     * 草稿 查看详情  模板2
     */
    public function detail2(){
        $class = input('get.class');
        $id = input('get.id');
        $info = $this->get_detail($class,$id);
        $this->assign('info',$info);
        return  $this->fetch();
    }
    /**
     * 草稿 删除
     */
    public function del(){
        $data = input('post.');
        $class = $data['class'];
        $id = $data['id'];
        $res = $this->get_detail($class,$id,true);
        if ($res){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }
    /**
     * 我的发布  1 审核通过  2  不通过
     */
    public function mypublish(){
        $len = array('responsibility' => 0,'learn' => 0,'organization' => 0,'special' => 0,'style' => 0,'volunteer' => 0,'incorrupt' => 0);
        $list = $this ->getDataList($len,2);  //  已审核
        foreach($list['data'] as $key => $value){
            //  获取审核人
            $review = Db::name('review')->where(['class' => $value['class'],'aid' => $value['id']])->find();
            if (!empty($review)){
                $list['data'][$key]['username'] = $review['username'];
                $list['data'][$key]['review_status'] = $review['status'];
                $list['data'][$key]['review_time'] = date('Y-m-d',$review['create_time']);
            }else{
                $list['data'][$key]['username'] = "*** 暂无 ***";
                $list['data'][$key]['review_status'] = 0;
                $list['data'][$key]['review_time'] = "0000-00-00";
            }
        }
        $this->assign('list',$list['data']);
        return $this->fetch();
    }
    /**
     * 加载更多  我的发布 2  我的草稿 3
     * @return array
     */
    public function more(){
        $len = input('post.');
        $type = $len['type'];  // 2  我的发布  3  我的草稿
        unset($len['type']);
        $list = $this ->getDataList($len,$type);
        if ($type == 2){
            foreach($list['data'] as $key => $value){
                //  获取审核人
                $review = Db::name('review')->where(['class' => $value['class'],'aid' => $value['id']])->find();
                if (!empty($review)){
                    $list['data'][$key]['username'] = $review['username'];
                    $list['data'][$key]['review_status'] = $review['status'];
                    $list['data'][$key]['review_time'] = date('Y-m-d',$review['create_time']);
                }else{
                    $list['data'][$key]['username'] = "*** 暂无 ***";
                    $list['data'][$key]['review_status'] = 0;
                    $list['data'][$key]['review_time'] = "0000-00-00";
                }
            }
        }
        return $list;
    }
    /**
     * 获取数据列表 党建责任 responsibility  两学一做 learn 组织建设 organization 特色创新 special 作风建设 style 志愿服务 volunteer 党风廉政 incorrupt
     * @param $len
     */
    public function getDataList($len,$status=0)
    {
        //从第几条开始取数据
        $count1 = $len['responsibility'];   // 党建责任
        $count2 = $len['learn'];  // 两学一做
        $count3 = $len['organization'];  // 组织建设
        $count4 = $len['special']; // 特色创新
        $count5 = $len['style'];  // 作风建设
        $count6 = $len['volunteer'];  // 志愿服务
        $count7 = $len['incorrupt'];  // 党风廉政

        $responsibility_check = false; //新闻数据状态 true为取空
        $learn_check = false;
        $organization_check = false;
        $special_check = false;
        $style_check = false;
        $volunteer_check = false;
        $incorrupt_check = false;
        $all_list = array();
        //获取数据  取满14条 或者取不出数据退出循环
        while(true)
        {
            // 党建责任
            if (!$responsibility_check && count($all_list) < 14){
                $res1 = $this->get_con(1,$count1,$status);
                if (empty($res1)){
                    $responsibility_check = true;
                }else{
                    $count1 ++ ;
                    $all_list = $this->changeTpye($all_list,$res1,1);
                }
            }
            // 两学一做
            if(!$learn_check && count($all_list) < 14) {
                $res2 = $this->get_con(2,$count2,$status);
                if(empty($res2)) {
                    $learn_check = true;
                }else {
                    $count2 ++;
                    $all_list = $this ->changeTpye($all_list,$res2,2);
                }
            }
            // 组织建设
            if(!$organization_check && count($all_list) < 14)
            {
                $res3 = $this->get_con(3,$count3,$status);
                if(empty($res3))
                {
                    $organization_check = true;
                }else {
                    $count3 ++;
                    $all_list = $this ->changeTpye($all_list,$res3,3);
                }
            }
            // 特色创新
            if (!$special_check && count($all_list) < 14){
                $res4 = $this->get_con(4,$count4,$status);
                if (empty($res4)){
                    $special_check = true;
                }else{
                    $count4 ++;
                    $all_list = $this->changeTpye($all_list,$res4,4);
                }
            }
            // 作风建设
            if (!$style_check && count($all_list) < 14){
                $res5 = $this->get_con(5,$count5,$status);
                if (empty($res5)){
                    $style_check = true;
                }else{
                    $count5++;
                    $all_list = $this->changeTpye($all_list,$res5,5);
                }
            }
            //  志愿服务
            if (!$volunteer_check && count($all_list) < 14){
                $res6 = $this->get_con(6,$count6,$status);
                if (empty($res6)){
                    $volunteer_check = true;
                }else{
                    $count6 ++;
                    $all_list = $this->changeTpye($all_list,$res6,6);
                }
            }
            //  党风廉政
            if (!$incorrupt_check && count($all_list) < 14){
                $res7 = $this->get_con(7,$count7,$status);
                if (empty($res7)){
                    $incorrupt_check = true;
                }else{
                    $count7 ++;
                    $all_list = $this->changeTpye($all_list,$res7,7);
                }
            }
            if(count($all_list) >= 14 || ($responsibility_check && $organization_check && $learn_check && $incorrupt_check && $volunteer_check && $style_check && $special_check)) {
                break;
            }
        }
        if (count($all_list) != 0)
        {
            return ['code' => 1,'msg' => '获取成功','data' => $all_list];
        }else{
            return ['code' => 0,'msg' => '获取失败','data' => $all_list];
        }
    }
    /**
     * 获取  数据详情
     * 1 responsibility  2 learn 3 organization 4 special 5 style 6 volunteer 7 incorrupt
     */
    public function get_detail($type,$id,$del=false){
        switch ($type) {    //根据类别获取表明
            case 1:
                $table = "responsibility";
                break;
            case 2:
                $table = "learn";
                break;
            case 3:
                $table = "organization";
                break;
            case 4:
                $table = "special";
                break;
            case 5:
                $table = "style";
                break;
            case 6:
                $table = "volunteer";
                break;
            case 7:
                $table = "incorrupt";
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
        $map = array(
            'id' => $id,
            'status' => ['egt',0],
        );
        if ($del){
            $rs = Db::name($table)->where('id',$id)->update(['status' => -1]);
            return $rs;
        }else{
            $info = Db::name($table)->where($map)->find();
            if(!empty($info['list_images'])){
                $info['list_images'] = json_decode($info['list_images']);
            }
            // 获取文件
            if($info['file']) {
                $temp = json_decode($info['file']);
                $arr[] = [];
                foreach($temp as $key => $value){
                    $savepath = Db::name('file')->where('id',$value)->value('savepath');
                    $savename = Db::name('file')->where('id',$value)->value('savename');
                    $arr[$key]['url'] = "http://".$_SERVER["SERVER_NAME"]."/uploads/download/".$savepath.$savename;
                    $arr[$key]['name'] = Db::name('file')->where('id',$value)->value('name');
                }
                $info['files'] = $arr;
            }else{
                $info['files'] = '';
            }
            if ($type == 4){
                $info['commend_img'] = json_decode($info['commend_img']);
                $info['voucher_img'] = json_decode($info['voucher_img']);
                if ($info['commend_img'][0] == 0){
                    $info['commend_img'] = null;
                }
                if ($info['voucher_img'][0] == 0){
                    $info['voucher_img'] = null;
                }
            }else{
                $info['commend_img'] = '';
                $info['voucher_img'] = '';
            }
            return $info;
        }
    }
    /**
     * 获取 每个表结构  数据
     * 1 responsibility  2 learn 3 organization 4 special 5 style 6 volunteer 7 incorrupt
     */
    public function get_con($type,$count=0,$status=0){
        switch ($type) {    //根据类别获取表明
            case 1:
                $table = "pb_responsibility";
                break;
            case 2:
                $table = "pb_learn";
                break;
            case 3:
                $table = "pb_organization";
                break;
            case 4:
                $table = "pb_special";
                break;
            case 5:
                $table = "pb_style";
                break;
            case 6:
                $table = "pb_volunteer";
                break;
            case 7:
                $table = "pb_incorrupt";
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
        $userid = session('userId');
        $order = 'create_time desc';
        $limit = "$count,2";
        if ($status == 2){
            $user_id = Db::name('ucenter_member')->where('username',$userid)->value('id');
            if ($user_id){
                $list = Db::query("select * from {$table} where (userid = '{$userid}' or create_user = '{$user_id}') and status IN (0,1,2) order BY {$order} limit {$limit}");
            }else{
                $list = Db::query("select * from {$table} where (userid = '{$userid}') and status IN (0,1,2) order BY {$order} limit {$limit}");
            }
        }else{
            $map = array(
                'userid' => $userid,
                'status' => ['eq',$status],
            );
            $list = Db::table($table)->where($map) ->order($order) ->limit($limit) ->select();
        }
        foreach($list as $key => $value){
            $list[$key]['create_time'] = date('Y-m-d',$value['create_time']);  // 时间转换
            if (empty($value['front_cover'])){  // 封面图
                $list[$key]['front_cover'] = '/home/images/common/3.png';
            }else{
                $Pic = Picture::where('id',$value['front_cover'])->find();
                $list[$key]['front_cover'] = $Pic['path'];
            }
            // 发布人
            if (empty($value['publisher'])){
                $User = WechatUser::where('userid',$value['userid'])->find();
                $list[$key]['publisher'] = $User['name'];
            }
            // class 值 判断
            if (isset($value['class'])){
                $list[$key]['style'] = $value['class'];
            }else{
                $list[$key]['style'] = 0;
            }
            // type 值 判断
            if (isset($value['type'])){
                $list[$key]['type'] = $value['type'];
            }else{
                $list[$key]['type'] = 0;
            }
        }
        if(!empty($list))
        {
            return $list[0];
        }else{
            return $list;
        }
    }
    /**
     * 进行数据区分
     * @param $list
     * @param $type 1党建责任  2两学一做 3 组织建设 4 特色创新 5 作风建设 6 志愿服务 7 党风廉政
     */
    private function changeTpye($all,$list,$type){
        $list['class'] = $type;
        array_push($all,$list);
        return $all;
    }
    /**
     * 积分明细
     */
    public function myintegral(){
        $this->anonymous();
        $userId = session('userId');
        $User = WechatUser::where('userid',$userId)->find();
        if (empty($User)){
            $score = 0;
        }else{
            $score1 = $User['score_party'];  // 党风廉政 4
            $score2 = $User['score_satisfaction'];  // 满意度测评积分
            $score3 = $User['score_work'];  // 两新党建
            $Arr = Db::name('score')->where('userid', $userId)->whereTime('create_time', 'y')->select();
            $score4 = 0;
            foreach ($Arr as $val) {
                $score4 += ($val['score_up'] / $val['score_down']);
            }
            $score = $score1 + $score2 + $score3 + $score4;  // 总分
        }
        $msg = Db::name('score')->where(['userid' => $userId])->whereTime('create_time', 'y')->order('id desc')->select();
        foreach($msg as $key => $value){
            $opt = '';
            $msg[$key]['score'] = $value['score_up'] / $value['score_down'];
            if ($value['score_up'] % 7 == 0){
                $opt = '（补）';
            }
            $msg[$key]['create_time'] = date('Y-m-d',$value['create_time']);
            switch ($value['class']){
                case 1: // 党建责任
                    $table = "responsibility";
                    $info = Db::name($table)->where(['id' => $value['aid']])->find();
                    $msg[$key]['title'] = $info['title'].$opt;
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
                        $msg[$key]['title'] = $info['title'].$opt;
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
                    $msg[$key]['title'] = $info['title'].$opt;
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
                    $msg[$key]['str'] = "特色创新".$opt;
                    break;
                case 5: // 作风建设
                    $table = "style";
                    $info = Db::name($table)->where(['id' => $value['aid']])->find();
                    $msg[$key]['title'] = $info['title'].$opt;
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
                    $msg[$key]['title'] = $info['title'].$opt;
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
                    $msg[$key]['title'] = $info['title'].$opt;
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
        // 人工干预  分数
        $hander = Db::name('handle')->where(['userid' => $userId])->whereTime('create_time', 'y')->order('create_time desc')->select();
        foreach($hander as $key => $value){
            $hander[$key]['create_time'] = date('Y-m-d',$value['create_time']);
            if ($value['type'] == 1){
                // 减
                $paly = '-';
            }else{
                // 加
                $paly = '+';
            }
            switch ($value['class']){
                case 1:  // 党风廉政
                    $hander[$key]['str'] = "党风廉政-人工干预";
                    $num = 1;
                    break;
                case 2:  // 满意度测评
                    $hander[$key]['str'] = "满意度测评-人工干预";
                    $num = 1;
                    break;
                case 3:  // 两新党建
                    $hander[$key]['str'] = "两新党建-人工干预";
                    $num = 1;
                    break;
                default:
                    $msg[$key]['str'] = "暂无数据";

            }
            $hander[$key]['score'] = $paly." ".$num;
        }
        $this->assign('handle',$hander);
        $this->assign('score',$score);
        $this->assign('msg',$msg);
        $this->assign('time',date('Y-m-d',time()));
        return $this->fetch();
    }
}
