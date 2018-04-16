<?php
/**
*  ��������
*/
namespace App\Model;
use Common\Model\BaseModel;
use Common\Third\AppPage;
class JgpushpersonModel extends BaseModel{
	
	//���ǰ
	public function _before_insert(&$data, $option){
		$data['addtime'] = time();
		$data['user_id'] =  I('post.user_id');
		$data['conf_id'] =  I('post.conf_id');
		$data['title'] = I('post.title');
		$data['content'] = I('post.content');
	
	}
	
	//����֮�����������û���Ϣ
	public function _after_insert($data,$option){
		//
		$uid = I('post.user_id');
		$jpush = D('User')->where('id='.$uid)->find();
		if(!jgpushgx($jpush['jpush'],$data['title'],$data['content'])){
			$this->error = '���û�û�е�¼app�ͻ��ˣ���������';
		}
		
		
	}
	//����������Ϣ�б�
	public function search(){
		$where = [];
		$where['user_id'] = I('post.user_id');
		//��ҳ
		$showrow = 15; //һҳ��ʾ������
		
		$curpage = I('post.page',1); //��ǰ��ҳ,��Ӧ�ô�������ֵ����

		$total = $this->where($where)->count();	

		$page = new AppPage($total, $showrow);
		if ($total > $showrow) {
			$data['page'] =  $page->myde_write();
		}
 
		$data['data'] = $this
		->where($where)
		->limit(($curpage - 1) * $showrow.','.$showrow)
		->order('id desc')
		->select();

		return $data;	
		
		
	}
	//����������ʷ��¼
	public function searchFront(){
		
		//����
		$where = array();
		$where['conf_id'] = I('post.conf_id');
		$where['user_id'] = I('post.user_id');
		
		//��ҳ
		$curpage = I('post.page',1); //��ǰ��ҳ,��Ӧ�ô�������ֵ����

		$total = $this->where($where)->count();	

		$page = new AppPage($total, $showrow);
		if ($total > $showrow) {
			$data['page'] =  $page->myde_write();
		}
		$data['data'] = $this		
		->where($where)
		->limit(($curpage - 1) * $showrow.','.$showrow)
		->order('id desc')
		->select();
		//p($this->getLastSql());
		return $data;
	}



	
	
}