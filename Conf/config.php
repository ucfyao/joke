<?php
/**
 * 配置文件
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */

if (!defined('THINK_PATH'))	exit('非法调用');
@ini_set('memory_limit', '20M');
return array(
	/*URL设置*/
	'URL_CASE_INSENSITIVE'  => true,
	'URL_MODEL'	=> 0,
	
	/*模板配置*/
	'TMPL_L_DELIM' 		=> '<{',
	'TMPL_R_DELIM' 	 	=> '}>',
	'TMPL_STRIP_SPACE'  => true,
	'TMPL_CACHE_ON'     => true,
	
	/*Token配置*/
	'TOKEN_ON'		 => false,
);
?>