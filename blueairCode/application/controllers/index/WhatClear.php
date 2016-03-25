<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	index文件夹下的 loadview主要用于展示
	可以考虑开启静态缓存
*/
class WhatClear extends CI_Controller {
	//同时对比产品最大数
	public $compareLength = 3;

	public function __construct(){
		parent::__construct();
		$this->load->model('Admin_model','admin');
		$this->load->model('Pic_model','pic');
		$this->load->model('Feature_model','feature');
		$this->load->model('Scene_model','scene');
		error_reporting(0);
	}

	/*
		什么样的净化器 index页
	*/
	public function index()
	{
		$this->load->view('index/whatClear/whatClear.html');
		// $this->output->cache($this->cache_time);
	}
	
	/*
		系列页
	*/
	public function serieList(){
		$data['serieList'] = $this->admin->getSeriesList();
		$this->load->view('index/whatClear/serieList.html',$data);
		// $this->output->cache($this->cache_time);
	}

	/*
		系列详情
	*/
	public function serieDetail(){
		$serie['serieID'] = $this->uri->segment(2);
		$serie['production'] = $this->admin->getProductionList($serie['serieID']);
		$serie['serieName'] = $this->admin->getSeriesName($serie['serieID']);
		$this->load->view('index/whatClear/serieDetail.html',$serie);
		// $this->output->cache($this->cache_time);
	}

	/*
		产品详细，不含参数，主要是各种描述
	*/
	public function productDeatail(){
		$production['ID'] = $this->uri->segment(2);
		$production['name'] = $this->admin->getProductionName($production['ID']);
		$production['pic'] = $this->pic->getProductionPic($production['ID']);
		$production['des'] = $this->feature->getFeatureDes($production['ID']);
		$production['label'] = $this->feature->getFeatureLabel($production['ID']);
		$production['tips'] = $this->feature->getTipsItems($production['ID']);
		$this->load->view('index/WhatClear/product.html',$production);
		// $this->output->cache($this->cache_time);
	}

	/*
		feature详细
	*/
	public function feature(){
		$production['ID'] = $this->uri->segment(2);
		$production['name'] = $this->admin->getProductionName($production['ID']);
		$production['feature'] = $this->feature->getFeatureItems($production['ID']);
		$this->load->view('index/WhatClear/feature.html',$production);
		// $this->output->cache($this->cache_time);
	}

	/*
		产品参数
	*/
	public function parameter(){
		$production['ID'] = $this->uri->segment(2);
		$production['name'] = $this->admin->getProductionName($production['ID']);
		$production['parameter'] = $this->admin->getProductionDetail($production['ID']);
		$this->load->view('index/WhatClear/parameter.html',$production);
		// $this->output->cache($this->cache_time);
	}

	/*
		对比页
	*/
	public function compare(){
		$data['compareID'] = $_SESSION['compareID'];
		$compareData['production']=$this->admin->getAllProductionList();
		foreach ($data['compareID'] as $key => $value) {
			if(is_numeric($value)){
				$compareData['compareDetail'][]= $this->admin->getProductionDetail($value);
			}
		}
		$this->load->view('index/whatClear/compare.html',$compareData);
		//对比页变化太大，不建议开启静态缓存
		// $this->output->cache($this->cache_time); 
	}

	/*
		加入对比动作
	*/
	public function addCompare(){
		$productionID = $_POST['productionID'];
		if(is_numeric($productionID)){
			if(in_array($productionID, $_SESSION['compareID'])){
				echo '已经加入对比';
				return ; 
			}
			elseif(sizeof($_SESSION['compareID']) >= $this->compareLength){
				echo '最多可以对比3个产品';
				return ; 
			}
			else{
				$_SESSION['compareID'][] = $productionID;
				if ($_POST['page_position']=='compare') {
					$production = $this->admin->getProductionDetail($productionID);
					echo json_encode($production);
				}
				return ;
			}
		}
	}

	/*
		删除对比动作
	*/
	public function delCompare(){
		$productionID = $_POST['productionID'];
		foreach ($_SESSION['compareID'] as $key => $value) {
			if($value == $productionID)	unset($_SESSION['compareID'][$key]);
		}
		echo 'success';
	}

	/*
		过滤器和配件页面
	*/
	public function filter(){
		$this->load->view('index/whatClear/filter.html');
	}

	/*
		帮你选页面
	*/
	public function help(){
		$data['scene'] = $this->scene->getScene();
		$this->load->view('index/whatClear/help.html',$data);
	}

	/*
		get 指定sceneID的 scale 
	*/
	public function getScale(){
		$sceneID = $_POST['sceneID'];
		$getScale = $this->scene->getScale($sceneID);
		echo json_encode($getScale);
	}

	/*
		get 指定sceneID&scaleID的 推荐
	*/

	public function advice(){
		$scaleID = $_POST['scaleID'];
		$scale['production']= $this->scene->getSceneProduct($scaleID);
		foreach ($scale['production'] as &$production) {
			$productName = $this->admin->getProductionName($production['productID']);
			$production['productName'] = $productName[0]['productName'];
		}
		unset($production);
		echo json_encode($scale);
	}


}
