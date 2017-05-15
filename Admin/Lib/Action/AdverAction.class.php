<?php
/**
 * 广告页
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class AdverAction extends BaseAction{
	public function index(){
		//从数据库读取所有广告
		$database_adver = D('Adver');
		$list = $database_adver->order('id ASC')->select();
		$this->assign('list',$list);
		$this->display();
	}
	//添加广告
	public function add(){
		$this->display();
	}
	//添加广告提交页
	public function modify(){
		//判断是否是post提交
		if($this->isPost()){
			//判断是否填写分类名,
			$name = $_POST['name'] = $this->_post('name','trim,htmlspecialchars');
			if(empty($name)){
				$this->error('广告名称必填~');
			}
			//判断是否填写广告代码,
			$code = $_POST['code'] = $this->_post('code','stripslashes');
			if(empty($code)){
				$this->error('广告代码必填~');
			}
			//如果填写广告开始时间,则将时间转换成数据形式存储
			$start_time = $_POST['start_time'] = $this->_post('start_time','trim,htmlspecialchars');
			if(!empty($start_time)){
				$_POST['start_time'] = strtotime($start_time);
			}
			//如果填写广告结束时间,则将时间转换成数据形式存储
			$end_time = $_POST['end_time'] = $this->_post('end_time','trim,htmlspecialchars');
			if(!empty($start_time)){
				$_POST['end_time'] = strtotime($end_time);
			}
			//向数据库推送广告
			if(D('Adver')->data($_POST)->add()){
				F('adver_list',NULL);
				$this->assign('jumpUrl',U('Adver/index'));
				$this->success('广告添加成功！');
			}else{
				$this->error('广告添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	//广告编辑
	public function edit(){
		//判断是否是get提交
		$id = $this->_get('id','trim,intval');
		$list = D('Adver')->where("id=$id")->find();
		$this->assign('list',$list);
		$this->display();
	}
	//修改广告提交页
	public function amend(){
		//判断是否是post提交
		if($this->isPost()){
			//判断是否填写分类名,
			$name = $_POST['name'] = $this->_post('name','trim,htmlspecialchars');
			if(empty($name)){
				$this->error('广告名称必填,现在返回~');
			}
			//判断是否填写广告代码,
			$code = $_POST['code'] = $this->_post('code','stripslashes');
			if(empty($code)){
				$this->error('广告代码必填,现在返回~');
			}
			//如果填写广告开始时间,则将时间转换成数据形式存储
			$start_time = $_POST['start_time'] = $this->_post('start_time','trim,htmlspecialchars');
			if(!empty($start_time)){
				$_POST['start_time'] = strtotime($start_time);
			}
			//如果填写广告结束时间,则将时间转换成数据形式存储
			$end_time = $_POST['end_time'] = $this->_post('end_time','trim,htmlspecialchars');
			if(!empty($start_time)){
				$_POST['end_time'] = strtotime($end_time);
			}
			//向数据库推送广告
			if(D('Adver')->data($_POST)->save()){
				F('adver_list',NULL);
				$this->assign('jumpUrl',U('Adver/index'));
				$this->success('广告修改成功！');
			}else{
				$this->error('广告修改失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	//广告删除提交页
	public function del(){
		//判断是否是get提交
		if($this->isGet()){
			$id = $this->_get('id','trim,intval');
			//向数据库请求删除分类
			if(D('Adver')->where("id=$id")->delete()){
				F('adver_list',NULL);
				$this->success('广告删除成功！');
			}else{
				$this->error('广告删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	//广告修改状态提交页
	public function status(){
		//判断是否是get提交
		if($this->isGet()){
			$data['id'] = $this->_get('id','trim,intval');
			$data['status'] = $this->_get('status','trim,intval');
			//向数据库请求修改广告状态
			if(D('Adver')->data($data)->save()){
				$this->success('状态修改成功！');
			}else{
				$this->error('状态修改失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
}