<?php
/**
*  �ҲμӵĴ�����б�
*/
namespace Home\Model;
use Common\Model\BaseModel;

class CompanybullModel extends BaseModel{
	
	protected $tableName = 'company_bull';
	
	public function searchFront($pagesize=15){
		$where = array();
		$title = I('get.title');
		if ($title) {
			$where['title'] = array('like',"%$title%");
		}
		$where['user_id'] = cookie(userid);
		//��ҳ
		$count = $this->where($where)->count();
		$page = new \Think\Page($count,$pagesize);
		//���÷�ҳ
		$page->setConfig('prev', '��һҳ');
		$page->setConfig('next', '��һҳ');
		$data['page'] = $page->show();
		$data['data'] = $this
		->where($where)
		->limit($page->firstRow.','.$page->listRows)
		->order('id desc')
		->select();
		
		return $data;
	}
	
	
	//���ǰ
	public function _before_insert(&$data, $option){
		$data['addtime'] = time();
		$data['user_id'] = cookie(userid);
		$data['content'] = htmlspecialchars_decode($_POST['content']);

	}
	
	
	
	

}