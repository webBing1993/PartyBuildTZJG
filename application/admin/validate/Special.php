<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/28
 * Time: 15:30
 */

namespace app\admin\validate;


use think\Validate;

class Special extends Validate {
    protected $rule = [
        'title' => 'require',
        'content' => 'require',
        'publisher' => 'require'
    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'content.require' => '内容不能为空',
        'publisher.require' => '发布人不能为空'
    ];

}