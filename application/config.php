<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

return [
    'url_route_on' => true,
//    'log'          => [
//        'type' => 'trace', // 支持 socket trace file
//    ],

    /* 默认模块和控制器 */
    'default_module' => 'home',

    /* URL配置 */
    'base_url'=>'',
    'parse_str'=>[
        '__ROOT__' => '/',
        '__STATIC__' => '/static',
        '__ADMIN__' => '/admin',
        '__HOME__' => '/home',
    ],

    /* UC用户中心配置 */
    'uc_auth_key' => '(.t!)=JTb_OPCkrD:-i"QEz6KLGq5glnf^[{p;je',

    /* 分页自定义 */
    'paginate' => [
        'type'     => '\org\Pager',
        'var_page' => 'page',
        'list_rows'=> 12
    ],
    
    //最新动态
    'work' => array(
        'login' => 'http://djz.0571ztnet.com/home/index/login',
        'token' => 'RMRUYhgJh7C',
        'encodingaeskey' => 'XO7KtGSIpsnGPR24x3UmfTnLXSmEfogGhmRqUkoefNj',
        'appid' => 'ww462067a674db3d65',
        'appsecret' => '06QvzGqUuq3Z0bKOopC-r4zxqgaKmDOmTn7NLaNZACA',
        'agentid' => 1000002
    ),
    //中心工作
    'central' => [
        'appid' => 'ww462067a674db3d65',
        'appsecret' => 'QV6kzWHpksfPkiEu6CVrPHrb5MdmsfbUnl1USGP-Kpo',
        'agentid' => 1000003
    ],
    //最多跑一次
    'policy' => [
        'appid' => 'ww462067a674db3d65',
        'appsecret' => '0Xlv76r8Kjyn9HUkgdvtVvrfchO9u_fiLlkLZjbCNmg',
        'agentid' => 1000005
    ],
    //廉政新市
    'learn' => [
        'appid' => 'ww462067a674db3d65',
        'appsecret' => 'mJwLr3o03lE1KCdIiuw6A--rbxjo6vaGvkjuVXmYpQA',
        'agentid' => 1000006
    ],
    //通讯名录
    'mail' => [
        'appid' => 'ww462067a674db3d65',
        'appsecret' => '8z74BDtf6DHxyBwf8oXxteXZJpz0SFMSHj-Kmg1FdAI',
        'agentid' => 1000007
    ],
    //消息审核
    'review' => array(
        'appid' => 'ww462067a674db3d65',
        'appsecret' => 'VcfYQrz6clrAf2_l5goyimsRTLea_GSg33J2nfZzR3s',
        'agentid' => 1000008
    ),

];
