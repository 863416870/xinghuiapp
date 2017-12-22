<?php
/**
*  ��ҵ����
*/
namespace Home\Model;
use Common\Model\BaseModel;

class DownloadModel extends BaseModel{
	

	
	public function search($pagesize=15){
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
		$data['downfile'] = json_encode($_POST['downfile'],JSON_UNESCAPED_UNICODE);
		

	}
	
	
	
	

}