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
</script></head><body width="100%"><div class="mainbox"><div id="nav" class="mainnav_title"><ul><a href="<?php echo U('User/index');?>" class="on">会员列表</a></ul></div><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th align="center">帐号</th><th align="center">邮箱</th><th align="center">注册时间</th><th align="center">注册IP</th><th align="center">最后登录时间</th><th align="center">最后登录IP</th><th align="center">管理操作</th></tr></thead><tbody><?php if(is_array($user_list)){ if(is_array($user_list)): $i = 0; $__LIST__ = $user_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td align="center"><?php echo ($vo["nickname"]); ?></td><td align="center"><?php echo ($vo["email"]); ?></td><td align="center"><?php echo (date('Y-m-d H:i:s',$vo["reg_time"])); ?></td><td align="center"><?php echo (long2ip($vo["reg_ip"])); ?></td><td align="center"><?php echo (date('Y-m-d H:i:s',$vo["last_time"])); ?></td><td align="center"><?php echo (long2ip($vo["last_ip"])); ?></td><td align="center"><a href="<?php echo U('User/edit?uid='.$vo['uid']);?>">编辑</a> | <a href='javascript:confirm_delete("<?php echo U('User/del?uid='.$vo['uid']);?>")'>删除</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?><tr><td align="center" colspan="7"><?php echo ($page); ?></td></tr><?php }else{ ?><tr><td align="center" class="red" colspan="7">列表为空！</td></tr><?php } ?></tbody></table></div></body></html>