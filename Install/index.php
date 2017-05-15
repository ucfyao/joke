<?php
/**
 * 安装程序页面
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
 
header('Content-Type: text/html; charset=UTF-8');
if (file_exists('../install.lock')) exit('你已经安装过本程序，如果想重新安装，请先删除站点根目录下的 install.lock 文件，然后再安装。');
if(!file_exists('./loowei.sql') || !file_exists('./db.php')) exit('缺少必要的安装文件!');
if('5.2.0' > phpversion() ) exit('您的php版本过低，不能安装本软件，请升级到5.2.0或更高版本再安装，谢谢！');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_time_limit(1000);
date_default_timezone_set('PRC');
define('SITEDIR', _dir_path(substr(dirname(__FILE__),0,-8)));

$steps= array(
	'1'=>'安装许可协议',
	'2'=>'运行环境检测',
	'3'=>'安装参数设置',
	'4'=>'安装详细过程',
	'5'=>'安装完成',
);
$step = isset($_GET['step'])? $_GET['step'] : 1;
$version = '2.0.7';
switch($step){
case '1':
    include_once ("./tmpl/step_1.html");
    exit();
case '2':

        $phpv = @ phpversion();
        $os = PHP_OS;
		$os = php_uname();
		$tmp = function_exists('gd_info') ? gd_info() : array();
        $server = $_SERVER["SERVER_SOFTWARE"];
        $host = (empty ($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_HOST"] : $_SERVER["SERVER_ADDR"]);
        $name = $_SERVER["SERVER_NAME"];
        $max_execution_time = ini_get('max_execution_time');
        $allow_reference = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $allow_url_fopen = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $safe_mode = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');

		$err=0;
		if(empty($tmp['GD Version'])){
			$gd =  '<font color=red>[×]Off</font>' ;
			$err++;
		}else{
			$gd =  '<font color=green>[√]On</font> '.$tmp['GD Version'];
		}
		if(function_exists('mysql_connect')){
			$mysql = '<font color=green>[√]On</font>';
		}else{
			$mysql = '<font color=red>[×]Off</font>';
			$err++;
		}
		if(ini_get('file_uploads')){
			$uploadSize = '<font color=green>[√]On</font> 文件限制:'.ini_get('upload_max_filesize');
		}else{
			$uploadSize = '禁止上传';
		}
		if(function_exists('session_start')){
			$session = '<font color=green>[√]On</font>' ;
		}else{
			$session = '<font color=red>[×]Off</font>';
			$err++;
		}
        $folder = array('/',
						'Uploads',
						'Cache',
        				'Cache/Cache',
        				'Cache/Data',
        				'Cache/Temp',
        				'Cache/Logs'
						);
        include_once ("./tmpl/step_2.html");
        exit();

case '3':

		if($_GET['testdbpwd']){
			$dbHost = $_POST['dbHost'].':'.$_POST['dbPort'];
			$conn = @mysql_connect($dbHost, $_POST['dbUser'], $_POST['dbPwd']);
			if($conn){die("1"); }else{die("");}
		}
		$scriptName = !empty ($_SERVER["REQUEST_URI"]) ?  $scriptName = $_SERVER["REQUEST_URI"] :  $scriptName = $_SERVER["PHP_SELF"];
        $rootpath = @preg_replace("/\/(I|i)nstall\/index\.php(.*)$/", "", $scriptName);
		$domain = empty ($_SERVER['HTTP_HOST']) ?  $_SERVER['HTTP_HOST']  : $_SERVER['SERVER_NAME'] ;
		$domain = $domain.$rootpath;
        include_once ("./tmpl/step_3.html");
        exit ();


case '4':

	if(intval($_GET['install'])){
			$n = intval($_GET['n']);
			$arr=array();

			$dbHost = trim($_POST['dbHost']);
			$dbPort = trim($_POST['dbPort']);
			$dbName = trim($_POST['dbName']);
			$dbHost = empty($dbPort) || $dbPort == 3306 ? $dbHost : $dbHost.':'.$dbPort;
			$dbUser = trim($_POST['dbUser']);
			$dbPwd= trim($_POST['dbPwd']);
			$dbPrefix = empty($_POST['dbPrefix']) ? 'loowei_' : trim($_POST['dbPrefix']);

			$admin_name =  trim($_POST['admin_name']);
			$admin_pwd = trim($_POST['admin_pwd']);
			
			$site_name = addslashes(trim($_POST['site_name']));
			$site_url = trim($_POST['site_url']);
			$site_url = rtrim($site_url,'/');
			$site_email = trim($_POST['site_email']);
			$seo_title = trim($_POST['seo_title']);
			$seo_keywords = trim($_POST['seo_keywords']);
			$seo_description = trim($_POST['seo_description']);
			$conn = @ mysql_connect($dbHost, $dbUser, $dbPwd);
			if(!$conn){
				$arr['msg'] = "连接数据库失败!";
				echo json_encode($arr);exit;
			}
			mysql_query("SET NAMES 'utf8'");
			$version = mysql_get_server_info($conn);
			if($version < 4.1){
				$arr['msg'] = '数据库版本太低!';
				echo json_encode($arr);exit;
			}

			if(!mysql_select_db($dbName, $conn)){
				if(!mysql_query("CREATE DATABASE IF NOT EXISTS `".$dbName."`;", $conn)){
					$arr['msg'] = '数据库 '.$dbName.' 不存在，也没权限创建新的数据库！';
					echo json_encode($arr);exit;
				}else{
					$arr['n']=0;
					$arr['msg'] = "成功创建数据库:{$dbName}<br>";
					echo json_encode($arr);exit;
				}
			}

			//读取数据文件
			$sqldata = file_get_contents('./loowei.sql');
			$sqlFormat = sql_split($sqldata, $dbPrefix);


			/**
			执行SQL语句
			*/
			$counts =count($sqlFormat);
			
			if($n < $counts) {
				$sql = trim($sqlFormat[$n]);
				$n++;

				if (strstr($sql, 'CREATE TABLE')){
					preg_match('/CREATE TABLE `([^ ]*)`/', $sql, $matches);
					mysql_query("DROP TABLE IF EXISTS `$matches[1]");
					$ret = mysql_query($sql);
					if($ret){
						$message =  '<font color="gree">成功创建数据表：'.$matches[1].'  </font><br />';
					}else{
						$message =  '<font  color="red">创建数据表失败：'.$matches[1].' </font><br />';
					}
					$arr=array('n'=>$n,'msg'=>$message);
					echo json_encode($arr); exit;
				}
			}

			if($i==999999)exit;


			$sqldata =   file_get_contents('./loowei_data.sql');
			sql_execute($sqldata, $dbPrefix);

			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$site_name' WHERE `name`='site_name'");
			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$site_url' WHERE `name`='site_url' ");
			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$site_email' WHERE `name`='site_email'");
			$site_logo = $site_url.'/Public/logo.jpg';
			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$site_logo' WHERE `name`='site_logo'");
			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$seo_title' WHERE `name`='seo_title'");
			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$seo_keywords' WHERE `name`='seo_keywords'");
			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$seo_description' WHERE `name`='seo_description'");
			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$seo_description' WHERE `name`='seo_description'");
			$site_safecode = md5($_SERVER['REQUEST_TIME']);
			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$site_safecode' WHERE `name`='site_safecode'");
			$site_create_time = $_SERVER['REQUEST_TIME'];
			mysql_query("UPDATE `{$dbPrefix}config` SET  `value` = '$site_create_time' WHERE `name`='site_create_time'");

			//读取配置文件，并替换真实配置数据
			$strConfig = file_get_contents('./db.php');
			$strConfig = str_replace('#DB_HOST#', $dbHost, $strConfig);
			$strConfig = str_replace('#DB_NAME#', $dbName, $strConfig);
			$strConfig = str_replace('#DB_USER#', $dbUser, $strConfig);
			$strConfig = str_replace('#DB_PWD#', $dbPwd, $strConfig);
			$strConfig = str_replace('#DB_PORT#', $dbPort, $strConfig);
			$strConfig = str_replace('#DB_PREFIX#', $dbPrefix, $strConfig);
			@file_put_contents('../Conf/db.php', $strConfig);
 
 			//插入管理员
			$admin_pwd = md5($admin_pwd);
			$admin_last_time = $_SERVER['REQUEST_TIME'];
			$admin_last_ip = ip2long(get_client_ip());
			$query = "INSERT INTO `{$dbPrefix}admin` (`admin_name`,`admin_pwd`,`admin_realname`,`admin_email`,`admin_qq`,`admin_phone`,`admin_login_count`,`admin_last_time`,`admin_last_ip`,`admin_level`,`admin_status`) VALUES('$admin_name','$admin_pwd','超级管理员','$site_email','123456','12345678901','0','$admin_last_time','$admin_last_ip','1','1')";
			mysql_query($query);
			$query = "INSERT INTO `{$dbPrefix}user` (`nickname`,`email`,`pwd`,`avatar_suffix`,`status`,`reg_time`,`reg_ip`,`last_time`,`last_ip`,`emailstatus`) VALUES('$admin_name','$site_email','$admin_pwd','','1','$admin_last_time','$admin_last_ip','$admin_last_time','$admin_last_ip','1')";
			mysql_query($query);
			$message  = '成功添加管理员<br/>成功写入配置文件<br/>安装完成．';
			$arr=array('n'=>999999,'msg'=>$message);
			echo json_encode($arr);exit;
	}

	 include_once ("./tmpl/step_4.html");
	 exit();

