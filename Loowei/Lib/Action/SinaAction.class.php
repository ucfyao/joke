<?php
/**
 * 插件
 *
 * 新浪微博登录  
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class SinaAction extends BaseAction {
	public function index(){
		if(empty($this->config['open_sina_connect'])){
			$this->assign('jumpUrl',$this->config['site_url']);
			$this->error('暂未开通 新浪微博 登录！');
		}
		unset($_SEESION);
		$_SESSION['url_referer'] = $_SERVER['HTTP_REFERER'];
		$appid    = $this->config['sina_appid']; 
		$appkey   = $this->config['sina_appkey']; 
		if(empty($appid) || empty($appkey)){
			$this->assign('jumpUrl',$this->config['site_url']);
			$this->error('请管理员 配置好新浪微博登录的appid 和 appkey ！');
		}
		$callback = $this->config['site_url'].'/index.php?m=sina&a=callback';
		$_SESSION['weibo']['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$login_url = 'https://api.weibo.com/oauth2/authorize?response_type=code&client_id='.$appid.'&redirect_uri='.urlencode($callback).'&state='.$_SESSION['weibo']['state'];
		header("Location:$login_url");
    }
	public function callback(){
		set_time_limit(180);
		//QQ登录成功后的回调地址,主要保存access token
		$appid    = $this->config['sina_appid']; 
		$appkey   = $this->config['sina_appkey']; 
		$callback = $this->config['site_url'].'/index.php?m=sina&a=callback';
		$code = $this->_get('code');
		if($_REQUEST['state'] == $_SESSION['weibo']['state']){
			$access_token_array = array(
										'client_id'=>$appid,
										'client_secret'=>$appkey,
										'grant_type'=>'authorization_code',
										'code'=>$code,
										'redirect_uri'=>$callback,
										);
			$access_token_url = 'https://api.weibo.com/oauth2/access_token';
			
			if(function_exists('curl_init')){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
				curl_setopt($ch, CURLOPT_POST, TRUE); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($access_token_array)); 
				curl_setopt($ch, CURLOPT_URL, $access_token_url);
				$ret = curl_exec($ch);
			}else{
				exit('您的服务器不支持“Curl组件”，新浪微博登录必须支持，无法开始采集！');
			}
			
			$result_array = json_decode($ret,true);
			
			if(empty($result_array['access_token'])){
				$this->assign('jumpUrl',$_SESSION['url_referer']);
				$this->error('新浪微博登录出现错误！将跳转来源页。');
			}
			
			$users_show_url = 'https://api.weibo.com/2/users/show.json?access_token='.$result_array['access_token'].'&uid='.$result_array['uid'];
		
			$user_show = $this->url_get_content($users_show_url);
			
			$arr = json_decode($user_show,true);
			
			if(!is_array($arr)){
				$this->assign('jumpUrl',$_SESSION['url_referer']);
				$this->error('新浪微博登录出现错误！将跳转来源页。');
			}
			
			//通过绑定帐号，判断是否已经注册过
			$openid = $arr['idstr'];
			
			//判断是否已经注册过
			$database_user_bind = D('User_bind');
			$condition_user_bind['openid'] = $openid;
			$condition_user_bind['opentype'] = 2;
			$user_bind = $database_user_bind->field('`uid`')->where($condition_user_bind)->find();
			if(is_array($user_bind)){
				$this->autologin($user_bind['uid']);
			}else{
				$database_user = D('User');
				$data_user['nickname'] = msubstr(htmlspecialchars($arr['screen_name']),0,20,false);
				$data_user['status'] = 1;
				$data_user['emailstatus'] = 2;
				$data_user['last_time'] = $data_user['reg_time'] = $_SERVER['REQUEST_TIME'];
				$data_user['last_ip'] = $data_user['reg_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
				if($vo = $database_user->data($data_user)->add()){
					$data_user_bind['openid'] = $openid;
					$data_user_bind['uid'] = $vo;
					$data_user_bind['opentype'] = 2;
					$database_user_bind->data($data_user_bind)->add();
					$this->autologin($vo);
				}
			}
		}else{
			$this->error('非法访问！');
		}
	}
	public function autologin($uid){
		$referer = !empty($_SESSION['url_referer']) ? $_SESSION['url_referer'] : $this->config['site_url'];
		
		$database_user = D('User');
		$condition_user['uid'] = $uid;
		$user = $database_user->field(true)->where($condition_user)->find();
		if(!empty($user)){
			if($user['status'] == 1){
				//保存用户登录记录
				$data_user['uid'] = $user['uid'];
				$data_user['last_time'] = $_SERVER['REQUEST_TIME'];
				$data_user['last_ip']  = ip2long($_SERVER['REMOTE_ADDR']);
				$database_user->data($data_user)->save();
				$user_info['uid'] = $user['uid'];
				$user_info['email'] = $user['email'];
				$user_info['nickname'] = $user['nickname'];
				$user_info['safecode'] = md5(substr($this->config['site_safecode'],0,9).$user_info['email'].substr($this->config['site_safecode'],9,9).$user_info['nickname'].substr($this->config['site_safecode'],18,9).$user_info['uid'].substr($this->config['site_safecode'],27,9));
				//设置用户信息并保存7天
				setcookie('user_info',urlencode(json_encode($user_info)),$_SERVER['REQUEST_TIME']+604800,'/');
				$_SESSION['user']['nickname'] = $user_info['nickname'];
				$_SESSION['user']['uid'] = $user_info['uid'];
				
				redirect($referer);
			}else{
				$this->assign('jumpUrl',$referer);
				$this->error('您被禁止登录！');
			}
		}else{
			exit('-1');
		}
	}
	public function url_get_content($url){
		if(function_exists('curl_init')){
			$ch = curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER,0);
			curl_setopt($ch,CURLOPT_TIMEOUT,30); //设置超时限制防止死循环
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$content = curl_exec($ch);
			curl_close($ch);
		}else{
			exit('您的服务器不支持“Curl组件”，新浪微博登录必须支持，无法开始采集！');
		}
		return $content;
	}
}