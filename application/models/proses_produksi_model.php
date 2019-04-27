<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses_produksi_model extends CI_Model {

	function view()
	{
		$this->db->order_by('kode_tek_olah', 'asc');
		return $this->db->get('tabel_teknologi_pengolahan');
	}

	function add($tek_olah)
	{
		$this->db->select('MAX(kode_tek_olah) as last_id');
		$this->db->order_by('kode_tek_olah', 'desc');
		$this->db->limit(1);
		$q = $this->db->get('tabel_teknologi_pengolahan')->row();
		$data = array(
			'kode_tek_olah' => $q->last_id+1
			,'tek_olah' => $tek_olah
		);

		$this->db->insert('tabel_teknologi_pengolahan', $data);
	}

	function select_data($id)
	{
		$this->db->where('kode_tek_olah', $id);
		return $this->db->get('tabel_teknologi_pengolahan');
	}

	function edit($id, $tek_olah)
	{
		$data = array(
			'tek_olah' => $tek_olah
		);
		$this->db->where('kode_tek_olah', $id);
		$this->db->update('tabel_teknologi_pengolahan', $data);
	}


}

/* End of file proses_produksi_model.php */
/* Location: ./application/models/proses_produksi_model.php */