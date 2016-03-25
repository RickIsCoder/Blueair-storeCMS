<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model{
    
    public function getAllUserInfo(){
		$data = $this->db->get('user')->result_array();
		return $data;
	}

	public function verify($username,$password){
		$getPassword = 'SELECT userID, username, userType FROM user WHERE username = ? and password = ?';
		$data = $this->db->query($getPassword,array($username, $password))->result_array();
		if (count($data) > 0) {
			return $data[0];
		}
		else{
			return FALSE;
		}
	}

    public function addUser($data){
        // should check if realy added
        // if add a user with an exist username?
        $f = $this->db->insert('user',$data);
        if($f){
            return TRUE;
        }
        else{
            return FALSE;
        }
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