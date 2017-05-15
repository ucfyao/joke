<?php
/**
 * 修改配置
 *
 * @package      	Loowei_xh
 * @author          Jaty
 * @copyright     	Copyright (c) 2013  (http://www.loowei.com)
 * @license         http://www.loowei.com/license.txt
 *
 */
class ConfigAction extends BaseAction {
    public function index(){
		$this->display();
	}
	public function sys(){
		set_time_limit(0);
		//读取出所有模板
		import('ORG.Util.Dir');
		$dir = new Dir('./Tpl/');
		
		$theme_array = $dir->_values;

		foreach($theme_array as $key=>$value){
			//如果目录下文件名称含有中文字符,则不在显示范围之内
			if($value['isDir'] && $value['filename'] == iconv('UTF-8','UTF-8',iconv('UTF-8','UTF-8',$value['filename']))){
				//判断此模板是否可读
				if($value['isReadable']){
					$theme_list[$key]['value'] = $value['filename'];
				}else{
					$theme_list[$key]['cannot_select'] = 1;
					$theme_list[$key]['value'] = $value['filename'].'（不可读，无法使用）';
				}
			}
		}
		$this->assign('theme_list',$theme_list);
		
		$this->display();
	}
	public function user(){
		$this->display();
	}
	public function mail(){
		$this->display();
	}
	public function water(){
		$this->display();
	}
	public function collect(){
		$this->display();
	}
	public function amend(){
		//如果是以POST方式提交,
		if($this->isPost()){
			//保存修改的系统配置至数据库
			foreach($_POST as $key=>$value){
				$data['name'] = $key;
				if($key == 'site_safecode'){
					$data['value'] = md5(stripslashes($value));
				}else{
					$data['value'] = stripslashes($value);
				}
				M('Config')->data($data)->save();
			}
			//从数据库中读取配置至快速缓存
			$configs = M('Config')->select();
			foreach($configs as $key=>$value){
				$config[$value['name']] = $value['value'];
			}
			F('config',$config);
			
			//判断若改了使用模板，则需要改配置文件
			if(isset($_POST['default_theme']) && $_POST['default_theme'] != $this->config['default_theme']){
				$config = file_get_contents('./Loowei/Conf/config.php');
				//使用正则替换默认的风格名称
				$new_config = preg_replace('/\'DEFAULT_THEME\'=>\'(.*?)\',/','\'DEFAULT_THEME\'=>\''.$_POST['default_theme'].'\',',$config);
				if(file_put_contents('./Loowei/Conf/config.php',$new_config)){
					$this->success('更换成功！');exit;
				}else{
					$this->error('更换失败！请重试~');
				}
			}
			
			$this->success('修改成功!');
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cache(){
		import('ORG.Util.Dir');
		Dir::delDir('./Cache');
		$this->assign('jumpUrl',U('Index/main'));
		$this->success('清除缓存成功！');
	}
}