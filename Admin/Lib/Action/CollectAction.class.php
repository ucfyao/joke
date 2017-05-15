<?php
/**
 * 采集页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class CollectAction extends BaseAction {
    public function index(){
		$this->display();
	}
	public function haha_modify(){
		set_time_limit(180);
		//得到最高的页数和现在跑到的页数
		$now_id = !empty($_REQUEST['now_id']) ? intval($_REQUEST['now_id']) : 1;
		$max_id = intval($_REQUEST['max_id']);
		//开始采集
		if($now_id<=$max_id){
			$now_url = 'http://www.haha.mx/good/day/'.$now_id;
			if(function_exists('curl_init')){
				$ch = curl_init($now_url);
				curl_setopt($ch,CURLOPT_HEADER,0);
				curl_setopt($ch,CURLOPT_TIMEOUT,30); //设置超时限制防止死循环
				curl_setopt($ch, CURLOPT_USERAGENT, "Baiduspider+(+http://www.baidu.com/search/spider.htm)");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$content = curl_exec($ch);
				curl_close($ch);
			}elseif(function_exists('file_get_contents')){
				$content = file_get_contents($now_url);
			}else{
				exit('您的服务器同时不支持“Curl组件”和“file_get_contents方法”，无法开始采集！');
			}
			if(!empty($content)){
				preg_match_all('/<p class=\"text word-wrap\"([\s\S]+)<div class=\"pos-re\">/U',$content,$joke);
				if(!empty($joke[1])){
					foreach($joke[1] as $value){
						
						if(strrpos($value,'class="thumbnail"') === false){
							preg_match('|<p class=\"text word-wrap\">([\s\S]+)</p>|',$value,$content);
							
							if(!empty($content[1])){
								$content = str_replace("\n",'',$content[1]);
								$content = trim(strip_tags($content));
								$this->add_article($content);
							}
						}
					}
					echo 'http://www.haha.mx/good/day/'.$now_id.' 采集完毕！';
					echo '<script>window.location="./admin.php?m=collect&a=haha_modify&now_id='.($now_id+1).'&max_id='.$max_id.'"</script>';
				}else{
					echo 'http://www.haha.mx/good/day/'.$now_id.' 未分割出内容！<br/>';
					echo '<a href="./admin.php?m=collect&a=haha_modify&now_id='.$now_id.'&max_id='.$max_id.'">重新采集此页面</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<a href="./admin.php?m=collect&a=haha_modify&now_id='.($now_id+1).'&max_id='.$max_id.'">跳过此页面，采集下一页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
			}else{
				echo 'http://www.haha.mx/good/day/'.$now_id.' 未获取到内容！<br/>';
				echo '<a href="./admin.php?m=collect&a=haha_modify&now_id='.$now_id.'&max_id='.$max_id.'">重新采集此页面</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				echo '<a href="./admin.php?m=collect&a=haha_modify&now_id='.($now_id+1).'&max_id='.$max_id.'">跳过此页面，采集下一页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}else{
			exit('已完成采集。');
		}
	}
	public function qiubai_modify(){
		set_time_limit(180);
		//得到最高的页数和现在跑到的页数
		$now_id = !empty($_REQUEST['now_id']) ? intval($_REQUEST['now_id']) : 1;
		$max_id = intval($_REQUEST['max_id']);
		//开始采集
		if($now_id<=$max_id){
			$now_url = 'http://www.qiushibaike.com/8hr/page/'.$now_id;
			if(function_exists('curl_init')){
				$ch = curl_init($now_url);
				curl_setopt($ch,CURLOPT_HEADER,0);
				curl_setopt($ch,CURLOPT_TIMEOUT,30); //设置超时限制防止死循环
				curl_setopt($ch, CURLOPT_USERAGENT, "Baiduspider+(+http://www.baidu.com/search/spider.htm)");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$content = curl_exec($ch);
				curl_close($ch);
			}elseif(function_exists('file_get_contents')){
				$content = file_get_contents($now_url);
			}else{
				exit('您的服务器同时不支持“Curl组件”和“file_get_contents方法”，无法开始采集！');
			}
			
			if(!empty($content)){
				preg_match_all('/class=\"article block untagged mb15\" ([\s\S]+) class=\"stats-buttons bar clearfix\"/U',$content,$joke);
				
				if(!empty($joke[1])){
					foreach($joke[1] as $value){
						echo "<pre/>";
						
						if(strrpos($value,'class="thumb"') === false){
							preg_match('|title="(.*?)">([\s\S]+)<div class="stats clearfix">|',$value,$content);
							if(!empty($content[2])){
								$content = trim(strip_tags($content[2]));
								$content = str_replace('@糗事百科','',$content);
								$content = str_replace('糗百','',$content);
								$content = str_replace('糗事百科','',$content);
								$this->add_article($content);
							}
						}
					}
					echo 'http://www.qiushibaike.com/8hr/page/'.$now_id.' 采集完毕！';
					echo '<script>window.location="./admin.php?m=collect&a=qiubai_modify&now_id='.($now_id+1).'&max_id='.$max_id.'"</script>';
				}else{
					echo 'http://www.qiushibaike.com/8hr/page/'.$now_id.' 未分割出内容！<br/>';
					echo '<a href="./admin.php?m=collect&a=qiubai_modify&now_id='.$now_id.'&max_id='.$max_id.'">重新采集此页面</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<a href="./admin.php?m=collect&a=qiubai_modify&now_id='.($now_id+1).'&max_id='.$max_id.'">跳过此页面，采集下一页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
			}else{
				echo 'http://www.qiushibaike.com/8hr/page/'.$now_id.' 未获取到内容！<br/>';
				echo '<a href="./admin.php?m=collect&a=qiubai_modify&now_id='.$now_id.'&max_id='.$max_id.'">重新采集此页面</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				echo '<a href="./admin.php?m=collect&a=qiubai_modify&now_id='.($now_id+1).'&max_id='.$max_id.'">跳过此页面，采集下一页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}else{
			exit('已完成采集。');
		}
	}
	protected function add_article($content){
		//内容
		$data_article['content'] = $content;
		if(mt_rand(0,3) != 4){
			//用户名字
			$data_article['uid'] = 1;
			//是否匿名发表
			$data_article['is_anonymous'] = 1;
		}else{
			//用户名字
			$data_article['uid'] = mt_rand(21,614);
			//是否匿名发表
			$data_article['is_anonymous'] = 0;
		}
		$data_article['type'] = 0;
		$data_article['time'] = $_SERVER['REQUEST_TIME'];
		$data_article['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
		//随机插进article或new表
		if(mt_rand(0,4) != 1){
			//顶和踩
			$data_article['upper'] = mt_rand($this->config['collect_upper_min'],$this->config['collect_upper_max']);
			$data_article['below'] = mt_rand($this->config['collect_below_min'],$this->config['collect_below_max']);
			//正则得到标题
			$content = htmlspecialchars_decode($data_article['content']);
			$content = str_replace(array('，','！','？','。','；',',','!','?','.',';','&'),',',$content);
			$content = explode(',',trim($content));
			$i = 0;
			foreach($content as $key=>$value){
				$value = trim(strip_tags($value));
				if(strlen($value) > 6){
					$title[$i] = $value;
					$i++;
				}
			}
			$key = mt_rand(0,count($title)-1);
			$data_article['title'] = array_shift(explode('&',$title[$key]));
			$database_article = D('Article');
		}else{
			$database_article = D('New');
		}
		$database_article->data($data_article)->add();
	}
}