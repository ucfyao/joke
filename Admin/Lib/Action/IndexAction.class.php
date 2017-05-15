<?php
/**
 * 后台首页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class IndexAction extends BaseAction {
    public function index(){
		$this->display();
	}
	public function main(){
		$server_info  = array(
							'运行环境'     => PHP_OS.' '.$_SERVER["SERVER_SOFTWARE"],
							'PHP运行方式'  => php_sapi_name(),
							'MYSQL版本'    => mysql_get_client_info(),
							'上传附件限制' => ini_get('upload_max_filesize'),
							'执行时间限制' => ini_get('max_execution_time').'秒',
							'磁盘剩余空间 '=> round((@disk_free_space(".")/(1024*1024)),2).'M',
					    );
		$this->assign('server_info',$server_info);
		$this->display();
	}
	public function pass(){
		$this->display();
	}
	public function amend_pass(){
		$condition_admin['admin_id'] = $_SESSION['admin']['admin_id'];
		$database_admin = D('Admin');
		$admin = $database_admin->field('`admin_id`,`admin_pwd`')->where($condition_admin)->find();
		$old_pass = $this->_post('old_pass');
		$new_pass = $this->_post('new_pass');
		$re_pass = $this->_post('re_pass');
		if($admin['admin_pwd'] != md5($old_pass)){
			$this->error('旧密码错误！');
		}else if($new_pass != $re_pass){
			$this->error('两次新密码不一致！');
		}else{
			$data_admin['admin_id'] = $admin['admin_id'];
			$data_admin['admin_pwd'] = md5($new_pass);
			if($database_admin->data($data_admin)->save()){
				$this->success('密码修改成功！');
			}else{
				$this->error('密码修改失败！请检查是否修改了内容。');
			}
		}
	}
	public function profile(){
		$database_admin = D('Admin');
		$condition_admin['admin_id'] = $_SESSION['admin']['admin_id'];
		$admin = $database_admin->where($condition_admin)->find();
		$this->assign('admin',$admin);
		$this->display();
	}
	public function amend_profile(){
		$database_admin = D('Admin');
		$data_admin['admin_id'] = $_SESSION['admin']['admin_id'];
		$data_admin['admin_realname'] = $this->_post('admin_realname');
		$data_admin['admin_email'] = $this->_post('admin_email');
		$data_admin['admin_qq'] = $this->_post('admin_qq');
		$data_admin['admin_phone'] = $this->_post('admin_phone');
		if($database_admin->data($data_admin)->save()){
			$this->success('资料修改成功！');
		}else{
			$this->error('资料修改失败！请检查是否修改了内容。');
		}
	}
	public function cache(){
		import('ORG.Util.Dir');
		Dir::delDir('./Cache/');
		Dir::delDir('./Admin/Runtime/');
		$this->assign('jumpUrl',U('Index/main'));
		$this->success('清除缓存成功！');
	}
}