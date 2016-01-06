<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Welcome controller 
class Welcome extends MY_Controller {
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
//		$this->load->view('index/index.html');
        redirect("cms");
	}

	
	/*
		退出按钮
		点击清session，并跳转至login页面
	*/
	public function clearSession(){
		if(session_destroy()){
			header('Location:login');
		};
	}
}