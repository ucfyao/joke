<?php
/**
 * 后台登录入口
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */

class LoginAction extends Action{
    public function index(){
		$config = F('config');
		if(empty($config)){
			$configs = M('Config')->select();
			foreach($configs as $key=>$value){
				$config[$value['name']] = $value['value'];
			}
			F('config',$config);
		}
		$this->config = $config;
		$this->assign('config',$config);
			
		$this->assign('TMPL_PUBLIC',TMPL_PATH.'Public/');
		$this->display();
    }
	public function check(){
		if($this->isAjax()){
			$condition['admin_name'] = $this->_post('admin_name');
			$database = D('Admin');
			$admin = $database->where($condition)->find();
			if(is_array($admin)){
				$admin_pwd = $this->_post('admin_pwd');
				if($admin['admin_pwd'] == md5($admin_pwd)){
					if($admin['admin_status'] == 1){
						unset($admin['admin_pwd']);
						$admin['admin_type'] = $admin['admin_level'] ? '超级管理员' : '管理员';
						$_SESSION['admin'] = $admin;
						$data['admin_id'] = $admin['admin_id'];
						$data['admin_last_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
						$data['admin_last_time'] = $_SERVER['REQUEST_TIME'];
						$data['admin_login_count'] = $admin['admin_login_count']+1;
						$database->data($data)->save(); //保存管理员最后登录的IP和时间
						exit('1'); //登录成功
					}else{
						exit('-3'); //管理员不被允许登录
					}
				}else{
					exit('-2'); //密码错误
				}
			}else{
				exit('-1'); //用户名不存在
			}
		}else{
			$this->assign('jumpUrl',U('Login/index'));
			$this->error('非法访问！将跳转登录页。');
		}
	}
	public function logout(){
		unset($_SESSION['admin']);
		$this->assign('jumpUrl',U('Login/index'));
		$this->success('退出成功！将跳转登录页。');
	}
}