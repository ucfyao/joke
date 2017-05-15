<?php
/**
 * 清理缓存页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class CacheAction extends BaseAction {
    public function index(){
		$this->display();
	}
	public function cache(){
		import('ORG.Util.Dir');
		Dir::delDir('./Cache');
		$this->success('清理缓存成功！~');
	}
	public function html_8hr(){
		import('ORG.Util.Dir');
		Dir::delDir('./html/8hr');
		$this->success('清理热门页面成功！~');
	}
	public function html_late(){
		import('ORG.Util.Dir');
		Dir::delDir('./html/late');
		$this->success('清理最新页面成功！~');
	}
	public function html_hot(){
		import('ORG.Util.Dir');
		Dir::delDir('./html/hot');
		$this->success('清理精华24小时内页面成功！~');
	}
	public function html_week(){
		import('ORG.Util.Dir');
		Dir::delDir('./html/week');
		$this->success('清理精华7天内页面成功！~');
	}
	public function html_month(){
		import('ORG.Util.Dir');
		Dir::delDir('./html/month');
		$this->success('清理精华30天内页面成功！~');
	}
	public function html_video(){
		import('ORG.Util.Dir');
		Dir::delDir('./html/video');
		$this->success('清理视频页面成功！~');
	}
	public function html_history(){
		import('ORG.Util.Dir');
		Dir::delDir('./html/history');
		$this->success('清理历史页面成功！~');
	}
	public function html_tag(){
		import('ORG.Util.Dir');
		Dir::delDir('./html/tag');
		$this->success('清理TAG页面成功！~');
	}
	public function html_all(){
		import('ORG.Util.Dir');
		Dir::delDir('./html');
		$this->success('清理全部页面缓存！~');
	}
}