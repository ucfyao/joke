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
</script></head><body width="100%"><div class="mainbox"><h1>测试版-未公开发行版</h1><form method="post" action="<?php echo U('Article/addVideo');?>"><table cellpadding=0 cellspacing=0 class="table_form" width="100%"><tr>
					视频网址：<input name="value" type="text" class="input-text" value="<?php echo ($value); ?>" size="80"/><select name="type"><?php if(is_array($all)): $i = 0; $__LIST__ = $all;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo); ?>" <?php if($type == $vo){ ?>selected="selected"<?php } ?>><?php echo ($key); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select><input type="submit" class="button" value="开始获取"/></tr></table></form><br/><br/><?php if($article && !empty($article['video'])){ ?><h1>以下为采集到的内容</h1><form method="post" action="<?php echo U('Article/submitaddVideo');?>"><table cellpadding=0 cellspacing=0 class="table_form" width="100%"><tr><td width="15%">标题：</td><td><input type="text" name="title" class="input-text" value="<?php echo ($article["title"]); ?>" size="60"/></td></tr><tr><td width="15%">内容：</td><td><input type="text" name="content" class="input-text" value="<?php echo ($article["content"]); ?>" size="60"/></td></tr><?php if($article['pic_large']){ ?><tr><td width="15%">大图片：</td><td><img src="<?php echo ($article["pic_large"]); ?>"/><input type="hidden" name="pic_large" value="<?php echo ($article["pic_large"]); ?>"/></td></tr><?php } if($article['pic_small']){ ?><tr><td width="15%">小图片：</td><td><img src="<?php echo ($article["pic_small"]); ?>"/><input type="hidden" name="pic_small" value="<?php echo ($article["pic_small"]); ?>"/></td></tr><?php } ?><tr><td width="15%">图片选择：</td><td><input type="radio" name="pic_type" value="1" <?php if($article['pic_large']){ ?>checked="checked"<?php } ?>/>使用大图&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="pic_type" value="2" <?php if(!$article['pic_large']){ ?>checked="checked"<?php } ?>/>使用小图
						</td></tr><tr><td width="15%">视频地址：</td><td><input type="text" name="video" value="<?php echo ($article["video"]); ?>" size="60"/></td></tr><?php if($article['tag']){ ?><tr><td width="15%">Tag：</td><td><input type="text" name="tag" value="<?php echo ($article["tag"]); ?>" size="60"/></td><?php } ?></table><div class="btn"><input TYPE="submit" name="dosubmit" value="发布" class="button" /></div></form><?php }else{ ?>
			还未开始获取视频，或视频获取失败！<?php } ?></body></html>