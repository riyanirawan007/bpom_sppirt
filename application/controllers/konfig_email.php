<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfig_email extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('lib_admin');
		$this->load->model('konfig_email_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('menu_model');	

		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
		if($this->session->userdata('user_token')){
			$data['konfig_email'] = $this->konfig_email_model->view();
			$data['action'] = base_url('konfig_email/edit');
			$this->lib_admin->display('konfig_email/view',$data);
		} else {
			return redirect('home/index');
		}
	}

	function edit() {
		$id = $this->input->post('id');
		$protocol = $this->input->post('protocol');
		$host = $this->input->post('host');
		$auth = $this->input->post('auth');
		$user = $this->input->post('user');
		$pass = $this->input->post('pass');
		$port = $this->input->post('port');
		$timeout = $this->input->post('timeout');
		$crypto = $this->input->post('crypto');

		$data = array(
			'protocol' => $protocol
			,'host' => $host
			,'auth' => $auth
			,'user' => $user
			,'pass' => $pass
			,'port' => $port
			,'timeout' => $timeout
			,'crypto' => $crypto
		);
		$this->db->where('id', $id);
		$this->db->update('tabel_konfig_email', $data);
		redirect('konfig_email','refresh');
	}

}

/* End of file konfig_email.php */
/* Location: ./application/controllers/konfig_email.php */