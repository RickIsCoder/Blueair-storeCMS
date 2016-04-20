<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pic_model extends MY_Model{

	public function addProductionPic($data){
		$this->db->insert('picture', $data);
		return  $this->db->insert_id();
	}

	public function getProductionPic($productID){
        $baseUrl = base_url("/upload/");
		$productPic_sql = "SELECT pictureID,CONCAT('$baseUrl/', pic_path) as pic_path FROM picture WHERE productID = ?";
		$data = $this->db->query($productPic_sql, $productID)->result_array();
		return $data;
	}

	public function delProductionPic($pid){
		$path = $this->pic->getPicturePath($pid);
        unlink('./upload/'.$path[0]['pic_path']);
		$this->db->where('pictureID',$pid);
		$f = $this->db->delete('picture');
		if($f) return true;
	}

	public function getPicturePath($id){
		$path_sql = "SELECT pic_path FROM picture WHERE pictureID = ?";
		$pic_path = $this->db->query($path_sql,$id)->result_array();
		return $pic_path;
	}

	public  function relativePicID($productID){
		$relativeID_sql = 'SELECT pictureID FROM picture WHERE productID = ?';
		$relativeID = $this->db->query($relativeID_sql,$productID)->result_array();
		return $relativeID;
	}
}