<?php
/**
 * 插件
 *
 * QQ登录  
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class QzoneAction extends BaseAction {
	public function index(){
		if(empty($this->config['open_qq_connect'])){
			$this->assign('jumpUrl',$this->config['site_url']);
			$this->error('暂未开通 QQ 登录！');
		}
		unset($_SEESION);
		$_SESSION['url_referer'] = $_SERVER['HTTP_REFERER'];
		$appid    = $this->config['qq_appid']; 
		$appkey   = $this->config['qq_appkey']; 
		if(empty($appid) || empty($appkey)){
			$this->assign('jumpUrl',$this->config['site_url']);
			$this->error('请管理员 配置好QQ登录的appid 和 appkey ！');
		}
		$callback = $this->config['site_url'].'/index.php?m=qzone&a=callback';
		$scope = 'get_user_info,add_share,add_weibo,add_t,add_pic_t,add_idol,add_video,add_pic_url,list_album,add_album,upload_pic,add_topic,add_one_blog';
		$_SESSION['qzone']['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$login_url = 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.$appid.'&redirect_uri='.urlencode($callback).'&state='.$_SESSION['qzone']['state'].'&scope='.$scope;
		header("Location:$login_url");
    }
	public function callback(){
		set_time_limit(180);
		//QQ登录成功后的回调地址,主要保存access token
		$appid    = $this->config['qq_appid']; 
		$appkey   = $this->config['qq_appkey']; 
		$callback = $this->config['site_url'].'/index.php?m=qzone&a=callback';
		if($_REQUEST['state'] == $_SESSION['qzone']['state']){
			$token_url = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&'.'client_id='.$appid.'&redirect_uri='.urlencode($callback).'&client_secret='.$appkey.'&code='.$_REQUEST['code'];
			$response = $this->url_get_content($token_url);
			if(strpos($response,'callback') !== false){
				$lpos = strpos($response,'(');
				$rpos = strrpos($response,')');
				$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
				$msg = json_decode($response);
				if (isset($msg->error)){
					$this->assign('jumpUrl',$_SESSION['url_referer']);
					$this->error('QQ登录出现错误！将跳转来源页。');
				}
			}
			$params = array();
			parse_str($response, $params);
			$access_token = $params['access_token'];
			//获取用户标示id
			$graph_url = 'https://graph.qq.com/oauth2.0/me?access_token='.$access_token;
			$str  = $this->url_get_content($graph_url);
			if(strpos($str,'callback') !== false){
				$lpos = strpos($str,'(');
				$rpos = strrpos($str, ')');
				$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
			}
			$user = json_decode($str);
			if(isset($user->error)){
				$this->assign('jumpUrl',$_SESSION['url_referer']);
				$this->error('QQ登录出现错误！将跳转来源页。');
			}
			$openid = $user->openid;
			
			//判断是否已经注册过
			$database_user_bind = D('User_bind');
			$condition_user_bind['openid'] = $openid;
			$condition_user_bind['opentype'] = 1;
			$user_bind = $database_user_bind->field('`uid`')->where($condition_user_bind)->find();
			if(is_array($user_bind)){
				$this->autologin($user_bind['uid']);
			}else{
				//获取用户信息
				$get_user_info = 'https://graph.qq.com/user/get_user_info?'.'access_token='.$access_token.'&oauth_consumer_key='. $appid.'&openid='.$openid.'&format=json';		
				$info = $this->url_get_content($get_user_info);
				$arr = json_decode($info, true);
			
				$database_user = D('User');
				$data_user['nickname'] = msubstr(htmlspecialchars($arr['nickname']),0,20,false);
				$data_user['status'] = 1;
				$data_user['emailstatus'] = 2;
				$data_user['last_time'] = $data_user['reg_time'] = $_SERVER['REQUEST_TIME'];
				$data_user['last_ip'] = $data_user['reg_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
				if($vo = $database_user->data($data_user)->add()){
					$data_user_bind['openid'] = $openid;
					$data_user_bind['uid'] = $vo;
					$data_user_bind['opentype'] = 1;
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
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_HEADER,0);
			curl_setopt($ch,CURLOPT_TIMEOUT,30); //设置超时限制防止死循环
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false); 
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
			$content = curl_exec($ch);
			curl_close($ch);
		}elseif(function_exists('file_get_contents')){
			$content = file_get_contents($url);
		}else{
			exit('您的服务器同时不支持“Curl组件”和“file_get_contents方法”，无法开始采集！');
		}
		if(empty($content)){
			$referer = !empty($_SESSION['url_referer']) ? $_SESSION['url_referer'] : $this->config['site_url'];
			$this->assign('jumpUrl',$referer);
			$this->error('QQ登录出现错误，请重试。');
		}else{
			return $content;
		}
	}
}