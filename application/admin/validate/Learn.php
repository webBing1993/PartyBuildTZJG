<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/24
 * Time: 17:27
 */

namespace app\admin\validate;


use think\Validate;

class Learn extends Validate {
    protected $rule = [
        'type' => 'require',
        'title' => 'require',
        'content' => 'require',
        'time' => 'require',
        'address' => 'require',
        'people' => 'require',
        'compere' => 'require',
        'publisher' => 'require'

    ];
    protected $message = [
        'type.require' => '类型不能为空',
        'title.require' => '标题不能为空',
        'content.require' => '内容不能为空',
        'time.require' => '时间不能为空',
        'address.require' => '地址不能为空',
        'people.require' => '参会人数不能为空',
        'compere.require' => '主持人不能为空',
        'publisher.require' => '发布人不能为空'
    ];

    protected  $scene = [
        'one' => ['type','title','content','publisher'],
        'two' => ['type','title','content','time','address','people','compere','publisher'],
    ];

}