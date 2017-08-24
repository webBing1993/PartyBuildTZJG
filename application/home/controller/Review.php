<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
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
    /* 发布审核 列表 */
    public function index(){
        $Push = new  Push();
        // 获取 发布审核 列表
        $list1 = $Push->get_list();
        // 获取 推送审核 列表
        $list2 = $Push->get_list(1);
        // 获取 已审核 列表
        $list3 = $Push->get_list(-1,true);
        $this->assign('list1',$list1);
        $this->assign('list2',$list2);
        $this->assign('list3',$list3);
        return $this->fetch();
    }
    /* 首页  加载更多*/
    public function listmore(){
        $Push = new  Push();
        $type = input('type');
        $len = input('length');
        switch ($type){
            case 0: // 发布审核
                $list = $Push->get_list(0,$len);
                break;
            case 1:  // 推送审核
                $list = $Push->get_list(1,$len);
                break;
            default : // 已审核
                $list = $Push->get_list(-1,$len,true);
                break;
        }
        if ($list){
            return $this->success('加载成功','',$list);
        }else{
            $this->error('加载失败');
        }
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

}