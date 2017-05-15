<?php
/**
 * 内容页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class ArticleAction extends BaseAction {
    public function index(){
		$database_article = D('Article');
		
		if($this->isPost()){
			if(!empty($_POST['id'])){
				$condition_article['id'] = intval($_POST['id']);
			}
			if(!empty($_POST['content'])){
				$condition_article['content'] = array('like','%'.htmlspecialchars($_POST['content']).'%');
			}
			if(!empty($_POST['status'])){
				$condition_article['status'] = $_POST['status']-1;
			}
			$article_list = $database_article->field(true)->where($condition_article)->order('`id` DESC')->select();
			$page = '搜索时不进行分页！';
			$this->assign('post',$_POST);
		}else{
			$count_article = $database_article->count('`uid`');
			import('ORG.Util.Page');
			$p = new Page($count_article,20);
			$article_list = $database_article->field(true)->order('`id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
			$page = $p->show();
		}
		
		$this->assign('article_list',$article_list);
		$this->assign('page',$page);
		
		$this->display();
	}
	public function edit(){
		$condition['id'] = $this->_get('id','intval');
		$database = D('Article');
		$article = $database->field(true)->where($condition)->find();
		if(empty($article)){
			$this->error('此条内容不存在。');
		}
		$this->assign('article',$article);
		$this->display();
	}
	public function amend(){
		if($this->isPost()){
			$condition['id'] = $this->_post('id','intval');
			$database = D('Article');
			$article = $database->field(true)->where($condition)->find();
			
			$data_article['id'] = $this->_post('id','intval');
			$data_article['title'] = $this->_post('title','htmlspecialchars,trim');
			$video = $this->_post('video','htmlspecialchars,trim');
			if(!empty($video) && !in_array(array_pop(explode('.',$video)),array('swf','flv')) && !get_headers($video)){
				$this->error('视频地址出错！');
			}else if(!empty($video) && $_FILES['pic']['error'] == 4){
				$this->error('若发布视频，则请发布与此视频相关图片做为缩略图！');
			}else{
				$data_article['video'] = $video;
			}
			$data_article['tag'] = $this->_post('tag','htmlspecialchars,trim');
			if(count(explode(' ',$data_article['tag'])) > 3){
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
					list($data_article['pic_width'],$data_article['pic_height']) = getimagesize($file_path);
					if($_FILES['pic']['type'] != 'image/gif' && $data_article['pic_width'] > 300 && $data_article['pic_width'] > 200 && $this->config['open_water']){
						Image::water($file_path,$this->config['water_file']);
					}
					$data_article['pic_url'] = $rand_num.'/'.$uploadList[0]['savename'];
					if(empty($video)){
						$data_article['type'] = 1;
					}else{
						$data_article['type'] = 2;
					}
					
					if(!empty($article['pic_url']) && strpos(strtolower($article['pic_url']),'http://') === false){
						unlink('./Uploads/Images/'.$article['pic_url']);
					}
				}else{
					$this->error($upload->getErrorMsg());
				}
			}
			$data_article['content'] = $this->_post('content','htmlspecialchars,nl2br,stripslashes,trim');
			if(empty($data_article['type']) && empty($data_article['content'])){
				$this->error('请填写内容！');
			}
			if(empty($data_article['title']) && !empty($data_article['content'])){
				//正则得到标题
				$content = htmlspecialchars_decode($data_article['content']);
				$content = str_replace(array('，','！','？','。','；',',','!','?','.',';','&','“','”','"','"','、','（','）','(',')'),',',$content); 
				$content = explode(',',trim($content));
				$i = 0;
				foreach($content as $key=>$value){
					$value = trim(strip_tags($value));
					if(strlen($value) > 6){
						$title[$i] = $value;
						$i++;
					}
				}
				$key = mt_rand(0,count($title)-1);
				$data_article['title'] = str_replace("\n",'',$title[$key]);
			}
			if($_POST['is_anonymous'] || empty($_SESSION['user']['uid'])) $data_article['is_anonymous'] = 1;
			$data_article['uid'] = $_SESSION['user']['uid'] ? $_SESSION['user']['uid'] : 1;
			$data_article['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
			$data_article['time'] = $_SERVER['REQUEST_TIME'];
			$data_article['upper'] = $this->_post('upper','intval');
			$data_article['below'] = $this->_post('below','intval');
			$data_article['status'] = $this->_post('status','intval');
			$database_article = D('Article');
			if($vo = $database_article->data($data_article)->save()){
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败！');
			}
		}else{
			$this->error('非法访问！');
		}
	}
	public function del(){
		$condition['id'] = $this->_get('id','intval');
		$database = D('Article');
		$article = $database->field('`id`,`pic_url`')->where($condition)->find();
		if(empty($article)){
			$this->error('此条内容不存在。');
		}
		if(!empty($article['pic_url']) && strpos(strtolower($article['pic_url']),'http://') === false){
			unlink('./Uploads/Images/'.$article['pic_url']);
		}
		if($database->where($condition)->delete()){
			$table = substr($condition['id'],-1);
			$database_reply = D('Reply_'.$table);
			$condition_reply['article_id'] = $condition['id'];
			$reply = $database_reply->where($condition_reply)->delete();
			$this->success('内容删除成功！');
		}else{
			$this->error('内容删除失败！');
		}
	}
	public function add(){
		$this->display();
	}
	public function ticket(){
		$database_new = D('New');
		$new = $database_new->field(true)->order('`id` DESC')->find();
		if(empty($new)){
			$this->assign('jumpUrl',U('Article/add'));
			$this->error('没有内容了！');
		}
		$this->assign('new',$new);
		$this->display();
	}
	public function inspect(){
		if($this->isPost()){
			$condition_new['id'] = $this->_post('id','intval');
			$action = $this->_post('action','intval');
			$content = $this->_post('content','strip_tags');
			$title = $this->_post('title');
			$database_new = D('New');
			if($action == 1){
				$new = $database_new->field('id',true)->where($condition_new)->find();
				$database_article = D('Article');
				$data_article = $new;
				
				$data_article['tag'] = $this->_post('tag','htmlspecialchars,trim');
				if(count(explode(' ',$data_article['tag'])) > 3){
					$this->error('最多3个标签，用空格分隔！');
				}
				
				$data_article['upper'] = mt_rand($this->config['collect_upper_min'],$this->config['collect_upper_max']);
				$data_article['below'] = mt_rand($this->config['collect_below_min'],$this->config['collect_below_max']);
				$data_article['time'] = $_SERVER['REQUEST_TIME'];
				if(empty($title) && empty($data_article['title'])){
					//正则得到标题
					$content = str_replace(array('，','！','？','。','；',',','!','?','.',';','&','“','”','"','"','、','（','）','(',')'),',',$content); 
					$content = explode(',',trim($content));
					$i = 0;
					foreach($content as $key=>$value){
						$value = trim(strip_tags($value));
						if(strlen($value) > 6){
							$title[$i] = $value;
							$i++;
						}
					}
					$key = mt_rand(0,count($title)-1);
					$data_article['title'] = $title[$key];
				}elseif(!empty($title)){
					$data_article['title'] = $title;
				}
				$data_article['content'] = $this->_post('content','stripslashes');
				if($database_article->data($data_article)->add()){
					$database_new->where($condition_new)->delete();
					redirect(U('Article/ticket'));
				}else{
					$this->error('添加进内容表失败！');
				}
			}else if($action < 1){
				$new = $database_new->field('`pic_url`')->where($condition_new)->find();
				if(!empty($new['pic_url'])){
					unlink('./Uploads/Images/'.$new['pic_url']);
				}
				$database_new->where($condition_new)->delete();
				$this->success('删除成功！');
			}
		}
	}
	public function addto(){
		if($this->isPost()){
			$data_article['title'] = $this->_post('title','title,trim');
			$video = $this->_post('video','htmlspecialchars,trim');
			if(!empty($video) && !in_array(array_pop(explode('.',$video)),array('swf','flv')) && !get_headers($video)){
				$this->error('视频地址出错！');
			}else if(!empty($video) && $_FILES['pic']['error'] == 4){
				$this->error('若发布视频，则请发布与此视频相关图片做为缩略图！');
			}else{
				$data_article['video'] = $video;
			}
			$data_article['tag'] = $this->_post('tag','htmlspecialchars,trim');
			if(count(explode(' ',$data_article['tag'])) > 3){
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
					list($data_article['pic_width'],$data_article['pic_height']) = getimagesize($file_path);
					if($_FILES['pic']['type'] != 'image/gif' && $data_article['pic_width'] > 300 && $data_article['pic_width'] > 200 && $this->config['open_water']){
						Image::water($file_path,$this->config['water_file']);
					}
					$data_article['pic_url'] = $rand_num.'/'.$uploadList[0]['savename'];
					if(empty($video)){
						$data_article['type'] = 1;
					}else{
						$data_article['type'] = 2;
					}
				}else{
					$this->error($upload->getErrorMsg());
				}
			}
			$data_article['content'] = $this->_post('content','htmlspecialchars,nl2br,stripslashes,trim');
			if(empty($data_article['type']) && empty($data_article['content'])){
				$this->error('请填写内容！');
			}
			if(empty($data_article['title']) && !empty($data_article['content'])){
				//正则得到标题
				$content = htmlspecialchars_decode($data_article['content']);
				$content = str_replace(array('，','！','？','。','；',',','!','?','.',';','&','“','”','"','"','、','（','）','(',')'),',',$content); 
				$content = explode(',',trim($content));
				$i = 0;
				foreach($content as $key=>$value){
					$value = trim(strip_tags($value));
					if(strlen($value) > 6){
						$title[$i] = $value;
						$i++;
					}
				}
				$key = mt_rand(0,count($title)-1);
				$data_article['title'] = str_replace("\n",'',$title[$key]);
			}
			if($_POST['is_anonymous'] || empty($_SESSION['user']['uid'])) $data_article['is_anonymous'] = 1;
			$data_article['uid'] = $_SESSION['user']['uid'] ? $_SESSION['user']['uid'] : 1;
			$data_article['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
			$data_article['time'] = $_SERVER['REQUEST_TIME'];
			$data_article['upper'] = $this->_post('upper','intval');
			$data_article['below'] = $this->_post('below','intval');
			$database_article = D('Article');
			if($vo = $database_article->data($data_article)->add()){
				$this->success('发布成功！');
			}else{
				$this->error('发布失败！');
			}
		}else{
			$this->error('非法访问！');
		}
	}
	public function addVideo(){
		$all['url'] = 'parse';
		$all['youku'] = 'youku';
		$all['tudou'] = 'tudou';
		$all['ku6'] = 'ku6';
		$all['56'] = '_56';
		$all['sina'] = 'sina';
		$all['qq'] = 'qq';
		$all['letv'] = 'letv';
		$all['sohu'] = 'sohu';
		$this->assign('all',$all);
		
		$value = empty( $_POST['value'] ) ? '' : (string) $_POST['value'];
		$this->assign('value',$value);
		
		$type = empty( $_POST['type'] ) || !is_string( $_POST['type'] ) || empty( $all[$_POST['type']] ) ? 'url' : $_POST['type'];
		$this->assign('type',$type);
		if($this->isPost()){
			import('ORG.Util.Video');
			$class_video = new class_video;
			$return = call_user_func_array( array( $class_video, $all[$type] ), array( $value ) );
			$article['title'] = $article['content'] = $return['title'];
			$article['pic_large'] = $return['img']['large'];
			$article['pic_small'] = $return['img']['small'];
			$article['video'] = $return['swf'];
			$article['tag'] = implode(' ',$return['tag']);
			$this->assign('article',$article);
		}
		$this->display();
	}
	public function submitaddVideo(){
		if($this->isPost()){
			$database_article = D('Article');
			$data_article['title'] = $this->_post('title');
			$data_article['content'] = $this->_post('content');
			$pic_type = $this->_post('pic_type');
			if($pic_type == 1){
				$data_article['pic_url'] = $this->_post('pic_large');
				$data_article['pic_height'] = 300;
				$data_article['pic_width'] = 400;
			}elseif($pic_type == 2){
				$data_article['pic_url'] = $this->_post('pic_small');
				$data_article['pic_height'] = 95;
				$data_article['pic_width'] = 127;
			}
			$data_article['video'] = $this->_post('video');
			$data_article['tag'] = $this->_post('tag');
			$data_article['uid'] = 1;
			$data_article['type'] = 2;
			$data_article['is_anonymous'] = 1;
			$data_article['status'] = 1;
			$data_article['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
			$data_article['time'] = $_SERVER['REQUEST_TIME'];
			if($vo = $database_article->data($data_article)->add()){
				$this->success('发布成功！');
			}else{
				$this->error('发布失败！');
			}
		}
	}
}