<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sertifikat_cek_model extends CI_Model {

	var $table = 'tabel_penerbitan_sert_pirt';

	function batas_waktu()
	{
		$this->db->where('id', 1);
		return $this->db->get('tabel_notifikasi_sertifikat');
	}

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_data() {
		$id = $this->session->userdata('code');
		$query = "SELECT a.nomor_r_permohonan, a.nomor_pirt, a.tanggal_pemberian_pirt, a.nomor_hk, a.nama_kepala_dinas, a.nama_kepala_dinas FROM tabel_penerbitan_sert_pirt a
		LEFT JOIN tabel_pen_pengajuan_spp b ON a.nomor_r_permohonan = b.nomor_permohonan
		LEFT JOIN tabel_daftar_perusahaan c ON b.kode_r_perusahaan = c.kode_perusahaan
		LEFT JOIN tabel_kabupaten_kota d ON c.id_r_urut_kabupaten = d.id_urut_kabupaten
		LEFT JOIN tabel_propinsi e ON d.no_kode_propinsi = e.no_kode_propinsi";
		if ($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3) {
			$query .= " WHERE e.no_kode_propinsi = $id";
		} else if ($this->session->userdata('user_segment') == 5) {
			$query .= " WHERE d.id_urut_kabupaten = $id";
		} 
		return $this->db->query($query);
		// $this->db->order_by('id_urut_penerbitan_sert', 'desc');
		// return $this->db->get($this->table);
	}


}

/* End of file sertifikat_cek_model.php */
/* Location: ./application/models/sertifikat_cek_model.php */