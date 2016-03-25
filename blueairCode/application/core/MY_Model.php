<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model{
	//加载数据缓存
	public function __construct(){
		parent::__construct();
		$this->load->driver('cache');
	}
}
