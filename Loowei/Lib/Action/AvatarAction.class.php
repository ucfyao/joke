<?php
/**
 * 头像相关
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class AvatarAction extends BaseAction{
    public function index(){
		$uid = $this->_get('uid','intval,abs');
		$size = $this->_get('size');
		$size = in_array($size, array('m','s')) ? $size : 'm';
		//判断有没有头像数据缓存
		$user_avatar = S('avatar_'.$uid);
		if(empty($user_avatar)){
			$database_user = D('User');
			$condition_user['uid'] = $uid;
			$database_field = '`avatar_suffix`';
			$user_avatar = $database_user->where($condition_user)->field($database_field)->find();
			S('avatar_'.$uid,$user_avatar,43200);
		}
		if(empty($user_avatar['avatar_suffix'])){
			$avatar= $this->config['site_url'].'/Uploads/Avatar/'.$size.'_avatar.gif';
		}else{
			$avatar_uid = sprintf("%09d",$uid);
			$avatar_dir = substr($avatar_uid,0,3).'/'.substr($avatar_uid,3,2).'/'.substr($avatar_uid,5,2).'/';
			$avatar = $this->config['site_url'].'/Uploads/Avatar/'.$avatar_dir.$size.'_'.$uid.'.'.$user_avatar['avatar_suffix'];
		}
		// 301重定向头像
		header("HTTP/1.1 301 Moved Permanently");
		header("Last-Modified:".date('r'));
		header("Expires: ".date('r', time() + 43200));
		header('Location: '.$avatar);
		exit;
    }
}