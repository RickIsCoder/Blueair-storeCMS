<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scene_model extends MY_Model{

	public function getScene(){
		$query_sql = 'SELECT sceneID,sceneName FROM scene';
		$sceneList = $this->db->query($query_sql)->result_array();
		return $sceneList;
	}

	public function getScale($sceneID){
		$query_sql = 'SELECT scaleID,scaleName FROM scale WHERE sceneID =' . $sceneID;
		$scaleList = $this->db->query($query_sql)->result_array();
		return $scaleList;
		
	}

	public function getSceneProduct($scaleID){
		$query_sql = 'SELECT sceneProductID,productID FROM sceneProduct WHERE scaleID =' . $scaleID;
		$sceneProduct = $this->db->query($query_sql)->result_array();
		return $sceneProduct;
		
	}

	public function sceneAdd($data){
		$this->db->insert('scene', $data);
		return  $this->db->insert_id();
	}

	public function sceneDel($sceneID){
		$this->db->where('sceneID',$sceneID);
		$f = $this->db->delete('scene');
		return $f;
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
		$f = $this->db->delete('sceneProduct');
		return $f;
	}

	public function checkSceneProduction($scaleID){
		$production_sql = 'SELECT productName,productID FROM product WHERE productID NOT IN (SELECT productID FROM sceneProduct WHERE scaleID = '.$scaleID.')';
		$noExistProdution = $this->db->query($production_sql)->result_array();	
		return 	$noExistProdution;
	}

	public function sceneProductionAdd($data){
		$this->db->insert('sceneProduct', $data);
		return  $this->db->insert_id();
	}

}