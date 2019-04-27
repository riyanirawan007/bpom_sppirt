<?php
class Lib_pbkpks {
	protected $_ci;
	function __construct(){
		$this->_ci = &get_instance();
	}
	
	function display($halaman,$data=null){
		$data['_content'] = $this->_ci->load->view("pbkpks/".$halaman,$data,true);
		$data['_content_menu_1'] = $this->_ci->load->view('pb1kp/menu_pb1kp',$data,true);
		$data['_content_menu_2'] = $this->_ci->load->view('pb2kp/menu_pb2kp',$data,true);
		$data['_content_menu_3'] = $this->_ci->load->view('pb3kp/menu_pb3kp',$data,true);
		
		$data['_content_menu_grading'] = $this->_ci->load->view('grading/menu_grading',$data,true);
		$data['_content_menu_pbkpks'] = $this->_ci->load->view('pbkpks/menu_pbkpks',$data,true);
		$this->_ci->load->view('index',$data);
	}
}
