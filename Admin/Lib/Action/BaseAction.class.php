<?php
/**
 * 后台基础页
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
		if(empty($_SESSION['admin']['admin_id'])){
			redirect(U('Login/index'));
		}else{
			$this->assign('TMPL_PUBLIC',TMPL_PATH.'Public/');
			$this->assign('admin',$_SESSION['admin']);
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
		}
    }
	/*
	 * 跳转页面
	 */
	public function notice($type,$msg,$url=''){
		if(!empty($url)){
			$this->assign('jumpUrl',$url);
		}
		if($type == 'success'){
			$this->success($msg);
		}else if($type == 'error'){
			$this->error($msg);
		}
	}
}