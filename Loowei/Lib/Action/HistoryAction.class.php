<?php
/**
 * 穿越页面
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class HistoryAction extends BaseAction {
    public function index(){
		if($_GET['year']){
			$year = $this->_get('year');
			$month = $this->_get('month');
			$day = $this->_get('day');
		}else{
			$date = date("Ymd",strtotime("-1 day"));
			$year = substr($date,0,4);
			$month = substr($date,4,2);
			$day = substr($date,6,2);
		}
		$this->assign('time',$year.'年'.$month.'月'.$day.'日');
		$startTime = mktime(0,0,0,$month,$day,$year);
		$stopTime  = mktime(23,59,59,$month,$day,$year);
		$condition_artile['time'] = array(array('egt',$startTime),array('elt',$stopTime));
		$condition_artile['status'] = 1;
		$database_article = D('Article');
		$count = $database_article->where($condition_artile)->count('id');
		import('@.ORG.Historypage');
		$p = new Page($count,$year,$month,$day);
		$prefix = C('DB_PREFIX');
		$article_list = D('')->field('`a`.*,`u`.`nickname`')->Table(array($prefix.'article'=>'a',$prefix.'user'=>'u'))->where("`a`.`uid`=`u`.`uid` AND `a`.`time`>='$startTime' AND `a`.`time`<='$stopTime' AND `a`.`status`=1")->limit($p->firstRow.','.$p->page_rows)->order('`a`.`id` DESC')->select();
		if(!empty($article_list)){
			foreach($article_list as $key=>$value){
				if(!empty($value['tag'])){
					$temp_tags = explode(' ',$value['tag']);
					foreach($temp_tags as $k=>$v){
						if($GLOBALS['config']['site_html']){
							$tags[$k]['url'] = $GLOBALS['config']['site_url'].'/tag/'.urlencode($v);
						}else{
							$tags[$k]['url'] = U('Tag/index?tag='.urlencode($v));
						}
						$tags[$k]['tag'] = $v;
					}
					$article_list[$key]['tags'] = $tags;
				}
				if(!empty($value['pic_url']) && strpos(strtolower($value['pic_url']),'http://') === false){
					$article_list[$key]['pic_url'] = $this->config['site_url'].'/Uploads/Images/'.$value['pic_url'];
				}
				if($this->config['site_html']){
					$article_list[$key]['url'] = $article_list[$key]['full_url'] = $this->config['site_url'].'/article/'.$value['id'];
					$article_list[$key]['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$value['uid'].'.jpg';
					$article_list[$key]['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$value['uid'].'.jpg';
					$article_list[$key]['user_url'] = $this->config['site_url'].'/users/'.$value['uid'].'/';
				}else{
					$article_list[$key]['full_url'] = $GLOBALS['config']['site_url'].'/index.php?m=article&a=index&id='.$value['id'];
					$article_list[$key]['url'] = U('Article/index?id='.$value['id']);
					$article_list[$key]['avatar_m'] = U('Avatar/index?size=m&uid='.$value['uid']);
					$article_list[$key]['avatar_s'] = U('Avatar/index?size=s&uid='.$value['uid']);
					$article_list[$key]['user_url'] = U('Users/index?uid='.$value['uid']);
				}
			}
			$this->assign('article_list',$article_list);
		}
		$this->assign('count',$count);
		$this->assign('pagebar',$p->show());
		/*输出日历*/
		$startYear = date('Y',$this->config['site_create_time']);
		$startMonth = date('m',$this->config['site_create_time']);
		$startDay = date('d',$this->config['site_create_time']);
		import("@.ORG.Calendar");
		$cal = new Calendar($year,$month,$day,$startYear,$startMonth,$startDay);
		$this->assign('getMonth',$cal->getMonth());
		$this->assign('getDay',$cal->getDay($startTime));
		$this->display();
    }
}