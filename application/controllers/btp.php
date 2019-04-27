<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Btp extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('lib_admin');
		$this->load->model('btp_model');
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
			$data['btp'] = $this->btp_model->view_grup()->result();
			$this->lib_admin->display('btp/view_grup',$data);
		} else {
			return redirect('home/index');
		}
	}

	function add_grup()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('nama_grup_btp', 'nama_grup_btp', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('btp/add_grup');
				} else {
					$nama_grup_btp = $this->input->post('nama_grup_btp');
					if ($this->btp_model->add_grup($nama_grup_btp)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('btp');	
				}	
			} else {
				$this->lib_admin->display('btp/add_grup');
			}
		} else {
			return redirect('home/index');
		}	
	}

	function edit_grup()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('nama_grup_btp', 'nama_grup_btp', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('btp/edit_grup');
				} else {
					$id = $this->input->post('id');
					$nama_grup_btp = $this->input->post('nama_grup_btp');
					if ($this->btp_model->edit_grup($id, $nama_grup_btp)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('btp');	
				}	
			} else {
				$id = $this->uri->segment(3);
				$data['btp'] = $this->btp_model->select_grup($id)->row_array();
				$this->lib_admin->display('btp/edit_grup',$data);
			}
		} else {
			return redirect('home/index');
		}	
	}

	function delete_grup()
	{
		$id = $this->uri->segment(3);
		$this->db->where('kode_grup_btp', $id);
		$delete = $this->db->delete('tabel_grup_btp');
		if ($delete) {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
		}
		redirect('btp');	
	}

	function komposisi()
	{
		if($this->session->userdata('user_token')){
			$data['komposisi'] = $this->btp_model->view_komposisi()->result();
			$this->lib_admin->display('btp/view_komposisi',$data);
		} else {
			return redirect('home/index');
		}
	}

	function add_komposisi()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('nama_bahan_tambahan_pangan', 'nama_bahan_tambahan_pangan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('btp/add_komposisi');
				} else {
					$kode_btp = $this->input->post('kode_btp');
					$kode_r_grup_btp = $this->input->post('kode_r_grup_btp');
					$nama_bahan_tambahan_pangan = $this->input->post('nama_bahan_tambahan_pangan');
					if ($this->btp_model->add_komposisi($kode_btp, $kode_r_grup_btp, $nama_bahan_tambahan_pangan)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('btp/komposisi');	
				}	
			} else {
				$data['grup'] = $this->btp_model->view_grup()->result();
				$this->lib_admin->display('btp/add_komposisi',$data);
			}
		} else {
			return redirect('home/index');
		}	
	}

	function edit_komposisi()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('nama_bahan_tambahan_pangan', 'nama_bahan_tambahan_pangan', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('btp/edit_komposisi');
				} else {
					$id = $this->input->post('id');
					$kode_btp = $this->input->post('kode_btp');
					$kode_r_grup_btp = $this->input->post('kode_r_grup_btp');
					$nama_bahan_tambahan_pangan = $this->input->post('nama_bahan_tambahan_pangan');
					if ($this->btp_model->edit_komposisi($id, $kode_btp, $kode_r_grup_btp, $nama_bahan_tambahan_pangan)) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('btp/komposisi');	
				}	
			} else {
				$id = $this->uri->segment(3);
				$data['komposisi'] = $this->btp_model->select_komposisi($id)->row_array();
				$data['grup'] = $this->btp_model->view_grup()->result();
				$this->lib_admin->display('btp/edit_komposisi',$data);
			}
		} else {
			return redirect('home/index');
		}	
	}

	function delete_komposisi()
	{
		$id = $this->uri->segment(3);
		$this->db->where('no_urut_btp', $id);
		$delete = $this->db->delete('tabel_bahan_tambahan_pangan');
		if ($delete) {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
		}
		redirect('btp/komposisi');	
	}

}

/* End of file btp.php */
/* Location: ./application/controllers/btp.php */