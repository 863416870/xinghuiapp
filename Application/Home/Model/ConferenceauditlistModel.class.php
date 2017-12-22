<?php
/**
*  �ҲμӵĴ�����б�
*/
namespace Home\Model;
use Common\Model\BaseModel;

class ConferenceauditlistModel extends BaseModel{
	//protected $tableName = 'conference_auditlist';
	protected $tableName = 'conference_auditlist';
	
	// ��Ҫ�μӵ� ������б� myauditlist
	
	public function myauditlist($pagesize = 15){
		$where = array();
		$where['user_id'] = cookie(userid); 
		$status = I('get.status');
			switch ( $status ) {
			case 1:
			$where['status'] = 1;	//���ͨ��
			break;  
			case 2:
			$where['status'] = 2;	//��˾ܾ�
			break;
			case 3:
			$where['status'] = 3; 	//����ˣ�����У�
			break;
			case 4:
			$where['status'] = 4;	//��ɨ��ǩ�����λ��У�
			break;
			case 5:
			$where['status'] = 5;	//�����ѽ���
			break;
			default:
			$where['status'] = 3;
		}
		
		//p($this->_Sql());
		
		
		//��ҳ
		$count = $this->alias('a')->where($where)->join('LEFT JOIN __CONFERENCE__ b on b.id=a.conf_id
		') ->count();
		$page = new \Think\Page($count,$pagesize);
		//���÷�ҳ
		$page->setConfig('prev', '��һҳ');
		$page->setConfig('next', '��һҳ');
		$data['page'] = $page->show();

		$data['data'] = $this ->alias('a')
		->field('a.*,b.*')
		->join('LEFT JOIN __CONFERENCE__ b on b.id=a.conf_id
		') 
		->where($where)
		->limit($page->firstRow.','.$page->listRows)
		->order('id desc')
		->select();
		//p($this->getLastSql());
		return $data;
	}
	
	
	
	
	
	
}