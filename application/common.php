<?php
/**
 * Created by PhpStorm.
 * User: 虚空之翼 <183700295@qq.com>
 * Date: 16/4/27
 * Time: 11:22
 */
/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 */
function think_ucenter_md5($str, $key = 'ThinkUCenter'){
    return '' === $str ? '' : md5(sha1($str) . $key);
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 (单位:秒)
 * @return string
 */
function think_ucenter_encrypt($data, $key, $expire = 0) {
    $key  = md5($key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char =  '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x=0;
        $char  .= substr($key, $x, 1);
        $x++;
    }
    $str = sprintf('%010d', $expire ? $expire + time() : 0);
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data,$i,1)) + (ord(substr($char,$i,1)))%256);
    }
    return str_replace('=', '', base64_encode($str));
}

/**
 * 系统解密方法
 * @param string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param string $key  加密密钥
 * @return string
 */
function think_ucenter_decrypt($data, $key){
    $key    = md5($key);
    $x      = 0;
    $data   = base64_decode($data);
    $expire = substr($data, 0, 10);
    $data   = substr($data, 10);
    if($expire > 0 && $expire < time()) {
        return '';
    }
    $len  = strlen($data);
    $l    = strlen($key);
    $char = $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char  .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}
/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}


/**
 * 获取文档封面图片
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 */
function get_cover($cover_id, $field = null){
    if(empty($cover_id)){
        return false;
    }
    $map = array(
        'status' => array('egt',0),
        'id' => $cover_id,
    );
    $picture = \app\home\model\Picture::where($map)->find();
    if($field == 'path'){
        if(!empty($picture['url'])){
            $picture['path'] = $picture['url'];
        }else{
            $picture['path'] = $picture['path'];
        }
    }
    return empty($field) ? $picture : $picture[$field];
}

/**
 * 统计所有数据
 * @param int $userId 用户ID3
 * @param string $event 相关统计表模型名字
 * @param int $source 相关目标的ID
 */
function save_log($userId, $event, $source = 0) {
    $department = \app\admin\model\WechatUser::where(['userid' => $userId])->value('department');
    $data = array(
        'userid' => $userId,
        'event' => $event,
        'source' => $source,
        'department_id' => json_decode($department)[0]  //TODO 获取多个部门
    );
    \app\admin\model\Log::create($data);
}
/*
 * 获取月份
 * @param str $time  时间格式
 */
function getMonth($time){
    $month = substr($time,5);
    $map = array('0','1');
    if(in_array(substr($month,0,1),$map)){
        //去掉前导零
        if(substr($month,0,1) == 0){
            return substr($month,1,1);
        }else{
            return substr($month,0,2);
        }
    }else{
        return substr($month,0,1);
    }
}
/*
 * 获取日
 */
function getDay($time){
    $num1 = strrpos($time,'.');
    if($num1 === false){
        $num2 = strrpos($time,'-');
        return substr($time,$num2+1);
    }elseif(substr($time,$num1+1)!='' && $num1 > 4){
        return substr($time,$num1+1);
    }
}
/**
 * 当前月份的个数
 */
function mouth_sum($mouth,$class,$type,$userid){
    // 当前月 已经上传的个数
    if ($mouth >= 10){
        $month_first = mktime(0,0,0,10,1,date('Y'));
        $month_final = mktime(23,59,59,12,date('t'),date('Y'));
    }else{
        $month_first = mktime(0,0,0,$mouth,1,date('Y'));
        $month_final = mktime(23,59,59,$mouth,date('t'),date('Y'));
    }
    $maps = ['class' => $class,'type' => $type,'userid' => $userid,'create_time' => ['between',[$month_first,$month_final]]];  // 每月
    $count = \think\Db::name('score')->where($maps)->count();  // 获取已发布个数
    return $count;
}
/**
 * 月份 延期算法
 */
