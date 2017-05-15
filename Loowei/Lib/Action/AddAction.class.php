<?php
/**
 * 添加内容
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class AddAction extends BaseAction {
	public function addto(){
		if($this->isPost()){
			$video = $this->_post('video','htmlspecialchars,trim');
			if(!empty($video) && !in_array(array_pop(explode('.',$video)),array('swf','flv')) && !get_headers($video)){
				$this->error('视频地址出错！');
			}else if(!empty($video) && $_FILES['pic']['error'] == 4){
				$this->error('若发布视频，则请发布与此视频相关图片做为缩略图！');
			}else{
				$data_new['video'] = $video;
			}
			$data_new['tag'] = $this->_post('tag','htmlspecialchars,trim');
			if(count(explode(' ',$data_new['tag'])) > 3){
				$this->error('最多3个标签，用空格分隔！');
			}
			if($_FILES['pic']['error'] != 4){
				$rand_num = mt_rand(1,99);
				$upload_dir = './Uploads/Images/'.$rand_num.'/';
				if(!is_dir($upload_dir)){
					mkdir($upload_dir,0777,true);
				}
				import('ORG.Util.UploadFile');
				$upload = new UploadFile();
				$upload->maxSize = $this->config['upload_pic_max_size']*1024*1024;
				$upload->allowExts = explode(',','jpg,gif,png,jpeg');
				$upload->savePath = $upload_dir; 
				if($_FILES['pic']['type'] != 'image/gif'){
					$upload->thumb=true;
					$upload->imageClassPath = 'ORG.Util.Image';
					$upload->thumbPrefix = '';
					$upload->thumbMaxWidth  = $this->config['upload_pic_width'];
					$upload->thumbMaxHeight = $this->config['upload_pic_height'];
					$upload->thumbRemoveOrigin = false;
				}
				$upload->saveRule = uniqid;
				if($upload->upload()){
					$uploadList = $upload->getUploadFileInfo();
					$file_path =  $upload_dir.$uploadList[0]['savename'];
					list($data_new['pic_width'],$data_new['pic_height']) = getimagesize($file_path);
					if($_FILES['pic']['type'] != 'image/gif' && $data_new['pic_width'] > 300 && $data_new['pic_width'] > 200 && $this->config['open_water']){
						Image::water($file_path,$this->config['water_file']);
					}
					$data_new['pic_url'] = $rand_num.'/'.$uploadList[0]['savename'];
					if(empty($video)){
						$data_new['type'] = 1;
					}else{
						$data_new['type'] = 2;
					}
				}else{
					$this->error($upload->getErrorMsg());
				}
			}
			if(!empty($_POST['title'])){
				$data_new['title'] = $this->_post('title','htmlspecialchars,trim');
			}
			$data_new['content'] = $this->_post('content','htmlspecialchars,nl2br,stripslashes,trim');
			if($_POST['is_anonymous'] || empty($_SESSION['user']['uid'])) $data_new['is_anonymous'] = 1;
			$data_new['uid'] = $_SESSION['user']['uid'] ? $_SESSION['user']['uid'] : 1;
			$data_new['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
			$data_new['time'] = $_SERVER['REQUEST_TIME'];
			$database_new = D('New');
			if($vo = $database_new->data($data_new)->add()){
				$this->assign('new_id',$vo);
				$this->display();
			}else{
				$this->error('发布失败！');
			}
		}else{
			$this->error('非法访问！');
		}
	}
}