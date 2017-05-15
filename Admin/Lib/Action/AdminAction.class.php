<?php
/**
 * 管理员管理页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class AdminAction extends BaseAction{
    public function index(){
		$this->is_super();
		$database_admin = D('Admin');
		$admin_list = $database_admin->field('`admin_id`,`admin_name`,`admin_realname`,`admin_qq`,`admin_phone`,`admin_login_count`,`admin_last_time`,`admin_last_ip`,`admin_level`,`admin_status`')->order('`admin_level` DESC')->select();
		$this->assign('admin_list',$admin_list);
		$this->display();
    }
	public function add(){
		$this->is_super();
		$this->display();
	}
	public function modify(){
		$this->is_super();
		if($this->isPost()){
			$data_admin['admin_name'] = $this->_post('admin_name');
			$admin_pwd = $this->_post('admin_pwd');
			$data_admin['admin_pwd'] = md5($admin_pwd);
			$data_admin['admin_realname'] = $this->_post('admin_realname');
			$data_admin['admin_qq'] = $this->_post('admin_qq');
			$data_admin['admin_phone'] = $this->_post('admin_phone');
			$data_admin['admin_status'] = $this->_post('admin_status','intval');
			$data_admin['admin_level'] = $this->_post('admin_level','intval');
			if($data_admin['admin_level'] > 1){
				$this->error('管理员等级非法！');
			}else{
				$data_admin['admin_last_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
				$data_admin['admin_last_time'] = $_SERVER['REQUEST_TIME'];
			}
			$database_admin = D('Admin');
			if($database_admin->data($data_admin)->add()){
				$this->success('添加成功！');
			}else{
				dump($database_admin);
				exit;
				$this->error('添加失败！');
			}
		}else{
			$this->error('非法访问！');
		}
	}
	public function edit(){
		$this->is_super();
		$condition_admin['admin_id'] = $this->_get('admin_id','intval');
		$database_admin = D('Admin');
		$admin = $database_admin->field('`admin_id`,`admin_name`,`admin_realname`,`admin_qq`,`admin_phone`,`admin_login_count`,`admin_last_time`,`admin_last_ip`,`admin_level`,`admin_status`')->where($condition_admin)->find();
		$this->assign('admin',$admin);
		$this->display();
	}
	public function amend(){
		$this->is_super();
		if($this->isPost()){
			$data_admin['admin_id'] = $this->_post('admin_id','intval');
			$data_admin['admin_name'] = $this->_post('admin_name');
			$admin_pwd = $this->_post('admin_pwd');
			if(!empty($admin_pwd)){
				$data_admin['admin_pwd'] = md5($admin_pwd);
			}
			$data_admin['admin_realname'] = $this->_post('admin_realname');
			$data_admin['admin_qq'] = $this->_post('admin_qq');
			$data_admin['admin_phone'] = $this->_post('admin_phone');
			$data_admin['admin_status'] = $this->_post('admin_status','intval');
			$database_admin = D('Admin');
			if($database_admin->data($data_admin)->save()){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
		}else{
			$this->error('非法访问！');
		}
	}
	public function del(){
		$this->is_super();
		$condition_admin['admin_id'] = $admin_id = $this->_get('admin_id','intval');
		if($admin_id == $_SESSION['admin']['admin_id']){
			$this->error('不能删除自己喔！');
		}
		$database_admin = D('Admin');
		if($database_admin->data($condition_admin)->delete()){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
	public function is_super(){
		if($_SESSION['admin']['admin_level'] != 1){
			$this->error('您不是超级管理员！');
		}
	}
}