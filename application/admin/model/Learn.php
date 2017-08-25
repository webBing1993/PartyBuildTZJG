<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/24
 * Time: 17:26
 */

namespace app\admin\model;


class Learn extends Base {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 0
    ];
}