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
        $list2 = $this ->getDataList($len,1);  // 推送审核
        $list3 = $this ->getDataList($len,2);  // 已审核
        $this ->assign('list1',$list1['data']);
        $this ->assign('list2',$list2['data']);
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
        $map = array(
            'status' => ['eq',$status],
        );
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
                $User = Member::where('uid',$value['create_user'])->find();
                $list[$key]['publisher'] = $User['nickname'];
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
     * 首页加载更多新闻列表
     * @return array
     */
    public function moreDataList(){
        $len = input('get.');
        $list = $this ->getDataList($len);
        //转化图片路径 时间戳
        foreach ($list['data'] as $k => $v)
        {
            $img_path = Picture::get($list['data'][$k]['front_cover']);
            $list['data'][$k]['time'] = date('Y-m-d',$v['create_time']);
            $list['data'][$k]['path'] = $img_path['path'];
        }
        return $list;
    }
    /*
     * 审核 详细页
     */
    public function detail(){
        $id = input('param.id');
        $list = Push::where(['id' => $id])->find();
        // 根据不同的值   获取相应的数据
        switch ($list['class']){
            case 1:
                // 根据 主图文id  获取详情
                break;
            case 2:
                // 根据 主图文id  获取详情
                break;
            case 3:
                // 根据 主图文id  获取详情
                break;
            default:
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    /*发布审核 */
    public function review(){
        $userId = session('userId');
        $user = WechatUser::where('userid', $userId)->find();
        $username = $user['name'];
        $msg = input('post.');
        $push = Push::where('id',$msg['id'])->find();
        //新建pushreview数据
        $data = array(
            'push_id' => $msg['id'],
            'user_id' => $userId,
            'username' => $username,
            'status' => $msg['status'],
        );
        $arr1 = $push['focus_main'];    //主图文id
        !empty($push['focus_vice']) ? $arr2 = json_decode($push['focus_vice']) : $arr2 = "";    //副图文id
        PushReview::create($data);
        Push::where('id',$msg['id'])->update(['status' => $msg['status']]);  // 修改状态值
        if ($msg['status'] == 1){  // 发布审核   通过
            $content = "恭喜您提交的文章【".$msg['title']."】已成功通过审核！";
            //主图文信息  副图文  根据 class 的值  改变状态
            switch ($push['class']){
                case 1:
                    // 改变  主图文的 副图文 状态值
                    $res = $this->change_status(1,$arr1,$arr2,1);
                    break;
                case 2:
                    // 改变  主图文的 副图文 状态值
                    $res = $this->change_status(2,$arr1,$arr2,1);
                    break;
                case  3:
                    // 改变  主图文的 副图文 状态值
                    $res = $this->change_status(3,$arr1,$arr2,1);
                    break;
                default :
            }
        }else{
            // 发布审核  不通过
            $content = "很抱歉，您提交的文章【".$msg['title']."】未能通过审核！";
            //主图文信息  副图文  根据 class 的值  改变状态
            switch ($push['class']){
                case 1:
                    // 改变  主图文的 副图文 状态值
                    $res = $this->change_status(1,$arr1,$arr2,0);
                    break;
                case 2:
                    // 改变  主图文的 副图文 状态值
                    $res = $this->change_status(2,$arr1,$arr2,0);
                    break;
                case  3:
                    // 改变  主图文的 副图文 状态值
                    $res = $this->change_status(3,$arr1,$arr2,0);
                    break;
                default :
            }
        }
        if ($res){
            $Wechat = new TPQYWechat(Config::get('user'));
            $message = array(
                'touser' => $push['create_user'],
                "msgtype" => 'text',
                "agentid" => '', // 个人中心
                "text" => array(
                    "content" => $content
                ),
                "safe" => "0"
            );
            $Wechat->sendMessage($message);  //审核通过，向用户推送提示
        }
    }
    /*推送 审核 */
    public function push(){
        $msg = input('post.');
        $push = Push::where('id',$msg['id'])->find();
        $this_info['status'] = $msg['status'];   //更新push表状态
        if($msg['status'] == 3 ){  // 审核通过
            // 发送消息
            $arr1 = $push['focus_main'];    //主图文id
            !empty($push['focus_vice']) ? $arr2 = json_decode($push['focus_vice']) : $arr2 = "";    //副图文id
            //主图文信息  根据 class 的值 获取不同的数据
            switch ($push['class']){
                case 1:
                    $res = $this->push_detail(1,$arr1,$arr2);
                    break;
                case 2:
                    $res = $this->push_detail(2,$arr1,$arr2);
                    break;
                case  3:
                    $res = $this->push_detail(3,$arr1,$arr2);
                    break;
            }
            if ($res['errcode'] == 0) {
                Push::where('id',$msg['id'])->update($this_info);    //改变推送状态
                return $this->success("审核通过，已推送消息");
            };
        }else{  //不通过
            Push::where('id',$msg['id'])->update($this_info);
            return $this->error("审核不通过");
        }
    }
    /**
     *  改变状态值
     */
    public function change_status($type,$main,$voice,$status){
        switch ($type) {    //根据类别获取表明
            case 1:
                $table = "work";
                break;
            case 2:
                $table = "centraltask";
                break;
            case 3:
                $table = "policy";
                break;
            case 4:
                $table = "learn";
                break;
            case 5:
                $table = "news";
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
        if (!empty($voice)){
            foreach($voice as $value){
                if ($status == 1){
                    // 审核通过
                    Db::name($table)->where(['id' => $value])->update(['status' => '']);
                }else{
                    Db::name($table)->where(['id' => $value])->update(['status' => '']);
                }
            }
        }
        if ($status == 1){
            Db::name($table)->where(['id' => $main])->update(['status' => '']);
        }else{
            Db::name($table)->where(['id' => $main])->update(['status' => '']);
        }
        return true;
    }
    /**
     * 获取推送详情
     */
    public function push_detail($type,$main,$voice){
        switch ($type) {    //根据类别获取表明
            case 1:
                $table = "work";
                $pre = "【第一聚焦】";
                $url = "news/detail";
                $agentid = '';
                break;
            case 2:
                $table = "centraltask";
                $pre = "【第一聚焦】";
                $url = "news/detail";
                $agentid = '';
                break;
            case 3:
                $table = "policy";
                $pre = "【第一聚焦】";
                $url = "news/detail";
                $agentid = '';
                break;
            case 4:
                $table = "learn";
                $pre = "【第一聚焦】";
                $url = "news/detail";
                $agentid = '';
                break;
            case 5:
                $table = "news";
                $pre = "【第一聚焦】";
                $url = "news/detail";
                $agentid = '';
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
        //活动基本信息  主图文
        $focus1 = Db::name($table)->find(['id' => $main]);
        if (empty($list)){
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
        if (!empty($voice)) {
            //副图文信息
            $information2 = array();
            foreach ($voice as $key => $value) {
                $focus = Db::name($table)->find(['id' => $value]);
                $title = $focus['title'];
                $str = strip_tags($focus['content']);
                $des = mb_substr($str, 0, 40);
                $content = str_replace("&nbsp;", "", $des);  //空格符替换成空
                $url = Config::get('host_url')."/home/".$url."/id/" . $focus['id'] . ".html";
                $img = Picture::get($focus['front_cover']);
                $path = Config::get('host_url') . $img['path'];
                $info = array(
                    "title" => $pre. $title,
                    "description" => $content,
                    "url" => $url,
                    "picurl" => $path,
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
        $Wechat = new TPQYWechat(Config::get('news'));
        $message = array(
//                   'totag' => "4", //审核标签用户
            'touser' =>'15036667391',
//                   "touser" => "@all",   //发送给全体，@all
            "msgtype" => 'news',
            "agentid" =>$agentid,  // 小镇动态
            "news" => $send,
            "safe" => "0"
        );
        $suc = $Wechat->sendMessage($message);
        return $suc;
    }
    /**
     * 获取 待审核  列表
     */
    public function get_list($status=0,$len=0,$opt=false){
        $map = [
            'status' => ['eq',$status]
        ];
        $list = $this->where($map)->order('create_time desc')->limit($len,10)->select();
        if (empty($list)){
            return null;
        }
        foreach($list as $value){
            $value['create_time'] = date('Y-m-d',$value['create_time']);
            $Pic = Picture::where(['id' => $value['front_cover']])->find();
            $value['front_cover'] = $Pic['name'];
            switch($value['class']){  // 根据 class 值找对应表
                case 1:
                    // 获取 主图文 详情
                    $value['pre'] = '【】';
                    break;
                case 2:
                    // 获取 主图文 详情
                    $value['pre'] = '【】';
                    break;
                case 3;
                    // 获取 主图文 详情
                    $value['pre'] = '【】';
                    break;

            }
            // 是否 获取审核人信息
            if ($opt){
                // 是
                $review = PushReview::where(['push_id' => $value['focus_main'] , 'status' => ['egt',-1]])->select();
                foreach($review as $val){
                    $val['create_time'] = date('Y-m-d',$val['create_time']);
                }
                $value['review'] = $review;
            }
        }
        return $list;
    }
}