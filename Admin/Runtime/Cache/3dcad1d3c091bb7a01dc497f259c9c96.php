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
</script></head><body width="100%"><div class="mainbox"><script type="text/javascript">function confirm_deleteall(){	var dialog = $.dialog({		title:'消息提醒：',		icon:'questions',		content:'您确定要删除吗？',		lock:true,		fixed: true,		opacity:'0.5',		ok:function (){			dialog.close();			return false;		}	});}</script><div id="nav" class="mainnav_title"><ul><a href="<?php echo U('Tag/index');?>" class="on">标签列表</a>|		<a href="<?php echo U('Tag/add');?>">添加标签</a></ul></div><form name="myform" id="myform" action="<?php echo U('Tag/modify');?>" method="post"><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th align="left" width="120">标签排序</th><th align="left">标签内容</th><th align="center">管理操作</th></tr></thead><tbody><?php if(is_array($tag_list)): $i = 0; $__LIST__ = $tag_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td align="left" width="120"><?php echo ($vo["tag_sort"]); ?></td><td align="left"><?php echo ($vo["tag_name"]); ?></td><td align="center"><a href="<?php echo U('Tag/edit?tag_id='.$vo['tag_id']);?>">编辑</a> | <a href='javascript:confirm_delete("<?php echo U('Tag/del?tag_id='.$vo['tag_id']);?>")'>删除</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table></div></form></body></html>