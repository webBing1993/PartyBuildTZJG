<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/29
 * Time: 11:18
 */

namespace app\admin\validate;


use think\Validate;

class Volunteer extends Validate {
    protected $rule = [
        'type' => 'require',
        'title' => 'require',
        'content' => 'require',
        'publisher' => 'require'
    ];
    protected $message = [
        'type.require' => '类型不能为空',
        'title.require' => '标题不能为空',
        'content.require' => '内容不能为空',
        'publisher' => '发布人不能为空'
    ];

}