<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/28
 * Time: 15:29
 */

namespace app\admin\controller;

use app\admin\model\Special as SpecialModel;
use app\admin\model\Picture;
use app\admin\model\Push;
use com\wechat\TPQYWechat;
use think\Config;
use think\Db;
/**
 * Class Special
 * @package app\admin\controller
 * 特色创新
 */
class Special extends Admin {
    /**
     * 主页
     */
    public function index() {
        $userId = $_SESSION['think']['user_auth']['id'];
        $user_id = Db::name('ucenter_member')->where('id',$userId)->value('username');
        $where = array();
        if($userId == 1) {
            $where = array(
                'status' => array('egt',0),
            );
        }else{
            $where['create_user|userid'] = array(['eq',$userId],['eq',$user_id],'or');
            $where['status'] = array('egt',0);
        }
        $list = $this->lists('Special',$where);
        int_to_string($list, array(
            'status' => array(0=>'待审核',1=>'已发布',2=>'未通过',3=>'草稿'),
        ));
        $this->assign('userid',$userId);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 新增
     */
    public function add() {
        if(IS_POST) {
            $data = input('post.');
            isset($data["file"]) ? $data["file"] = json_encode($data["file"]) : $data["file"] = "";
            isset($data["list_images"]) ? $data["list_images"] = json_encode($data["list_images"]) : $data["list_images"] = "";
            if(empty($data['id'])) {
                unset($data['id']);
            }
            $Model = new SpecialModel();
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            isset($data["commend_img"]) ? $data["commend_img"] = json_encode($data["commend_img"]) : $data["commend_img"] = "";
            isset($data["voucher_img"]) ? $data["voucher_img"] = json_encode($data["voucher_img"]) : $data["voucher_img"] = "";
            $res = $Model->validate(true)->save($data);
            if($res){
                $user_id = Db::name('ucenter_member')->where('id',$_SESSION['think']['user_auth']['id'])->value('username');
                get_score(4,$res,$user_id);
                return $this->success("新增成功",Url("Special/index"));
            }else{
                return $this->error($Model->getError());
            }
        }else{
            $this->default_pic();
            $this->assign('msg','');
            $this->getPublisher();
            return $this->fetch('edit');
        }
    }

    /**
     * 修改
     */
    public function edit(){
        $Model = new SpecialModel();
        if(IS_POST) {
            $data = input('post.');
            isset($data["file"]) ? $data["file"] = json_encode($data["file"]) : $data["file"] = "";
            isset($data["commend_img"]) ? $data["commend_img"] = json_encode($data["commend_img"]) : $data["commend_img"] = "";
            isset($data["voucher_img"]) ? $data["voucher_img"] = json_encode($data["voucher_img"]) : $data["voucher_img"] = "";
            isset($data["list_images"]) ? $data["list_images"] = json_encode($data["list_images"]) : $data["list_images"] = "";
            $res = $Model->validate(true)->save($data,['id'=>input('id')]);
            if($res){
                return $this->success("修改成功",Url("Special/index"));
            }else{
                return $this->get_update_error_msg($Model->getError());
            }
        }else{
            $this->default_pic();
            $id = input('id');
            $msg = $Model::get($id);
            $msg['c'] = 0;
            if($msg['commend_img']){
                $images = json_decode($msg['commend_img']);
                $msg['c'] = count($images);
                foreach($images as $k => $val){
                    $msg['commend_img'.($k+1)] = $val;
                }
            }
            $msg['v'] = 0;
            if($msg['voucher_img']){
                $images = json_decode($msg['voucher_img']);
                $msg['v'] = count($images);
                foreach($images as $k => $val){
                    $msg['voucher_img'.($k+1)] = $val;
                }
            }
            if($msg['file']) {
                $temp = json_decode($msg['file']);
                $arr[] = [];
                foreach($temp as $key => $value){
                    $arr[$key]['id'] = $value;
                    $arr[$key]['name'] = Db::name('file')->where('id',$value)->value('name');
                }
                $msg['files'] = $arr;
            }else{
                $msg['files'] = '';
            }
            if ($msg['list_images']){
                $msg['list_images'] = json_decode($msg['list_images']);
            }
            $this->assign('msg',$msg);
            return $this->fetch();
        }
    }

    /**
     * 删除
     */
    public function del(){
        $id = input('id');
        $info = array(
            'status' => '-1',
        );
        $sta = SpecialModel::where('id',$id)->update($info);
        if($sta){
            if (Db::name('score')->where(['class' => 4,'aid' => $id])->find()){
                Db::name('score')->where(['class' => 4,'aid' => $id])->delete(); // 特色创新
            }
            return $this->success('删除成功!');
        }else{
            return $this->error('删除失败!');
        }
    }

    /**
     * 推送列表
     */
    public function pushlist() {
        $Model = new SpecialModel();
        if(IS_POST){
            $id = input('id');
            $info = array(
                'id' => array('neq',$id),
                'status' => 1,
                'create_time' => $this->getMonthTime()
            );
            $infoes = $Model::where($info)->select();
            foreach ($infoes as $value) {
                    $value['type_text'] = "【特色创新】";
            }
            return $this->success($infoes);
        }else{
            //消息列表
            $map = array(
                'class' => 4,
                'status' => array('egt',-1),
            );
            $list = $this->lists('Push',$map);
            int_to_string($list,array(
                'status' => array(-1 => '不通过', 0 => '未审核', 1=> '已发送'),
            ));
            //数据重组
            foreach ($list as $value) {
                $msg = $Model::where('id',$value['focus_main'])->find();
                $value['title'] = $msg['title'];
            }
            $this->assign('list',$list);

            $info = array(
                'status' => 1,
                'create_time' => $this->getMonthTime()
            );
            $infoes = $Model::where($info)->select();
            foreach ($infoes as $value) {
                $value['type_text'] = "【特色创新】";
            }
            $this->assign('info',$infoes);

            return $this->fetch();
        }
    }

    /**
     * 推送
     */
    public function push()
    {
        $Model = new SpecialModel();
        $data = input('post.');
        $arr1 = $data['focus_main'];    //主图文id
        isset($data['focus_vice']) ? $arr2 = $data['focus_vice'] : $arr2 = "";    //副图文id
        if ($arr1 == -1) {
            return $this->error("请选择主图文!");
        } else {
            //主图文信息
            $focus1 = $Model::where('id', $arr1)->find();
            $title1 = $focus1['title'];
            $type_name1 = "【特色创新】";
            $str1 = strip_tags($focus1['content']);
            $des1 = mb_substr($str1, 0, 100);
            $content1 = str_replace("&nbsp;", "", $des1);  //空格符替换成空
            if($focus1['templet'] == 1) {
                $url1 = "http://www.0576ztnet.com/home/special/detail/id/" . $focus1['id'] . ".html";
            }else {
                $url1 = "http://www.0576ztnet.com/home/special/detail2/id/" . $focus1['id'] . ".html";
            }
            $img1 = Picture::get($focus1['front_cover']);
            $path1 = "http://www.0576ztnet.com" . $img1['path'];
            $information1 = array(
                "title" => $type_name1 . $title1,
                "description" => $content1,
                "url" => $url1,
                "picurl" => $path1,
            );
        }

        $information = array();
        if (!empty($arr2)) {
            //副图文信息
            $information2 = array();
            foreach ($arr2 as $key => $value) {
                $focus = $Model::where('id', $value)->find();
                $type_name = "【特色创新】";
                $title = $focus['title'];
                $str = strip_tags($focus['content']);
                $des = mb_substr($str, 0, 100);
                $content = str_replace("&nbsp;", "", $des);  //空格符替换成空
                if($focus['templet'] == 1) {
                    $url = "http://www.0576ztnet.com/home/special/detail/id/" . $focus['id'] . ".html";
                }else {
                    $url = "http://www.0576ztnet.com/home/special/detail2/id/" . $focus['id'] . ".html";
                }
                $img = Picture::get($focus['front_cover']);
                $path = "http://www.0576ztnet.com" . $img['path'];
                $info = array(
                    "title" => $type_name . $title,
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
        $Wechat = new TPQYWechat(Config::get('special'));
        $message = array(
//            'totag' => "2", //审核标签用户
//            "touser" => "18768112486",
            "touser" => "@all",   //发送给全体，@all
            "msgtype" => 'news',
            "agentid" => 1000005,
            "news" => $send,
            "safe" => "0"
        );
        $msg = $Wechat->sendMessage($message);
        if ($msg['errcode'] == 0) {
            $data['focus_vice'] ? $data['focus_vice'] = json_encode($data['focus_vice']) : $data['focus_vice'] = null;
            $data['create_user'] = session('user_auth.username');
            $data['class'] = 4;
            //保存到推送列表
            $s = Push::create($data);
            if ($s) {
                return $this->success("发送成功");
            } else {
                return $this->error("发送失败");
            }
        } else {
            return $this->error("发送失败");
        }
    }

    /**
     * 新闻通知预览
     */
    public function preview(){
        $Model = new SpecialModel();
        $id = input('id');
        $list = $Model::get($id);
        if ($list['list_images']){
            $list['list_images'] = json_decode($list['list_images']);
        }
        if(json_decode($list['commend_img'])){
            $list['commend_img'] = json_decode($list['commend_img']);
        }else{
            $list['commend_img'] = array();
        }
        if(json_decode($list['voucher_img'])){
            $list['voucher_img'] = json_decode($list['voucher_img']);
        }else{
            $list['voucher_img'] = array();
        }
        // 获取文件
        if($list['file']) {
            $temp = json_decode($list['file']);
            $arr[] = [];
            foreach($temp as $key => $value){
                $savepath = Db::name('file')->where('id',$value)->value('savepath');
                $savename = Db::name('file')->where('id',$value)->value('savename');
                $arr[$key]['url'] = "http://".$_SERVER["SERVER_NAME"]."/uploads/download/".$savepath.$savename;
                $arr[$key]['see_url'] = "http://ow365.cn/?i=14505&furl=http://".$_SERVER["SERVER_NAME"]."/uploads/download/".$savepath.$savename;
                $arr[$key]['name'] = Db::name('file')->where('id',$value)->value('name');
            }
            $list['files'] = $arr;
        }else{
            $list['files'] = '';
        }
        $this->assign('list',$list);
        $this->assign('url',"http://ow365.cn/?i=14505&furl=http://".$_SERVER["SERVER_NAME"]);
        return $this->fetch();
    }
    /**
     * 审核
     */
    public function review(){
        $userId = $_SESSION['think']['user_auth']['id'];
        $user_id = Db::name('ucenter_member')->where('id',$userId)->value('username');
        $nickname = Db::name('member')->where('id',$userId)->value('nickname');
        $arr = input('post.');
        $id = $arr['id'];  // 获取数组
        $pass = $arr['pass'];  // 审核状态  1 通过 2 不通过
        if (empty($id)){
            return $this->error('系统参数错误');
        }
        $res = SpecialModel::where('id','in',$id)->update(['status' => $pass]);
        if ($res){
            $data = array();
            foreach($id as $value){
                $tem = array(
                    'class' => 4,
                    'aid' => $value,
                    'userid' => $user_id,
                    'username' => $nickname,
                    'create_time' => time(),
                    'status' => $pass
                );
                array_push($data,$tem);
            }
            Db::name('review')->insertAll($data);
            return $this->success('审核成功');
        }else{
            return $this->error('审核失败');
        }
    }
}