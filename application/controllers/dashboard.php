<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Dashboard extends CI_Controller {
        
        public function __construct()
	    {
    		parent::__construct();
    		$this->load->library('lib_admin');
    		$this->load->helper(array('form', 'url'));
    		$this->load->library('form_validation');
    		
	    }
	    
	    public function index()
    	{
			$data['dashboard'] = "";
			$this->lib_admin->display('dashboard/view',$data);
			//return view_dashboard('dashboard/view');
			
    	}

    }
?>