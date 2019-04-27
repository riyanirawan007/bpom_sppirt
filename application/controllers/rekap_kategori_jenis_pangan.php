<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_kategori_jenis_pangan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('rekap_kategori_jenis_pangan_model');
		$this->load->model('menu_model');	
		//$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
		
			//$data['rekap_pengajuan'] = $this->rekap_pengajuan_model->view()->result();
			return view_dashboard('rekap_kategori_jenis_pangan/view');
		
	}

	public function cetak()
	{

	}


}

/* End of file Rekap_pengajuan.php */
/* Location: ./application/controllers/Rekap_pengajuan.php */