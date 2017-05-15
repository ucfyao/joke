<?php
/**
 * 友情链接
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class FlinkAction extends BaseAction{
	public function index(){
		//从数据库读取所有链接
		$database_adver = D('Flink');
		$list = $database_adver->order('`sort` ASC,`id` ASC')->select();
		$this->assign('list',$list);
		$this->display();
	}
	//添加链接页
	public function add(){
		$this->display();
	}
	//添加链接提交页
	public function modify(){
		//判断是否是post提交
		if($this->isPost()){
			//判断是否填写链接名,
			$name = $_POST['name'] = $this->_post('name','trim,htmlspecialchars');
			if(empty($name)){
				$this->error('链接名称必填~');
			}
			//判断是否填写链接网址,
			$_POST['url'] = $this->_post('url','trim,htmlspecialchars');
			if(empty($_POST['url'])){
				$this->error('链接地址必填~');
			}
			//向数据库推送链接
			if(D('Flink')->data($_POST)->add()){
				F('flink_list',NULL);
				$this->assign('jumpUrl',U('Flink/index'));
				$this->success('链接添加成功！');
			}else{
				$this->error('链接添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	//链接编辑
	public function edit(){
		//判断是否是get提交
		$condition['id'] = $this->_get('id','trim,intval');
		$list = D('Flink')->where($condition)->find();
		$this->assign('list',$list);
		$this->display();
	}
	//修改链接提交页
	public function amend(){
		//判断是否是post提交
		if($this->isPost()){
			//判断是否填写链接名,
			$name = $_POST['name'] = $this->_post('name','trim,htmlspecialchars');
			if(empty($name)){
				$this->error('链接名称必填~');
			}
			//判断是否填写链接网址,
			$_POST['url'] = $this->_post('url','trim,htmlspecialchars');
			if(empty($_POST['url'])){
				$this->error('链接地址必填~');
			}
			//向数据库推送链接
			if(D('Flink')->data($_POST)->save()){
				F('flink_list',NULL);
				$this->assign('jumpUrl',U('Flink/index'));
				$this->success('链接修改成功！');
			}else{
				$this->error('链接修改失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	//链接删除提交页
	public function del(){
		//判断是否是get提交
		if($this->isGet()){
			$condition['id'] = $this->_get('id','trim,intval');
			//向数据库请求删除分类
			if(D('Flink')->where($condition)->delete()){
				F('flink_list',NULL);
				$this->success('链接删除成功！');
			}else{
				$this->error('链接删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
}