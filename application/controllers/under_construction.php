<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Under_construction extends CI_Controller {
        
        public function __construct()
	    {
    		parent::__construct();
    		$this->load->library('lib_admin');
    		$this->load->helper(array('form', 'url'));
    		$this->load->library('form_validation');
    		
	    }
	    
	    public function index()
    	{
			$data['under_construction'] = "";
			$this->lib_admin->display('under_construction',$data);
    	}

    }
?>