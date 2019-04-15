<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={:C('DEFAULT_CHARSET')}" />
<title>欢迎使用本系统</title>
<link rel="stylesheet" type="text/css" href="<?php echo ($TMPL_PUBLIC); ?>Css/style.css" /> 
<link rel="stylesheet" type="text/css" href="<?php echo ($TMPL_PUBLIC); ?>Css/main.css" /> 
<script type="text/javascript" src="./Public/Js/jquery.js"></script> 
</head>
<body width="100%">
<div id="loader" >正在加载中...</div>
<div id="result" class="result none"></div>
<div class="mainbox">
	<script type="text/javascript">if(self==top){window.top.location.href = '<?php echo U("Login/index");?>';}</script>
	<link rel='stylesheet' type='text/css' href='<?php echo ($TMPL_PUBLIC); ?>Css/main.css' />
	<div id="Profile" class="list">
		<h1><b>个人信息</b><span>Profile&nbsp; Info</span></h1>
		<ul>
			<li><span>会员名:</span><?php echo ($admin["admin_name"]); ?></li>
			<li><span>会员组:</span><?php echo ($admin["admin_type"]); ?></li>
			<li><span>最后登陆时间:</span><?php echo (date('Y-m-d H:i:s',$admin["admin_last_time"])); ?></li>
			<li><span>最后登陆IP:</span><?php echo (long2ip($admin["admin_last_ip"])); ?></li>
			<li><span>登陆次数:</span><?php echo ($admin["admin_login_count"]); ?></li>
		</ul>
	</div>
	<div id="official" class="list">
		<h1><b>授权状态</b><span>Authorization status</span></h1>
		<ul>
			<li><span>系统版本:</span><?php echo ($config["sys_version"]); ?></li>
			<li><span>授权类型:</span><b id="license">加载中...</b></li>
			<li><span>序列号:</span><b id="sn">加载中...</b></li>			
			<li><span>官方推荐1:</span><b id="recommend_1">加载中...</b></li>
			<li><span>官方推荐2:</span><b id="recommend_2">加载中...</b></li>
		</ul>
	</div> 
	<div id="system"  class="list">
		<h1><b>系统信息</b><span>System&nbsp; Info</span></h1>
		<ul>
		<?php if(is_array($server_info)): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><span><?php echo ($key); ?>:</span><?php echo ($vo); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
	<div id="system"  class="list">
		<h1><b>官方动态</b><span>Official&nbsp; Update</span></h1>
		<ul id="official_news">
			<li>加载中...</li>
		</ul>
	</div>
	<script type="text/javascript" src="http://www.loowei.com/xh/index.php?m=license&a=js&host=<?php echo ($_SERVER["SERVER_NAME"]); ?>&key=<?php echo ($config["sys_key"]); ?>&version=<?php echo (urlencode($config["sys_version"])); ?>"></script>
</body>
</html>