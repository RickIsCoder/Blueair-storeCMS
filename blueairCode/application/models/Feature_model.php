<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feature_model extends MY_Model{

	public function getFeatureItems($productionID){
		$feature_sql = 'SELECT featureID,featureTitle,featureContent FROM feature WHERE productID=?';
		$data = $this->db->query($feature_sql,$productionID)->result_array();
		return $data;
	}

	public function getTipsItems($productionID){
		$tips_sql = 'SELECT tipsID,tips FROM tips WHERE productID=?';
		$data = $this->db->query($tips_sql,$productionID)->result_array();
		return $data;
	}

	public  function addFeature($data){
		$this->db->insert('feature', $data);
		return  $this->db->insert_id();
	}

	public function delFeature($featureID){
		$this->db->where('featureID',$featureID);
		$this->db->delete('feature');
		return TRUE;
	}

	public function editFeature($featureID,$data){
		$this->db->where('featureID',$featureID);
		$this->db->update('feature',$data);
		return TRUE;
	}


	public  function addTips($data){
		$this->db->insert('tips', $data);
		return  $this->db->insert_id();
	}

	public function delTips($tipsID){
		$this->db->where('tipsID',$tipsID);
		$this->db->delete('tips');
		return TRUE;
	}

	public function editTips($tipsID,$data){
		$this->db->where('tipsID',$tipsID);
		$this->db->update('tips',$data);
		return TRUE;
	}

}