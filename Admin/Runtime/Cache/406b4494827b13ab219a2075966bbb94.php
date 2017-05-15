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
</script></head><body width="100%"><div class="mainbox"><form id="myform" method='post' name="login" target="formSubmit" action="<?php echo U('Collect/haha_modify');?>"><table cellpadding=0 cellspacing=0 class="table_form" width="100%"><tr><td width="140">采集哈哈:</td><td><input type="text" class="input-text" name="max_id" size="20" value="40"/>页数   <input TYPE="submit"  name="dosubmit" value="开始采集" class="button" style="margin:0 50px;"/>采集地址：http://www.haha.mx/good/day，建议24小时采集一次！</td></tr></table></form><form id="myform" method='post' name="login" target="formSubmit" action="<?php echo U('Collect/qiubai_modify');?>" style="margin-top:20px;"><table cellpadding=0 cellspacing=0 class="table_form" width="100%"><tr><td width="140">采集糗百:</td><td><input type="text" class="input-text" name="max_id" size="20" value="35"/>页数   <input TYPE="submit"  name="dosubmit" value="开始采集" class="button" style="margin:0 50px;"/>采集地址：http://www.qiushibaike.com/8hr，建议8小时采集一次！</td></tr></table></form><div><div style="margin-top:30px;font-size:14px;margin-bottom:10px;">采集执行结果：</div><iframe name="formSubmit" id="formSubmit" style="width:500px;"></iframe></div></body></html>