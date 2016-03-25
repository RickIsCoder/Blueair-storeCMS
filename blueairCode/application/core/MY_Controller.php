<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	public $cache_time;//静态页面缓存过期时间，单位:分钟
	public function __construct(){
		parent::__construct();
        $this->db->db_debug = FALSE;
		$this->cache_time = 1;	
        error_reporting(1);
	}
}
