<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scene extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Scene_model','scene');
	}

	/*
		添加一个场景
	*/
	public function addScene(){
		$sceneName['sceneName'] = $_POST['sceneName'];
		$newID = $this->scene->sceneAdd($sceneName);
		if(is_numeric($newID))echo "success";
	}

	/*
		删除场景
	*/
	public function delScene(){
		$sceneID = $_POST['sceneID'];
		$f = $this->scene->sceneDel($sceneID);
		if($f) echo "success";
	}

	/*
		编辑场景
	*/
	public function editScene(){
		$sceneID = $_POST['sceneID'];
		$sceneName = $_POST['sceneName'];
		$f = $this->scene->sceneEdit($sceneID,$sceneName);
		if($f) echo "success";
		
	}

	/*
		添加规模
	*/
	public function addScale(){
		$scale= $_POST;
		$newID = $this->scene->scaleAdd($scale);
		if(is_numeric($newID))echo "success";

	}

	/*
		删除规模
	*/
	public function delScale(){
		$scaleID = $_POST['scaleID'];
		$f = $this->scene->scaleDel($scaleID);
		if($f) echo "success";
	}

	/*
		编辑规模
	*/
	public function editScale(){
		$scaleID = $_POST['scaleID'];
		$scaleName = $_POST['scaleName'];
		$f = $this->scene->scaleEdit($scaleID,$scaleName);
		if($f) echo "success";
	}

	/*
		删除场景相关产品
	*/
	public function delSceneProduction(){
		$sceneProductID= $_POST['productionID'];
		$f = $this->scene->sceneProductionDel($sceneProductID);
		if($f) echo "success";
	}

	/*
		get不在指定规模列表中的其他产品
	*/
	public function checkSceneProduction(){
		$scaleID = $_POST['scaleID'];
		$noExistProdution = $this->scene->checkSceneProduction($scaleID);
		echo json_encode($noExistProdution);
	}

	/*
		添加场景产品
	*/
	public function addSceneProduction()
	{
		$data['scaleID']= $_POST['scaleID'];
		$data['productID']= $_POST['productionID'];
		$newID = $this->scene->sceneProductionAdd($data);
		if(is_numeric($newID))echo "success";
	}




}
