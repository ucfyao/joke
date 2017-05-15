<?php
/**
 * 用户中心页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class UsersAction extends BaseAction {
    public function index(){
		$uid = $this->_get('uid','intval');
		if(empty($uid)){
			$this->error('你知道你是在看谁吗？');
		}
		
		$condition_user['uid'] = $uid;
		$database_user = D('User');
		$user = $database_user->field(true)->where($condition_user)->find();
		if(is_array($user)){
			if($this->config['site_html']){
				$user['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$user['uid'].'.jpg';
				$user['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$user['uid'].'.jpg';
				$user['user_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/';
				$user['article_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/article/';
				$user['comment_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/comment/';
				$user['follow_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/follow/';
				$user['fans_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/fans/';
			}else{
				$user['avatar_m'] = U('Avatar/index?size=m&uid='.$user['uid']);
				$user['avatar_s'] = U('Avatar/index?size=s&uid='.$user['uid']);
				$user['user_url'] = U('Users/index?uid='.$user['uid']);
				$user['article_url'] = U('Users/index?uid='.$user['uid']);
				$user['comment_url'] = U('Users/comment?uid='.$user['uid']);
				$user['follow_url'] = U('Users/follow?uid='.$user['uid']);
				$user['fans_url'] = U('Users/fans?uid='.$user['uid']);
			}
			$this->assign('user',$user);
		}else{
			$this->error('此用户不存在喔！');
		}
		if($_SESSION['user']['uid'] != $uid){
			$condition_fans['follow_uid'] = $uid;
			$condition_fans['fans_uid'] = $_SESSION['user']['uid'];
			$database_fans = D('Fans_'.substr($_SESSION['user']['uid'],0,1));
			if($fans = $database_fans->where($condition_fans)->find()){
				$this->assign('is_follow',true);
			}
		}
		
		$condition_article['uid'] = $uid;
		$condition_article['status'] = 1;
		$condition_article['is_anonymous'] = 0;
		$database_article = D('Article');
		$count = $database_article->where($condition_article)->count('id');
		import('@.ORG.Userpage');
		$p = new Page($count,$uid,'article');
		$article_list = $database_article->field(true)->where($condition_article)->limit($p->firstRow.','.$p->page_rows)->order('`id` DESC')->select();
		if(!empty($article_list)){
			foreach($article_list as $key=>$value){
				if(!empty($value['tag'])){
					$temp_tags = explode(' ',$value['tag']);
					foreach($temp_tags as $k=>$v){
						if($GLOBALS['config']['site_html']){
							$tags[$k]['url'] = $GLOBALS['config']['site_url'].'/tag/'.urlencode($v);
						}else{
							$tags[$k]['url'] = U('Tag/index?tag='.urlencode($v));
						}
						$tags[$k]['tag'] = $v;
					}
					$article_list[$key]['tags'] = $tags;
				}
				if(!empty($value['pic_url']) && strpos(strtolower($value['pic_url']),'http://') === false){
					$article_list[$key]['pic_url'] = $this->config['site_url'].'/Uploads/Images/'.$value['pic_url'];
				}
				if($this->config['site_html']){
					$article_list[$key]['url'] = $article_list[$key]['full_url'] = $this->config['site_url'].'/article/'.$value['id'];
				}else{
					$article_list[$key]['full_url'] = $GLOBALS['config']['site_url'].'/index.php?m=article&a=index&id='.$value['id'];
					$article_list[$key]['url'] = U('Article/index?id='.$value['id']);
				}
			}
			$this->assign('article_list',$article_list);
		}
		$this->assign('article_list',$article_list);
		$this->assign('count',$count);
		
		$this->assign('pagebar',$p->show());

		$this->display();
    }
	public function comment(){
		$uid = $this->_get('uid','intval');
		if(empty($uid)){
			$this->error('你知道你是在看谁吗？');
		}
		$condition_user['uid'] = $uid;
		$database_user = D('User');
		$user = $database_user->field(true)->where($condition_user)->find();
		if(is_array($user)){
			if($this->config['site_html']){
				$user['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$user['uid'].'.jpg';
				$user['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$user['uid'].'.jpg';
				$user['user_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/';
				$user['article_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/article/';
				$user['comment_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/comment/';
				$user['follow_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/follow/';
				$user['fans_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/fans/';
			}else{
				$user['avatar_m'] = U('Avatar/index?size=m&uid='.$user['uid']);
				$user['avatar_s'] = U('Avatar/index?size=s&uid='.$user['uid']);
				$user['user_url'] = U('Users/index?uid='.$user['uid']);
				$user['article_url'] = U('Users/index?uid='.$user['uid']);
				$user['comment_url'] = U('Users/comment?uid='.$user['uid']);
				$user['follow_url'] = U('Users/follow?uid='.$user['uid']);
				$user['fans_url'] = U('Users/fans?uid='.$user['uid']);
			}
			$this->assign('user',$user);
		}else{
			$this->error('此用户不存在喔！');
		}
		
		if($_SESSION['user']['uid'] != $uid){
			$condition_fans['follow_uid'] = $uid;
			$condition_fans['fans_uid'] = $_SESSION['user']['uid'];
			$database_fans = D('Fans_'.substr($_SESSION['user']['uid'],0,1));
			if($fans = $database_fans->where($condition_fans)->find()){
				$this->assign('is_follow',true);
			}
		}
		
		$condition_reply['uid'] = $uid;
		$condition_reply['status'] = 1;
		$database_reply = D('Reply');
		$count = $database_reply->where($condition_reply)->count('reply_id');
		import('@.ORG.Userpage');
		$p = new Page($count,$uid,'comment');
		$prefix = C('DB_PREFIX');
		$comment_list = D('')->Table(array($prefix.'reply'=>'r',$prefix.'article'=>'a'))->where("`r`.`article_id`=`a`.`id` AND `r`.`reply_status`='1' AND `r`.`uid`='$uid'")->order('`r`.`reply_id` DESC')->limit($p->firstRow.','.$p->page_rows)->select();
		
		if(!empty($comment_list)){
			foreach($comment_list as $key=>$value){
				if($this->config['site_html']){
					$comment_list[$key]['url'] = $comment_list[$key]['full_url'] = $this->config['site_url'].'/article/'.$value['id'];
				}else{
					$comment_list[$key]['full_url'] = $GLOBALS['config']['site_url'].'/inde.php?m=article&a=index&id='.$value['id'];
					$comment_list[$key]['url'] = U('Article/index?id='.$value['id']);
				}
			}
			$this->assign('comment_list',$comment_list);
		}
		$this->assign('count',$count);
		
		$this->assign('pagebar',$p->show());
		$this->display();
    }
	public function to_follow(){
		$uid = $this->_get('uid','intval');
		if($uid<1){
			$this->error('用户标识非法！');
		}else if(empty($_SESSION['user']['uid'])){
			$this->error('请登录后再进行关注！');
		}else if($_SESSION['user']['uid'] == $uid){
			$this->error('自己就没必要关注了吧！');
		}else{
			$database_user = D('User');
			$condition_user['uid'] = $uid;
			$user = $database_user->field('`uid`')->where($condition_user)->find();
			if(is_array($user)){
				$uid = $_SESSION['user']['uid'];
				$database_fans_my = D('Fans_'.substr($uid,0,1));
				$condition_fans['follow_uid'] = $user['uid'];
				$condition_fans['fans_uid'] = $uid;
				if($database_fans_my->where($condition_fans)->find()){
					$this->error('您已经关注过了喔！');
				}else{
					$database_fans_your = D('Fans_'.substr($user['uid'],0,1));
					if(!$database_fans_your->where($condition_fans)->find()){
						if(!$database_fans_your->data($condition_fans)->add()){
							$this->error('关注失败！');
						}
					}
					if(substr($user['uid'],0,1) == substr($uid,0,1)){
						$this->success('关注成功！');
					}else if($database_fans_my->data($condition_fans)->add()){
						$this->success('关注成功！');
					}else{
						$this->error('关注异常失败！请重试。');
					}
				}
			}else{
				$this->error('此用户不存在喔！');
			}
		}	
	}
	public function follow(){
		$uid = $this->_get('uid','intval');
		if($uid<1){
			$this->error('用户标识非法！');
		}
		
		$condition_user['uid'] = $uid;
		$database_user = D('User');
		$user = $database_user->field(true)->where($condition_user)->find();
		if(is_array($user)){
			if($this->config['site_html']){
				$user['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$user['uid'].'.jpg';
				$user['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$user['uid'].'.jpg';
				$user['user_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/';
				$user['article_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/article/';
				$user['comment_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/comment/';
				$user['follow_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/follow/';
				$user['fans_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/fans/';
			}else{
				$user['avatar_m'] = U('Avatar/index?size=m&uid='.$user['uid']);
				$user['avatar_s'] = U('Avatar/index?size=s&uid='.$user['uid']);
				$user['user_url'] = U('Users/index?uid='.$user['uid']);
				$user['article_url'] = U('Users/index?uid='.$user['uid']);
				$user['comment_url'] = U('Users/comment?uid='.$user['uid']);
				$user['follow_url'] = U('Users/follow?uid='.$user['uid']);
				$user['fans_url'] = U('Users/fans?uid='.$user['uid']);
			}
			$this->assign('user',$user);
		}else{
			$this->error('此用户不存在喔！');
		}
		
		if($_SESSION['user']['uid'] != $uid){
			$condition_fans['follow_uid'] = $uid;
			$condition_fans['fans_uid'] = $_SESSION['user']['uid'];
			$database_fans = D('Fans_'.substr($_SESSION['user']['uid'],0,1));
			if($fans = $database_fans->where($condition_fans)->find()){
				$this->assign('is_follow',true);
			}
		}
		
		$database_fans = D('Fans_'.substr($uid,0,1));
		$condition_fans['fans_uid'] = $uid;
		$count = $database_fans->where($condition_fans)->count('follow_uid');
		$this->assign('count',$count);
		$prefix = C('DB_PREFIX');
		$fans_table = $prefix.'fans_'.substr($uid,0,1);
		$follow = D('')->field('`f`.`follow_uid`,`u`.`uid`,`u`.`nickname`')->Table(array($fans_table=>'f',$prefix.'user'=>'u'))->where('`f`.`fans_uid`='.$uid.' AND `u`.`uid`=`f`.`follow_uid`')->select();
		
		foreach($follow as $key=>$value){
			if($this->config['site_html']){
				$follow[$key]['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$value['uid'].'.jpg';
				$follow[$key]['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$value['uid'].'.jpg';
				$follow[$key]['user_url'] = $this->config['site_url'].'/users/'.$value['uid'].'/';
			}else{
				$follow[$key]['avatar_m'] = U('Avatar/index?size=m&uid='.$value['uid']);
				$follow[$key]['avatar_s'] = U('Avatar/index?size=s&uid='.$value['uid']);
				$follow[$key]['user_url'] = U('Users/index?uid='.$value['uid']);
			}
		}
		$this->assign('follow',$follow);
		$this->display();
	}
	public function fans(){
		$uid = $this->_get('uid','intval');
		if($uid<1){
			$this->error('用户标识非法！');
		}
		
		$condition_user['uid'] = $uid;
		$database_user = D('User');
		$user = $database_user->field(true)->where($condition_user)->find();
		if(is_array($user)){
			if($this->config['site_html']){
				$user['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$user['uid'].'.jpg';
				$user['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$user['uid'].'.jpg';
				$user['user_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/';
				$user['article_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/article/';
				$user['comment_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/comment/';
				$user['follow_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/follow/';
				$user['fans_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/fans/';
			}else{
				$user['avatar_m'] = U('Avatar/index?size=m&uid='.$user['uid']);
				$user['avatar_s'] = U('Avatar/index?size=s&uid='.$user['uid']);
				$user['user_url'] = U('Users/index?uid='.$user['uid']);
				$user['article_url'] = U('Users/index?uid='.$user['uid']);
				$user['comment_url'] = U('Users/comment?uid='.$user['uid']);
				$user['follow_url'] = U('Users/follow?uid='.$user['uid']);
				$user['fans_url'] = U('Users/fans?uid='.$user['uid']);
			}
			$this->assign('user',$user);
		}else{
			$this->error('此用户不存在喔！');
		}
		
		if($_SESSION['user']['uid'] != $uid){
			$condition_fans['follow_uid'] = $uid;
			$condition_fans['fans_uid'] = $_SESSION['user']['uid'];
			$database_fans = D('Fans_'.substr($_SESSION['user']['uid'],0,1));
			if($fans = $database_fans->where($condition_fans)->find()){
				$this->assign('is_follow',true);
			}
		}
		$database_fans = D('Fans_'.substr($uid,0,1));
		$condition_fans['follow_uid'] = $uid;
		$count = $database_fans->where($condition_fans)->count('follow_uid');
		$this->assign('count',$count);
		$prefix = C('DB_PREFIX');
		$fans_table = $prefix.'fans_'.substr($uid,0,1);
		$fans = D('')->field('`f`.`follow_uid`,`u`.`uid`,`u`.`nickname`')->Table(array($fans_table=>'f',$prefix.'user'=>'u'))->where('`f`.`follow_uid`='.$uid.' AND `u`.`uid`=`f`.`fans_uid`')->select();
		
		foreach($fans as $key=>$value){
			if($this->config['site_html']){
				$fans[$key]['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$value['uid'].'.jpg';
				$fans[$key]['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$value['uid'].'.jpg';
				$fans[$key]['user_url'] = $this->config['site_url'].'/users/'.$value['uid'].'/';
			}else{
				$fans[$key]['avatar_m'] = U('Avatar/index?size=m&uid='.$value['uid']);
				$fans[$key]['avatar_s'] = U('Avatar/index?size=s&uid='.$value['uid']);
				$fans[$key]['user_url'] = U('Users/index?uid='.$value['uid']);
			}
		}
		$this->assign('fans',$fans);
		$this->display();
	}
	public function profile(){
		if(empty($_SESSION['user']['uid'])){
			$this->assign('jumpUrl',$this->config['site_url']);
			$this->error('请先进行登录！');
		}
		$user = $_SESSION['user'];
		if($this->config['site_html']){
			$user['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$user['uid'].'.jpg';
			$user['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$user['uid'].'.jpg';
			$user['user_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/';
			$user['article_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/article/';
			$user['comment_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/comment/';
			$user['follow_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/follow/';
			$user['fans_url'] = $this->config['site_url'].'/users/'.$user['uid'].'/fans/';
		}else{
			$user['avatar_m'] = U('Avatar/index?size=m&uid='.$user['uid']);
			$user['avatar_s'] = U('Avatar/index?size=s&uid='.$user['uid']);
			$user['user_url'] = U('Users/index?uid='.$user['uid']);
			$user['article_url'] = U('Users/index?uid='.$user['uid']);
			$user['comment_url'] = U('Users/comment?uid='.$user['uid']);
			$user['follow_url'] = U('Users/follow?uid='.$user['uid']);
			$user['fans_url'] = U('Users/fans?uid='.$user['uid']);
		}
		$this->assign('user',$user);
		
		$this->display();
	}
	public function amend_pass(){
		if($this->isPost()){
			if(empty($_SESSION['user']['uid'])){
				$this->error('请先进行登录！');
			}	
			$old_password = $this->_post('old_password','htmlspecialchars,trim');
			$new_password = $this->_post('new_password','htmlspecialchars,trim');
			$re_password = $this->_post('re_password','htmlspecialchars,trim');
			if(empty($old_password)){
				$this->error('请输入旧密码！');
			}else if(empty($new_password)){
				$this->error('请输入新密码！');
			}else if($new_password != $re_password){
				$this->error('两次密码不一致！');
			}
			$database_user = D('User');
			$condition_user['uid'] = $_SESSION['user']['uid'];
			$condition_user['status'] = 1;
			$user = $database_user->field('`uid`,`pwd`')->where($condition_user)->find();
			if($user['pwd'] != md5($old_password)){
				$this->error('旧密码错误！');
			}else{
				$data_user['uid'] = $_SESSION['user']['uid'];
				$data_user['pwd'] = md5($new_password);
				if($database_user->data($data_user)->save()){
					$this->success('修改成功！请下次使用新密码登录。');
				}else{
					$this->error('修改失败！请重试。');
				}
			}
		}
	}
	public function amend_avatar(){
		if(empty($_SESSION['user']['uid'])){
			$this->error('请先进行登录！');
		}
		
		$avatar_uid = sprintf("%09d",$_SESSION['user']['uid']);
		$avatar_dir = substr($avatar_uid,0,3).'/'.substr($avatar_uid,3,2).'/'.substr($avatar_uid,5,2).'/';
		if(!is_dir('./Uploads/Avatar/'.$avatar_dir)){
			mkdir('./Uploads/Avatar/'.$avatar_dir,0777,true);
		}

		$database_user = D('User');
		$condition_user['uid'] = $_SESSION['user']['uid'];
		$now_user = $database_user->field('`avatar_suffix`')->where($condition_user)->find();
		
		
		import("ORG.Util.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize = 2097152;
		$upload->allowExts = explode(',','jpg,gif,png,jpeg');
		$upload->savePath = './Uploads/Avatar/'.$avatar_dir;
		$upload->saveRule = 'get_uid'; 
		$upload->uploadReplace = true;
		if($_FILES['new_avatar']['type'] != 'image/gif'){
			$upload->thumb = true;
			$upload->imageClassPath = 'ORG.Util.Image';
			$upload->thumbPrefix = 'm_,s_';
			$upload->thumbMaxWidth  = '100,32';
			$upload->thumbMaxHeight = '100,32';
			$upload->thumbRemoveOrigin = true;
		}
		if($upload->upload()){
			$uploadList = $upload->getUploadFileInfo();
			if($_FILES['new_avatar']['type'] == 'image/gif'){
				copy('./Uploads/Avatar/'.$avatar_dir.$uploadList[0]['savename'],'./Uploads/Avatar/'.$avatar_dir.'m_'.$uploadList[0]['savename']);
				copy('./Uploads/Avatar/'.$avatar_dir.$uploadList[0]['savename'],'./Uploads/Avatar/'.$avatar_dir.'s_'.$uploadList[0]['savename']);
				unlink('./Uploads/Avatar/'.$avatar_dir.$uploadList[0]['savename']);
			}
			$database_user = D('User');
			$data_user['avatar_suffix'] = array_pop(explode('.',$uploadList[0]['savename']));
			$data_user['uid'] = $_SESSION['user']['uid'];
			$database_user->data($data_user)->save();
			if($now_user['avatar_suffix'] != $data_user['avatar_suffix']){
				unlink('./Uploads/Avatar/'.$avatar_dir.'m_'.$_SESSION['user']['uid'].'.'.$now_user['avatar_suffix']);
				unlink('./Uploads/Avatar/'.$avatar_dir.'s_'.$_SESSION['user']['uid'].'.'.$now_user['avatar_suffix']);
			}
			S('avatar_'.$_SESSION['user']['uid'],NULL);
			$this->success('头像修改成功！');
		}else{
			$this->error($upload->getErrorMsg());
		}
	}
}