<?php
/**
 * 后台配置文件
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
 
	$config = require './Conf/config.php';	//公有配置文件
	$db = require './Conf/db.php';					//数据库配置文件
	/*
	 * 后台私有配置项	
	 */
	$private = array(
		
	);
	return array_merge($config,$db,$private);
?>