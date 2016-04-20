<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Index controller 
class Index extends MY_Controller {
	/*
		用构造函数读取用户登录状态。
		未登录跳至login
		登陆跳至对应的index页面
	*/
	public function __construct(){
		parent::__construct();
        if (!$_SESSION['username']) {
            redirect('login');
        }
	}

	/*
		Default function for welcome controller 
	*/
	public function index()
	{
        $this->load->view('index/index.html');
	}
    
	public function whyBlueAir()
	{
        $this->load->view('index/whyBlueAir.html');
	}
    
	public function whyCleaner()
	{
        $this->load->view('index/whyCleaner.html');
	}
    
	public function whichOne()
	{
        $this->load->view('index/whichOne.html');
	}
    
    public function productDetail(){
        $this -> load -> view('index/productDetail.html');
    }
    
	public function chooseForYou()
	{
        $this->load->view('index/chooseForYou.html');
	}

	//获取本次登陆的商店用户拥有的产品
	//返回json字符串(http://www.bejson.com/ 可以查看看json内容)
	public function getStoreProductions(){
		$this->load->model('admin_model','admin');
		$date = $this->admin->getProductionForExhibition();
		echo json_encode($date);
	}

	//获取所有场景信息
	public function getAllSceneInfo(){
		$this->load->model('Scene_model','scene');
		$sceneInfo = $this->scene->getScene();
		foreach ($sceneInfo as &$sceneItem) {
			$sceneItem['scale'] = $this->scene->getScale($sceneItem['sceneID']);
			foreach ($sceneItem['scale'] as &$scaleItem) {
				$scaleItem['productions'] = $this->scene->getScaleProduct($scaleItem['scaleID']);
			}
			unset($scaleItem);
		}
		unset($sceneItem);
		echo json_encode($sceneInfo);
	}
    
    public function getProductDetail(){
		$this->load->model('admin_model','admin');
        $productID = $_POST["productID"];
        $data = NULL;
        if(isset($productID)){
            $data = $this->admin->getProductionDetail($productID);
        }
        echo json_encode($data);
    }
    
}