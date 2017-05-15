var id = 0;
var vote = false;
$(function(){
	ticket();
});
function ticket_vote(action){
	if(!vote){
		alert('别点急了喔，慢慢看嘛！');
	}else{
		vote = false;
		$.post(site_url+'/index.php?m=ticket&a=inspect',{id:id,action:action},function(data){
			ticket();
		});
	}
}
function ticket(){
	$.post(site_url+'/index.php?m=ticket&a=fetch',{id:id},function(data){
		$('#pic').html('');
		$('#tag').html('');
		if(data != 'null'){
			data = $.parseJSON(data);
			id = data.id;
			$('#content').html(data.content);
			if(data.type ==1){
				$('#pic').html('<img src="'+data.pic_url+'"/><p style="color:gray;">点击图片可看大图</p>');
			}else if(data.type == 2){
				$('#pic').html('<img src="'+data.pic_url+'"/><p style="color:gray;">点击图片可看视频</p>');
				$('#enlarge').attr('video',data.video);
			}
			if(data.tag != ''){
				$('#tag').html('<b>标签：</b>'+data.tag);
			}
			vote = true;
		}else{
			$('#content').html('没有新的内容啦！');	
		}
	});
}
function tobig(){
	var video = $('#enlarge').attr('video');
	if(video == undefined){
		var content = '<img src="'+$('#pic img').attr('src')+'" />';
		alertto('查看大图',content);
	}else{
		var content = '<embed src="'+video+'" type="application/x-shockwave-flash" width="580" height="420" allowfullscreen="true" allownetworking="all" allowscriptaccess="always" />';
		alertto('查看视频',content);
	}
}
function alertto(title,content){
	var dialog = $.dialog({
		title:title,
		content:content,
		id:'ardialog',
		lock:true,
		zIndex:9999
	});
}