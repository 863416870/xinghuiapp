<?php
/**
*  ��������
*/
namespace App\Model;
use Common\Model\BaseModel;
use Common\Third\AppPage;
class SignjgpushModel extends BaseModel{
	
	
	//���ǰ
	public function _before_insert(&$data, $option){
		$data['addtime'] = time();
		$data['user_id'] =  I('post.user_id');
		$data['conf_id'] =  I('post.conf_id');
		$data['title'] = I('post.title');
		$data['content'] = I('post.content');
	}
	
	//ǩ����ļ���������Ϣ�б�
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
		->field('title,content,addtime')
		->where($where)
		->limit(($curpage - 1) * $showrow.','.$showrow)
		->order('id desc')
		->select();

		return $data;	
		
		
	}
	
	//��Ϣ�ϲ�
	
	public function msgMerge(){
		
		$user_id = I('post.user_id');
		
		//��ҳ
		$showrow = 15; //һҳ��ʾ������
		
		$curpage = I('post.page',1); //��ǰ��ҳ,��Ӧ�ô�������ֵ����

		$total = $this->where(array('user_id'=>$user_id))->count();	

		$page = new AppPage($total, $showrow);
		if ($total > $showrow) {
			$data['page'] =  $page->myde_write();
		}
		/**
		->field('name')
		  ->table('think_user_0')
		  ->union('SELECT name FROM think_user_1')
		  ->union('SELECT name FROM think_user_2')
		  ->select();
		
		*/
		$m = M();
		/* $data['data'] = $m
		
		->table('tzht_signjgpush')
		->union('SELECT * FROM tzht_signjgpush')
		->union('SELECT * FROM tzht_jgpushperson')
		->where($where)
		->limit(($curpage - 1) * $showrow.','.$showrow)
		->order('addtime desc')
		->select(); */
		$start = ($curpage - 1) * $showrow;
		$data['data'] = $m->query("
			select id,title,content,addtime,type from (select * from tzht_jgpushperson union select * from tzht_signjgpush) as c where(user_id=$user_id) order by addtime desc LIMIT $start,$showrow;

		");

		return $data;	
		
		
	}
	
	
}