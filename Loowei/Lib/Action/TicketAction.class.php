<?php
/**
 * 审核内容
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class TicketAction extends BaseAction{
    public function index(){
		$this->display();
    }
	public function fetch(){
		$id = $this->_post('id');
		$database_new = D('New');
		$condition_new['status'] = 1;
		if(empty($id)){
			$count = $database_new->where($condition_new)->max('id');
			$condition_new['id'] = array('lt',mt_rand($count-50,$count));
		}else{
			$condition_new['id'] = array('lt',$id);
		}
		$new = $database_new->field(true)->where($condition_new)->order('`id` DESC')->find();
		
		if(!empty($new['pic_url']) && strpos(strtolower($new['pic_url']),'http://') === false){
			$new['pic_url'] = $this->config['site_url'].'/Uploads/Images/'.$new['pic_url'];
		}
		echo json_encode($new);
	}
	public function inspect(){
		if($this->isPost()){
			$condition_new['id'] = $this->_post('id','intval');
			$action = $this->_post('action','intval');
			$database_new = D('New');
			if($action == 1){
				$database_new->where($condition_new)->setInc('upper');
				$new = $database_new->field('id',true)->where($condition_new)->find();
				if($new['upper'] >= $this->config['article_upper']){
					unset($new['upper'],$new['below']);
					$database_article = D('Article');
					$data_article = $new;
					$data_article['time'] = $_SERVER['REQUEST_TIME'];
					
					if(empty($data_article['title'])){
						//正则得到标题
						$content = htmlspecialchars_decode($data_article['content']);
						$content = str_replace(array('，','！','？','。','；',',','!','?','.',';','&','“','”','"','"','、','（','）','(',')'),',',$content); 
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
						$data_article['title'] = $title[$key];
					}
					if($database_article->data($data_article)->add()){
						$database_new->where($condition_new)->delete();
					}
				}
			}else if($action < 0){
				$database_new->where($condition_new)->setInc('below');
				$new = $database_new->field('`below`')->where($condition_new)->find();
				if($new['below'] >= $this->config['article_below']){
					$database_new->where($condition_new)->setField('status',0);
				}
			}
		}else{
			$this->error('非法访问！');
		}
	}
}