<?php
/**
 * 回复点评页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class CommentsAction extends BaseAction{
    public function index(){
		$return['is_login'] = $_SESSION['user'] ? true : false;
		$return['article_id'] = $article_id = $this->_get('article_id','intval');
		$table = substr($article_id,-1);
		$prefix = C('DB_PREFIX');
		$reply_table = $prefix.'reply_'.$table;
		$return['comments'] = D('')->field('`r`.*,`u`.`uid`,`u`.`nickname`')->Table(array($reply_table=>'r',$prefix.'user'=>'u'))->where("`r`.`article_id`='$article_id' AND `r`.`reply_status`='1' AND `r`.`uid`=`u`.`uid`")->order('`r`.`reply_sort` ASC')->select();
		foreach($return['comments'] as $key=>$value){
			if($GLOBALS['config']['site_html']){
				$return['comments'][$key]['avatar_m'] = $GLOBALS['config']['site_url'].'/avatar/m/'.$value['uid'].'.jpg';
				$return['comments'][$key]['avatar_s'] = $GLOBALS['config']['site_url'].'/avatar/s/'.$value['uid'].'.jpg';
				$return['comments'][$key]['user_url'] = $GLOBALS['config']['site_url'].'/users/'.$value['uid'].'/';
			}else{
				$return['comments'][$key]['avatar_m'] = U('Avatar/index?size=m&uid='.$value['uid']);
				$return['comments'][$key]['avatar_s'] = U('Avatar/index?size=s&uid='.$value['uid']);
				$return['comments'][$key]['user_url'] = U('Users/index?uid='.$value['uid']);
			}
		}
		echo json_encode($return);
	}
	public function reply_to(){
		if($this->isPost() && !empty($_SESSION['user'])){
			$condition['article_id'] = $article_id = $this->_post('article_id','intval');
			$table = substr($article_id,-1);
			$database = D('Reply_'.$table);
			$reply = $database->field('`reply_sort`')->where($condition)->order('`reply_id` DESC')->find();
			$data['reply_sort'] = is_array($reply) ? $reply['reply_sort']+1 : 1;
			$data['article_id'] = $article_id;
			$data['uid'] = $_SESSION['user']['uid'];
			$data['reply_content'] = $this->_post('reply_content');
			$data['reply_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
			$data['reply_time'] = $_SERVER['REQUEST_TIME'];
			if($data['reply_id'] = $database->data($data)->add()){
				$data_reply = $data;
				unset($data_reply['reply_id']);
				D('Reply')->data($data_reply)->add();
				$condition_article['id'] = $article_id;
				$database_article = D('Article');
				$database_article->where($condition_article)->setInc('reply');
				$data['nickname'] = $_SESSION['user']['nickname'];
				if($GLOBALS['config']['site_html']){
					$data['avatar_m'] = $GLOBALS['config']['site_url'].'/avatar/m/'.$_SESSION['user']['uid'].'.jpg';
					$data['avatar_s'] = $GLOBALS['config']['site_url'].'/avatar/s/'.$_SESSION['user']['uid'].'.jpg';
					$data['user_url'] = $GLOBALS['config']['site_url'].'/users/'.$_SESSION['user']['uid'].'/';
				}else{
					$data['avatar_m'] = U('Avatar/index?size=m&uid='.$_SESSION['user']['uid']);
					$data['avatar_s'] = U('Avatar/index?size=s&uid='.$_SESSION['user']['uid']);
					$data['user_url'] = U('Users/index?uid='.$_SESSION['user']['uid']);
				}
				echo json_encode($data);
			}else{
				echo -1;
			}
		}else{
			$this->error('非法访问！');
		}
	}
	public function vote_to(){
		if($this->isPost()){
			$condition['id'] = $id = $this->_post('id','intval');
			$database = D('Article');
			$action = $this->_post('action','intval');
			if($action==1){
				if($database->where($condition)->setInc('upper')){
					$vote = unserialize($_COOKIE['vote']);
					$vote[$id] = 1;
					setcookie('vote',serialize($vote),$_SERVER['REQUEST_TIME']+1000000,'/');
				}
			}else{
				if($database->where($condition)->setInc('below')){
					$vote = unserialize($_COOKIE['vote']);
					$vote[$id] = -1;
					setcookie('vote',serialize($vote),$_SERVER['REQUEST_TIME']+1000000,'/');
				}
			}
		}else{
			$this->error('非法访问！');
		}
	}
}