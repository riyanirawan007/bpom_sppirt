<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_kemasan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('lib_admin');
		$this->load->model('jenis_kemasan_model');
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
			$data['jenis_kemasan'] = $this->jenis_kemasan_model->view()->result();
			$this->lib_admin->display('jenis_kemasan/view',$data);
		} else {
			return redirect('home/index');
		}
	}

	function add()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('jenis_kemasan', 'jenis_kemasan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('jenis_kemasan/add');
				} else {
					$jenis_kemasan = $this->input->post('jenis_kemasan');
					$ket_kemasan = $this->input->post('ket_kemasan');
					if ($this->jenis_kemasan_model->add($jenis_kemasan, $ket_kemasan)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('jenis_kemasan');	
				}
				
			} else {
				$this->lib_admin->display('jenis_kemasan/add');
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function edit()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('jenis_kemasan', 'jenis_kemasan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('jenis_kemasan/edit');
				} else {
					$id = $this->input->post('id');
					$jenis_kemasan = $this->input->post('jenis_kemasan');
					$ket_kemasan = $this->input->post('ket_kemasan');
					if ($this->jenis_kemasan_model->edit($id, $jenis_kemasan, $ket_kemasan)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('jenis_kemasan');	
				}
				
			} else {
				$id = $this->uri->segment(3);
				$data['jenis_kemasan'] = $this->jenis_kemasan_model->select_data($id)->row_array();
				$this->lib_admin->display('jenis_kemasan/edit',$data);
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function delete()
	{
		$id = $this->uri->segment(3);
		$this->db->where('kode_kemasan', $id);
		$delete = $this->db->delete('tabel_kemasan');
		if ($delete) {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
		}
		redirect('jenis_kemasan');	
	}

}

/* End of file jenis_kemasan.php */
/* Location: ./application/controllers/jenis_kemasan.php */