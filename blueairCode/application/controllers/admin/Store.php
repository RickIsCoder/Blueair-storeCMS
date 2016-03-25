<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Admin_model','admin');
		$this->load->model('Pic_model','pic');
		//error_reporting(0);
	}

	/*
		store登陆后的首页
	*/
	public function index()
	{
		$data['production'] = $this->admin->getStoreProductionList($_SESSION['userID']);
		$this->load->view('index/whatClear/cms_list.html',$data);	
		$this->output->cache($this->cache_time);	
	}


	/*
		store只能从“总的数据库”里面添加删除产品，没有修改权限	
	*/

	/*
		添加
	*/
	public function addStoreProduction(){
		$data['userID'] = $_SESSION['userID'];
		$data['productID'] = $_POST['productID'];
		$F = $this->admin->addStoreProduction($data);
		if($F)echo 'success';
		else echo 'error';
	}

	/*
		删除
	*/
	public function delStoreProduction(){
		$data['userID'] = $_SESSION['userID'];
		$data['productID'] = $_POST['productID'];
		$F = $this->admin->delStoreProduction($data);
		if($F)echo 'success';
		else echo 'error';
	}
}
