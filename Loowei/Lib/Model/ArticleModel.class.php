<?php
	class ArticleModel extends Model{
		public function get_lists($type,$day,$hour,$order,$limit,$page,$rows,$user,$condition,$url){
			if(empty($user)){
				if(empty($condition)){
					$condition_article['status'] = 1;
					if($type != -1){
						$condition_article['type'] = $type;
					}
					if(!empty($hour)){
						$time = $_SERVER['REQUEST_TIME'] - ($hour*3600);
						$condition_article['time'] = array('gt',$time);
					}else if(!empty($day)){
						$time = $_SERVER['REQUEST_TIME'] - ($day*86400);
						$condition_article['time'] = array('gt',$time);
					}
				}else{
					$condition = str_replace(array(' eq ',' neq ',' gt ',' egt ',' lt ',' elt ',' heq ',' nheq '),array(' = ',' != ',' > ',' >= ',' < ',' <= ',' === ',' !== '),$condition);
					$condition_article = $condition;
				}
				if(!empty($limit)){
					$result['article_list'] = $this->field(true)->where($condition_article)->order($order)->limit($limit)->select();
				}else{
					$count = $this->where($condition_article)->count('`id`');
					
					import('@.ORG.page');
					$p = new Page($count,$url,$rows);
					$result['article_list'] = $this->field(true)->where($condition_article)->order($order)->limit($p->firstRow.','.$p->page_rows)->select();
					$result['page'] = $p->show();
				}
			}else{
				$prefix = C('DB_PREFIX');
				$table = array($prefix.'article'=>'a',$prefix.'user'=>'u');
				if(empty($condition)){
					$condition_article = '`a`.`status`=1 AND `u`.`status`=1 AND `a`.`uid`=`u`.`uid`';
					
					if($type != -1){
						$condition_article .= ' AND `a`.`type`='.$type;
					}
					if(!empty($hour)){
						$time = $_SERVER['REQUEST_TIME'] - ($hour*3600);
						$condition_article .= ' AND `a`.`time`>'.$time;
					}else if(!empty($day)){
						$time = $_SERVER['REQUEST_TIME'] - ($day*86400);
						$condition_article .= ' AND `a`.`time`>'.$time;
					}
				}else{
					$condition = str_replace(array(' eq ',' neq ',' gt ',' egt ',' lt ',' elt ',' heq ',' nheq '),array(' = ',' != ',' > ',' >= ',' < ',' <= ',' === ',' !== '),$condition);
					$condition_article = $condition;
				}

				if(!empty($limit)){
					$result['article_list'] = D('')->field('`a`.*,`u`.`nickname`')->Table($table)->where($condition_article)->order($order)->limit($limit)->select();
				}else{
					$count = D('')->Table($table)->where($condition_article)->count('`a`.`id`');
					import('@.ORG.page');
					$p = new Page($count,$url,$rows);
					$result['article_list'] = D('')->field('`a`.*,`u`.`nickname`')->Table($table)->where($condition_article)->order($order)->limit($p->firstRow.','.$p->page_rows)->select();
					$result['page'] = $p->show();
				}
			}
			
			if(!empty($result['article_list'])){
				foreach($result['article_list'] as $key=>$value){
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
						$result['article_list'][$key]['tags'] = $tags;
					}
					if(!empty($value['pic_url']) && strpos(strtolower($value['pic_url']),'http://') === false){
						$result['article_list'][$key]['pic_url'] = $GLOBALS['config']['site_url'].'/Uploads/Images/'.$value['pic_url'];
					}
					if($GLOBALS['config']['site_html']){
						$result['article_list'][$key]['url'] = $result['article_list'][$key]['full_url'] = $GLOBALS['config']['site_url'].'/article/'.$value['id'];
						$result['article_list'][$key]['avatar_m'] = $GLOBALS['config']['site_url'].'/avatar/m/'.$value['uid'].'.jpg';
						$result['article_list'][$key]['avatar_s'] = $GLOBALS['config']['site_url'].'/avatar/s/'.$value['uid'].'.jpg';
						$result['article_list'][$key]['user_url'] = $GLOBALS['config']['site_url'].'/users/'.$value['uid'].'/';
					}else{
						$result['article_list'][$key]['full_url'] = $GLOBALS['config']['site_url'].'/index.php?m=article&a=index&id='.$value['id'];
						$result['article_list'][$key]['url'] = U('Article/index?id='.$value['id']);
						$result['article_list'][$key]['avatar_m'] = U('Avatar/index?size=m&uid='.$value['uid']);
						$result['article_list'][$key]['avatar_s'] = U('Avatar/index?size=s&uid='.$value['uid']);
						$result['article_list'][$key]['user_url'] = U('Users/index?uid='.$value['uid']);
					}
				}
			}

			return $result;
		}
	}
?>