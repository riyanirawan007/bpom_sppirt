<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manajemen_user extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('lib_admin');
		$this->load->model('auth');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	}

	public function index()
	{
		if($this->session->userdata('user_token')){
			$data['manajemen_user'] = $this->auth->view()->result();
			$this->lib_admin->display('manajemen_user/view',$data);
		} else {
			return redirect('home/index');
		}
	}



	function add()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('password', 'password', 'required|min_length[6]');
				if ($this->form_validation->run() == FALSE)	
				{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('manajemen_user/add');
				} else {
					if ($this->auth->register()) {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
					}
					redirect('manajemen_user');	
				}

			} else {
				$hak_akses = $this->db->get('tabel_login_hak_akses');
				$data = array(
					'provinsi' => $this->auth->get_provinsi(),
					'kota' => $this->auth->get_kota(),
					'hak_akses' => $hak_akses
				);
				$this->lib_admin->display('manajemen_user/add',$data);
			}

		} else {
			return redirect('home/index');
		}	
	}

	function delete()
	{
		$id = $this->uri->segment(3);
		$delete = $this->auth->delUser($id);
		if ($delete) {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
		}
		redirect('manajemen_user');	
	}

	function edit()
	{
		if($this->session->userdata('user_token')){
			if (isset($_POST['foto'])) {
				if ($_FILES['userfile']['name'] == "") {
					$picture = $this->input->post('picture');
				} else {

					$config['upload_path'] = './foto/';
					$config['allowed_types'] = '*';

					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload())
					{
						$error = array('error' => $this->upload->display_errors());

						$this->load->view('upload_form', $error);
					}
					else
					{
						// $data = array('upload_data' => $this->upload->data());

						// $this->load->view('upload_success', $data);
						$picture = $_FILES['userfile']['name'];
						$id = $this->input->post('id_user');
						$data = array('picture' => $picture);

						$this->db->where('id_user', $id);
						$this->db->update('tabel_login_user', $data);
						$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
					}
					redirect('manajemen_user/edit/'.$id);
				}
			}
			if (isset($_POST['submit'])) {
				$id = $this->input->post('id_user');
				// $picture = $this->input->post('picture');
				if ($this->auth->editUser($id)) {
					$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
				}
				redirect('manajemen_user/edit/'.$id);
			} else {
				$id = $this->uri->segment(3);
				$hak_akses = $this->db->get('tabel_login_hak_akses');
				$data = array(
					'provinsi' => $this->auth->get_provinsi(),
					'kota' => $this->auth->get_kota(),
					'hak_akses' => $hak_akses,
					'user' => $this->auth->view_by_id($id)->row()
				);
				$this->lib_admin->display('profil/view',$data);
			}

		} else {
			return redirect('home/index');
		}	
	}
}

/* End of file manajemen_user.php */
/* Location: ./application/controllers/manajemen_user.php */