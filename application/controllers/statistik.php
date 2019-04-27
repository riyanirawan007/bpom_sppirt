<?php
class Statistik extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('rekap_irtp_model','rekap_irtp');
		$this->load->model('menu_model');	
		if(checkUserAuthorize()) checkMenuAuthority();
	}
	
	function index(){
		return view_dashboard('statistik/sppirt_pengajuan');
	}
	
	function sppirt_pengajuan(){
		return view_dashboard('statistik/sppirt_pengajuan');
	}
	
	function sppirt_penerbitan(){
		return view_dashboard('statistik/sppirt_penerbitan');
	}
	
	function sppirt_irtp(){
		return view_dashboard('statistik/sppirt_irtp');
	}

	
	function sppirt_pelaksanaan_pkp(){
		return view_dashboard('statistik/sppirt_pkp');
	}

	function sppirt_jenis_pangan(){
		return view_dashboard('statistik/sppirt_jenis_pangan');
	}

	function get_users()
	{
		echo json_encode(array('data'=>count($this->db->get('tabel_login_user')->result_array()) ));
	}
}