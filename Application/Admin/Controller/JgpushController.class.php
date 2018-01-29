<?php

/**
 * �������͹��������
 */
namespace Admin\Controller;


class JgpushController extends CommonController{
	//ϵͳ
	private $app = null;
	
	//�̳и���
	public function _initialize(){
		parent::_initialize();
		$this->jg = D('Jgpush');
	}
	//app����ҳ
	
	public function index(){
		$data = $this->jg->search();
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
			if($this->jg->create(I('post.',1))){
				if($this->jg->add()){
					$this->ajaxReturn(array('status'=>1));
					exit;
				}else{
					$this->ajaxReturn(array('status'=>2));
					
				}
			}
		}
		
		$this->display();
		
	}
	
	//ɾ��
	public function delete(){
		if($this->jg->delete(I('get.id', 0)) !== FALSE){
			$this->ajaxReturn(1);
		}else{
			$this->error($this->jg->getError());
			$this->ajaxReturn(0);
		}
	}
}
