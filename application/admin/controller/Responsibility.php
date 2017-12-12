<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/21
 * Time: 14:39
 */

namespace app\admin\controller;

use app\admin\model\Picture;
use app\admin\model\Push;
use app\admin\model\Responsibility as ResponsibilityModel;
use com\wechat\TPQYWechat;
use think\Config;
use think\Db;

/**
 * Class Responsibility
 * @package app\admin\controller
 * 党建责任
 */
class Responsibility extends Admin {
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
        $list = $this->lists('Responsibility',$where);
        int_to_string($list, array(
            'type' => array(1=>"专题研究",2=>"责任清单",3=>"述职报告",4=>"工作计划"),
            'status' => array(0=>'待审核',1=>'已发布',2=>'不通过',3=>'草稿'),
        ));
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 新增
     */
    public function add() {
        if(IS_POST) {
            $data = input('post.');
            if(empty($data['id'])) {
                unset($data['id']);
            }
            isset($data["file"]) ? $data["file"] = json_encode($data["file"]) : $data["file"] = "";
            isset($data["list_images"]) ? $data["list_images"] = json_encode($data["list_images"]) : $data["list_images"] = "";
            $Model = new ResponsibilityModel();
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            $res = $Model->validate(true)->save($data);
            if($res){
                $user_id = Db::name('ucenter_member')->where('id',$_SESSION['think']['user_auth']['id'])->value('username');
                get_score(1,$res,$user_id);
                return $this->success("新增成功",Url("Responsibility/index"));
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
        $Model = new ResponsibilityModel();
        if(IS_POST) {
            $data = input('post.');
            isset($data["file"]) ? $data["file"] = json_encode($data["file"]) : $data["file"] = "";
            isset($data["list_images"]) ? $data["list_images"] = json_encode($data["list_images"]) : $data["list_images"] = "";
            $res = $Model->validate(true)->save($data,['id'=>input('id')]);
            if($res){
                return $this->success("修改成功",Url("Responsibility/index"));
            }else{
                return $this->get_update_error_msg($Model->getError());
            }
        }else{
            $this->default_pic();
            $id = input('id');
            $msg = $Model::get($id);
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
        $sta = ResponsibilityModel::where('id',$id)->update($info);
        if($sta){
            if (Db::name('score')->where(['class' => 1,'aid' => $id])->find()){
                Db::name('score')->where(['class' => 1,'aid' => $id])->delete(); // 党风廉政
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
        $Model = new ResponsibilityModel();
        if(IS_POST){
            $id = input('id');
            $info = array(
                'id' => array('neq',$id),
                'status' => 1,
                'create_time' => $this->getMonthTime()
            );
            $infoes = $Model::where($info)->select();
            foreach ($infoes as $value) {
                switch ($value['type']) {
                    case 1:
                        $value['type_text'] = "【专题研究】";
                        break;
                    case 2:
                        $value['type_text'] = "【责任清单】";
                        break;
                    case 3:
                        $value['type_text'] = "【述职报告】";
                        break;
                    case 4:
                        $value['type_text'] = "【工作计划】";
                        break;
                    default:
                        $value['type_text'] = "【无】";
                        break;
                }
            }
            return $this->success($infoes);
        }else{
            //消息列表
            $map = array(
                'class' => 1,
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
                switch ($value['type']) {
                    case 1:
                        $value['type_text'] = "【专题研究】";
                        break;
                    case 2:
                        $value['type_text'] = "【责任清单】";
                        break;
                    case 3:
                        $value['type_text'] = "【述职报告】";
                        break;
                    case 4:
                        $value['type_text'] = "【工作计划】";
                        break;
                    default:
                        $value['type_text'] = "【无】";
                        break;
                }
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
        $Model = new ResponsibilityModel();
        $data = input('post.');
        $arr1 = $data['focus_main'];    //主图文id
        isset($data['focus_vice']) ? $arr2 = $data['focus_vice'] : $arr2 = "";    //副图文id
        if ($arr1 == -1) {
            return $this->error("请选择主图文!");
        } else {
            //主图文信息
            $focus1 = $Model::where('id', $arr1)->find();
            $title1 = $focus1['title'];
            switch ($focus1['type']) {
                case 1:
                    $type_name1 = "【专题研究】";
                    break;
                case 2:
                    $type_name1 = "【责任清单】";
                    break;
                case 3:
                    $type_name1 = "【述职报告】";
                    break;
                case 4:
                    $type_name1 = "【工作计划】";
                    break;
                default:
                    $type_name1 = "【暂无】";
                    break;
            }
            $str1 = strip_tags($focus1['content']);
            $des1 = mb_substr($str1, 0, 100);
            $content1 = str_replace("&nbsp;", "", $des1);  //空格符替换成空
            if($focus1['templet'] == 1) {
                $url1 = "http://www.0576ztnet.com/home/responsibility/detail/id/" . $focus1['id'] . ".html";
            }else {
                $url1 = "http://www.0576ztnet.com/home/responsibility/detail2/id/" . $focus1['id'] . ".html";
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
                switch ($focus['type']) {
                    case 1:
                        $type_name = "【专题研究】";
                        break;
                    case 2:
                        $type_name = "【责任清单】";
                        break;
                    case 3:
                        $type_name = "【述职报告】";
                        break;
                    case 4:
                        $type_name = "【工作计划】";
                        break;
                    default:
                        $type_name = "【暂无】";
                        break;
                }
                $title = $focus['title'];
                $str = strip_tags($focus['content']);
                $des = mb_substr($str, 0, 100);
                $content = str_replace("&nbsp;", "", $des);  //空格符替换成空
                if($focus['templet'] == 1) {
                    $url = "http://www.0576ztnet.com/home/responsibility/detail/id/" . $focus['id'] . ".html";
                }else {
                    $url = "http://www.0576ztnet.com/home/responsibility/detail2/id/" . $focus['id'] . ".html";
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
        $Wechat = new TPQYWechat(Config::get('responsibility'));
        $message = array(
//            'totag' => "2", //审核标签用户
//            "touser" => "18768112486",
            "touser" => "@all",   //发送给全体，@all
            "msgtype" => 'news',
            "agentid" => 1000002,
            "news" => $send,
            "safe" => "0"
        );
        $msg = $Wechat->sendMessage($message);
        if ($msg['errcode'] == 0) {
            $data['focus_vice'] ? $data['focus_vice'] = json_encode($data['focus_vice']) : $data['focus_vice'] = null;
            $data['create_user'] = session('user_auth.username');
            $data['class'] = 1;
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
}