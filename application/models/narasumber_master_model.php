<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Narasumber_master_model extends CI_Model {

	function tingkat_pendidikan_view()
	{
		$this->db->order_by('id_tingkat_pendidikan', 'asc');
		return $this->db->get('tabel_tingkat_pendidikan');
	}

	function jabatan_view()
	{
		$this->db->order_by('kode_jabatan', 'asc');
		return $this->db->get('tabel_jabatan');
	}

	function golongan_view()
	{
		$this->db->order_by('id_golongan', 'asc');
		return $this->db->get('tabel_golongan');
	}

	function instansi_view()
	{
		$this->db->order_by('kode', 'asc');
		return $this->db->get('tabel_instansi');
	}

}

/* End of file narasumber_master_model.php */
/* Location: ./application/models/narasumber_master_model.php */