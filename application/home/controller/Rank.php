<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/10/19
 * Time: 19:09
 */

namespace app\home\controller;
use app\home\model\WechatUser;
use think\Controller;
use think\Db;

/**
 * Class Rank
 * 排行榜
 */
class Rank extends Base {
    /**
     * 个人中心 支部排行榜
     */
    public function department(){
        $this->anonymous();
        $userId = session('userId');
        if($userId != "visitor"){
            //所在部门名称
            $personal = Db::table('pb_wechat_department_user')
                ->alias('a')
                ->join('pb_wechat_department b','a.departmentid = b.id','LEFT')
                ->where('a.userid',$userId)
                ->find();
        }else{
            $personal = array(
                'id' => '',
            );
        }
        //总榜，获取部门人员信息
        $dpall = Db::table('pb_wechat_department')
            ->alias('a')
            ->join('pb_wechat_department_user b','a.id = b.departmentid','LEFT')
            ->join('pb_wechat_user c','b.userid = c.userid','LEFT')
            ->field('a.id,a.name,c.score')
            ->select();

        //合并相同数组的数据并值累加
        $item = array();
        foreach($dpall as $k=>$v){
            if ($v['score'] != 0){
                $v['score'] += 10;
            }
            if(!isset($item[$v['id']])){
                $item[$v['id']]=$v;
            }else{
                $item[$v['id']]['score']+=$v['score'];
            }
        }

        //倒序，字段score排序
        $sort = array(
            'direction' => 'SORT_DESC',
            'field' => 'score',
        );
        $arrSort = array();
        foreach ($item as $k => $v){
            foreach ($v as $key => $value){
                $arrSort[$key][$k] = $value;
            }
        }
        if($sort['direction'] && $arrSort){
            array_multisort($arrSort[$sort['field']],constant($sort['direction']),$item);
        }

        //获取头部信息，并取20名用户
        $new = array();
        foreach ($item as $key=>$value){
            if($value['id'] == $personal['id']){
                $personal['score'] = $value['score'];
            }
            if($value['score'] != 0){
                $new[$key] = $value;
            }
        }
        $last = array();
        foreach ($new as $k => $v){
            if($v['id'] == $personal['id']){
                $personal['score'] = $v['score'];
                $personal['rank'] = $k+1;
            }
            if($k < 20){ //取小于20名排行
                if ($v['id'] != 2){
                    $last[$k] = $v;
                } else {
                    $personal['contrast']= $k+1;
                }
            }
        }
        $this->assign('all',$last);
        $this->assign('personal',$personal);
        return $this->fetch();
    }

}