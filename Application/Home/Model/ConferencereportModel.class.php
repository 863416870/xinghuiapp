<?php
/**
*  ��ҵ����
*/
namespace Home\Model;
use Think\Model\RelationModel;

class ConferencereportModel extends RelationModel{
	
	protected $tableName = 'conference_report';
	
	protected $_auto = array (
		array('type','survey'), 	
		array('create_date','c_date','','callback'),
		 
	);
	protected $_link = array(
		'Conferencere_portques'	=>	self::HAS_MANY,
	);
	
	public function c_date(){
		$time=time();
		$date = date('Y-m-d',$time);
		return $date;
	}
	
	public function search($pagesize=15){
		$where = array();
		
		$where['user_id'] = cookie(userid);
		$where['conf_id'] = I('get.id');
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
	
	
	
}