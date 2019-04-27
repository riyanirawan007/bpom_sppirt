<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alasan_pencabutan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('lib_admin');
		$this->load->model('alasan_pencabutan_model');
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
			$data['alasan_pencabutan'] = $this->alasan_pencabutan_model->view()->result();
			$this->lib_admin->display('alasan_pencabutan/view',$data);
		} else {
			return redirect('home/index');
		}
	}

	function add()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('alasan_pencabutan', 'alasan_pencabutan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('alasan_pencabutan/add');
				} else {
					$alasan_pencabutan = $this->input->post('alasan_pencabutan');
					if ($this->alasan_pencabutan_model->add($alasan_pencabutan)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('alasan_pencabutan');	
				}
				
			} else {
				$this->lib_admin->display('alasan_pencabutan/add');
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function edit()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('alasan_pencabutan', 'alasan_pencabutan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('alasan_pencabutan/edit');
				} else {
					$id = $this->input->post('id');
					$alasan_pencabutan = $this->input->post('alasan_pencabutan');
					if ($this->alasan_pencabutan_model->edit($id, $alasan_pencabutan)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('alasan_pencabutan');	
				}
				
			} else {
				$id = $this->uri->segment(3);
				$data['alasan_pencabutan'] = $this->alasan_pencabutan_model->select_data($id)->row_array();
				$this->lib_admin->display('alasan_pencabutan/edit',$data);
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function delete()
	{
		$id = $this->uri->segment(3);
		$this->db->where('kode_alasan_pencabutan', $id);
		$delete = $this->db->delete('tabel_alasan_pencabutan');
		if ($delete) {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
		}
		redirect('alasan_pencabutan');	
	}

}

/* End of file alasan_pencabutan.php */
/* Location: ./application/controllers/alasan_pencabutan.php */