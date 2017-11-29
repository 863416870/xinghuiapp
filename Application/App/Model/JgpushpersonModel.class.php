<?php
/**
*  ��������
*/
namespace App\Model;
use Common\Model\BaseModel;
use Common\Third\AppPage;
class JgpushpersonModel extends BaseModel{
	
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
}