case '5':
	$scriptName = !empty ($_SERVER["REQUEST_URI"]) ?  $scriptName = $_SERVER["REQUEST_URI"] :  $scriptName = $_SERVER["PHP_SELF"];
    $rootpath = @preg_replace("/\/(I|i)nstall\/index\.php(.*)/", "", $scriptName);
	$domain = empty ($_SERVER['HTTP_HOST']) ?  $_SERVER['HTTP_HOST']  : $_SERVER['SERVER_NAME'] ;
	$domain = $domain.$rootpath;
	include_once ("./tmpl/step_5.html");
	@touch('../install.lock');
    exit ();
}
function dir_delete($dir) {
	$dir = _dir_path($dir);
	if (!is_dir($dir)) return FALSE;
	$list = glob($dir.'*');
	foreach((array)$list as $v) {
		is_dir($v) ? dir_delete($v) : @unlink($v);
	}
    return @rmdir($dir);
}
function testwrite( $d )
{
	$tfile = "_test.txt";
	$fp = @fopen( $d."/".$tfile, "w" );
	if ( !$fp )
	{
		return false;
	}
	fclose( $fp );
	$rs = @unlink( $d."/".$tfile );
	if ( $rs )
	{
		return true;
	}
	return false;
}

function sql_execute($sql,$tablepre) {
    $sqls = sql_split($sql,$tablepre);
	if(is_array($sqls))
    {
		foreach($sqls as $sql)
		{
			if(trim($sql) != '')
			{
				mysql_query($sql);
			}
		}
	}
	else
	{
		mysql_query($sqls);
	}
	return true;
}

function  sql_split($sql,$tablepre) {

	if($tablepre != "loowei_") $sql = str_replace("loowei_", $tablepre, $sql);
	$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8",$sql);

	if($r_tablepre != $s_tablepre) $sql = str_replace($s_tablepre, $r_tablepre, $sql);
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query)
	{
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		$queries = array_filter($queries);
		foreach($queries as $query)
		{
			$str1 = substr($query, 0, 1);
			if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
		}
		$num++;
	}
	return $ret;
}

function _dir_path($path){
	$path = str_replace('\\', '/', $path);
	if(substr($path, -1) != '/') $path = $path.'/';
	return $path;
}
// 获取客户端IP地址
function get_client_ip(){
    static $ip = NULL;
    if ($ip !== NULL) return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos =  array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip   =  trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}

?>