<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/24
 * Time: 17:25
 */

namespace app\admin\controller;

use app\admin\model\Learn as LearnModel;
/**
 * Class Learn
 * @package app\admin\controller
 * 两学一做
 */
class Learn extends Admin {
    /**
     * 主页
     */
    public function index() {
        $map = array(
            'status' => array('egt',0)
        );
        $list = $this->lists('Learn',$map);
        int_to_string($list, array(
            'type' => array(1=>"方案部署",2=>"三会一课",3=>"年度计划",4=>"主题党日"),
            'status' => array(0=>'待审核',1=>'已发布',2=>'未通过'),
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
            $Model = new LearnModel();
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            if($data['time']) {
                $data['time'] = strtotime($data['time']);
            }
            $res = $Model->validate(true)->save($data);
            if($res){
                get_score(2,$res,$_SESSION['think']['user_auth']['id']);
                return $this->success("新增成功",Url("Learn/index"));
            }else{
                return $this->error($Model->getError());
            }
        }else{
            $this->default_pic();
            $this->assign('msg','');
            return $this->fetch('edit');
        }
    }

    /**
     * 修改
     */
    public function edit(){
        $Model = new LearnModel();
        if(IS_POST) {
            $data = input('post.');
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            if($data['time']) {
                $data['time'] = strtotime($data['time']);
            }
            $res = $Model->validate(true)->save($data,['id'=>input('id')]);
            if($res){
                return $this->success("修改成功",Url("Learn/index"));
            }else{
                return $this->get_update_error_msg($Model->getError());
            }
        }else{
            $this->default_pic();
            $id = input('id');
            $msg = $Model::get($id);
            if($msg['type'] == 2) {
                $msg['class1'] = $msg['class'];
                $msg['class2'] = 0;
            }elseif ($msg['type'] == 3){
                $msg['class1'] = 0;
                $msg['class2'] = $msg['class'];
            }else {
                $msg['class1'] = 0;
                $msg['class2'] = 0;
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
        $sta = LearnModel::where('id',$id)->update($info);
        if($sta){
            return $this->success('删除成功!');
        }else{
            return $this->error('删除失败!');
        }
    }
}