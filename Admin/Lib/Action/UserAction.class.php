<?php
/**
 * 用户管理
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class UserAction extends BaseAction{
    public function index(){
		$database_user = D('User');
		$condition_user['uid'] = array('neq',1);
		$count_user = $database_user->where($condition_user)->count('`uid`');
		import('ORG.Util.Page');
		$p = new Page($count_user,20);
		$user_list = $database_user->where($condition_user)->order('`uid` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('user_list',$user_list);
		$this->assign('page',$p->show());
		
		$this->display();
    }
	public function edit(){
		$condition_user['uid'] = $this->_get('uid','intval');
		$database_user = D('User');
		$user = $database_user->where($condition_user)->find();
		$this->assign('user',$user);
		$this->display();
	}
	public function amend(){
		if($this->isPost()){
			$data_user['uid'] = $this->_post('uid','intval');
			$data_user['status'] = $this->_post('status','intval');
			$data_user['nickname'] = $this->_post('nickname');
			$data_user['email'] = $this->_post('email');
			$pwd = $this->_post('pwd');
			if(!empty($pwd)){
				$data_user['pwd'] = md5($pwd);
			}
			$clear_avatar = $this->_post('clear_avatar','intval');
			if(!empty($clear_avatar)){
				$data_user['avatar_suffix'] = '';
			}
			$database_user = D('User');
			if($database_user->data($data_user)->save()){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
		}else{
			$this->error('非法访问！');
		}
	}
	public function del(){
		$condition_user['uid'] = $this->_get('uid','intval');
		$database_user = D('User');
		if($database_user->data($condition_user)->delete()){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
}