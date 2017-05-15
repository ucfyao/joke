<?php
/**
 * 图片列表页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class PicAction extends BaseAction {
	public function get_pic(){
		$page = $this->_get('page');
		if($page>1){
			$prefix = C('DB_PREFIX');
			$return['article'] = D('')->field('a.`id`,`a`.`title`,`a`.`content`,`a`.`pic_url`,`a`.`pic_height`,`a`.`pic_width`,`a`.`reply`,`a`.`is_anonymous`,`a`.`time`,`u`.`uid`,`u`.`nickname`')->Table(array($prefix.'article'=>'a',$prefix.'user'=>'u'))->where("`a`.`uid`=`u`.`uid` AND `a`.`status`='1' AND `a`.`type`='1' AND `a`.`pic_height`<'1000'")->limit(($page-1)*$this->config['waterfall_rows'],$this->config['waterfall_rows'])->order('`a`.`id` DESC')->select();
			if(empty($return['article'])){
				$return['is_empty'] = true;
			}else{
				$return['is_empty'] = false;
			}
			echo json_encode($return);
		}
	}
}