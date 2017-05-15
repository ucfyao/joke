<?php
/**
 * 注册登录页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class LoginAction extends Action{
	public function login(){
		if($this->isPost()){
			$condition_user['nickname'] = $this->_post('login');
			$database_user = D('User');
			$user = $database_user->field(true)->where($condition_user)->find();
			if(!empty($user)){
				if($user['status'] == 1){
					$password = $this->_post('password');
					if($user['pwd'] == md5($password)){
						$config = F('config');
						if($config['user_emailcheck'] && $user['emailstatus'] == 0){
							$this->reg_email($user['uid'],$user['nickname'],$user['email']);
							exit('-5');
						}
						//保存用户登录记录
						$data_user['uid'] = $user['uid'];
						$data_user['last_time'] = $_SERVER['REQUEST_TIME'];
						$data_user['last_ip']  = ip2long($_SERVER['REMOTE_ADDR']);
						$database_user->data($data_user)->save();
						$user_info['uid'] = $user['uid'];
						$user_info['email'] = $user['email'];
						$user_info['nickname'] = $user['nickname'];
						
						$user_info['safecode'] = md5(substr($config['site_safecode'],0,9).$user_info['email'].substr($config['site_safecode'],9,9).$user_info['nickname'].substr($config['site_safecode'],18,9).$user_info['uid'].substr($config['site_safecode'],27,9));
						//设置用户信息并保存7天
						setcookie('user_info',urlencode(json_encode($user_info)),$_SERVER['REQUEST_TIME']+604800,'/');
						$_SESSION['user']['nickname'] = $user_info['nickname'];
						$_SESSION['user']['uid'] = $user_info['uid'];
						exit('1');
					}elseif(empty($user['pwd'])){
						exit('-4');
					}else{
						exit('-3');
					}
				}else{
					exit('-2');
				}
			}else{
				exit('-1');
			}
		}else{
			$this->error('非法访问！');
		}
	}
	//登录状态
	public function loginstatus(){
		//首先判断SESSION
		if(empty($_SESSION['user'])){
			//得到COOKIE信息
			$user_info = json_decode(urldecode(htmlspecialchars($_COOKIE['user_info'])),true);
			if(is_array($user_info)){
				//判断COOKIE是否安全
				$config = F('config');
				if($user_info['safecode'] == md5(substr($config['site_safecode'],0,9).$user_info['email'].substr($config['site_safecode'],9,9).$user_info['nickname'].substr($config['site_safecode'],18,9).$user_info['uid'].substr($config['site_safecode'],27,9))){
					$database_user = D('User');
					$condition_user['uid'] = $user_info['uid'];
					$database_field = '`uid`';
					$user = $database_user->field($database_field)->where($condition_user)->find();
					//判断用户是否存在
					if(!empty($user)){
						$return['uid'] = $_SESSION['user']['uid'] = $user['uid'];
						$return['nickname'] = $_SESSION['user']['nickname'] = $user_info['nickname'];
						//保存用户登录记录
						$data_user['uid'] = $user['uid'];
						$data_user['last_time'] = $_SERVER['REQUEST_TIME'];
						$data_user['last_ip']  = ip2long($_SERVER['REMOTE_ADDR']);
						$database_user->data($data_user)->save();
						//返回消息
						exit(json_encode($return));
					}
				}else{
					//设置用户信息过期
					setcookie('user_info','',$_SERVER['REQUEST_TIME']-1,'/');
				}
			}
		}else{
			if(!empty($_COOKIE['user_info'])){
				exit(json_encode($_SESSION['user']));
			}else{
				unset($_SESSION['user']);
			}
		}
		exit('0');
	}
	//退出登录
	public function loginout(){

		unset($_SESSION['user']);
		session_destroy();
		setcookie('user_info','',$_SERVER['REQUEST_TIME']-1,'/');
		redirect($_SERVER['HTTP_REFERER']);
	}
	//注册
	public function reg(){
		if($this->isPost()){
			$database_user = D('User');
			$nickname = $this->_post('login');
			$condition_nickname['nickname'] = $nickname;
			if($database_user->field('`uid`')->where($condition_nickname)->find()){
				exit('-1');
			}
			$email = $this->_post('email');
			$condition_email['email'] = $email;
			if($database_user->field('`uid`')->where($condition_email)->find()){
				exit('-2');
			}
			$data_user['email'] = $email;
			$data_user['nickname'] = $nickname;
			$data_user['pwd'] = $this->_post('password','htmlspecialchars,md5');
			$data_user['status'] = 1;
			$data_user['last_time'] = $data_user['reg_time'] = $_SERVER['REQUEST_TIME'];
			$data_user['last_ip'] = $data_user['reg_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
			$config = F('config');
			if(empty($config['user_emailcheck'])){
				$data_user['emailstatus'] = 2;
			}else{
				$data_user['emailstatus'] = 0;
			}
			if($uid = $database_user->data($data_user)->add()){
				$user_info['uid'] = $uid;
				$user_info['email'] = $data_user['email'];
				$user_info['nickname'] = $data_user['nickname'];
				$user_info['safecode'] = md5(substr($config['site_safecode'],0,9).$user_info['email'].substr($config['site_safecode'],9,9).$user_info['nickname'].substr($config['site_safecode'],18,9).$user_info['uid'].substr($config['site_safecode'],27,9));
				if($config['user_emailcheck']){
					$this->reg_email($uid,$data_user['nickname'],$data_user['email']);
					exit('-4');
				}else{
					//设置用户信息并保存7天
					setcookie('user_info',urlencode(json_encode($user_info)),$_SERVER['REQUEST_TIME']+604800,'/');
					$_SESSION['user']['nickname'] = $user_info['nickname'];
					$_SESSION['user']['uid'] = $uid;
					exit('1');
				}
			}else{
				exit('-3');
			}
		}else{
			$this->error('非法访问！');
		}
	}
	private function reg_email($uid,$nickname,$email){
		import('@.ORG.Smtp');
		$config = F('config');
		$smtpserver = $config['mail_server'];//SMTP服务器 
		$port = $config['mail_port'];//SMTP服务器端口
		$smtpuser = $config['mail_user'];//服务器的用户帐号
		$smtppwd = $config['mail_pwd'];//SMTP服务器的用户密码
		$sender = $config['mail_from'];//发送人的昵称
		$mailto = $email; //接收人的邮箱地址
		$mailsubject = '您好，来自'.$config['site_name'].'的邮箱验证！';//邮件的标题
		$mailbody = $config['user_emailchecktpl'];//邮件的内容
		$mailbody = str_replace('{sitename}',$config['site_name'],$mailbody);
		$mailbody = str_replace('{url}',$config['site_url'].'/index.php?m=login&a=get_reg&uid='.$uid.'&code='.md5($uid.$config['site_safecode'].$email),$mailbody);
		$mailbody = str_replace('{username}',$nickname,$mailbody);
		$mailbody = str_replace('{email}',$email,$mailbody);
		$mailbody = str_replace('{time}',date('Y年m月d日 H时i分s秒',$_SERVER['REQUEST_TIME']),$mailbody);
		$mailtype = "TXT";////邮件格式（HTML/TXT）,TXT为文本邮件
		$smtp = new smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender); //这里面的一个true是表示使用身份验证,否则不使用身份验证
		$smtp->debug = false;//是否显示发送的调试信息
		$send=$smtp->sendmail($mailto,$sender,$mailsubject,$mailbody,$mailtype);
	}
	public function fetchpass(){
		$database_user = D('User');
		$condition_user['nickname'] = $this->_post('login');
		$condition_user['email'] = $this->_post('email','htmlspecialchars,urldecode');
		$condition_user['status'] = 1;
		$user = $database_user->field('`uid`,`pwd`,`nickname`,`email`')->where($condition_user)->find();
		if(empty($user)){
			exit('-1');
		}
		import('@.ORG.Smtp');
		$config = F('config');
		$smtpserver = $config['mail_server'];//SMTP服务器 
		$port = $config['mail_port'];//SMTP服务器端口
		$smtpuser = $config['mail_user'];//服务器的用户帐号
		$smtppwd = $config['mail_pwd'];//SMTP服务器的用户密码
		$sender = $config['mail_from'];//发送人的昵称
		$mailto = $user['email']; //接收人的邮箱地址
		$mailsubject = '您好，来自'.$config['site_name'].'的邮箱验证！';//邮件的标题
		$mailbody = $config['user_getpwdemaitpl'];//邮件的内容
		$mailbody = str_replace('{sitename}',$config['site_name'],$mailbody);
		$mailbody = str_replace('{url}',$config['site_url'].'/index.php?m=login&a=get_pass&uid='.$user['uid'].'&code='.md5($user['uid'].$config['site_safecode'].$user['pwd']),$mailbody);
		$mailbody = str_replace('{username}',$user['nickname'],$mailbody);
		$mailbody = str_replace('{email}',$user['email'],$mailbody);
		$mailbody = str_replace('{time}',date('Y年m月d日 H时i分s秒',$_SERVER['REQUEST_TIME']),$mailbody);
		$mailtype = "TXT";////邮件格式（HTML/TXT）,TXT为文本邮件
		$smtp = new smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender); //这里面的一个true是表示使用身份验证,否则不使用身份验证
		$smtp->debug = false;//是否显示发送的调试信息
		$send=$smtp->sendmail($mailto,$sender,$mailsubject,$mailbody,$mailtype);
		exit('1');
	}
	public function get_pass(){
		$database_user = D('User');
		$condition_user['uid'] = $this->_get('uid','intval');
		$condition_user['status'] = 1;
		$user = $database_user->field(true)->where($condition_user)->find();
		$config = F('config');
		if(empty($user)){
			$this->assign('jumpUrl',$config['site_url']);
			$this->error('此用户不存在!将返回首页。');
		}else{
			$code = $this->_get('code');
			if($code == md5($user['uid'].$config['site_safecode'].$user['pwd'])){
				$this->assign('user',$user);
				$this->assign('code',$code);
				$this->assign('config',$config);
				$this->assign('TMPL_PUBLIC',$config['site_url'].ltrim(TMPL_PATH,'.').'Public');
				$this->assign('flink_list',F('flink_list'));
				$this->display();
			}else{
				$this->assign('jumpUrl',$config['site_url']);
				$this->error('您的链接已经失效！将返回首页。');
			}
		}
	}
	public function set_pass(){
		$password = $this->_post('password');
		$re_password = $this->_post('re_password');
		if(empty($password)){
			$this->error('请输入密码！');
		}else if($password != $re_password){
			$this->error('两次密码不一致！');
		}
		
		$database_user = D('User');
		$condition_user['uid'] = $this->_post('uid','intval');
		$condition_user['status'] = 1;
		$user = $database_user->field('`uid`,`pwd`,`nickname`,`email`')->where($condition_user)->find();
		$config = F('config');
		if(empty($user)){
			$this->assign('jumpUrl',$config['site_url']);
			$this->error('此用户不存在!将返回首页。');
		}else if($password == $user['pwd']){
			$this->assign('jumpUrl',$config['site_url']);
			$this->success('修改成功!将返回首页。');
		}else{
			$code = $this->_post('code');
			if($code == md5($user['uid'].$config['site_safecode'].$user['pwd'])){
				$data_user['uid'] = $user['uid'];
				$data_user['pwd'] = md5($password);
				if($database_user->data($data_user)->save()){
					$this->assign('jumpUrl',$config['site_url']);
					$this->success('修改成功！将返回首页。');
				}else{
					$this->error('修改失败！请重试。');
				}
			}else{
				$this->assign('jumpUrl',$config['site_url']);
				$this->error('您的链接已经失效！将返回首页。');
			}
		}
	}
	public function get_reg(){
		$database_user = D('User');
		$condition_user['uid'] = $this->_get('uid','intval');
		$condition_user['status'] = 1;
		$user = $database_user->field('`uid`,`nickname`,`email`')->where($condition_user)->find();
		$config = F('config');
		if(empty($user)){
			$this->assign('jumpUrl',$config['site_url']);
			$this->error('此用户不存在!将返回首页。');
		}else{
			$code = $this->_get('code');
			if($code == md5($user['uid'].$config['site_safecode'].$user['email'])){
				$data_user['uid'] = $user['uid'];
				$data_user['emailstatus'] = 1;
				if($database_user->data($data_user)->save()){
					$this->assign('jumpUrl',$config['site_url']);
					$this->success('邮箱验证成功！您可以用此帐号登录啦。');
				}else{
					$this->error('邮箱验证失败！');
				}
			}else{
				$this->assign('jumpUrl',$config['site_url']);
				$this->error('您的链接已经失效！将返回首页。');
			}
		}
	}
}
?>