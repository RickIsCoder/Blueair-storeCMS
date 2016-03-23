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

}