<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scene_model extends MY_Model{

	public function getScene(){
        $baseUrl = base_url("/uploadIcon/");
		$query_sql = "SELECT sceneID,sceneName,CONCAT('$baseUrl/',scenePic_path) as scenePic_path FROM scene";
		$sceneList = $this->db->query($query_sql)->result_array();
		return $sceneList;
	}
    
	public function getSceneById($id){
        $baseUrl = base_url("/uploadIcon/");
		$query_sql = "SELECT sceneID,sceneName,CONCAT('$baseUrl/',scenePic_path) as scenePic_path FROM scene where sceneID = ?";
		$sceneList = $this->db->query($query_sql, $id)->result_array();
		return count($sceneList) > 0 ? $sceneList[0] : NULL;
	}

	public function getScale($sceneID){
		$query_sql = 'SELECT scaleID,scaleName FROM scale WHERE sceneID = ?';
		$scaleList = $this->db->query($query_sql,$sceneID)->result_array();
		return $scaleList;
		
	}

	public function getScaleProduct($scaleID){
		$baseUrl = base_url("/upload/");
		$query_sql = "SELECT sceneProduct.sceneProductID,sceneProduct.productID,product.productName,CONCAT('$baseUrl/',picture.pic_path) as pic_path FROM sceneProduct LEFT JOIN product ON sceneProduct.productID = product.productID LEFT JOIN picture ON sceneproduct.productID = picture.productID WHERE scaleID = ? GROUP BY sceneProduct.sceneProductID";
		$sceneProduct = $this->db->query($query_sql, $scaleID)->result_array();
		
		return $sceneProduct;
	}

	public function sceneAdd($data){
		$this->db->insert('scene', $data);
		return  $this->db->insert_id();
	}

	public function sceneDel($sceneID){
		$query_sql = 'SELECT scenePic_path FROM scene WHERE sceneID = ?';
		$scenePic = $this->db->query($query_sql, $sceneID)->result_array();
		unlink('./uploadIcon/'.$scenePic[0]['scenePic_path']);
		$this->db->where('sceneID',$sceneID);
		$F_scene = $this->db->delete('scene');

		$delProductionSql = 'DELETE FROM sceneproduct WHERE scaleID IN (SELECT scaleID FROM scale WHERE sceneID = ? )';
		$F_production = $this->db->query($delProductionSql,$sceneID);

		$delScaleSql = 'DELETE FROM scale WHERE sceneID = ?';

		$F_scale = $this->db->query($delScaleSql,$sceneID);

		if ($F_scene&&$F_scale&&$F_production) {
			return true;
		}
		else{
			return false;
		}
	}

	public function sceneEdit($sceneID,$sceneName){
		$this->db->where('sceneID',$sceneID);
		$data['sceneName'] = $sceneName;
		$f = $this->db->update('scene',$data);
		return $f;
	}

	public function scaleAdd($data){
		$this->db->insert('scale', $data);
		return  $this->db->insert_id();
	}

	public function scaleDel($scaleID){
		$this->db->where('scaleID',$scaleID);
		$f = $this->db->delete('scale');
		return $f;
	}

	public function scaleEdit($scaleID,$scaleName){
		$this->db->where('scaleID',$scaleID);
		$data['scaleName'] = $scaleName;
		$f = $this->db->update('scale',$data);
		return $f;
	}

	public function sceneProductionDel($sceneProductID){
		$this->db->where('sceneProductID',$sceneProductID);
		$f = $this->db->delete('sceneproduct');
		return $f;
	}

	public function checkSceneProduction($scaleID){
		$production_sql = 'SELECT productName,productID FROM product WHERE productID NOT IN (SELECT productID FROM sceneProduct WHERE scaleID = ?)';
		$noExistProdution = $this->db->query($production_sql, $scaleID)->result_array();	
		return 	$noExistProdution;
	}

	public function sceneProductionAdd($data){
		$this->db->insert('sceneProduct', $data);
		return  $this->db->insert_id();
	}

	public function updateIcon($sceneID,$data){
		$query_sql = 'SELECT scenePic_path FROM scene WHERE sceneID = ?';
		$scenePic = $this->db->query($query_sql, $sceneID)->result_array();
		unlink('./uploadIcon/'.$scenePic[0]['scenePic_path']);

		$this->db->where('sceneID',$sceneID);
		$F = $this->db->update('scene',$data);
		return $F;
	}




}