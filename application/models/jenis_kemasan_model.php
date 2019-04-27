<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_kemasan_model extends CI_Model {

	function view()
	{
		return $this->db->get('tabel_kemasan');
	}

	function add($jenis_kemasan, $ket_kemasan)
	{
		$this->db->select('MAX(kode_kemasan) as last_id');
		$this->db->order_by('kode_kemasan', 'desc');
		$this->db->limit(1);
		$q = $this->db->get('tabel_kemasan')->row();
		$data = array(
			'kode_kemasan' => $q->last_id+1
			,'jenis_kemasan' => $jenis_kemasan
			,'ket_kemasan' => $ket_kemasan
		);

		$this->db->insert('tabel_kemasan', $data);
	}

	function select_data($id)
	{
		$this->db->where('kode_kemasan', $id);
		return $this->db->get('tabel_kemasan');
	}

	function edit($id, $jenis_kemasan, $ket_kemasan)
	{
		$data = array(
			'jenis_kemasan' => $jenis_kemasan
			,'ket_kemasan' => $ket_kemasan
		);
		$this->db->where('kode_kemasan', $id);
		$this->db->update('tabel_kemasan', $data);
	}

}

/* End of file jenis_kemasan_model.php */
/* Location: ./application/models/jenis_kemasan_model.php */