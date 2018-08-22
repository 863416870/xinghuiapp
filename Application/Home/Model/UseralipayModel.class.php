<?php
/**
 * ����ģ��
 */
namespace Home\Model;
use Common\Model\BaseModel;

class UseralipayModel extends BaseModel{
	protected $tableName = 'User_alipay';
	
	//����
	public function orderlist($pagesize=10){
		//����
		$where = array();
		$where['a.user_id'] = cookie(userid);

		$title = I('get.title');
		if ($title) {
			$where['a.title'] = array('like',"%$title%");
		}
		//��ҳ
		$count = $this->alias('a')->join('LEFT JOIN __USER__ b ON b.id=a.user_id
			')->where($where)->count();
		$page = new \Think\Page($count,$pagesize);
		//���÷�ҳ
		$page->setConfig('prev', '��һҳ');
		$page->setConfig('next', '��һҳ');
		$data['page'] = $page->show();
		$data['data'] = $this->alias('a')
		->field('a.*,b.username')
		->join('LEFT JOIN __USER__ b ON b.id=a.user_id
			')
		->where($where)
		->limit($page->firstRow.','.$page->listRows)
		->order('id desc')
		->select();
		
		return $data;
	}
}