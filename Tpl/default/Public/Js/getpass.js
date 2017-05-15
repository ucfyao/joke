$(function(){
	$('.menu .drpmdown-list').css({left:($(window).width()-960)/2+421});
	$('input[placeholder],textarea[placeholder]').placeholder();
	$('.main .col_l .block').mouseover(function(){
		$(this).find('.detail').show();
	}).mouseout(function(){
		$(this).find('.detail').hide();
	});
	var hot_bgcolor = $('.hot .dropdown').css('background-color');
	$('.hot').mouseover(function(){
		$(this).find('.dropdown').css({'background-color':'#F8F2E2','border':'1px solid #EED6B0','border-bottom':'none'});
		$(this).find('.drpmdown-list').show();
	}).mouseout(function(){
		$(this).find('.dropdown').css({'background-color':hot_bgcolor,'border':'none'});
		$(this).find('.drpmdown-list').hide();
	});

	//设置表单验证
	$('#setpass-form').submit(function(){
		$('#setpass_password').val($.trim($('#setpass_password').val()));
		$('#setpass_re_password').val($.trim($('#setpass_re_password').val()));
		if($('#setpass_password').val().length < 6){
			alert('密码最少6位！');$('#setpass_password').focus();return false;
		}else if($('#setpass_password').val() != $('#setpass_re_password').val()){
			alert('两次密码不一致！');return false;
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