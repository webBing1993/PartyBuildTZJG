<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/8/22
 * Time: 15:04
 */

namespace app\admin\controller;
use app\admin\model\News as NewsModel;
use com\wechat\TPQYWechat;
use app\admin\model\Push;
use think\Config;
use app\admin\model\Picture;
/**
 * Class News
 * @package 党建动态
 */
class News extends Admin
{
    /**
     * 主页
     */
    public function index(){
        $map = array(
            'status' => array('egt',0),
        );
        $search = input('search');
        if ($search != '') {
            $map['title'] = ['like','%'.$search.'%'];
        }
        $list = $this->lists('News',$map);
        int_to_string($list,array(
            'status' => [0 => "已发布" , 1 => "已发布"],
            'recommend' => [0 => "否" ,1=>"是"]
        ));
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 添加  修改
     */
    public function edit(){
        $noticeModel = new NewsModel();
        if(IS_POST) {
            $data = input('post.');
            if (empty($data['id'])){
                unset($data['id']);
                $res = $noticeModel->validate(true)->save($data);
            }else{
                $res = $noticeModel->validate(true)->save($data,['id' => $data['id']]);
            }
            if ($res){
                return $this->success("操作成功",Url('News/index'));
            }else{
                $this->get_update_error_msg($noticeModel->getError());
            }
        }else {
            $info = $noticeModel->get_content(input('id'));
            $this->assign('info',$info);
            return $this->fetch();
        }
    }
    /**
     * 删除
     */
    public function del(){
        $id = input('id');
        if (empty($id)){
            return $this->error('系统错误,参数不存在');
        }
        NewsModel::where('id',$id)->update(['status' => -1]);
        $info = Push::where(['class' => 1 ,'focus_main' => $id])->find();
        if ($info){
            $result = Push::where(['class' => 1 ,'focus_main' => $id])->update(['status' => -2]);
            if ($result){
                return $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('删除失败');
        }
    }
    /*
     * 推送列表
     */
    public function pushlist(){
        if(IS_POST){
            $id = input('id');//主图文id
            //副图文本周内的新闻消息
            $info = array(
                'id' => array('neq',$id),
                'status' => 0
            );
            $infoes = NewsModel::where($info)->whereTime('create_time','w')->select();
            return $this->success($infoes);
        }else{
            //新闻消息列表
            $list=$this->lists('News',['status' =>1]);
            int_to_string($list,array(
                'status' => array(1=> '已推送')
            ));
            $this->assign('list',$list);
            //主图文本周内的新闻消息
            $infoes = NewsModel::where(['status' => 0])->whereTime('create_time','w')->select();
            $this->assign('info',$infoes);
            return $this->fetch();
        }
    }
    /*
     * 新闻推送
     */
    public function push(){
        $data = input('post.');
        $arr1 = $data['focus_main'];      //主图文id
        isset($data['focus_vice']) ? $arr2 = $data['focus_vice'] : $arr2 = "";  //副图文id
        if($arr1 == -1){
            return $this->error('请选择主图文');
        }else{
            //主图文信息
            $info1 = NewsModel::where('id',$arr1)->find();
        }
        $update['status'] = '1';
        $title1 = $info1['title'];
        NewsModel::where(['id'=>$arr1])->update($update); // 更新推送后的状态
        $str1 = strip_tags($info1['content']);
        $des1 = mb_substr($str1,0,40);
        $content1 = str_replace("&nbsp;","",$des1);  //空格符替换成空
        $pre = '【党建动态】';
        $url1 = "http://".$_SERVER['HTTP_HOST']."/home/News/detail/id/".$info1['id'].".html";
        $image1 = Picture::get($info1['front_cover']);
        $path1 = "http://".$_SERVER['HTTP_HOST'].$image1['path'];
        $information1 = array(
            'title' => $pre.$title1,
            'description' => $content1,
            'url'  => $url1,
            'picurl' => $path1
        );
        $information = array();
        if(!empty($arr2)){
            //副图文信息
            $information2 = array();
            foreach($arr2 as $key=>$value){
                NewsModel::where(['id'=>$value])->update($update); // 更新推送后的状态
                $info2 = NewsModel::where('id',$value)->find();
                $title2 = $info2['title'];
                $str2 = strip_tags($info2['content']);
                $des2 = mb_substr($str2,0,40);
                $content2 = str_replace("&nbsp;","",$des2);  //空格符替换成空
                $pre1 = '【党建动态】';
                $url2 = "http://".$_SERVER['HTTP_HOST']."/home/News/detail/id/".$info2['id'].".html";
                $image2 = Picture::get($info2['front_cover']);
                $path2 = "http://".$_SERVER['HTTP_HOST'].$image2['path'];
                $information2[] = array(
                    "title" =>$pre1.$title2,
                    "description" => $content2,
                    "url" => $url2,
                    "picurl" => $path2,
                );
            }
            //数组合并,主图文放在首位
            foreach($information2 as $key=>$value){
                $information[0] = $information1;
                $information[$key+1] = $value;
            }
        }else{
            $information[0] = $information1;
        }
        //重组成article数据
        $send = array();
        $re[] = $information;
        foreach($re as $key => $value){
            $key = "articles";
            $send[$key] = $value;
        }

        //发送给企业号
        $Wechat = new TPQYWechat(Config::get('party'));
        $message = array(
            "touser" => '',
            "msgtype" => 'news',
            "agentid" => '',  // 通知公告
            "news" => $send,
            "safe" => "0"
        );
        $msg = $Wechat->sendMessage($message);  // 推送至审核
        if($msg['errcode'] == 0){
            return $this->success('发送成功');
        }else{
            $this->error('发送失败');
        }
    }
}