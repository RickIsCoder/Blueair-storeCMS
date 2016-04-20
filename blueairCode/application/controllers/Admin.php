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
		$this->load->model('scene_model','scene');
        $this->load->model('Feature_model','feature');
		$this->load->model('Pic_model','pic');
	}

    /*
        验证用户类型和请求方式
    */
    function verifyUsertypeMethod($type){  
        if( $_SESSION['userType'] != $type OR $_SERVER['REQUEST_METHOD'] != 'POST'){
            echo "operation not allowed";
            die;
        }
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
    
    public function scene(){
        if($_SESSION['userType'] == 1){
            $this->load->view('superAdmin/scene.html',$data);
        }
        else{
            redirect('index');
        }
    }

    // /admin/getUserList
    public function getUserList(){
        $this->verifyUsertypeMethod(1);
        $userList = $this->user->getAllUserInfo();
        echo json_encode($userList);
    }
    
    public function addUser(){
        $this->verifyUsertypeMethod(1);
        $data['username'] = $_POST['username'];
        $data['password'] = $_POST['password'];
        $data['userType'] = $_POST['userType'];
        $data['address'] = $_POST['address'];
        $data['remark'] = $_POST['remark'];
        $userID = $this->user->addUser($data);
        echo $userID;
    }
    
    public function delUser(){
        $this->verifyUsertypeMethod(1);
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
    
    public function editUser(){
        $this->verifyUsertypeMethod(1);
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
    
    public function getProductList(){
        //$this->verifyUsertypeMethod();
        $serieID = $_POST["serieID"];
        $data = $this->admin->getAdminProductsBySerieId($serieID);
        echo json_encode($data);
    }

    //del production form DB 
    public function productionDel(){
        $this->verifyUsertypeMethod(1);
        $f = $this->admin->delProduction($_POST['productionID']);
        if($f == TRUE) echo 'success';
    }

    //    add production to store
    public function addStoreProduction(){
        $this->verifyUsertypeMethod(2);
        $data['userID'] = $_SESSION['userID'];
        $data['productID'] = $_POST['productID'];
        $F = $this->admin->addStoreProduction($data);
        if($F)echo 'success';
        else echo 'error';
    }

    // del production from store
    public function delStoreProduction(){
        $this->verifyUsertypeMethod(2);
        $data['userID'] = $_SESSION['userID'];
        $data['productID'] = $_POST['productID'];
        $F = $this->admin->delStoreProduction($data);
        if($F)echo 'success';
        else echo 'error';
    }


    //insert OR edit product
    //param : form(int,string,int),productID(int)
    //method : POST
    public function productionEdit(){
        $this->verifyUsertypeMethod(1);
        $type =  $_POST['type'];
        switch ($type) {
            case 1:
                $data = $_POST;
                unset($data['type']);
                unset($data['productID']);
                
                $newID = $this->admin->addProduction($data);

                foreach ($_SESSION['pic_path'] as $picItem) {
                    $pic['productID']  = $newID;
                    $pic['pic_path']    = $picItem;
                    $this->pic->addProductionPic($pic);
                }   
                unset($_SESSION['pic']);

                foreach($_SESSION['feature'] as $featureItem){
                    $feature['productID'] = $newID;
                    $feature['featureTitle'] = $featureItem['featureTitle'];
                    $feature['featureContent'] = $featureItem['featureContent'];
                    $this->feature->addFeature($feature);
                }
                unset($_SESSION['feature']);

                foreach ($_SESSION['tips'] as $tipItem) {
                    $tips['productID'] = $newID;
                    $tips['tips'] = $tipItem;
                    $newTipsID = $this->feature->addTips($tips);
                }
                unset($_SESSION['tips']);

                break;
            
            case 2:
                $data = $_POST;
                $id = $data['productID'];
                unset($data['type']);
                unset($data['productID']);
                foreach ($data as &$v) { //转变checkBox.val， Boolean转为tinyint
                    if($v === 'true') $v = 1;
                }

                $newID = $this->admin->editProduction($id,$data);
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
    public function upload(){
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
            $pictureId = uniqid();
            $_SESSION['pic_path'][$pictureId] = $data['upload_data']['file_name'];
            $response['pic_path'] = base_url("/upload/" . $data['upload_data']['file_name']);
            $response['pictureID'] = $pictureId;
        }
        else{
            $picData['productID'] = $_POST['productID'];
            $picData['pic_path'] = $data['upload_data']['file_name'];
            
            $response['pic_path'] = base_url("/upload/" . $picData['pic_path']);
            $response['pictureID'] = $this->pic->addProductionPic($picData);
        }

       echo json_encode($response);
    }

    //删除图片
    //param : pictureID(int)
    //method:POST
    public function delPicture(){
        $this->verifyUsertypeMethod(1);

        $pid = $_POST['pictureID'];
        if($_POST['productID'] != -1){
            $this->pic->delProductionPic($pid);//数据库删除图片信息（包含物理文件删除）
            echo "del_success";            
        }
        else{
            unlink('./upload/'.$_SESSION['pic_path'][$pid]);//物理文件删除
            unset($_SESSION['pic_path'][$pid]);
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
        $this->verifyUsertypeMethod(1);
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
        $this->verifyUsertypeMethod(1);

        $data['featureTitle'] = $_POST['title'];
        $data['featureContent'] = $_POST['content'];

        if($_POST['productID'] == -1){
            $featureID = uniqid();
            $data['featureID'] = $featureID;
            $_SESSION['feature'][$featureID] = $data;//新添加的产品的feature用session记录
        }else{
            $data['productID'] = $_POST['productID'];
            $newFeatureID = $this->feature->addFeature($data);
            $data['featureID'] = $newFeatureID;
        }

        echo json_encode($data);//已有的产品会额外返回商品ID
    }

    //删除产品feature 
    //param:featureID(int),productionID(int)
    //method:POST
    public function delFeature(){
        $this->verifyUsertypeMethod(1);
        $featureID = $_POST['featureID'];

        if ($_POST['productID'] == -1) {
            unset($_SESSION['feature'][$featureID]);
            echo "del_success";
        }
        else{
            $F = $this->feature->delFeature($featureID);
        }

        echo $F ? "del_success" : "del_failure";
    }

    //添加tips 
    //param:productID(int),tips(string) 
    //method:POST
    public function addTips(){
        $this->verifyUsertypeMethod(1);

        if ($_POST['productID'] == -1) {
            $tipsID = uniqid();
            $_SESSION['tips'][$tipsID] = $_POST['tips'];//新的tips用 SESSION 记录
            $data['tips'] = $_POST['tips'];
            $data['tipsID'] = $tipsID;
        }
        else{
            $data['productID'] = $_POST['productID'];
            $data['tips'] = $_POST['tips'];
            $newTipsID = $this->feature->addTips($data);
            $data['tipsID'] = $newTipsID;
        }

        echo json_encode($data);
    }

    //删除tips 
    //param:tipsID(int) ,productID(int)
    //method:POST
    public function delTips(){
        $this->verifyUsertypeMethod(1);
        if ($_POST['productID'] == -1) {
            unset($_SESSION['tipsID'][$tipsID]);
            echo "del_success";
        }
        else{
            $tipsID = $_POST['tipsID'];
            $F = $this->feature->delTips($tipsID);
            echo $F ? "del_success" : "del_failure";
        }

    }

    //清除session信息 
    //param:clear(boolean) 
    //method:POST
    public function clearSeesionData(){
        $this->verifyUsertypeMethod(1);

        if($_POST['clear']){
            foreach ($_SESSION['pic_path'] as $v) {
                unlink('./upload/' . $v);
            }

            unlink('./uploadIcon/' . $_SESSION['sceneIcon']);

            unset($_SESSION['pic_path']);
            unset($_SESSION['feature']);
            unset($_SESSION['tips']);
            unset($_SESSION['sceneIcon']);
            unset($_SESSION['scale']);
            echo 'clear';
        }
    }


    ################## 应用场景管理 ########################

    public function sceneData(){
        if($_SESSION['userType'] == 1){
            $data['sceneList'] = $this->scene->getScene();
            foreach ($data['sceneList'] as &$list) {
                $list['scale']= $this->scene->getScale($list['sceneID']);
                foreach ($list['scale'] as &$scale) {
                    $scale['production']= $this->scene->getScaleProduct($scale['scaleID']);
                    $scale['noExistProdution'] = $this->scene->checkSceneProduction($scale['scaleID']);
                    foreach ($scale['production'] as &$production) {
                        $productName = $this->admin->getProductionName($production['productID']);
                        $production['productName'] = $productName[0]['productName'];
                    }
                    unset($production);
                }
                unset($scale);
            }
            unset($list);
            echo json_encode($data);
        }
        else{
            echo "error";
        }
    }
    
    //获取场景信息
    public function sceneInfo(){
        $scene = $this->scene->getSceneById($_POST['sceneID']);
        if($scene == NULL){
            echo "error";
            die();
        }
        $scene['scale']= $this->scene->getScale($_POST['sceneID']);
        foreach ($scene['scale'] as &$scale) {
            $scale['production']= $this->scene->getScaleProduct($scale['scaleID']);
            foreach ($scale['production'] as &$production) {
                $productName = $this->admin->getProductionName($production['productID']);
                $production['productName'] = $productName[0]['productName'];
            }
            unset($production);
        }
        unset($scale);

        echo json_encode($scene);
    }

    //删除场景
    public function delScene(){
        $sceneID = $_POST['sceneID'];
        $F = $this->scene->sceneDel($sceneID);
        echo $F;
    }

    //编辑场景
    public function editScene(){
        if($_POST['sceneID']!=-1){
            $sceneID = $_POST['sceneID'];
            $sceneName = $_POST['sceneName'];
            $F = $this->scene->sceneEdit($sceneID,$sceneName);
            echo $F;
        }
        else{
            $sceneName['sceneName'] = $_POST['sceneName'];
            $sceneName['scenePic_path'] = $_SESSION['sceneIcon'];//新场景的图存在session
            $newID = $this->scene->sceneAdd($sceneName);
            $scale['sceneID'] = $newID;
            foreach ($_SESSION['scale'] as $scaleItem) {
                $scale['scaleName'] = $scaleItem;
                $this->scene->scaleAdd($scale);
            }
            unset($scale);
            unset($_SESSION['scale']);
/*
            $sceneData['scenePic_path'] = $_SESSION['sceneIcon'];
            $F = $this->scene->updateIcon($newID,$sceneData);
*/
            unset($_SESSION['sceneIcon']);
            echo $F;
        }

    }

    //添加规模
    public function addScale(){
        if($_POST['sceneID'] == -1){
            $newID = uniqid();
            $_SESSION['scale'][$newID] = $_POST['scale'];
            echo $newID;
        }
        else{
            $scale["scaleName"] = $_POST['scale'];
            $scale["sceneID"] = $_POST['sceneID'];
            $newID = $this->scene->scaleAdd($scale);
            echo $newID;
        }
    }

    //删除规模
    public function delScale(){
        $scaleID = $_POST['scaleID'];
        $f = $this->scene->scaleDel($scaleID);
        if($f) echo "success";
    }


    //删除场景相关产品
    //param:productionID(int)
    //return boolean
    public function delSceneProduction(){
        $sceneProductID= $_POST['productionID'];
        $f = $this->scene->sceneProductionDel($sceneProductID);
        echo $f;
    }

    //get不在指定规模列表中的其他产品
    //param:scaleID(int)
    public function checkSceneProduction(){
        $scaleID = $_POST['scaleID'];
        $noExistProdution = $this->scene->checkSceneProduction($scaleID);
        echo json_encode($noExistProdution);
    }

    //添加场景产品 
    //param:scaleID(int),productionID(int)
    public function addSceneProduction()
    {
        $data['scaleID']= $_POST['scaleID'];
        $data['productID']= $_POST['productionID'];
        $data['sceneProductID'] = $this->scene->sceneProductionAdd($data);

        if(is_numeric($data['sceneProductID'])){
           echo json_encode($data); 
        }  
        else {
            echo 'error';
        }
    }

    //更换场景配图
    public function uploadIcon(){
        $config['upload_path']      = './uploadIcon/';
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

        if($_POST['sceneID'] == -1){
            unlink('./uploadIcon/' . $_SESSION['sceneIcon']);//物理文件删除
            $_SESSION['sceneIcon'] = $data['upload_data']['file_name'];
            echo base_url('/uploadIcon/' . $_SESSION['sceneIcon']);
        }
        else{
            $sceneData['scenePic_path'] = $data['upload_data']['file_name'];
            $F = $this->scene->updateIcon($_POST['sceneID'],$sceneData);
            if (!$F) {
                echo "error";
            }
            else{
                echo base_url('/uploadIcon/' . $data['upload_data']['file_name']);;
            }
        }
    }

    //email test
    public function sendEmail(){
        $this->load->library('email');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.qq.com';
        $config['smtp_user'] = 'blueairsales@qq.com';
        $config['smtp_pass'] = 'Blueair2016';
        $config['mailtype'] = 'html';
        $config['validate'] = true;
        $config['priority'] = 1;
        $config['crlf'] = "\r\n";
        $config['smtp_port'] = 25;
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);

        $this->email->from('blueairsales@qq.com', 'BlueAir');
        $this->email->to('blueairsales@qq.com');//steven.wang@blueair.cn
        $this->email->cc('');//抄送
        $this->email->bcc('');//暗送

        $this->email->subject('');
        $this->email->message('测试'.json_encode($_POST));

        $F = $this->email->send();
        echo $F;    
    }

}