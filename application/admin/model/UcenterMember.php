<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/11/23
 * Time: 10:31
 */

namespace app\admin\model;


class UcenterMember extends Base {
    protected $insert = [
        'reg_time' => NOW_TIME,
        'status' => 1,
    ];

}