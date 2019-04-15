<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<title>后台登录_<?php echo ($config["site_name"]); ?> - Powered by loowei.com</title>
		<link rel="stylesheet" type="text/css" href="<?php echo ($TMPL_PUBLIC); ?>Css/login.css"/>
		<script src="./Public/Js/jquery.js"></script>
		<script src="<?php echo ($TMPL_PUBLIC); ?>Js/login.js"></script>
	</head>
	<body>
		<div class="wrap">
			<h1><a href="<?php echo U('Login/index');?>">后台登录_<?php echo ($config["site_name"]); ?> - Powered by loowei.com</a></h1>
			<form method="post" id="form_login" action="<?php echo U('Login/check');?>" autoComplete="off">
				<div class="login">
					<ul>
						<li>
							<input class="input" id="admin_name" name="admin_name" type="text" placeholder="帐号" title="帐号" />
						</li>
						<li>
							<input class="input" id="admin_pwd" type="password" name="admin_pwd" placeholder="密码" title="密码" />
						</li>
					</ul>
					<button type="submit" name="submit" class="btn">登录</button>
				</div>
			</form>
		</div>
	</body>
</html>