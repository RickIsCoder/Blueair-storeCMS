<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model{
		public function addUser($data){
		$this->db->insert('user',$data);
		$data = json_encode($data);
		return $this->db->insert_id();
	}

	public function editUser($userID,$data){
		$this->db->where('userID', $userID);
		$f = $this->db->update('user', $data);
		return $f;
	}

	public function delUser($userID){
		$this->db->where('userID',$userID);
		$f = $this->db->delete('user');
		return $f;
	}
}