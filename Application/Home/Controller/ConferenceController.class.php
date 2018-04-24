<?php
/**
 * 会议管理
 *
 */

namespace Home\Controller;
use Org\Nx\Response;
class ConferenceController extends PublicController{
	private $cert = null;
	private $cate = null;
	private $user = null;
	private $jgpush = null;
	private $part = null;
	public function _initialize(){
		parent::_initialize();
		$this->model = D('Conference'); 
		$this->cert = D('Certify');
		$this->user = D('User');
		$this->cate = D('Conferencecate');
		//个人推送
		$this->jgpush = D('Jgpushperson');
		//合作伙伴
		$this->part = D('Userpartners');
		//p($info);	
		
	}

	//已参加会议
    public function index(){
        
    	$data = $this->model->searchFront();
		//p($data);
    	/* p($this->model->getlastsql());
    	
    	p($_COOKIE);die; */
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page']
			));


		$this->display();
    }

    //添加前置操作方法
    
	public function _before_add(){  
    
		$uid = cookie(userid);
		$info = $this->cert->field('is_cert,uid,type')->where(array('uid'=>$uid))->select();
		//p($info);
		//is_cert 为空，或者!=2(企业) !=5(个人会议发布认证)的时候，提示要实名认证
		if($info[0]['is_cert'] > 3 && $info[0]['is_cert'] !=4){
			$this->error('请先个人会议发布认证或者企业认证，认证审核通过后即可发布会议',U('Certify/index'));
		}
      }  
	
	//新建会议
	public function add(){
		
		$uid = cookie(userid);
		$user_part = M('User_part');
		if (IS_POST) {
			//p($_POST);die;
			if($this->model->create(I('post.',1))){
				if($id = $this->model->add()){
					$data = $this->model->field('title,ctime,qtime,brief,uid,is_private')->where(array('id'=>$id))->find();
					if($data['is_private'] == 1){
						//查找所有pid下的子id
						$user = $this->user->field('id,pid,jpush')->where('pid='.$data['uid'])->select();
						foreach($user as $k=>$v){
							//推送二级账户消息
							jgpushInside($v['jpush'],$id,$data['title'],$data['brief']);
						}
						
					}
					if($id){
						$part = $this->model->field('part_id')->where(array('id'=>$id))->find();
						//添加成功把当前用户，合作伙伴id，会议id，添加到中间表
						$pro = array(
							'user_id'=>$uid,
							'conf_id'=>$id,
							'part_id'=>$part['part_id']
						);
						$user_part->add($pro);
					}
					
					$this->error('添加成功',U('Conference/index'));
				}else{
					$this->error('添加失败');
				}
			}else{
				$this->error($this->model->getError());
			}
		}
		//获取栏目
		$cate = $this->cate->getTree();
		$this->assign('cate',$cate);
		//p($cate);
		
		//获取举办公司名称
		$cert = $this->cert->field('id,company')->where(array('uid'=>$uid))->find();
		//p($cert);
		//获取合作伙伴
		//$parts = $this->part->alias('a')->field('a.companyname,b.part_id')->join('left join tzht_user b on b.id=')->where(array('conf_user_id'=>$uid))->select();
		$parts = $this->part->field('id,companyname')->where(array('conf_user_id'=>$uid))->select();
		
		$this->assign(array(
			'cert'=>$cert,
			'parts'=>$parts,
		));
		$this->display();
	}
	
	//编辑
	public function edit(){
		$id = I('get.id');
		$uid = cookie(userid);
		$user_part = M('User_part');
		if(IS_POST){
			//p($_POST);die;
			if($this->model->create(I('post.'), 2)){
				if(FALSE !== $this->model->updateConf()){
					//修改中间表中数据
					$part = $this->model->field('part_id')->where('id='.$id)->find();
					$user_part->where('user_id='.$uid)->data($part)->save();
					$this->success('修改成功！', U('show', array('id' => $id)));
				}
			}
			$this->error($this->model->getError());
		
		}
		
		//获取栏目
		$cate = $this->cate->getTree();
		
		$data = $this->model->where(array('id'=>$id))->find();
		//p($data);
		//资料文档array_filter(explode(',', $data['downfile']));json_decode($data['downfile'],true)
		$row['downfile'] = $data['downfile'];		
		$row['downfileArr'] = array_filter(explode('###', $row['downfile']));
		$pic = json_decode($data['companypic']);
		
		$partid = explode(',',$data['part_id']);
		//dump($partid);
		//p($partid);
		//p($row['downfileArr']);
		//p($row);
		//修改时，获取的认证公司
		$cert = D('Certify')->field('company')->where('uid='.$uid)->find();
		//p($cert);
		//获取合作伙伴
		$parts = $this->part->field('id,companyname')->where(array('conf_user_id'=>$uid))->select();
		//$parts = $this->part->alias('a')->field('a.id,a.companyname,b.part_id')
		//->join('left join tzht_user_part b on b.user_id=a.conf_user_id')->where(array('conf_user_id'=>$uid,'a.id'=>array('in' , $data['part_id'])))->select();
		//$parts = array_column($parts,'id');
		
		//p($parts);
		//p($partid);
		$this->assign(array(
			'cate'=>$cate,
			'row' =>$row,
			'companypic'=>$pic,
			'data'=>$data,
			'cert'=>$cert,
			'parts'=>$parts,
			'partid'=>$partid
			)
		
		);
		//p($data);
		
		$this->display();
	}
	
	//审核人员：同意
	public function agreed(){
		$id = I('get.id');
		$uid = I('get.uid');
		$audit = D('Conference_audit');
		$audit2 = D('Conference_auditlist');
		$jpush = $this->user->where('id='.$uid)->find();
		$data =  $audit->where(array('conf_id'=>$id,'user_id'=>$uid))->setField('status',1);
		$auditlist =  $audit2->where(array('conf_id'=>$id,'user_id'=>$uid))->setField('status',1);
		
		if($data && $auditlist){
			//极光推送审核成功
			jgpushAgreed($jpush['jpush']);
			$code = array('status'=>'y','info'=>'您已同意');
		}else{
			$code = array('status'=>'n','info'=>'操作失败');
		}
		$this->ajaxReturn($code);
		
	}
	//审核人员：拒绝
	public function refused(){
		$id = I('get.id');
		$uid = I('get.uid');
		$audit = D('Conference_audit');
		$audit2 = D('Conference_auditlist');
		$jpush = $this->user->where('id='.$uid)->find();
		$data =  $audit->where(array('conf_id'=>$id,'user_id'=>$uid))->setField('status',2);
		$auditlist =  $audit2->where(array('conf_id'=>$id,'user_id'=>$uid))->setField('status',2);
		
		if($data && $auditlist){
			//极光推送拒绝提醒
			jgpushRefused($jpush['jpush']);
			$code = array('status'=>'y','info'=>'您已拒绝');
		}else{
			$code = array('status'=>'n','info'=>'操作失败');
		}
		$this->ajaxReturn($code);
	}
	
	
	//删除会议
	public function delete(){
		$id = I('get.id', 0);
		//待审核表
		$auditlist = D('Conferenceauditlist');
		$audit = D('Conferenceaudit');
		
		if($this->model->delete($id) !== FALSE){
			//发布者删除会议时，也删除掉待审核表中的相对应的id
			$auditlist->where(array('conf_id'=>$id))->delete();
			$audit->where(array('conf_id'=>$id))->delete();
			$code = array('status'=>'y','info'=>'会议删除成功');
		}else{
			$this->error($this->model->getError());
			$code = array('status'=>'n','info'=>'会议删除失败');
		}
		$this->ajaxReturn($code);
	}
	
	//显示单条数据
	public function show(){
		
		$data = $this->model->showOne();
		//p($data);
		$row['downfile'] = $data['downfile'];
		$row['downfileArr'] = array_filter(explode('###', $row['downfile']));
		//p($row);
		$pic = json_decode($data['companypic']);
		$this->assign(array(
			'data'=>$data,
			'row'=>$row,
			'companypic'=>$pic
		));
		
		$this->display();
	}
	
	//生成二维码
	public function dicaoliao($id){
		qrcode($id);
	}
	
	//审核人员列表
	public function auditlist(){
	
		$data = $this->user->auditPartici();
		
		//p($data);die;
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page']
			));

		$this->display();
	}
	
	public function signList(){
		$data = $this->user->signList();
		//p($data);die;
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page']
			));

		$this->display();
	}
	
	//我参加的 待审核列表
	public function myaudit(){
		$res = D('Conferenceauditlist')-> myauditlist();
		
		$this->assign(array(
			'data' => $res['data'],
			'page' => $res['page'],
			
		));
			
		
		$this->display();
		
	}
	
	




}