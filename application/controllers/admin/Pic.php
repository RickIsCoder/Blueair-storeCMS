<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
    编辑/添加产品中关于图片的两个操作，上传和删除
*/

class Pic extends MY_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Pic_model','pic');
        // error_reporting(0);
    }

    /*
        上传图片
    */    
    public function upload()
    {
        $config['upload_path']      = './upload/';
        $config['allowed_types']    = 'gif|jpg|png|jpeg';
        $config['max_size']     = 4096;
        $config['max_width']    = 20000;
        $config['max_height']   = 20000;
        $config['file_name']	= md5(uniqid(rand()));
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file')){
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        }
        else{
            $data = array('upload_data' => $this->upload->data());
        }

        if($_POST['productID'] != ''){
            $response['pic_path'] = $data['upload_data']['file_name'];
            $picData['relativeID'] = 'product_'.$_POST['productID'];
            $picData['pic_path'] = $response['pic_path'];
            $response['pictureID'] = $this->pic->addProductionPic($picData);
        }
        else{
            $_SESSION['pic_path'][] = $data['upload_data']['file_name'];
            $response['pic_path'] = $data['upload_data']['file_name'];
            $response['pictureID'] = $data['upload_data']['file_name'];
        }

       echo json_encode($response);
    }

    /*
        删除图片
    */
    public function delPicture(){
        $pid = $_POST['pictureID'];
        if($_POST['productID'] != ''){
            $this->pic->delProductionPic($pid);
            echo "del_success";            
        }
        else{
            unlink('./upload/'.$pid);
            echo "del_success"; 
        }

    }
}