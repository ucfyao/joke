<?php
/**
 * 前台基础类
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class BaseAction extends Action{
	public $config;
    public function _initialize(){
		$_GET['page'] = !empty($_GET['page']) ? intval($_GET['page']) : 1;
		$config = F('config');
		if(empty($config)){
			$configs = M('Config')->select();
			foreach($configs as $key=>$value){
				$config[$value['name']] = $value['value'];
			}
			F('config',$config);
		}
		
		$this->config = $GLOBALS['config'] = $config;
		$this->assign('config',$config);
		$this->assign('TMPL_PUBLIC',$config['site_url'].'/Tpl/'.C('DEFAULT_THEME').'/Public');
		$this->assign('vote',unserialize($_COOKIE['vote']));
		
		$flink_list = F('flink_list');
		if(empty($flink_list)){
			$flink_database = D('Flink');
			$fink_codition['status'] = 1;
			$flink_list = $flink_database->field('`name`,`info`,`url`')->where($fink_codition)->order('`sort` ASC,`id` ASC')->select();
			F('flink_list',$flink_list);
		}
		$this->assign('flink_list',$flink_list);
		
		$adver_list = F('adver_list');
		if(empty($adver_list)){
			$adver_database = D('Adver');
			$adver_condition['status'] = 1;
			$old_adver_list = $adver_database->field('`id`,`start_time`,`end_time`,`code`')->where($adver_condition)->select();
			foreach($old_adver_list as $key=>$value){
				if($value['end_time'] != '0' && $value['end_time'] < $_SERVER['REQUEST_TIME']){
					$adver_list[$value['id']] = '广告已经过期！';
				}else if($value['start_time'] < $_SERVER['REQUEST_TIME']){
					$adver_list[$value['id']] = $value['code'];
				}
			}
			F('adver_list',$adver_list);
		}
		$this->assign('adver_list',$adver_list);
		
		//首先判断SESSION
		if(empty($_SESSION['user']) && !empty($_COOKIE['user_info'])){
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
					}
				}else{
					setcookie('user_info','',$_SERVER['REQUEST_TIME']-1,'/');
				}
			}else{
				setcookie('user_info','',$_SERVER['REQUEST_TIME']-1,'/');
			}
		}else if(empty($_COOKIE['user_info'])){
			unset($_SESSION['user']);
		}
    }
}