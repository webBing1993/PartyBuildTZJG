<?php
namespace app\admin\controller;

use app\admin\model\Activity;
use app\admin\model\Comment;
use app\admin\model\Config;
use app\admin\model\Course;
use app\admin\model\Focus;
use app\admin\model\Log;
use app\admin\model\WechatLog;
use app\admin\model\WechatUser;
use app\user\model\UcenterMember;
use think\Controller;
use think\Db;

class Index extends Admin {

    public function index() {

        return $this->fetch();
    }

    /**
     * 修改密码
     */
    public function editpassword(){
        if(IS_POST){
            $id = input('id');
            $password = input('password');
            $repassword = input('repassword');
            if(!empty($password)){
                if($password != $repassword){
                    return $this->error('两次输入的密码不一致!');
                }else{
                    $data['password'] = think_ucenter_md5($repassword, config('uc_auth_key')); //转码存入数据库
                }
            }else{
                return $this->error("密码不能为空!");
            }
            $result = UcenterMember::where('id',$id)->update($data);
            if($result){
                return $this->success("修改成功",Url('Index/index'));
            }else{
                return $this->error("修改失败");
            }
        }else{
            //获取用户的信息
            $user = session('user_auth');
            $this->assign('user',$user);
            
            return $this->fetch();
        }
    }
}