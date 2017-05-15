<?php
/**
 * 内容页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class ArticleAction extends BaseAction {
    public function index(){
		$id = $this->_get('id','intval');
		$prefix = C('DB_PREFIX');
		$article = D('')->field('`a`.*,`u`.`nickname`')->Table(array($prefix.'article'=>'a',$prefix.'user'=>'u'))->where("`a`.`uid`=`u`.`uid` AND `a`.`id`='$id' AND `a`.`status`=1")->find();
		if(!empty($article)){
			if(!empty($article['tag'])){
				$temp_tags = explode(' ',$article['tag']);
				foreach($temp_tags as $k=>$v){
					if($GLOBALS['config']['site_html']){
						$tags[$k]['url'] = $GLOBALS['config']['site_url'].'/tag/'.urlencode($v);
					}else{
						$tags[$k]['url'] = U('Tag/index?tag='.urlencode($v));
					}
					$tags[$k]['tag'] = $v;
				}
				$article['tags'] = $tags;
			}
			if(!empty($article['pic_url']) && strpos(strtolower($article['pic_url']),'http://') === false){
				$article['pic_url'] = $this->config['site_url'].'/Uploads/Images/'.$article['pic_url'];
			}
			
			$database_article = D('Article');
			$condition_prev_article['status'] = 1;
			$condition_prev_article['id'] = array('lt',$article['id']);
			$prev_article = $database_article->field(true)->where($condition_prev_article)->order('`id` DESC')->find();
			
			$condition_next_article['status'] = 1;
			$condition_next_article['id'] = array('gt',$article['id']);
			$next_article = $database_article->field(true)->where($condition_next_article)->order('`id` ASC')->find();
			
			
			if($this->config['site_html']){
				$article['url'] = $article['full_url'] = $this->config['site_url'].'/article/'.$article['id'];
				$article['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$article['uid'].'.jpg';
				$article['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$article['uid'].'.jpg';
				$article['user_url'] = $this->config['site_url'].'/users/'.$article['uid'].'/';
				
				if(!empty($prev_article)){
					$prev_article['url'] = $this->config['site_url'].'/article/'.$prev_article['id'];
				}
				if(!empty($next_article)){
					$next_article['url'] = $this->config['site_url'].'/article/'.$next_article['id'];
				}
			}else{
				$article['full_url'] = $GLOBALS['config']['site_url'].'/index.php?m=article&a=index&id='.$article['id'];
				$article['url'] = U('Article/index?id='.$article['id']);
				$article['avatar_m'] = U('Avatar/index?size=m&uid='.$article['uid']);
				$article['avatar_s'] = U('Avatar/index?size=s&uid='.$article['uid']);
				$article['user_url'] = U('Users/index?uid='.$article['uid']);
				
				if(!empty($prev_article)){
				$prev_article['url'] = U('Article/index?id='.$prev_article['id']);
				}
				if(!empty($next_article)){
					$next_article['url'] = U('Article/index?id='.$next_article['id']);
				}
			}
			
			$table = substr($id,-1);
			$reply_table = $prefix.'reply_'.$table;
			$comments = D('')->field('`r`.*,`u`.`nickname`')->Table(array($reply_table=>'r',$prefix.'user'=>'u'))->where("`r`.`article_id`='$id' AND `r`.`reply_status`='1' AND `r`.`uid`=`u`.`uid`")->order('`r`.`reply_sort` ASC')->select();
			
			foreach($comments as $key=>$value){
				if($this->config['site_html']){
					$comments[$key]['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$value['uid'].'.jpg';
					$comments[$key]['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$value['uid'].'.jpg';
					$comments[$key]['user_url'] = $this->config['site_url'].'/users/'.$value['uid'].'/';
				}else{
					$comments[$key]['avatar_m'] = U('Avatar/index?size=m&uid='.$value['uid']);
					$comments[$key]['avatar_s'] = U('Avatar/index?size=s&uid='.$value['uid']);
					$comments[$key]['user_url'] = U('Users/index?uid='.$value['uid']);
				}
				$comments[$key]['reply_ip'] = long2ip($value['reply_ip']);
			}
		
			$this->assign('article',$article);
			$this->assign('comments',$comments);
			$this->assign('prev_article',$prev_article);
			$this->assign('next_article',$next_article);
		}else{
			$this->assign('jumpUrl',$this->config['site_url']);
			$this->error('此内容不存在！换个吧。');
		}
		$this->display();
    }
}