<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feature extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Feature_model','feature');
        //error_reporting(0);
    }

    /*
    	获取产品ID
    	$data['productionID'] = $this->uri->segment(2);
    	该产品Feature页详细，命名可能不一致，但是数据不差
    */
	public function feature(){
		$data['productionID'] = $this->uri->segment(2);
		$data['des'] = $this->feature->getFeatureDes($data['productionID']);
		$data['label'] = $this->feature->getFeatureLabel($data['productionID']);
		$data['featureItems'] = $this->feature->getFeatureItems($data['productionID']);
		$data['TipsItems'] = $this->feature->getTipsItems($data['productionID']);
		$this->load->view('admin/feature.html',$data);
		
		// $this->output->cache($this->cache_time);
	}


	/*
		添加产品的feature
	*/
	public function addFeature(){
		$data['productID'] = $_POST['productID'];
		$data['featureTitle'] = $_POST['title'];
		$data['featureContent'] = $_POST['content'];
		$newFeatureID = $this->feature->addFeature($data);
		echo $newFeatureID;
	}

	/*
		删除产品feature
	*/
	public function delFeature(){
		$featureID = $_POST['featureID'];
		$F = $this->feature->delFeature($featureID);
		if($F) echo "success";
	}

	/*
		编辑产品feature
	*/
	public function editFeature(){
		$featureID = $_POST['featureID'];
		$data['featureTitle'] = $_POST['featureTitle'];
		$data['featureContent'] = $_POST['featureContent'];
		$F = $this->feature->editFeature($featureID,$data);
		if($F) echo "success";
	}

	/*
		Description是产品必带的且只有一条的属性，添加产品的时候自动创建初值为NULL，没有添加/删除接口，只有修改
	*/
	public function editDescription(){
		$productID = $_POST['productID'];
		$des['description'] = $_POST['des'];
		$F = $this->feature->editDescription($productID,$des);
		if($F) echo "success";
	}

	/*
		同上
	*/
	public function editLabel(){
		$productID = $_POST['productID'];
		$des['label'] = $_POST['label'];
		$F = $this->feature->editLabel($productID,$des);
		if($F) echo "success";
	}


	/*
		添加tips	
	*/
	public function addTips(){
		$data['productID'] = $_POST['productID'];
		$data['tips'] = $_POST['tips'];
		$newTipsID = $this->feature->addTips($data);
		echo $newTipsID;
	}

	/*
		编辑tips
	*/
	public function editTips(){
		$tipsID = $_POST['tipsID'];
		$data['tips'] = $_POST['tips'];
		$F = $this->feature->editTips($tipsID,$data);
		if($F) echo "success";
	}

	/*
		删除tips
	*/
	public function delTips(){
		$tipsID = $_POST['tipsID'];
		$F = $this->feature->delTips($tipsID);
		if($F) echo "success";
	}


}
