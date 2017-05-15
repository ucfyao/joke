<?php
/**
 * 标签页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class TagAction extends BaseAction {
    public function index(){
		$tag = $this->_get('tag','htmlspecialchars,urldecode');
		$tag = $this->is_utf8($tag);
		$condition_article['content|tag'] = array('like','%'.$tag.'%');
		$condition_article['status'] = 1;
		$database_article = D('Article');
		$count = $database_article->where($condition_article)->count('id');
		$this->assign('count',$count);
		$this->assign('tag',$tag);
		if($count != 0){
			import('@.ORG.Tagpage');
			$p = new Page($count,$tag);
			$prefix = C('DB_PREFIX');
			$article_list = D('')->field('a.*,`u`.`nickname`')->Table(array($prefix.'article'=>'a',$prefix.'user'=>'u'))->where("`a`.`uid`=`u`.`uid` AND (`a`.`content` like '%$tag%' OR `a`.`tag` like '%$tag%')  AND `a`.`status`=1")->limit($p->firstRow.','.$p->page_rows)->order('`a`.`id` DESC')->select();
			foreach($article_list as $key=>$value){
				$article_list[$key]['content'] = str_replace($tag,'<b class="red">'.$tag.'</b>',$value['content']);
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
					$article_list[$key]['url'] = $article_list[$key]['full_url'] =  $this->config['site_url'].'/article/'.$value['id'];
					$article_list[$key]['avatar_m'] = $this->config['site_url'].'/avatar/m/'.$value['uid'].'.jpg';
					$article_list[$key]['avatar_s'] = $this->config['site_url'].'/avatar/s/'.$value['uid'].'.jpg';
					$article_list[$key]['user_url'] = $this->config['site_url'].'/users/'.$value['uid'].'/';
				}else{
					$article_list[$key]['full_url'] = $this->config['site_url'].'/index.php?m=article&a=index&id='.$value['id'];
					$article_list[$key]['url'] = U('Article/index?id='.$value['id']);
					$article_list[$key]['avatar_m'] = U('Avatar/index?size=m&uid='.$value['uid']);
					$article_list[$key]['avatar_s'] = U('Avatar/index?size=s&uid='.$value['uid']);
					$article_list[$key]['user_url'] = U('Users/index?uid='.$value['uid']);
				}
			}
			$this->assign('article_list',$article_list);
			$this->assign('pagebar',$p->show());
		}
		$this->display();
    }
	public function tagcloud(){
		$condition_article['tag'] = array('neq','');
		$condition_article['status'] = 1;
		$database_article = D('Article');
		$article = $database_article->field('`tag`')->where($condition_article)->order('`id` DESC')->limit(100)->select();
		$i = 0;
		$tag_name_array = array();
		foreach($article as $value){
			$tag    = explode(' ',$value['tag']);
			if(!in_array($tag[0],$tag_name_array)){
				$tagcloud[$i]['tag_name'] = $tag_name_array[] = $tag[0];
				
				$tagcloud[$i]['num'] = $this->get_rand();
				if($GLOBALS['config']['site_html']){
					$tagcloud[$i]['tag_url'] = $this->config['site_url'].'/tag/'.urlencode($tag[0]);
				}else{
					$tagcloud[$i]['tag_url'] = U('Tag/index?tag='.urlencode($tag[0]));
				}
				$i++;
			}
			if(!empty($tag[1]) && !in_array($tag[1],$tag_name_array)){
				$tagcloud[$i]['tag_name'] = $tag_name_array[] = $tag[1];
				$tagcloud[$i]['num'] = $this->get_rand();
				if($GLOBALS['config']['site_html']){
					$tagcloud[$i]['tag_url'] = $this->config['site_url'].'/tag/'.urlencode($tag[1]);
				}else{
					$tagcloud[$i]['tag_url'] = U('Tag/index?tag='.urlencode($tag[1]));
				}
				$i++;
			}
			if(!empty($tag[2]) && !in_array($tag[2],$tag_name_array)){
				$tagcloud[$i]['tag_name'] = $tag_name_array[] = $tag[2];
				$tagcloud[$i]['num'] = $this->get_rand();
				if($GLOBALS['config']['site_html']){
					$tagcloud[$i]['tag_url'] = $this->config['site_url'].'/tag/'.urlencode($tag[2]);
				}else{
					$tagcloud[$i]['tag_url'] = U('Tag/index?tag='.urlencode($tag[2]));
				}
				$i++;
			}
		}
		$this->assign('tagcloud',$tagcloud);
		
		$this->display();
	}
	private function is_utf8($word){ 
		if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true){ 
			return $word; 
		}else{ 
			return iconv('gbk','utf-8',$word); 
		} 
	} 
	private function get_rand(){
		$rand = mt_rand(0,30);
		if($rand<2){
			return 3;
		}elseif($rand<6){
			return 2;
		}else{
			return 1;
		}
	}
}