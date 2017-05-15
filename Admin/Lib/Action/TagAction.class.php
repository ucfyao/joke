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
		$database_hot_tag = D('Hot_tag');
		$tag_list = $database_hot_tag->order('`tag_sort` ASC,`tag_id` DESC')->select();
		$this->assign('tag_list',$tag_list);
		
		$this->display();
	}
	public function add(){
		$this->display();
	}
	public function modify(){
		$database_hot_tag = D('Hot_tag');
			
		if($database_hot_tag->data($_POST)->add()){
			$this->success('增加成功！');
		}else{
			$this->error('增加失败！');
		}
	}
	public function edit(){
		$database_hot_tag = D('Hot_tag');
		$condition_hot_tag['tag_id'] = $this->_get('tag_id');
		$hot_tag = $database_hot_tag->where($condition_hot_tag)->find();
		$this->assign('hot_tag',$hot_tag);
		
		$this->display();
	}
	public function amend(){
		$database_hot_tag = D('Hot_tag');
			
		if($database_hot_tag->data($_POST)->save()){
			$this->success('修改成功！');
		}else{
			$this->error('修改失败！');
		}
	}
	public function del(){
		$database_hot_tag = D('Hot_tag');
		$condition_hot_tag['tag_id'] = $this->_get('tag_id');
		if(!empty($condition_hot_tag['tag_id']) && $database_hot_tag->where($condition_hot_tag)->delete()){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
}