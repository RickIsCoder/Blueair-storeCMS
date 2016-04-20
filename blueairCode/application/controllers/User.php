<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('User_model','user');
	}
    
    public function login(){
        if($_SESSION['username']){
            redirect('index');
        }
		$this->load->view('index/login.html');
	}
    
    public function verify(){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$userData = $this->user->verify($username,$password);
		if($userData != FALSE){
			$_SESSION['username'] = $userData['username'];
			$_SESSION['userType'] = $userData['userType'];
			$_SESSION['userID'] = $userData['userID'];
			echo "success";
		}
		else{
			echo "failure";
		}
	}
    
    /*
		退出按钮
		点击清session，并跳转至login页面p
	*/
	public function logout(){
		if(session_destroy()){
			header('Location:login');
		};
	}
}