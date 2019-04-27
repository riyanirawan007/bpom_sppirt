<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_pangan_model extends CI_Model {

	function view_grup_jenis_pangan()
	{
		$this->db->order_by('kode_grup_jenis_pangan', 'ASC');
		return $this->db->get('tabel_grup_jenis_pangan');
	}

	function view_grup_jenis_pangan_by_status()
	{
		$this->db->where('status', 1);
		$this->db->order_by('kode_grup_jenis_pangan', 'ASC');
		return $this->db->get('tabel_grup_jenis_pangan');
	}

	function add_proses_grup_jenis_pangan($nama_grup_jenis_pangan)
	{
		$this->db->select('MAX(kode_grup_jenis_pangan) AS last_id');
		$this->db->order_by('kode_grup_jenis_pangan', 'desc');
		$this->db->limit(1);
		$q = $this->db->get('tabel_grup_jenis_pangan')->row();

		$data = array(
			'kode_grup_jenis_pangan' => $q->last_id+1
			,'nama_grup_jenis_pangan' => $nama_grup_jenis_pangan
		);
		$this->db->insert('tabel_grup_jenis_pangan', $data);
	}

	function select_grup_jenis_pangan($id)
	{
		$this->db->where('kode_grup_jenis_pangan', $id);
		return $this->db->get('tabel_grup_jenis_pangan');
	}

	function edit_proses_grup_jenis_pangan($id, $nama_grup_jenis_pangan)
	{
		$data = array(
			'nama_grup_jenis_pangan' => $nama_grup_jenis_pangan
		);
		$this->db->where('kode_grup_jenis_pangan', $id);
		$this->db->update('tabel_grup_jenis_pangan', $data);
	}

	function view_jenis_pangan()
	{
		$this->db->select('*');
		$this->db->from('tabel_jenis_pangan');
		$this->db->join('tabel_grup_jenis_pangan', 'tabel_grup_jenis_pangan.kode_grup_jenis_pangan = tabel_jenis_pangan.kode_r_grup_jenis_pangan');
		$this->db->order_by('id_urut_jenis_pangan', 'ASC');
		return $this->db->get();
	}

	function add_proses_jenis_pangan($kode_jenis_pangan, $kode_r_grup_jenis_pangan, $jenis_pangan, $deskripsi)
	{
		$data = array(
			'kode_jenis_pangan' => $kode_jenis_pangan
			,'kode_r_grup_jenis_pangan' => $kode_r_grup_jenis_pangan
			,'jenis_pangan' => $jenis_pangan
			,'deskripsi' => $deskripsi
		);
		$this->db->insert('tabel_jenis_pangan', $data);
	}

	function select_jenis_pangan($id)
	{
		$this->db->where('id_urut_jenis_pangan', $id);
		return $this->db->get('tabel_jenis_pangan');
	}

	function edit_proses_jenis_pangan($id, $kode_jenis_pangan, $kode_r_grup_jenis_pangan, $jenis_pangan, $deskripsi)
	{
		$data = array(
			'kode_jenis_pangan' => $kode_jenis_pangan
			,'kode_r_grup_jenis_pangan' => $kode_r_grup_jenis_pangan
			,'jenis_pangan' => $jenis_pangan
			,'deskripsi' => $deskripsi
		);
		$this->db->where('id_urut_jenis_pangan', $id);
		$this->db->update('tabel_jenis_pangan', $data);
	}

}

/* End of file Jenis_pangan_model.php */
/* Location: ./application/models/Jenis_pangan_model.php */