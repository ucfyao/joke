$(function(){
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
				if(data != -1){
					data = $.parseJSON(data);
					$('#reply_'+data.article_id).append('<div id="comment-'+data.reply_id+'" class="comment-block"><div class="avatars"><img src="'+site_url+'/avatar/s/'+data.uid+'.jpg" alt="'+data.nickname+'"/></div><div class="replay"><a href="/users/'+data.uid+'" class="userlogin" target="_blank">'+data.nickname+'</a><span class="body">'+data.reply_content+'</span></div><div class="report">'+data.reply_sort+'</div></div>');
					form.parent().remove();
					$('#comments-'+data.article_id).html(parseInt($('#comments-'+data.article_id).html())+1);
				}else{
					alertbox('回复失败：','回复时出现错误，请重试！');
				}
			});
		}
		return false;
	});
});