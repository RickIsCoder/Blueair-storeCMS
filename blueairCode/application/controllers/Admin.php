<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// controller for page jump!
class Admin extends MY_Controller {
	/*
		用构造函数读取用户登录状态。
		未登录跳至login
		登陆跳至对应的index页面
	*/
	public function __construct(){
		parent::__construct();
        if (!$_SESSION['username']) {
            redirect('login');
            die();
        }
        $this->load->model('Admin_model','admin');
        $this->load->model('User_model','user');
		$this->load->model('Scene_model','scene');
		$this->load->model('Pic_model','pic');
	}

	/*
		Default function for welcome controller 
	*/
	public function index()
	{
        if($_SESSION['userType'] == 1){
            $this->load->view('superAdmin/index.html');
        }
        else{
            $this->load->view('salerAmin/index.html');
        }
	}
    
    public function users(){
        if($_SESSION['userType'] == 1){
            $this->load->view('superAdmin/users.html');
        }
        else{
            redirect('index');
        }
    }
    
    // /admin/getUserList
    public function getUserList(){
        if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['userType'] == 1){
            $userList = $this->user->getAllUserInfo();
            echo json_encode($userList);
        }
        else{
            echo "operation not allowed";
        }
    }
    
    public function addUser(){
        if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['userType'] == 1){
            $data['username'] = $_POST['username'];
            $data['password'] = $_POST['password'];
            $data['userType'] = $_POST['type'];
            $data['address'] = $_POST['address'];
            $data['remark'] = $_POST['remark'];
            $userID = $this->user->addUser($data);
            echo $userID;
        }
        else{
            echo "operation not allowed";
        }
    }
    
    public function delUser(){
        if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['userType'] == 1){
            $userID = $_POST['userID'];
            $f = $this->user->delUser($userID);
            if($f){
                echo "success"; 
            }
            else{
                echo "failure";
            }
        }
        else{
            echo "operation not allowed";
        }
    }
    
    public function editUser(){
        if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['userType'] == 1){
            $userID = $_POST['userID'];
            $data['username'] = $_POST['username'];
            $data['password'] = $_POST['password'];
            $data['userType'] = $_POST['userType'];
            $data['address'] = $_POST['address'];
            $data['remark'] = $_POST['remark'];
            $f = $this->user->editUser($userID,$data);
            if($f){
                echo "success";
            }
            else{
                echo "failure";
            }
        }
        else{
            echo "operation not allowed";
        }
    }
    
    public function getProductList(){
        if(true || $_SERVER['REQUEST_METHOD'] == "POST"){
            $serieId = $_POST["serieId"];
            var_dump($this->admin->getAdminProductsBySerieIs(25));
        }
        else{
            echo "operation not allowed";
        }
    }
}