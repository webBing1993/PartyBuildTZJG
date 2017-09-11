<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/9/11
 * Time: 20:32
 */

namespace app\admin\model;
use think\Model;

class Handle extends Model
{
    public function cuser(){
        return $this->hasOne('Member','id','create_user');
    }
}