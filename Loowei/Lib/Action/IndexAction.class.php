<?php
/**
 * 前台首页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class IndexAction extends BaseAction {
	public function _empty(){
		if(!file_exists('./Tpl/'.C('DEFAULT_THEME').'/Index/'.ACTION_NAME.'.html') && !file_exists('./Tpl/'.C('DEFAULT_THEME').'/Index/'.ACTION_NAME.'.php')){
			header('HTTP/1.1 404 Not Found');
			$this->display('Index:404');
		}elseif($this->config['site_waterfall'] && ACTION_NAME == 'pic'){
				$this->display('waterfall');
		}else{
			$this->display(ACTION_NAME);
		}
    }
}