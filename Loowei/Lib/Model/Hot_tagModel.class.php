<?php
	class Hot_tagModel extends Model{
		public function get_lists($limit){
			$condition_hot_tag['tag_status']  = 1;
			if(!empty($limit)){
				$hot_tag_list = $this->field('`tag_name`')->where($condition_hot_tag)->order('`tag_sort` ASC,`tag_id` ASC')->limit($limit)->select();
			}else{
				$hot_tag_list = $this->field('`tag_name`')->where($condition_hot_tag)->order('`tag_sort` ASC,`tag_id` ASC')->select();
			}
			if(!empty($hot_tag_list)){
				foreach($hot_tag_list as $key=>$value){
					if($GLOBALS['config']['site_html']){
						$hot_tag_list[$key]['tag_url'] = $GLOBALS['config']['site_url'].'/tag/'.urlencode($value['tag_name']);
					}else{
						$hot_tag_list[$key]['tag_url'] = U('Tag/index?tag='.urlencode($value['tag_name']));
					}
				}
			}
			$return['hot_tag_list'] = $hot_tag_list;
			if($GLOBALS['config']['site_html']){
				$return['more_tag_url'] = $GLOBALS['config']['site_url'].'/tagcloud/1';
			}else{
				$return['more_tag_url'] = U('Tag/tagcloud?page=1');
			}
			return $return;
		}
	}
?>