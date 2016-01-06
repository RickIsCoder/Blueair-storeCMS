<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('User_model','admin');
	}

	/*
		添加用户
	*/
	public function addUser(){
		//var_dump($_POST);
		$data['username'] = $_POST['username'];
		$data['password'] = $_POST['password'];
		$data['userType'] = $_POST['type'];
		$data['address'] = $_POST['address'];
		$data['remark'] = $_POST['remark'];
		$data['userID'] = $this->admin->addUser($data);
		echo $data['userID'];
	}

	/*
		编辑用户信息
	*/
	public function editUser(){
		$userID = $_POST['userID'];
		$data['username'] = $_POST['username'];
		$data['password'] = $_POST['password'];
		$data['userType'] = $_POST['userType'];
		$data['address'] = $_POST['address'];
		$data['remark'] = $_POST['remark'];
		$f = $this->admin->editUser($userID,$data);
		if($f)echo "success"; else echo "error";
	}

	/*
		删除用户
	*/
	public function delUser(){
		$userID = $_POST['userID'];
		$f = $this->admin->delUser($userID);
		if($f)echo "success"; else echo "error";
	}
}

