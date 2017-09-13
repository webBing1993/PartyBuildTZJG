<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/29
 * Time: 15:50
 */

namespace app\admin\controller;

use app\admin\model\Incorrupt as IncorruptModel;
/**
 * Class Incorrupt
 * @package app\admin\controller
 * 党风廉政
 */
class Incorrupt extends Admin {
    /**
     * 主页
     */
    public function index() {
        $map = array(
            'status' => array('egt',0)
        );
        $list = $this->lists('Incorrupt',$map);
        int_to_string($list, array(
            'type' => array(1=>"廉政责任",2=>"廉政教育",3=>"纪检报告"),
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
            $Model = new IncorruptModel();
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            $res = $Model->validate(true)->save($data);
            if($res){
                get_score(7,$res,$_SESSION['think']['user_auth']['id']);
                return $this->success("新增成功",Url("Incorrupt/index"));
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
        $Model = new IncorruptModel();
        if(IS_POST) {
            $data = input('post.');
            $res = $Model->validate(true)->save($data,['id'=>input('id')]);
            if($res){
                return $this->success("修改成功",Url("Incorrupt/index"));
            }else{
                return $this->get_update_error_msg($Model->getError());
            }
        }else{
            $this->default_pic();
            $id = input('id');
            $msg = $Model::get($id);
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
        $sta = IncorruptModel::where('id',$id)->update($info);
        if($sta){
            return $this->success('删除成功!');
        }else{
            return $this->error('删除失败!');
        }
    }
}