function mouth_info($class,$type,$userid,$aid,$sum){
    date_default_timezone_set("PRC");        //初始化时区
    // 根据总分来确定存储分数
    $score_up1 = $sum;
    $score_down1 = 10;
    $score_up2 = $sum * 7;
    $score_down2 = 100;
    // 获取当前月份
    $mouth = date('n');
    if ($mouth <= 1){
        if (mouth_sum($mouth,$class,$type,$userid) < 1){
            // 可以积分
            \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => $score_up1,'score_down' => $score_down1,'create_time' => time()]);
        }
    }else{
        if ($mouth > 10){
            $mouth = 10;
        }
        if (mouth_sum($mouth,$class,$type,$userid) < 1){
            for ($i = 1;$i <= $mouth;$i++){
                if (mouth_sum($i,$class,$type,$userid) < 1){
                    // 还没存储
                    if ($i < $mouth){
                        $time = 'Y-'.$i.'-20';
                        // 可以积分 延期 积分打折
                        \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => $score_up2,'score_down' => $score_down2,'create_time' => strtotime(date($time))]);
                        break;
                    }else{
                        // 可以积分 正常积分
                        \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => $score_up1,'score_down' => $score_down1,'create_time' => time()]);
                        break;
                    }
                }
            }
        }
    }
}
/**
 * 积分规则
 * $class
 * 1 responsibility   2 learn   3 organization  4 special   5 style   6 volunteer  7 incorrupt
 *
 */
