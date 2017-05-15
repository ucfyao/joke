<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset={:C('DEFAULT_CHARSET')}" /><title>网站后台管理 Powered by loowei.com</title><script type="text/javascript">if(self==top){window.top.location.href = '<?php echo U("Login/index");?>';}</script><link rel="stylesheet" type="text/css" href="<?php echo ($TMPL_PUBLIC); ?>Css/style.css" /><script type="text/javascript" src="./Public/Js/jquery.js"></script><script type="text/javascript" src="./Public/Js/jquery.artDialog.js?skin=aero"></script><script type="text/javascript" src="./Public/Js/iframeTools.js"></script><script type="text/javascript" src="./Public/Js/jquery.form.js"></script><script type="text/javascript" src="./Public/Js/jquery.validate.js"></script><script type="text/javascript" src="./Public/Js/date/WdatePicker.js"></script><script type="text/javascript" src="./Public/Js/jquery.colorpicker.js"></script><script type="text/javascript">	function confirm_delete(url){
		var dialog = $.dialog({
			title:'消息提醒：',
			icon:'question',
			content:'您确定要删除吗？删除后不可恢复，请谨慎操作。',
			lock:true,
			fixed:true,
			opacity:'0.5',
			ok:function (){
				location.href = url;
				return false;
			},
			cancel:true
		});
	}
</script></head><body width="100%"><div class="mainbox"><div id="nav" class="mainnav_title"><ul><a href="<?php echo U('Config/index');?>" class="on">站点配置</a>|
		<a href="<?php echo U('Config/sys');?>">系统参数</a>|
		<a href="<?php echo U('Config/user');?>">会员设置</a>|
		<a href="<?php echo U('Config/mail');?>">系统邮箱</a>|
		<a href="<?php echo U('Config/water');?>">上传图片配置</a>|
		<a href="<?php echo U('Config/other');?>">第三方配置</a>|
		<a href="<?php echo U('Config/mobile');?>">手机版设置</a>|
		<a href="<?php echo U('Config/collect');?>">采集设置</a></ul></div><form id="myform" method='post' name="login" action="<?php echo U('Config/amend');?>"><table cellpadding=0 cellspacing=0 class="table_form" width="100%"><tr><th width="140">网站名称:</th><td><input type="text" class="input-text" name="site_name" value="<?php echo ($config["site_name"]); ?>" size="60"/></td></tr><tr><th width="140">网站网站:</th><td><input type="text" class="input-text" name="site_url" value="<?php echo ($config["site_url"]); ?>" size="60"/></td></tr><tr><th width="140">网站LOGO:</th><td><input type="text" class="input-text" name="site_logo" value="<?php echo ($config["site_logo"]); ?>" size="60"/></td></tr><tr><th width="140">站长邮箱:</th><td><input type="text" class="input-text" name="site_email" value="<?php echo ($config["site_email"]); ?>" size="60"/></td></tr><tr><th width="140">网站备案ICP号:</th><td><input type="text" class="input-text" name="site_icp" value="<?php echo ($config["site_icp"]); ?>" size="60"/></td></tr><tr><th width="140">网站SEO标题:</th><td><input type="text" class="input-text" name="seo_title" value="<?php echo ($config["seo_title"]); ?>" size="80"/><font color="red">一般不超过80个字符</font></td></tr><tr><th width="140">网站SEO关键词:</th><td><input type="text" class="input-text" name="seo_keywords" value="<?php echo ($config["seo_keywords"]); ?>" size="80"/><font color="red">一般不超过100个字符</font></td></tr><tr><th width="140">网站SEO描述:</th><td><textarea rows="4" cols="80" name="seo_description"><?php echo ($config["seo_description"]); ?></textarea><font color="red">一般不超过200个字符</font></td></tr><tr><th width="140">网站底部信息:</th><td><textarea rows="6" cols="80" name="site_footer"><?php echo ($config["site_footer"]); ?></textarea><font color="red">可填写统计代码等HTML代码，前台隐藏不可见！</font></td></tr></table><div class="btn"><input TYPE="submit"  name="dosubmit" value="提交" class="button" /><input type="reset"  value="取消" class="button" /></div></form></body></html>