<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
use app\admin\model\Member;
use app\home\model\WechatUser;
use  app\home\model\Push;
use app\home\model\PushReview;
use com\wechat\TPQYWechat;
use app\home\model\Picture;
use think\Config;
use think\Db;
/**
 * Class Review
 * @package 消息审核
 */
class Review extends Base{
    /**
     *  发布审核 列表
     */
    public function index(){
        $this ->anonymous();
        $len = array('responsibility' => 0,'learn' => 0,'organization' => 0,'special' => 0,'style' => 0,'volunteer' => 0,'incorrupt' => 0);
        $list1 = $this ->getDataList($len);  // 发布审核
        // 获取 推送审核列表
        $Push = new Push();
        $list2 = $Push->get_list();
        $list3 = $this ->getDataList($len,2);  // 已审核
        foreach($list3['data'] as $key => $value){
            //  获取审核人
            $review = Db::name('review')->where(['class' => $value['class'],'aid' => $value['id']])->find();
            if (!empty($review)){
                $list3['data'][$key]['username'] = $review['username'];
                $list3['data'][$key]['review_status'] = $review['status'];
                $list3['data'][$key]['review_time'] = date('Y-m-d',$review['create_time']);
            }else{
                $list3['data'][$key]['username'] = "*** 数据缺失 ***";
                $list3['data'][$key]['review_status'] = 0;
                $list3['data'][$key]['review_time'] = "0000-00-00";
            }
        }
        $this ->assign('list1',$list1['data']);
        $this ->assign('list2',$list2);
        $this ->assign('list3',$list3['data']);
        return $this->fetch();
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
        if(!empty($info['list_images'])){
            $info['list_images'] = json_decode($info['list_images']);
        }
        if ($type == 4){
            if(!empty($info['commend_img'])){
                $info['commend_img'] = json_decode($info['commend_img']);
            }
            if(!empty($info['voucher_img'])){
                $info['voucher_img'] = json_decode($info['voucher_img']);
            }
        }else{
            $info['commend_img'] = '';
            $info['voucher_img'] = '';
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
        if ($status == 2){
            $map = array(
                'status' => ['in',[1,$status]],
            );
        }else{
            $map = array(
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
                if (empty($value['create_user'])){
                    $User = WechatUser::where('userid',$value['userid'])->find();
                    $list[$key]['publisher'] = $User['name'];
                }else{
                    $User = Member::where('uid',$value['create_user'])->find();
                    $list[$key]['publisher'] = $User['nickname'];
                }
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
     * 加载更多  发布审核  已审核  
     * @return array
     */
    public function moreDataList(){
        $len = input('post.');
        $type = $len['type'];  //  获取 类型   0  发布审核  1 推送审核  2  已审核
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
                    $list['data'][$key]['username'] = "*** 数据缺失 ***";
                    $list['data'][$key]['review_status'] = 0;
                    $list['data'][$key]['review_time'] = "0000-00-00";
                }
            }
        }
        return $list;
    }
    /**
     * @return 加载更多  推送审核
     */
    public function more(){
        $msg = input('post.');
        $Push = new Push();
        $list = $Push->get_list(0,$msg['len']);
        if ($list){
            return ['code' => 1,'msg' => '获取成功','data' => $list];
        }else{
            return ['code' => 0,'msg' => '获取失败','data' => null];
        }
    }
    /*
     * 审核 详细页  模板1
     */
    public function detail(){
        $class = input('get.class');
        $id = input('get.id');
        $info = $this->get_detail($class,$id);
        $this->assign('class',$class);
        $this->assign('info',$info);
        return  $this->fetch();
    }
    /*
     * 审核 详细页  模板2
     */
    public function detail2(){
        $class = input('get.class');
        $id = input('get.id');
        $info = $this->get_detail($class,$id);
        $this->assign('class',$class);
        $this->assign('info',$info);
        return  $this->fetch();
    }
    /**
     * 发布审核  去审核
     */
    public function review(){
        $this->anonymous();
        $userId = session('userId');
        $user = WechatUser::where('userid', $userId)->find();
        $username = $user['name'];
        $msg = input('post.');
        //新建review数据
        $data = array(
            'class' => $msg['class'],
            'aid' => $msg['id'],
            'userid' => $userId,
            'username' => $username,
            'status' => $msg['status'],
            'create_time' => time()
        );
        Db::name('review')->insert($data);
        $res = $this->change_status($msg['class'],$msg['id'],$msg['status']);
        if ($res){
           return $this->success('审核成功');
        }else{
            return $this->error('审核失败');
        }
    }
    /**
     * 推送审核  去审核
     */
    public function push(){
        $msg = input('post.');
        $userId = session('userId');
        $user = WechatUser::where('userid', $userId)->find();
        $username = $user['name'];
        $info = Push::where('id',$msg['id'])->find();
        if($msg['status'] == 1 ){  // 审核通过  推送消息
            $this->push_detail($info['class'],$info['focus_main'],$info['focus_vice']);
        }
        $res = Push::where('id',$msg['id'])->update(['status' => $msg['status']]);
        if ($res){
            PushReview::create(['push_id' => $msg['id'],'user_id' => $userId, 'username' => $username,'review_time' => time(),'status' => $msg['status']]);
            return $this->success('审核成功');
        }else{
            return $this->error('审核失败');
        }
    }
    /**
     *  改变状态值
     */
    public function change_status($type,$id,$status){
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
        switch ($status){
            case 1:  // 发布审核  通过
                $res = Db::name($table)->where(['id' => $id])->update(['status' => 1]);
                break;
            default:  // 发布审核  不通过
                $res = Db::name($table)->where(['id' => $id])->update(['status' => 2]);
        }
        return $res;
    }
    /**
     * 获取推送详情
     * 党建责任 responsibility  两学一做 learn 组织建设 organization 特色创新 special 作风建设 style 志愿服务 volunteer 党风廉政 incorrupt
     */
    public function push_detail($type,$main,$vice=''){
        switch ($type) {    //根据类别获取表明
            case 1:
                $table = "responsibility";
                $pre = "【党建责任】";
                $temp = Db::name($table)->where('id',$main)->value('templet');
                if ($temp == 1){
                    $url = "Responsibility/detail";
                }else{
                    $url = "Responsibility/detail2";
                }
                $agentid = 1000002;
                $Wechat = new TPQYWechat(Config::get('responsibility'));
                break;
            case 2:
                $table = "learn";
                $pre = "【两学一做】";
                $temp = Db::name($table)->where('id',$main)->value('templet');
                if ($temp == 1){
                    $url = "Learn/detail";
                }else{
                    $url = "Learn/detail2";
                }
                $agentid = 1000003;
                $Wechat = new TPQYWechat(Config::get('learn'));
                break;
            case 3:
                $table = "organization";
                $pre = "【组织建设】";
                $temp = Db::name($table)->where('id',$main)->value('templet');
                if ($temp == 1){
                    $url = "Organization/detail";
                }else{
                    $url = "Organization/detail2";
                }
                $agentid = 1000004;
                $Wechat = new TPQYWechat(Config::get('organization'));
                break;
            case 4:
                $table = "special";
                $pre = "【特色创新】";
                $temp = Db::name($table)->where('id',$main)->value('templet');
                if ($temp == 1){
                    $url = "Special/detail";
                }else{
                    $url = "Special/detail2";
                }
                $agentid = 1000005;
                $Wechat = new TPQYWechat(Config::get('special'));
                break;
            case 5:
                $table = "style";
                $pre = "【作风建设】";
                $temp = Db::name($table)->where('id',$main)->value('templet');
                if ($temp == 1){
                    $url = "Style/detail";
                }else{
                    $url = "Style/detail2";
                }
                $agentid = 1000013;
                $Wechat = new TPQYWechat(Config::get('style'));
                break;
            
            case 6:
                $table = "volunteer";
                $pre = "【志愿服务】";
                $temp = Db::name($table)->where('id',$main)->value('templet');
                if ($temp == 1){
                    $url = "Volunteer/detail";
                }else{
                    $url = "Volunteer/detail2";
                }
                $agentid = 1000006;
                $Wechat = new TPQYWechat(Config::get('volunteer'));
                break;
            case 7:
                $table = "incorrupt";
                $pre = "【党风廉政】";
                $temp = Db::name($table)->where('id',$main)->value('templet');
                if ($temp == 1){
                    $url = "Incorrupt/detail";
                }else{
                    $url = "Incorrupt/detail2";
                }
                $agentid = 1000007;
                $Wechat = new TPQYWechat(Config::get('incorrupt'));
                break;
            default:
                return $this->error("无该数据表");
        }
        //活动基本信息  主图文
        $focus1 = Db::name($table)->where('id',$main)->find();
        if (empty($focus1)){
            $this ->error('该内容不存在或已删除!');
        }
        $title1 = $focus1['title'];
        $str1 = strip_tags($focus1['content']);
        $des1 = mb_substr($str1, 0, 40);
        $content1 = str_replace("&nbsp;", "", $des1);  //空格符替换成空
        $url1 = Config::get('host_url')."/home/".$url."/id/" . $focus1['id'] . ".html";
        $img1 = Picture::get($focus1['front_cover']);
        $path1 = Config::get('host_url') . $img1['path'];
        $information1 = array(
            "title" => $pre . $title1,
            "description" => $content1,
            "url" => $url1,
            "picurl" => $path1,
        );
        $information = array();
        if (!empty($vice)) {
            //副图文信息
            $vice = json_decode($vice);
            $information2 = array();
            foreach ($vice as $key => $value) {
                $focus2 = Db::name($table)->where('id',$value)->find();
                $title2 = $focus2['title'];
                $str2 = strip_tags($focus2['content']);
                $des2 = mb_substr($str2, 0, 41);
                $content2 = str_replace("&nbsp;", "", $des2);  //空格符替换成空
                $url2 = Config::get('host_url')."/home/".$url."/id/" . $focus2['id'] . ".html";
                $img2 = Picture::get($focus2['front_cover']);
                $path2 = Config::get('host_url') . $img2['path'];
                $info = array(
                    "title" =>$pre.$title2,
                    "description" => $content2,
                    "url" => $url2,
                    "picurl" => $path2,
                );
                $information2[] = $info;
            }
            //数组合并，主图文放在首位
            foreach ($information2 as $k => $v) {
                $information[0] = $information1;
                $information[$k + 1] = $v;
            }
        } else {
            $information[0] = $information1;
        }
        //重组成article数据
        $send = array();
        $re[] = $information;
        foreach ($re as $key => $value) {
            $key = "articles";
            $send[$key] = $value;
        }
        //发送给服务号
        $message = array(
//            'touser' =>'17557289172',
                   "touser" => "@all",   //发送给全体，@all
            "msgtype" => 'news',
            "agentid" =>$agentid,
            "news" => $send,
            "safe" => "0"
        );
        $suc = $Wechat->sendMessage($message);
        return $suc;
    }
}