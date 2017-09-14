<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/8/22
 * Time: 16:10
 */

namespace app\admin\model;
use think\Model;
class Push extends Model
{
    public $insert = [
        'status' => 0,
        'create_time' => NOW_TIME,
    ];
}