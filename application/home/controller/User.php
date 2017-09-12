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
        $info = WechatUserTag::where(['userid' => $userId,'tagid' => ''])->find();
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
     * 草稿 查看详情
     */
    public function detail(){
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
            if (!$responsibility_check && count($all_list) < 7){
                $res1 = $this->get_con(1,$count1,$status);
                if (empty($res1)){
                    $responsibility_check = true;
                }else{
                    $count1 ++ ;
                    $all_list = $this->changeTpye($all_list,$res1,1);
                }
            }
            // 两学一做
            if(!$learn_check && count($all_list) < 7) {
                $res2 = $this->get_con(2,$count2,$status);
                if(empty($res2)) {
                    $learn_check = true;
                }else {
                    $count2 ++;
                    $all_list = $this ->changeTpye($all_list,$res2,2);
                }
            }
            // 组织建设
            if(!$organization_check && count($all_list) < 7)
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
            if (!$special_check && count($all_list) < 7){
                $res4 = $this->get_con(4,$count4,$status);
                if (empty($res4)){
                    $special_check = true;
                }else{
                    $count4 ++;
                    $all_list = $this->changeTpye($all_list,$res4,4);
                }
            }
            // 作风建设
            if (!$style_check && count($all_list) < 7){
                $res5 = $this->get_con(5,$count5,$status);
                if (empty($res5)){
                    $style_check = true;
                }else{
                    $count5++;
                    $all_list = $this->changeTpye($all_list,$res5,5);
                }
            }
            //  志愿服务
            if (!$volunteer_check && count($all_list) < 7){
                $res6 = $this->get_con(6,$count6,$status);
                if (empty($res6)){
                    $volunteer_check = true;
                }else{
                    $count6 ++;
                    $all_list = $this->changeTpye($all_list,$res6,6);
                }
            }
            //  党风廉政
            if (!$incorrupt_check && count($all_list) < 7){
                $res7 = $this->get_con(7,$count7,$status);
                if (empty($res7)){
                    $incorrupt_check = true;
                }else{
                    $count7 ++;
                    $all_list = $this->changeTpye($all_list,$res7,7);
                }
            }
            if(count($all_list) >= 7 || ($responsibility_check && $organization_check && $learn_check && $incorrupt_check && $volunteer_check && $style_check && $special_check)) {
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
    public function get_detail($type,$id){
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
        $info = Db::name($table)->where($map)->find();
        return $info;
    }
    /**
     * 获取 每个表结构  数据
     * 1 responsibility  2 learn 3 organization 4 special 5 style 6 volunteer 7 incorrupt
     */
    public function get_con($type,$count=0,$status=0){
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
        $userid = session('userId');
        if ($status == 2){
            $map = array(
                'create_user' => $userid,
                'status' => ['in',[1,$status]],
            );
        }else{
            $map = array(
                'create_user' => $userid,
                'status' => ['eq',$status],
            );
        }
        $order = 'create_time desc';
        $limit = "$count,1";
        $list = Db::name($table)->where($map) ->order($order) ->limit($limit) ->select();
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
                $User = WechatUser::where('userid',$value['create_user'])->find();
                $list[$key]['publisher'] = $User['name'];
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
    public function item(){
        return $this->fetch();
    }
}
