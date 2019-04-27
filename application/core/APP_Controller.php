<?php
	class APP_Controller extends CI_Controller{
		/**
		*	This function shall delete previous cache
		* 	prevent user transaction roll back 
		* 	@return integer
		*/
		public function preventCache(){
			// Header must revalidate statements to delete the last cache
			$this->output->set_header("HTTP/1.0 200 OK");
			$this->output->set_header("HTTP/1.1 200 OK");
			$this->output->set_header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
			$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
			$this->output->set_header("Pragma: no-cache");
			
			return 1;
		}	
		
		public function addCookie(){
			// Set cookie token prevent back button after login
			$cookie = array(
				'name'   => 'cookie_token',
				'value'  => md5(rand(5, 20)),
				'expire' => '86500',
				'path'   => '/',
    			'prefix' => 'myprefix_',
			);
			
			$this->input->set_cookie($cookie);
		}
		
		public function delCookie(){
			// Set cookie token prevent back button after login
			$cookie = array(
				'name'   => 'cookie_token',
				'value'  => '',
				'expire' => '0',
				'path'   => '/',
   				'prefix' => 'myprefix_',
			);
			
			$this->input->set_cookie($cookie);
			
			return 1;
		}
		
		public function valid_login(){
			if(!$this->session->userdata('user_token')){
				redirect('home/index');
			}
		}
	}
?>