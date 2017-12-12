<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/29
 * Time: 15:51
 */

namespace app\admin\model;


class Incorrupt extends Base {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 0
    ];
}