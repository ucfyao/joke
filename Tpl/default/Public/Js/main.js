$(function(){
	$('.menu .drpmdown-list').css({left:($(window).width()-960)/2+421});
	$('input[placeholder],textarea[placeholder]').placeholder();
	$('.main .col_l .block').mouseover(function(){
		$(this).find('.detail').show();
	}).mouseout(function(){
		$(this).find('.detail').hide();
	});
	var float_top = $('#box').offset().top;
	var col_l_top_height = $('.col_l').offset().top+$('.col_l').height();
	if($(window).height() > 630 && float_top+520 < col_l_top_height){
		$('#float-ad2').show();
	}
	$(window).scroll(function(){
		var scroll = $(window).scrollTop();
		if(scroll > 300){
			$('.goTop').show();
		}else{
			$('.goTop').hide();
		}
		if(scroll > float_top && scroll < col_l_top_height){
			$('#box #float').addClass('div2');
		}else{
			$('#box #float').removeClass('div2');
		}
	});
	var hot_bgcolor = $('.hot .dropdown').css('background-color');
	$('.hot').mouseover(function(){
		$(this).find('.dropdown').css({'background-color':'#F8F2E2','border':'1px solid #EED6B0','border-bottom':'none'});
		$(this).find('.drpmdown-list').show();
	}).mouseout(function(){
		$(this).find('.dropdown').css({'background-color':hot_bgcolor,'border':'none'});
		$(this).find('.drpmdown-list').hide();
	});
	$('.showVideo').click(function(){
		var video = $(this).attr('video');
		$(this).parent().html('<embed src="'+video+'" type="application/x-shockwave-flash" allowfullscreen="true" quality="high" allowNetWorking="all" allowScriptAccess="always" />');
	});
	$('.share').mouseover(function(){
		var e = $(this).closest('.bar').find('.sharebox');
		e.css('display','block');
	}).mouseout(function(){
		var e = $(this).closest('.bar').find('.sharebox');
		setTimeout(function(){e.data("hover")!=1&&e.hide()},50);
	});
	$('.sharebox').mouseleave(function(){
		$(this).data('hover',0);
		$(this).closest('.bar').find('.sharebox').css('display','none');
	}).mouseenter(function(){
		$(this).data('hover',1);
	});
	$('.comment a').click(function(){
			var id = $(this).attr('id');
			var articleid = $(this).attr('articleid');
			if($('#qiushi_'+id).html() == null){
				var showReply = $(this).html();
				$(this).html('...');
				$.getJSON(site_url+'/index.php?m=comments&a=index&article_id='+articleid,function(data){
					var reply = '<div class="comments" id="qiushi_comments-'+data.article_id+'"><div id="reply_'+data.article_id+'">';
					var comments = data.comments;
					if(comments != null){
						for(var i=0;i<comments.length;i++){
							comment = comments[i];
							reply = reply+'<div id="comment-'+comment.reply_id+'" class="comment-block"><div class="avatars"><img src="'+comment.avatar_s+'" alt="'+comment.nickname+'"/></div><div class="replay"><a href="'+comment.user_url+'" class="userlogin" target="_blank">'+comment.nickname+'</a><span class="body">'+comment.reply_content+'</span></div><div class="report">'+comment.reply_sort+'</div></div>';
						}
					}
					if(data.is_login){
						reply = reply + '</div><div class="input-block clearfix"><form method="post" action="'+site_url+'/index.php?m=comments&a=vote_to" class="login"><input type="hidden" value="'+data.article_id+'" name="article_id" id="article_id"/><textarea class="comment_input original" name="reply_content">请不要发表与本内容无关的评论，您有了账号就是有身份的人了，我们可认识您。</textarea><div class="row"><a href="javascript:close_comment('+data.article_id+');" class="closebtn">收回评论 ↑</a><button type="submit" id="comment_submit">发表</button></div></form></div></div>';
					}else{
						reply = reply + '</div><div class="input-block "><div><a href="javascript:close_comment('+data.article_id+');" class="closebtn">收回评论 ↑</a><button type="button" id="comment_submit" onclick="loginbox();">登录</button>后才能回复</div></div></div>';
					}
					$('#qiushi_block_'+data.article_id).append(reply);
					$('#'+id).html(showReply);
					$('.comment_input').focus(function(){
						if($(this).val()=='请不要发表与本内容无关的评论，您有了账号就是有身份的人了，我们可认识您。'){
							$(this).val('');
						}
						$(this).removeClass('original');
					}).blur(function(){
						if($(this).val()==''){
							$(this).val('请不要发表与本内容无关的评论，您有了账号就是有身份的人了，我们可认识您。');
							$(this).addClass('original');
						}
					});
					$('.comments .input-block form').submit(function(){
						var form = $(this);
						var input = form.find('.comment_input');
						var content = $.trim(input.val());
						if(content == '' || content == '请不要发表与本内容无关的评论，您有了账号就是有身份的人了，我们可认识您。'){
							alert('请填写回复内容！');
							input.focus();
						}else{
							form.find('button').attr('disabled','disabled').html('发表中...');
							$.post(site_url+'/index.php?m=comments&a=reply_to',form.serialize(),function(data){
								data = $.parseJSON(data);
								$('#reply_'+data.article_id+' .no-reply').remove();
								$('#reply_'+data.article_id).append('<div id="comment-'+data.reply_id+'" class="comment-block"><div class="avatars"><img src="'+data.avatar_s+'" alt="'+data.nickname+'"/></div><div class="replay"><a href="'+data.user_url+'" class="userlogin" target="_blank">'+data.nickname+'</a><span class="body">'+data.reply_content+'</span></div><div class="report">'+data.reply_sort+'</div></div>');
								form.parent().remove();
								$('#comments-'+data.article_id).html(parseInt($('#comments-'+data.article_id).html())+1);
							});
						}
						return false;
					});
				});
			}else{
				$('#qiushi_'+id).toggle('fast');
			}
		return false;
	});
	//登录表单验证
	$('#login-form').submit(function(){
		$('#login_login').val($.trim($('#login_login').val()));
		if($('#login_login').val().length < 3){
			alert('用户名最少3位！');$('#login_login').focus();return false;
		}else if($('#login_login').val().length > 20){
			alert('用户名最长20位！');$('#login_login').focus();return false;
		}
		$('#login_password').val($.trim($('#login_password').val()));
		if($('#login_password').val().length < 6){
			alert('密码最少6位！');$('#login_password').focus();return false;
		}
		$.post(site_url+'/index.php?m=login&a=login',$(this).serialize(),function(data){
			if(data==1){
				window.location.reload();
			}else if(data==-1){
				alert('帐号不存在！');$('#login_login').focus();
			}else if(data==-2){
				alert('用户被禁止登录，请联系客服！');$('#login_login').focus();
			}else if(data==-3){
				alert('密码错误！');$('#login_password').focus();
			}else if(data==-4){
				alert('您是使用第三方帐号注册，并未设置密码！请使用第三方帐号登录。');
			}else if(data==-5){
				alert('您的邮箱需要验证！已向您的邮箱发送了验证邮件。');
			}
		});
		return false;
	});
	//注册表单验证
	$('#reg-form').submit(function(){
		$('#reg_login').val($.trim($('#reg_login').val()));
		if($('#reg_login').val().length < 3){
			alert('用户名最少3位！');$('#reg_login').focus();return false;
		}else if($('#reg_login').val().length > 20){
			alert('用户名最长20位！');$('#reg_login').focus();return false;
		}
		$('#reg_password').val($.trim($('#reg_password').val()));
		if($('#reg_password').val().length < 6){
			alert('密码最少6位！');$('#reg_password').focus();return false;
		}
		$('#reg_email').val($.trim($('#reg_email').val()));
		var email = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		if($('#reg_email').val().length > 40){
			alert('您这邮箱太长了吧，邮箱最多40位喔！您可以使用QQ,163等邮箱~');$('#reg_email').focus();return false;
		}else if(!email.test($('#reg_email').val())){
			alert('搞笑喔，这也算邮箱？');$('#reg_email').focus();return false;
		}
		$.post(site_url+'/index.php?m=login&a=reg',$(this).serialize(),function(data){
			if(data==1){
				window.location.reload();
			}else if(data==-1){
				alert('用户名已存在！请更换。');$('#reg_login').focus();
			}else if(data==-2){
				alert('邮箱已存在！请更换。');$('#reg_email').focus();
			}else if(data==-3){
				alert('网络异常！请重试注册。');
			}else if(data==-4){
				if($.dialog.list['regbox']){$.dialog.list['regbox'].close();}alert('验证邮箱的邮件已经发送到您刚填写的邮箱！请进入邮箱验证。\n若长时间没有收到请检查一下垃圾邮件！');
			}
		});
		return false;
	});
	//找回密码表单
	$('#fetchpass-form').submit(function(){
		$('#fetchpass_login').val($.trim($('#fetchpass_login').val()));
		if($('#fetchpass_login').val().length < 3){
			alert('用户名最少3位！');$('#fetchpass_login').focus();return false;
		}else if($('#fetchpass_login').val().length > 20){
			alert('用户名最长20位！');$('#fetchpass_login').focus();return false;
		}
		$('#fetchpass_email').val($.trim($('#fetchpass_email').val()));
		var email = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		if($('#fetchpass_email').val().length > 40){
			alert('您这邮箱太长了吧，邮箱最多40位喔！您可以使用QQ,163等邮箱~');$('#fetchpass_email').focus();return false;
		}else if(!email.test($('#fetchpass_email').val())){
			alert('搞笑喔，这也算邮箱？');$('#fetchpass_email').focus();return false;
		}
		$.post(site_url+'/index.php?m=login&a=fetchpass',$(this).serialize(),function(data){
			if(data==1){
				if($.dialog.list['fetchpassbox']){$.dialog.list['fetchpassbox'].close();}
				alert('找回密码的邮件已经发送到您刚填写的邮箱！请进入邮箱验证。\n若长时间没有收到请检查一下垃圾邮件！');
			}else if(data==-1){
				alert('用户名和邮箱不匹配！');$('#fetchpass_login').focus();
			}
		});
		return false;
	});
	//发贴更换类型
	$('#m-add-tab a').click(function(){
		$(this).addClass('current').siblings('a').removeClass('current');
		var name = $(this).attr('name');
		switch(name){
			case 'joke':
				$('.new_article .picbox,.new_article .videobox').hide();
				break;
			case 'pic':
				$('.new_article .videobox').hide();$('.new_article .picbox').show();break;
			default:
				$('.new_article .picbox,.new_article .videobox').show();
		}
	});
});
//加入收藏
function add_Favorite(href,title){
	try{
		if(window.sidebar && 'object' == typeof( window.sidebar ) && 'function' == typeof( window.sidebar.addPanel)){
			window.sidebar.addPanel(title,href,'');
		}else if(document.all && 'object' == typeof( window.external)){
			window.external.addFavorite(href,title);
		}else{
			alertbox('收藏失败：','您使用的浏览器不支持此功能，请按“Ctrl + D”键手工加入收藏！');
		}
	}catch(e){
		alertbox('收藏失败：','您使用的浏览器不支持此功能，请按“Ctrl + D”键手工加入收藏！');
	}
}
function alertbox(title,content,icon,width,height){
	var dialog = $.dialog({
		title:title,
		icon:icon,
		content:content,
		width:width,
		height:height,
		lock:true,
		fixed: true,
		opacity:'0.7',
		ok:function (){
			dialog.close();
			return false;
		}
	});
}
function loginbox(){
	if($.dialog.list['regbox']){$.dialog.list['regbox'].close();}
	if($.dialog.list['fetchpassbox']){$.dialog.list['fetchpassbox'].close();}
	var login_dialog = $.dialog({
		title:'登录：',
		content:document.getElementById('loginbox'),
		lock:true,
		fixed: true,
		id:'loginbox',
		opacity:'0.7',
		ok:false
	});
}
function regbox(){
	if($.dialog.list['loginbox']){$.dialog.list['loginbox'].close();}
	if($.dialog.list['fetchpassbox']){$.dialog.list['fetchpassbox'].close();}
	var reg_dialog = $.dialog({
		title:'注册：',
		content:document.getElementById('regbox'),
		lock:true,
		fixed: true,
		id:'regbox',
		opacity:'0.7',
		ok:false
	});
}
function fetchpassbox(){
	if($.dialog.list['regbox']){$.dialog.list['regbox'].close();}
	if($.dialog.list['loginbox']){$.dialog.list['loginbox'].close();}
	var reg_dialog = $.dialog({
		title:'找回密码：',
		content:document.getElementById('fetchpassbox'),
		lock:true,
		fixed: true,
		id:'fetchpassbox',
		opacity:'0.7',
		ok:false
	});
}
function close_comment(id){
	$('#comments-'+id).removeClass('voted');
	$('#qiushi_comments-'+id).hide('fast');
	$('#comments-'+id).data('show',0);
}
function checkContentLength(){
	var id = $('#add_content');
	var content = id.val();
	var length = content.length;
	if(length>500){
		$('#length').html('<h3 style="color:red">已经超出文本长度限制，您需要删除'+(length-500)+'个字</h3>').css('display','block');
	}else{
		$('#length').html('<h3>您还可以再输入'+(500-length)+'个字</h3>').css('display','block');
	}	
	if(window.localStorage){window.localStorage.setItem('article',content);}
}
function check_add(){
	if($('#add_read').val() != 1){
		$('#error').html('请阅读并同意投稿下方的"投稿须知"');
		return false;
	}
	var content = $.trim($('#add_content').val());
	if(content==''){
		$('#error').html('请填写糗事内容！');
		$('#add_content').val('').focus();
		return false;
	}else if(content.length>500){
		$('#error').html('糗事内容过多！');
		$('#add_content').focus();
		return false;
	}else{
		$('#add_content').val(content);
	}
	var video = $.trim($('#add_video').val());
	if(video != '' && video.indexOf('http')<0){
		$('#error').html('若发布视频，则请正确填写视频地址！');
		$('#add_video').focus();
		return false;
	}else{
		$('#add_video').val(video);
	}
	var pic = $('#add_pic').val();
	if(video != '' && pic == ''){
		$('#error').html('若发布视频，则请发布与此视频相关图片做为缩略图！');
		$('#add_video').focus();
		return false;
	}
	
	var tag = $.trim($('#add_tag').val());
	if(tag != '' && tag.split(' ').length >3){
		$('#error').html('最多3个标签，用空格分隔！');
		$('#add_tag').focus();
		return false;
	}else{
		$('#add_tag').val(tag);
	}
}
function qiushi_vote(id,action){
	if(action==1){
		$('#up-'+id).html(parseInt($('#up-'+id).html())+1).addClass('voted');
	}else{
		$('#dn-'+id).html(parseInt($('#dn-'+id).html())-1).addClass('voted');
	}
	$('#up-'+id+',#dn-'+id).attr('href','javascript:;');
	$.post(site_url+'/index.php?m=comments&a=vote_to',{id:id,action:action});
}