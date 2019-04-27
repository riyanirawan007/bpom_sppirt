<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses_produksi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('lib_admin');
		$this->load->model('proses_produksi_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('menu_model');	
		//$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
		if($this->session->userdata('user_token')){
			$data['proses_produksi'] = $this->proses_produksi_model->view()->result();
			$this->lib_admin->display('proses_produksi/view',$data);
		} else {
			return redirect('home/index');
		}
	}

	function add()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('tek_olah', 'tek_olah', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('proses_produksi/add');
				} else {
					$tek_olah = $this->input->post('tek_olah');
					if ($this->proses_produksi_model->add($tek_olah)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('proses_produksi');	
				}
				
			} else {
				$this->lib_admin->display('proses_produksi/add');
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function edit()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('tek_olah', 'tek_olah', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('tek_olah/edit');
				} else {
					$id = $this->input->post('id');
					$tek_olah = $this->input->post('tek_olah');
					if ($this->proses_produksi_model->edit($id, $tek_olah)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('proses_produksi');	
				}
				
			} else {
				$id = $this->uri->segment(3);
				$data['proses_produksi'] = $this->proses_produksi_model->select_data($id)->row_array();
				$this->lib_admin->display('proses_produksi/edit',$data);
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function delete()
	{
		$id = $this->uri->segment(3);
		$this->db->where('kode_tek_olah', $id);
		$delete = $this->db->delete('tabel_teknologi_pengolahan');
		if ($delete) {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
		}
		redirect('proses_produksi');	
	}

}

/* End of file proses_produksi.php */
/* Location: ./application/controllers/proses_produksi.php */