<?php
/**
 * 登陆日志 *
 */

namespace Admin\Controller;

class LogController extends CommonController{
	
	public function _initialize(){
		parent::_initialize();
		$this->model = D('Log'); 
	}
	
	
	//
	public function index(){
		$data = $this->model->search();
		//p($data);
    	/* p($this->model->getlastsql());
    	
    	p($_COOKIE);die; */
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page']
			));

		$this->display();
		
	}
	
	//删除日志
	public function delete(){
		
		if($this->model->delete(I('get.id', 0)) !== FALSE){
			$this->ajaxReturn(1);
		}else{
			$this->error($this->model->getError());
			$this->ajaxReturn(0);
		}
	}


}
