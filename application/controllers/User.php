<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('User_model','userModel');
	}
    
    public function login(){
		$this->load->view('index/login.html');
	}
    
    public function verify(){
		$username = $_POST['username'];
		$password = $_POST['password'];

		$this->load->model('admin_model','adminModel');
		$verifyData = $this->adminModel->verify($username,$password);
		if($verifyData != FALSE){
			$_SESSION['username'] = $verifyData[0]['username'];
			$_SESSION['userType'] = $verifyData[0]['userType'];
			$_SESSION['userID'] = $verifyData[0]['userID'];
			echo $_SESSION['userType'];
		}
		else{
			echo "error";
		}
	}

}