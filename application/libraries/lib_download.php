<?php
class Lib_download {
	protected $_ci;
	function __construct(){
		$this->_ci = &get_instance();
	}
	
	function display($halaman,$data=null){
		$data['_content'] = $this->_ci->load->view($halaman,$data,true);
		$data['_content_menu'] = $this->_ci->load->view('pb2kp/menu_pb2kp',$data,true);
		$data['_content_menu_grading'] = $this->_ci->load->view('grading/menu_grading',$data,true);
		$this->_ci->load->view('download',$data);
	}
	
	
	
	
}
