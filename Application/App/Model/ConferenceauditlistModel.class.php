<?php
/**
*  �ҲμӵĴ�����б�
*/
namespace App\Model;
use Common\Model\BaseModel;
use Common\Third\AppPage;

class ConferenceauditlistModel extends BaseModel{
	//protected $tableName = 'conference_auditlist';
	protected $tableName = 'conference_auditlist';
	
	// ��Ҫ�μӵ� ������б� myauditlist
	
	public function myauditlist(){
		$where = array();
		$where['user_id'] = I('post.user_id'); 
		$status = I('post.status');
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
		
		
		
		//��ҳ
		$showrow = 15; //һҳ��ʾ������
		
		$curpage = I('post.page',1); //��ǰ��ҳ,��Ӧ�ô�������ֵ����

		$total = $this->alias('a')->where($where)->count();	

		$page = new AppPage($total, $showrow);
		if ($total > $showrow) {
			$data['page'] =  $page->myde_write();
		}

		$data['data'] = $this ->alias('a')
		->field('a.*,b.*')
		->join('LEFT JOIN __CONFERENCE__ b on b.id=a.conf_id
		') 
		->where($where)
		->limit(($curpage - 1) * $showrow.','.$showrow)
		->order('id desc')
		->select();
		//p($this->getLastSql());
		return $data;
	}
	
	
	
	
	
	
}