<?php

/**
 * ������������
 */
namespace Admin\Controller;


class AppController extends CommonController{
	//ϵͳ
	private $app = null;
	
	//�̳и���
	public function _initialize(){
		parent::_initialize();
		$this->app = D('App');
	}
	//app����ҳ
	
	public function index(){
		$data = $this->app->search();
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page']
			));
		$this->display();
		
		
		
	}
	//���app����ҳͼƬ
	
	public function add(){
		if(IS_POST){
			// p($_POST);
			// p($_FILES);die;
			if($this->app->create(I('post.',1))){
				if($this->app->add()){
					$this->ajaxReturn(array('status'=>1));
					exit;
				}else{
					
					$this->ajaxReturn(array('status'=>1));
				}
			}
		}
		
		$this->display();
		
	}
	//
	public function edit(){
		
		
		
		$this->display();
	}
	
	
}
