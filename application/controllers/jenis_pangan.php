<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_pangan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('lib_admin');
		$this->load->model('jenis_pangan_model');
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
			$data['jenis_pangan'] = $this->jenis_pangan_model->view_grup_jenis_pangan()->result();
			$this->lib_admin->display('jenis_pangan/view_grup_jenis_pangan',$data);
		} else {
			return redirect('home/index');
		}
	}

	function add_grup_jenis_pangan()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('nama_grup_jenis_pangan', 'nama_grup_jenis_pangan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('jenis_pangan/add_grup_jenis_pangan');
				} else {
					$nama_grup_jenis_pangan = $this->input->post('nama_grup_jenis_pangan');
					if ($this->jenis_pangan_model->add_proses_grup_jenis_pangan($nama_grup_jenis_pangan)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('jenis_pangan');	
				}
				
			} else {
				$this->lib_admin->display('jenis_pangan/add_grup_jenis_pangan');
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function edit_grup_jenis_pangan()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('nama_grup_jenis_pangan', 'nama_grup_jenis_pangan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('jenis_pangan/edit_grup_jenis_pangan');
				} else {
					$id = $this->input->post('id');
					$nama_grup_jenis_pangan = $this->input->post('nama_grup_jenis_pangan');
					if ($this->jenis_pangan_model->edit_proses_grup_jenis_pangan($id, $nama_grup_jenis_pangan)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('jenis_pangan');	
				}
			} else {
				$id = $this->uri->segment(3);
				$data['jenis_pangan'] = $this->jenis_pangan_model->select_grup_jenis_pangan($id)->row_array();
				$this->lib_admin->display('jenis_pangan/edit_grup_jenis_pangan',$data);
			}
			
		} else {
			return redirect('home/index');
		}	
	}


	function delete_grup_jenis_pangan()
	{
		$id = $this->uri->segment(3);
		$this->db->where('kode_grup_jenis_pangan', $id);
		$delete = $this->db->delete('tabel_grup_jenis_pangan');
		if ($delete) {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
		}
		redirect('jenis_pangan');	
	}

	function nama_jenis_pangan()
	{
		if($this->session->userdata('user_token')){
			$data['jenis_pangan'] = $this->jenis_pangan_model->view_jenis_pangan()->result();
			$this->lib_admin->display('jenis_pangan/view_jenis_pangan',$data);
		} else {
			return redirect('home/index');
		}
	}

	function add_jenis_pangan()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('kode_jenis_pangan', 'kode_jenis_pangan', 'required');
				$this->form_validation->set_rules('kode_r_grup_jenis_pangan', 'kode_r_grup_jenis_pangan', 'required');
				$this->form_validation->set_rules('jenis_pangan', 'jenis_pangan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('jenis_pangan/add_jenis_pangan');
				} else {
					$kode_jenis_pangan = $this->input->post('kode_jenis_pangan');
					$kode_r_grup_jenis_pangan = $this->input->post('kode_r_grup_jenis_pangan');
					$jenis_pangan = $this->input->post('jenis_pangan');
					$deskripsi = $this->input->post('deskripsi');
					if ($this->jenis_pangan_model->add_proses_jenis_pangan($kode_jenis_pangan, $kode_r_grup_jenis_pangan, $jenis_pangan,$deskripsi)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('jenis_pangan/nama_jenis_pangan');	
				}
				
			} else {
				$data['grup'] = $this->jenis_pangan_model->view_grup_jenis_pangan_by_status()->result();
				$this->lib_admin->display('jenis_pangan/add_jenis_pangan',$data);
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function edit_jenis_pangan()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('kode_jenis_pangan', 'kode_jenis_pangan', 'required');
				$this->form_validation->set_rules('kode_r_grup_jenis_pangan', 'kode_r_grup_jenis_pangan', 'required');
				$this->form_validation->set_rules('jenis_pangan', 'jenis_pangan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('jenis_pangan/edit_jenis_pangan');
				} else {
					$id = $this->input->post('id');
					$kode_jenis_pangan = $this->input->post('kode_jenis_pangan');
					$kode_r_grup_jenis_pangan = $this->input->post('kode_r_grup_jenis_pangan');
					$jenis_pangan = $this->input->post('jenis_pangan');
					$deskripsi = $this->input->post('deskripsi');
					if ($this->jenis_pangan_model->edit_proses_jenis_pangan($id, $kode_jenis_pangan, $kode_r_grup_jenis_pangan, $jenis_pangan,$deskripsi)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('jenis_pangan/nama_jenis_pangan');	
				}
			} else {
				$id = $this->uri->segment(3);
				$data['grup'] = $this->jenis_pangan_model->view_grup_jenis_pangan_by_status()->result();
				$data['jenis_pangan'] = $this->jenis_pangan_model->select_jenis_pangan($id)->row_array();
				$this->lib_admin->display('jenis_pangan/edit_jenis_pangan',$data);
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function delete_jenis_pangan()
	{
		$id = $this->uri->segment(3);
		$this->db->where('id_urut_jenis_pangan', $id);
		$delete = $this->db->delete('tabel_jenis_pangan');
		if ($delete) {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
		}
		redirect('jenis_pangan/nama_jenis_pangan');	
	}

	function non_aktif() {
		$id = $this->uri->segment(3);
		$data = array('status' => 0);
		$this->db->where('kode_grup_jenis_pangan', $id);
		$this->db->update('tabel_grup_jenis_pangan', $data);
		redirect('jenis_pangan','refresh');
	}
	function aktif() {
		$id = $this->uri->segment(3);
		$data = array('status' => 1);
		$this->db->where('kode_grup_jenis_pangan', $id);
		$this->db->update('tabel_grup_jenis_pangan', $data);
		redirect('jenis_pangan','refresh');
	}

}

/* End of file Jenis_pangan.php */
/* Location: ./application/controllers/Jenis_pangan.php */