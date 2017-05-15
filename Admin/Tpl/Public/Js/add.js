function check_add(){
	var title = $.trim($('#add_title').val());
	if(title==''){
		$('#error').html('请填写糗事标题！');
		$('#add_title').val('').focus();
		return false;
	}else{
		$('#add_title').val(title);
	}
	var content = $.trim($('#add_content').val());
	if(content==''){
		$('#error').html('请填写糗事内容！');
		$('#add_content').val('').focus();
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