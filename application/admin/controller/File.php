<?php

namespace app\admin\controller;

use app\admin\model\File as FileModel;
use app\admin\model\Picture;
use think\Config;
use think\Db;

class File extends Admin 
{
    /* 文件上传 */
    public function upload(){
		$return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
		/* 调用文件上传组件上传文件 */
		$File = new FileModel();
		$file_driver = Config::get('upload_drive'); 
		$info = $File->upload(
			$_FILES,
            Config::get('download_upload'),
            Config::get('upload_drive'),
            Config::get("upload_{$file_driver}_config")
		);
        /* 记录附件信息 */
        if($info){
            $return['data'] = $info['download'];
            $return['info'] = "上传成功";
            $return['path'] = '/uploads/download/'.$info['download']['savepath'].$info['download']['savename'];
        } else {
            $return['status'] = 0;
            $return['info'] = $File->getError();
        }

        /* 返回JSON数据 */
        return json_encode($return);
    }

    /* 下载文件 */
    public function download($id = null){
        if(empty($id) || !is_numeric($id)){
            return $this->error('参数错误！');
        }

//        $logic = D('Download', 'Logic');
//        if(!$logic->download($id)){
//            return $this->error($logic->getError());
//        }

    }

    /**
     * 上传图片
     */
    public function uploadPicture(){
        /* 调用文件上传组件上传文件 */
        $Picture = new Picture();
        $pic_driver = Config::get('upload_drive');
        $info = $Picture->upload(
            $_FILES,
            Config::get('download_upload'),
            Config::get('upload_drive'),
            Config::get("upload_{$pic_driver}_config")
        ); 

        /* 记录图片信息 */
        /* 记录附件信息 */
        if($info){
            $return['data'] = $info['picture'];
            $return['code'] = 1;
        } else {
            $return['code'] = 0;
            $return['info'] = $Picture->getError();
        }

        return json_encode($return);
    }
    /**
     * 文件删除
     */
    public function file_delete(){
        $id = input('id');
        if (empty($id)){
            return $this->error('系统参数错误');
        }
        $file = Db::name('file')->find(['id' => $id]);
        if ($file){
            $path = "./uploads/download/".$file['savepath'].$file['savename'];
            if (file_exists($path)){
                unlink($path);
                Db::name('file')->delete(['id' => $id]);
                return $this->success('删除成功');
            }else{
                return $this->error('$path'.$path);
            }
        }else{
            return $this->success('删除成功');
        }
    }
}
