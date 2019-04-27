<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alasan_pencabutan_model extends CI_Model {

	function view()
	{
		return $this->db->get('tabel_alasan_pencabutan');
	}

	function add($alasan_pencabutan)
	{
		$data = array(
			'alasan_pencabutan' => $alasan_pencabutan
		);

		$this->db->insert('tabel_alasan_pencabutan', $data);
	}

	function select_data($id)
	{
		$this->db->where('kode_alasan_pencabutan', $id);
		return $this->db->get('tabel_alasan_pencabutan');
	}

	function edit($id, $alasan_pencabutan)
	{
		$data = array(
			'alasan_pencabutan' => $alasan_pencabutan
		);
		$this->db->where('kode_alasan_pencabutan', $id);
		$this->db->update('tabel_alasan_pencabutan', $data);
	}

}

/* End of file alasan_pencabutan_model.php */
/* Location: ./application/models/alasan_pencabutan_model.php */