function get_score($class,$aid,$userid){
    $user = \app\home\model\WechatUserTag::where(['tagid' => 1 ,'userid' => $userid])->find();
    if (!empty($user)){
        // 考核人员
        switch ($class) {    //根据类别获取表明
            case 1:  // 党建责任
                $table = "responsibility";
                $info = \think\Db::name($table)->where(['id' => $aid])->find();
                $map = ['class' => $class,'type' => $info['type'],'userid' => $userid];
                $count = \think\Db::name('score')->where($map)->whereTime('create_time','y')->count();  // 获取已发布个数
                switch ($info['type']){
                    case 1:  // 专题研究
                        if ($count < 2){
                            // 可以积分
                            \think\Db::name('score')->insert(['class' => $class,'type' => $info['type'],'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 1,'create_time' => time()]);
                        }
                        break;
                    case 2:  // 责任清单
                        switch($info['class']){
                            case 1: // 工作要点
                                $type = 7;
                                break;
                            case 2:  //责任清单
                                $type = 8;
                                break;
                            default:
                                $type = 0;
                        }
                        $mapp = ['class' => $class,'type' => $type,'userid' => $userid];
                        $counts = \think\Db::name('score')->where($mapp)->whereTime('create_time','y')->count();  // 获取已发布个数
                        if ($counts < 1){
                            // 可以积分
                            \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 1,'create_time' => time()]);
                        }
                        break;
                    case 3:  // 述职报告
                        switch ($info['class']){
                            case 1:
                                // 书记述职
                                $type = 5;
                                break;
                            case 2:
                                // 支部书记  述职
                                $type = 6;
                                break;
                            default:
                                $type = 0;
                        }
                        $maps = ['class' => $class,'type' => $type,'userid' => $userid];
                        $count = \think\Db::name('score')->where($maps)->whereTime('create_time','y')->count();  // 获取已发布个数
                        if ($count < 1){
                            // 可以积分
                            \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 1,'create_time' => time()]);
                        }
                        break;

                }
                break;
            case 2:  // 两学一做
                $table = "learn";
                $info = \think\Db::name($table)->where(['id' => $aid])->find();
                $map = ['class' => $class,'type' => $info['type'],'userid' => $userid];  // 1方案部署，2三会一课，3年度计划，4支部活动
                $count = \think\Db::name('score')->where($map)->whereTime('create_time','y')->count();  // 获取已发布个数
                switch ($info['type']){
                    case 1:  // 方案部署
                        if ($count < 2){
                            // 可以积分
                            \think\Db::name('score')->insert(['class' => $class,'type' => $info['type'],'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 1,'create_time' => time()]);
                        }
                        break;
                    case 3:  // 年度计划
                        switch ($info['class']){
                            case 5:
                                // 培训计划
                                $type = 5;
                                break;
                            case 6:
                                // 理论研究
                                $type = 6;
                                break;
                            default:
                                $type = 0;
                        }
                        $maps = ['class' => $class,'type' => $type,'userid' => $userid];
                        $counts = \think\Db::name('score')->where($maps)->whereTime('create_time','y')->count();  // 获取已发布个数
                        if ($counts < 1){
                            // 可以积分
                            \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 1,'create_time' => time()]);
                        }
                        break;
                    case 4:   // 支部活动
                        mouth_info($class,$info['type'],$userid,$aid,2);
                        break;
                    case 2:  // 三会一课  1支部委员大会 2党支部委员会 3党小组会 4党课
                        switch ($info['class']){
                            case 1:
                                // 支部党员大会
                                $type = 7;
                                date_default_timezone_set("PRC");        //初始化时区
                                $season = ceil((date('n'))/3);//当月是第几季度
                                $start = mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
                                $end = mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'));
                                $maps = ['class' => $class,'type' => $type,'userid' => $userid,'create_time' => ['between',[$start,$end]]];  // 每季度
                                $count = \think\Db::name('score')->where($maps)->count();  // 获取已发布个数
                                if ($count < 1){
                                    // 可以积分
                                    \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 4,'create_time' => time()]);
                                }
                                break;
                            case 2:
                                // 党支部委员会
                                $type = 8;
                                mouth_info($class,$type,$userid,$aid,1);
                                break;
                            case 3:
                                // 党小组会
                                $type = 9;
                                $flag = \think\Db::name('score')->where(['class' => $class,'type' => $type,'userid' => $userid,'aid' =>0])->whereTime('create_time','y')->find();
                                if (!$flag){
                                    mouth_info($class,$type,$userid,$aid,1);
                                }
                                break;
                            case 4:
                                // 党课
                                $type = 10;
                                $maps = ['class' => $class,'type' => $type,'userid' => $userid];
                                $count = \think\Db::name('score')->where($maps)->whereTime('create_time','y')->count();  // 获取已发布个数
                                if ($count < 1){
                                    // 可以积分
                                    \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 1,'create_time' => time()]);
                                }
                                break;
                            default:
                        }
                        break;
                }
                break;
            case 3:  // 组织建设
                $table = "organization";
                $info = \think\Db::name($table)->where(['id' => $aid])->find();
                $map = ['class' => $class,'type' => $info['type'],'userid' => $userid]; // 1规范建设，2离退休党员台账资料,3党费收缴,4信息录用
                switch ($info['type']){
                    case 1:  // 规范性建设
                        switch ($info['class']){
                            case 1: // 阵地建设
                                $type = 5;
                                $mapp = ['class' => $class,'type' => $type,'userid' => $userid];
                                $count = \think\Db::name('score')->where($mapp)->whereTime('create_time','y')->count();  // 获取已发布个数
                                if ($count < 1){
                                    // 可以积分
                                    \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 2,'create_time' => time()]);
                                }
                                break;
                            case 2: // 按期换届
                                $type = 6;
                                $mapp = ['class' => $class,'type' => $type,'userid' => $userid];
                                $count = \think\Db::name('score')->where($mapp)->whereTime('create_time','y')->count();  // 获取已发布个数
                                if ($count < 1){
                                    // 可以积分
                                    \think\Db::name('score')->insert(['class' => $class,'type' => $type,'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 2,'create_time' => time()]);
                                }
                                break;
                            case 3: // 主题党日
                                $type = 7;
                                mouth_info($class,$type,$userid,$aid,1);
                                break;
                        }
                        break;
                    case 2:  // 台账资料
                        $flag = \think\Db::name('score')->where(['class' => $class,'type' => $info['type'],'userid' => $userid,'aid' =>0])->whereTime('create_time','y')->find();
                        if (!$flag){
                            $count = \think\Db::name('score')->where($map)->whereTime('create_time','y')->count();  // 获取已发布个数
                            if ($count < 1) {
                                // 可以积分
                                \think\Db::name('score')->insert(['class' => $class, 'type' => $info['type'], 'aid' => $aid, 'userid' => $userid, 'score_up' => 3, 'score_down' => 1, 'create_time' => time()]);
                            }
                        }
                        break;
                    case 3: // 党费
                        $count = \think\Db::name('score')->where($map)->whereTime('create_time','y')->count();  // 获取已发布个数
                        if ($count < 1) {
                            // 可以积分
                            \think\Db::name('score')->insert(['class' => $class, 'type' => $info['type'], 'aid' => $aid, 'userid' => $userid, 'score_up' => 1, 'score_down' => 1, 'create_time' => time()]);
                        }
                        break;
                    case 4:  // 信息录用
                        $count = \think\Db::name('score')->where($map)->whereTime('create_time','y')->count();  // 获取已发布个数
                        if ($count < 4){
                            // 可以积分
                            \think\Db::name('score')->insert(['class' => $class,'type' => $info['type'],'aid' => $aid,'userid' => $userid,'score_up' => 1,'score_down' => 2,'create_time' => time()]);
                        }
                }
                break;
            case 4:  // 特色创新
                $maps = ['class' => $class,'type' => 0,'userid' => $userid];
                $counts = \think\Db::name('score')->where($maps)->whereTime('create_time','y')->count();  // 获取已发布个数
                if ($counts < 1){
                    // 可以积分
                    $table = "special";
                    $info = \think\Db::name($table)->where(['id' => $aid])->find();
                    if ($info['commend_img']){
                        $commend_flag = true;
                        foreach(json_decode($info['commend_img']) as $val){
                            if ($val){
                                $commend_flag = false;
                                break;
                            }
                        }
                    }else{
                        // 空
                        $commend_flag = true;
                    }
                    if ($info['voucher_img']){
                        $voucher_flag = true;
                        foreach(json_decode($info['voucher_img']) as $val){
                            if ($val){
                                $voucher_flag = false;
                                break;
                            }
                        }
                    }else{
                        // 空
                        $voucher_flag = true;
                    }
                    if ($commend_flag && $voucher_flag){  // 1200字以上+2分
                        \think\Db::name('score')->insert(['class' => $class,'type' => 0,'aid' => $aid,'userid' => $userid,'score_up' => 2,'score_down' => 1,'create_time' => time()]);
                    }else if($commend_flag && !$voucher_flag){ // 提供交流凭证+3分
                        \think\Db::name('score')->insert(['class' => $class,'type' => 0,'aid' => $aid,'userid' => $userid,'score_up' => 5,'score_down' => 1,'create_time' => time()]);
                    }else if(!$commend_flag && $voucher_flag){  // 提供表彰、刊发照片+5分
                        \think\Db::name('score')->insert(['class' => $class,'type' => 0,'aid' => $aid,'userid' => $userid,'score_up' => 7,'score_down' => 1,'create_time' => time()]);
                    }else{
                        \think\Db::name('score')->insert(['class' => $class,'type' => 0,'aid' => $aid,'userid' => $userid,'score_up' => 10,'score_down' => 1,'create_time' => time()]);
                    }
                }
                break;
            case 5:  // 作风建设
                $table = "style";
                $info = \think\Db::name($table)->where(['id' => $aid])->find();
                $map = ['class' => $class,'type' => $info['type'],'userid' => $userid]; // 1方案部署、2金点子、3培树典型、4党员清单
                $count = \think\Db::name('score')->where($map)->whereTime('create_time','y')->count();  // 获取已发布个数
                if ($count < 1){
                    // 可以积分
                    \think\Db::name('score')->insert(['class' => $class,'type' => $info['type'],'aid' => $aid,'userid' => $userid,'score_up' => 2,'score_down' => 1,'create_time' => time()]);
                }
                break;
            case 6:  // 志愿服务
                $table = "volunteer";
                $info = \think\Db::name($table)->where(['id' => $aid])->find();
                $map = ['class' => $class,'type' => $info['type'],'userid' => $userid]; // 1四跑志愿活动，2一条街三走进 3 最多跑一次
                $count = \think\Db::name('score')->where($map)->whereTime('create_time','y')->count();  // 获取已发布个数
                if ($count < 1){
                    // 可以积分
                    \think\Db::name('score')->insert(['class' => $class,'type' => $info['type'],'aid' => $aid,'userid' => $userid,'score_up' => 2,'score_down' => 1,'create_time' => time()]);
                }
                break;
            case 7:  // 党风廉政
                $table = "incorrupt";
                $info = \think\Db::name($table)->where(['id' => $aid])->find();
                $map = ['class' => $class,'type' => $info['type'],'userid' => $userid]; // 1 廉政责任 2  廉政教育 3 纪检报告
                $count = \think\Db::name('score')->where($map)->whereTime('create_time','y')->count();  // 获取已发布个数
                if ($count < 1){
                    // 可以积分
                    \think\Db::name('score')->insert(['class' => $class,'type' => $info['type'],'aid' => $aid,'userid' => $userid,'score_up' => 2,'score_down' => 1,'create_time' => time()]);
                }
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
    }
}
