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
        $this->load->model('Feature_model','feature');
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
            $this->load->view('salerAdmin/index.html');
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
            $data['userType'] = $_POST['userType'];
            $data['address'] = $_POST['address'];
            $data['remark'] = $_POST['remark'];
            $userID = $this->user->addUser($data);
            echo $userID;
        }
        else{
            echo FALSE;
        }
    }
    
    public function delUser(){
        if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['userType'] == 1){
            $userID = $_POST['userID'];
            if($userID == $_SESSION["userID"]){
                echo "不可以删除自己。";
            }
            else{
                $f = $this->user->delUser($userID);
                if($f){
                    echo "success"; 
                }
                else{
                    echo "failure";
                }
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
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $serieID = $_POST["serieID"];
            $data = $this->admin->getAdminProductsBySerieIs($serieID);
            echo json_encode($data);
        }
        else{
            echo "operation not allowed";
        }
    }

    //del production form DB 
    public function productionDel(){
        if( $_SESSION['userType'] == 1 && $_SERVER['REQUEST_METHOD'] == 'POST'){
            $f = $this->admin->delProduction($_POST['productionID']);
            if($f == TRUE) echo 'success';
        }
        else {
            echo "operation not allowed";
        }
    }

    //    add production to store
    public function addStoreProduction(){
        if($_SESSION['userType'] == 2 && $_SERVER['REQUEST_METHOD'] == 'POST'){
            $data['userID'] = $_SESSION['userID'];
            $data['productID'] = $_POST['productID'];
            $F = $this->admin->addStoreProduction($data);
            if($F)echo 'success';
            else echo 'error';
        }
        else{
            echo "operation not allowed";
        }
    }

    // del production from store
    public function delStoreProduction(){
        if($_SESSION['userType'] == 2 && $_SERVER['REQUEST_METHOD'] == 'POST'){
            $data['userID'] = $_SESSION['userID'];
            $data['productID'] = $_POST['productID'];
            $F = $this->admin->delStoreProduction($data);
            if($F)echo 'success';
            else echo 'error';   
        }
        else{
            echo "operation not allowed";
        }
    }


    //insert OR edit product
    //param : form(int,string,int),productID(int)
    //method : POST
    public function productionEdit(){
        if( $_SESSION['userType'] != 1 OR $_SERVER['REQUEST_METHOD'] != 'POST'){
            echo "operation not allowed";
            die;
        }

        $type =  $_POST['type'];
        switch ($type) {
            case 1:
                echo "new";
                /*
                    1.添加参数,返回ID
                    2.根据ID 循环添加临时存储于session的图片
                    3.destory session['pic']
                    4.根据ID 循环添加临时存储于session的feture
                    5.destory session['feature']
                */

                break;
            
            case 2:
                echo "edit";
                /*
                    更新参数即可
                */
                break;
            
            default:
                echo "operation error";
                break;
        }
    }


    /*
        图片操作
    */

    //上传图片   
    //param : file(file),productID(int)
    //method:POST
    public function upload()
    {
        $config['upload_path']      = './upload/';
        $config['allowed_types']    = 'gif|jpg|png|jpeg';
        $config['max_size']     = 4096;
        $config['max_width']    = 20000;
        $config['max_height']   = 20000;
        $config['file_name']    = md5(uniqid(rand()));
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file')){
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        }
        else{
            $data = array('upload_data' => $this->upload->data());
        }

        if($_POST['productID'] == -1){//新添加的pic_path用session记录
            $_SESSION['pic_path'][] = $data['upload_data']['file_name'];
            $response['pic_path'] = $data['upload_data']['file_name'];
            $response['pictureID'] = $data['upload_data']['file_name'];
        }
        else{
            $response['pic_path'] = $data['upload_data']['file_name'];

            $picData['relativeID'] = 'product_'.$_POST['productID'];
            $picData['pic_path'] = $response['pic_path'];
            $response['pictureID'] = $this->pic->addProductionPic($picData);
        }

       echo json_encode($response);
    }

    //删除图片
    //param : pictureID(int)
    //method:POST
    public function delPicture(){
        if( $_SESSION['userType'] != 1 OR $_SERVER['REQUEST_METHOD'] != 'POST'){
            echo "operation not allowed";
            die;
        }

        $pid = $_POST['pictureID'];
        if($_POST['productID'] != -1){
            $this->pic->delProductionPic($pid);//数据库删除图片信息（包含物理文件删除）
            echo "del_success";            
        }
        else{
            unlink('./upload/'.$pid);//物理文件删除
            echo "del_success"; 
        }

    }



    
    /*
        feature&&tips
    */

    //获取指定ID的param，pic，featureItems，TipsItems
    //param : productionID(int)
    //method:POST
    public function feature(){
        $data['productionID'] = $_POST['pId'];
        $data['param'] = $this->admin->getProductionDetail($data['productionID']);
        $data['pic'] = $this->pic->getProductionPic($data['productionID']);
        $data['featureItems'] = $this->feature->getFeatureItems($data['productionID']);
        $data['TipsItems'] = $this->feature->getTipsItems($data['productionID']);
        echo json_encode($data);
    }

    //添加产品的feature 
    //param:productionID(int),featureTitle(string),featureContent(string) 
    //method:POST
    public function addFeature(){
        $data['featureTitle'] = $_POST['title'];
        $data['featureContent'] = $_POST['content'];

        if($_POST['productID'] == -1){
            $_SESSION['feature'][] = $data;//新添加的feature用session记录
            echo TRUE;
        }else{
            $data['productID'] = $_POST['productID'];
            $newFeatureID = $this->feature->addFeature($data);
            echo $newFeatureID;
        }
    }

    //删除产品feature 
    //param:featureID(int) 
    //method:POST
    public function delFeature(){
        $featureID = $_POST['featureID'];
        $F = $this->feature->delFeature($featureID);
        if($F) echo "success";
    }

    // 编辑产品feature 
    //param:featureID(int),featureTitle(string),featureContent(string) 
    //method:POST
    public function editFeature(){
        $featureID = $_POST['featureID'];
        $data['featureTitle'] = $_POST['featureTitle'];
        $data['featureContent'] = $_POST['featureContent'];
        $F = $this->feature->editFeature($featureID,$data);
        if($F) echo "success";
    }

    //添加tips 
    //param:tipsID(int),tips(string) 
    //method:POST
    public function addTips(){
        if ($_POST['productID'] == -1) {
            $_SESSION['tips'][] = $_POST['tips'];//新的tips用 SESSION 记录
            echo TRUE;
        }
        else{
            $data['productID'] = $_POST['productID'];
            $data['tips'] = $_POST['tips'];
            $newTipsID = $this->feature->addTips($data);
            echo $newTipsID;
        }
    }

    //编辑tips 
    //param:tipsID(int),tips(string) 
    //method:POST
    public function editTips(){
        $tipsID = $_POST['tipsID'];
        $data['tips'] = $_POST['tips'];
        $F = $this->feature->editTips($tipsID,$data);
        if($F) echo "success";
    }

    //删除tips 
    //param:tipsID(int) 
    //method:POST
    public function delTips(){
        $tipsID = $_POST['tipsID'];
        $F = $this->feature->delTips($tipsID);
        if($F) echo "success";
    }

    //清除session信息 
    //param:clear(boolean) 
    //method:POST
    public function clearSeesionData(){
        if($_POST['clear']){
            foreach ($_SESSION['pic_path'] as $v) {
                unlink('./upload/'.$v);
            }
            unset($_SESSION['pic_path']);
            echo 'clear';
        }
    }






}