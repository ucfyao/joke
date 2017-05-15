$(function(){
	$('.menu .drpmdown-list').css({left:($(window).width()-960)/2+421});
	$('input[placeholder],textarea[placeholder]').placeholder();
	$('#to_top').css({top:$(window).height()-40});
	var $container = $('#pic_main');
	var page = 2;
	var is_load = 0;
	$container.masonry({
		itemSelector : '.pic_item'
	});
	$('.hot').mouseover(function(){
		$(this).find('.dropdown').css({'background-color':'#F8F2E2','border':'1px solid #EED6B0','border-bottom':'none'});
		$(this).find('.drpmdown-list').show();
	}).mouseout(function(){
		$(this).find('.dropdown').css({'background-color':'white','border':'none'});
		$(this).find('.drpmdown-list').hide();
	});
	$(window).scroll(function(){
		if($(window).scrollTop() > 300){
			$('.goTop').show();
		}else{
			$('.goTop').hide();
		}
		if($(document).scrollTop() + $(window).height() == $(document).height()){
			if(is_load==0){
				is_load = 1;
				$('#pic_page_loading').css({display:'block',top:$('#pic_main').height()+100});
				$.getJSON(site_url+'/index.php?m=pic&a=get_pic&page='+page,function(data){
					if(!data.is_empty){
						var pic_item = '';
						for(var i=0;i<data.article.length;i++){
							var article = data.article[i];
							pic_item = pic_item + '<div class="pic_item new_item"><div><a href="/article/'+article.id+'" target="_blank" title="查看大图"><img src="'+site_url+'/Uploads/Images/'+article.pic_url+'" height="'+(article.pic_height/article.pic_width*200)+'px"/></a></div><p class="pic_content">'+article.content.substr(0,100)+'...&nbsp;&nbsp;<a href="/article/'+article.id+'#comments_'+article.id+'" target="_blank">评论('+article.reply+')</a></p>';
							if(article.is_anonymous == 0){
								pic_item = pic_item + '<dl class="user_dl"><dt class="user_avatar"><img src="'+site_url+'/index.php?m=avatar&a=index&size=s&uid='+article.uid+'" alt="'+article.nickname+'"/></dt><dd class="user_uname"><a href="/users/'+article.uid+'/" target="_blank">'+article.nickname+'</a></dd><dd class="user_time">'+timetodate(article.time)+' 发布</dd></dl>';
							}
							pic_item = pic_item + '</div>';
						}
						$container.append(pic_item);
						$container.masonry("reload").find('.new_item').removeClass('new_item');
						is_load = 0;
						page++;
						$('#pic_page_loading').hide();
					}else{
						$('#pic_page_loading').css({display:'block',top:$('#pic_main').height()+160,width:'500px','margin-left':'220px'}).html('没有图片可以加载啦！看看其他的吧。');
					}
				});
			}
		}
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
});
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
Date.prototype.pattern=function(fmt){       
     var o = {        
     "M+" : this.getMonth()+1, //月份       
     "d+" : this.getDate(), //日      
     "h+" : this.getHours() == 0 ? 12 : this.getHours(), //小时       
     "H+" : this.getHours(), //小时       
     "m+" : this.getMinutes(), //分       
     "s+" : this.getSeconds(), //秒       
     "q+" : Math.floor((this.getMonth()+3)/3), //季度       
     "S" : this.getMilliseconds() //毫秒       
     };       
     var week = {       
     "0" : "\u65e5",       
     "1" : "\u4e00",       
     "2" : "\u4e8c",       
     "3" : "\u4e09",       
     "4" : "\u56db",       
     "5" : "\u4e94",       
     "6" : "\u516d"      
     };      
     if(/(y+)/.test(fmt)){      
         fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));       
     }      
 
     if(/(E+)/.test(fmt)){       
         fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "\u661f\u671f" : "\u5468") : "")+week[this.getDay()+""]);       
     }       

     for(var k in o){       
         if(new RegExp("("+ k +")").test(fmt)){       
             fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));       
         }       
     }       
     return fmt;       
}  
function  timetodate(time){
	return  new Date(parseInt(time)*1000).pattern('yyyy-MM-dd hh时');   //"yyyy/MM/dd,hh,mm,ss"    
}
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