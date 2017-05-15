<?php
/**
 * 更新Sitemap
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class SitemapAction extends Action {
    public function sitemap_baidu(){
		$this->assign('config',F('config'));
		$article = D('Article')->field(true)->order('id DESC')->limit(1000)->select();
		$this->assign('article',$article);
		$this->display();
	}
	public function sitemap(){
		$this->assign('config',F('config'));
		$article = D('Article')->field(true)->order('id DESC')->limit(1000)->select();
		$this->assign('article',$article);
		$this->display();
	}
	public function rss(){
		$this->assign('config',F('config'));
		$article = D('Article')->field(true)->order('`id` DESC')->limit(100)->select();
		$this->assign('article',$article);
		$this->display();
	}
	public function get_sitemap(){
		$config = F('config');
		$sitemap_baidu = $this->url_get_content($config['site_url'].'/admin.php?m=sitemap&a=sitemap_baidu');
		$sitemap = $this->url_get_content($config['site_url'].'/admin.php?m=sitemap&a=sitemap');
		$rss = $this->url_get_content($config['site_url'].'/admin.php?m=sitemap&a=rss');
		echo '<html><head></head><body style="background-color:white;margin:0;padding:50px;">';
		if($sitemap_baidu){
			if(file_put_contents('./sitemap_baidu.xml',$sitemap_baidu)){
				echo '百度搜索引擎的网站地图生成成功！./sitemap_baidu.xml<br/>';
			}else{
				echo '百度搜索引擎 需要的内容 没有保存成功！请重试。<br/>';
			}
		}else{
			echo '没有获取到 百度搜索引擎 需要的内容！请重试。<br/>';
		}
		if($sitemap){
			if(file_put_contents('./sitemap.xml',$sitemap)){
				echo '其他搜索引擎 的网站地图生成成功！./sitemap.xml<br/>';
			}else{
				echo '其他搜索引擎 需要的内容 没有保存成功！请重试。<br/>';
			}
		}else{
			echo '没有获取到 其他搜索引擎 需要的内容！请重试。<br/>';
		}
		if($rss){
			if(file_put_contents('./rss.xml',$rss)){
				echo 'RSS订阅源生成成功！./rss.xml';
			}else{
				echo 'RSS订阅源 需要的内容 没有保存成功！请重试。./rss.xml';
			}
		}else{
			echo '没有获取到 RSS订阅源 需要的内容！请重试。';
		}
		echo '</body></html>';
	}
	public function url_get_content($url){
		if(function_exists('curl_init')){
			$ch = curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER,0);
			curl_setopt($ch,CURLOPT_TIMEOUT,30); //设置超时限制防止死循环
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($ch);
			curl_close($ch);
		}elseif(function_exists('file_get_contents')){
			$content = file_get_contents($url);
		}else{
			exit('您的服务器同时不支持“Curl组件”和“file_get_contents方法”，无法开始采集！');
		}
		return $content;
	}
}