<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WhyBlueAir extends CI_Controller {

	//why blueAir index page
	public function index()
	{
		$this->load->view('index/WhyBlueAir/WhyBlueAir.html');
	}
	
}
