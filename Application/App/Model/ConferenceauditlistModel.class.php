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
		//���еĹ�������
		$where['is_private'] = 0;
		
		//$status = I('post.status');
			//$data = $this->where(array('user_id'=>$where['user_id']))->select();
			//p($data['status']);
			/* switch ( $data['status'] ) {
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
		} */
		
		 
		
		//��ҳ
		$showrow = 15; //һҳ��ʾ������
		
		$curpage = I('post.page',1); //��ǰ��ҳ,��Ӧ�ô�������ֵ����

		$total = $this->alias('a')->where($where)->count();	

		$page = new AppPage($total, $showrow);
		if ($total > $showrow) {
			//$data['page'] =  $page->myde_write();
		}
		
		/*
		 
		$sql = $m->where(array('user_id'=>$where['user_id']))->buildSql(); ;
		$data['data'] = $this ->alias('a')
		->field('a.status,b.id,b.title,b.ctime,b.etime,b.qtime,b.address,b.xxaddress,b.is_user,b.is_private,b.companypic')
		->join('LEFT JOIN __CONFERENCE__ b on b.id=a.conf_id
		') 
		->where($where)
		->limit(($curpage - 1) * $showrow.','.$showrow)
		->order('id desc')->table($sql)
		->select();
		*/
		$sql = 'SELECT conf_id FROM tzht_conference_del WHERE user_id = '.$where['user_id'];
		$limit = ($curpage - 1) * $showrow.','.$showrow;
		$data['data'] = $this->query('SELECT a.status,b.id,b.title,b.ctime,b.etime,b.qtime,b.address,b.xxaddress,b.is_user,b.is_private,b.companypic FROM tzht_conference_auditlist a
 LEFT JOIN tzht_conference b on b.id=a.conf_id 
WHERE user_id='.$where['user_id'].' and is_private ='.$where['is_private'].' and  a.conf_id not in ('.$sql.') ORDER BY id desc LIMIT '.$limit);
		
		//p($this->_Sql());die;
		return $data;
	}
	
	
	//�����˻���ʾ���˻����ڲ������б�
	public function privateConfList(){
		$data['uid'] = I('post.uid');
		$user = D('User');
		$map = $user->field('id,pid')->where(array('id'=>$data['uid']))->find();
		//p($map);
		//�ڲ�˽�ܻ���
		$where['is_private'] = 1 ;
		//����״̬ 0��ʼ1����
		$state = I('post.state');
		switch ( $state ) {
			case 1:
			$where['statuses'] =  array('eq',1);
			break;  
			case 0:
			$where['statuses'] =  array('eq',0);
			break;		
			default:
			
		}
		
		$where['uid'] = $map['pid'];
		
		
		$showrow = 10; //һҳ��ʾ������
		
		$curpage = I('post.page',1);; //��ǰ��ҳ,��Ӧ�ô�������ֵ����
	
		$total = $this->where($where)->count();	
		
		
		$page = new AppPage($total, $showrow);
		if ($total > $showrow) {
			//$data['page'] =  $page->myde_write();
		}
		
		$sql = 'SELECT conf_id FROM tzht_conference_del WHERE user_id = '.$data['uid'];
		$limit = ($curpage - 1) * $showrow.','.$showrow;
			
		$data['data'] = $this->query('SELECT a.status,b.id,b.title,b.ctime,b.etime,b.qtime,b.address,b.xxaddress,b.is_user,b.is_private,b.companypic FROM tzht_conference_auditlist a
 LEFT JOIN tzht_conference b on b.id=a.conf_id 
WHERE uid='.$where['uid'].' and is_private =1 and  a.conf_id not in ('.$sql.') ORDER BY id desc LIMIT '.$limit);
			
		//p($this->_Sql());die;
		return $data;
		
		
	}
	
	
	
	
	
}