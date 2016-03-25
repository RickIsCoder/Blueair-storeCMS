<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
	/*
		开启静态缓存 $cache_time 配置于 \application\core\MY_controller
 		$this->output->cache($this->cache_time);
 		admin文件夹下的所有控制器方法涉及对DB的修改，不建议开启静态缓存
	*/


	/*
		构造函数，预加载所有的M层接口供C层调用
	*/
	public function __construct(){
		parent::__construct();
		$this->load->model('Admin_model','admin');
		$this->load->model('Scene_model','scene');
		$this->load->model('Pic_model','pic');
		$this->load->model('Feature_model','feature');
		// error_reporting(0);
	}

	/*
		后台index
	*/
	public function index(){
		$this->load->view('admin/index.html');
		// $this->output->cache($this->cache_time);
	}

	/*
		产品管理CMS
	*/
	public function productionCMS(){
		$data['production']=$this->admin->getAllProductionList();
        
		$this->load->view('backEnd/adminCMS',$data);
		// $this->output->cache($this->cache_time);

	}

	/*
		管理CMS
	*/
	public function userCMS(){
		$data['userInfo'] = $this->admin->getAllUserInfo();
		$this->load->view('admin/user.html',$data);
		// $this->output->cache($this->cache_time);
	}

	/*
		场景管理CMS
	*/
	public function sceneCMS(){
		$data['sceneList'] = $this->scene->getScene();
		foreach ($data['sceneList'] as &$list) {
			$list['scale']= $this->scene->getScale($list['sceneID']);
			foreach ($list['scale'] as &$scale) {
				$scale['production']= $this->scene->getSceneProduct($scale['scaleID']);
				foreach ($scale['production'] as &$production) {
					$productName = $this->admin->getProductionName($production['productID']);
					$production['productName'] = $productName[0]['productName'];
				}
				unset($production);
			}
			unset($scale);
		}
		unset($list);
		$this->load->view('admin/scene.html',$data);
		// $this->output->cache($this->cache_time);
	}


	/*
		添加的产品在写入DB前，用session记录，因此每次添加产品前，清除上次添加产品的session信息
	*/
	public function clearSeesionData(){
		foreach ($_SESSION['pic_path'] as $v) {
			unlink('./upload/'.$v);
		}
		unset($_SESSION['pic_path']);
	}


	/*
		产品参数和图片view页面，和添加产品，通用一套模板
		利用URL第二个片段区分
		$productID = $this->uri->segment(2);
		非空即为产品参数图片
		为空即为添加新产品
	*/
	public function productionDetail(){
		$productID = $this->uri->segment(2);
		$data['series'] = $this->admin->getSeriesList();
		if(is_numeric($productID)){
			$data['productionDetail'] = $this->admin->getProductionDetail($productID);
			$data['productionDetail']['pic'] = $this->pic->getProductionPic($productID);
		}
		else{
			$data['productionDetail'] = null;
		}
		$this->load->view('admin/edit.html',$data);
		// $this->output->cache($this->cache_time);
	}

	/*
		添加/编辑产品DB操作，存在productID则为编辑已有产品，更新DB，不存在则为添加的新产品，插入DB
	*/
	public function productionEdit(){
		$productID = $_POST['productID'];
		$data = $_POST;
		unset($data['productID']);
		if (is_numeric($productID)) {
			$this->admin->editProduction($productID,$data);
			unset($_SESSION['pic_path']);
			echo "success";
		}
		else{
			$newProductionID = $this->admin->addProduction($data);
			foreach ($_SESSION['pic_path'] as $v) {
	            $picData['relativeID'] = 'product_'.$newProductionID;
	            $picData['pic_path'] = $v;
	            $response['pictureID'] = $this->pic->addProductionPic($picData);
			}
			echo $newProductionID;
		}
	}

	/*
		删除产品
	*/
	/*
		添加系列
	*/
	public function serieAdd(){
		$data = array('serieName'=>$_POST['serieName']);
		$newSerieID=$this->admin->addSerie($data);
		echo $newSerieID;
	}

	public function serieDel(){
		$data = array('serieName'=>$_POST['serieName']);
		$f = $newSerieID=$this->admin->delSerie($_POST['serieID']);
		if ($f) {
			echo "success";
		}
	}

	
}