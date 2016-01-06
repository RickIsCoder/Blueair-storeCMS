<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// controller for page jump!
class Navigator extends MY_Controller {
    
    public function cms(){
		switch ($_SESSION['userType']) {
			case '1':
                redirect('productionCMS');
				$this->load->view("backEnd/adminCMS");
				break;
			
			case '2':
				$this->load->view("backEnd/salesmanCMS");
				break;

			default:
				break;
		}		
	}

}