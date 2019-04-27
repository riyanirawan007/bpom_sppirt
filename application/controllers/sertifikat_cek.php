<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sertifikat_cek extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('lib_admin');
		$this->load->model('sertifikat_cek_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('menu_model');	
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	function index()
	{
		$this->lib_admin->display('notifikasi_sertifikat/sertifikat_view',array());
	}

	function data_sertifikat() {

		$this->db->where('id', 1);
		$x = $this->db->get('tabel_notifikasi_sertifikat')->row();
		$ns = $x->notifikasi_sertifikat;

		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		$sertifikat_data = $this->sertifikat_cek_model->get_data();

		$data = array();
		$no = 1;
		foreach($sertifikat_data->result() as $r) {

			

			$begin = $r->tanggal_pemberian_pirt;
			$end = date('Y-m-d', strtotime('+5 years', strtotime($begin)));
			$countdown = date('Y-m-d', strtotime('-'.$ns.' months', strtotime($end)));
			$date1 = new DateTime($end);
			// $date2 = new DateTime($countdown);
			$now = date_create('Y-m-d');
			$date2 = date_create($now);
			$diff = date_diff($date1,$date2);

			// if ($end <= date('Y-m-d')) {
			// 	$status = "<label class='label label-danger'>Masa berlaku sertifikat telah habis</label>";
			// } else {

			$status = "<label class='label label-warning'>Sisa masa berlaku ".$diff->days." Hari</label>";
			// }
			$masa_tenggat = $this->db->get('tabel_notifikasi_sertifikat')->row();
			$tenggat = $masa_tenggat->notifikasi_sertifikat;
			$tenggat = $tenggat * 30;

			if ($diff->format("%R%a") >= -$tenggat AND $diff->format("%R%a") <= 0) {
				$data[] = array(
					$no,
					$r->nomor_r_permohonan,
					$r->nomor_pirt,
					$r->tanggal_pemberian_pirt,
					$r->nomor_hk,
					$r->nama_kepala_dinas,
					$end,
					$status
				);
				$no++;
			}
			
			
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $sertifikat_data->num_rows(),
			"recordsFiltered" => $sertifikat_data->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	function notifikasi_sertifikat()
	{
		if($this->session->userdata('user_token')){
			if (isset($_POST['submit'])) {
				$id = $this->input->post('id');
				$notifikasi_sertifikat = $this->input->post('notifikasi_sertifikat');
				$data = array(
					'notifikasi_sertifikat' => $notifikasi_sertifikat
				);
				$this->db->where('id', $id);
				$this->db->update('tabel_notifikasi_sertifikat', $data);
				redirect('sertifikat_cek/notifikasi_sertifikat','refresh');
			} else {
				$data['batas_waktu'] = $this->sertifikat_cek_model->batas_waktu();
				$this->lib_admin->display('notifikasi_sertifikat/batas_waktu',$data);
			}
		} else {
			return redirect('home/index');
		}
	}

}

/* End of file sertfikat_cek.php */
/* Location: ./application/controllers/sertfikat_cek.php */