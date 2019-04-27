<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Btp_model extends CI_Model {

	function view_grup()
	{
		return $this->db->get('tabel_grup_btp');
	}

	function add_grup($nama_grup_btp)
	{
		$data = array(
			'nama_grup_btp' => $nama_grup_btp
		);

		$this->db->insert('tabel_grup_btp', $data);
	}

	function select_grup($id)
	{
		$this->db->where('kode_grup_btp', $id);
		return $this->db->get('tabel_grup_btp');
	}

	function edit_grup($id, $nama_grup_btp)
	{
		$data = array(
			'nama_grup_btp' => $nama_grup_btp
		);
		$this->db->where('kode_grup_btp', $id);	
		$this->db->update('tabel_grup_btp', $data);
	}

	function view_komposisi()
	{
		$this->db->select('*');
		$this->db->from('tabel_bahan_tambahan_pangan');
		$this->db->join('tabel_grup_btp', 'tabel_grup_btp.kode_grup_btp = tabel_bahan_tambahan_pangan.kode_r_grup_btp');
		$this->db->order_by('no_urut_btp', 'asc');
		return $this->db->get();
	}

	function add_komposisi($kode_btp, $kode_r_grup_btp, $nama_bahan_tambahan_pangan)
	{
		$data = array(
			'kode_btp' => $kode_btp
			,'kode_r_grup_btp' => $kode_r_grup_btp
			,'nama_bahan_tambahan_pangan' => $nama_bahan_tambahan_pangan
		);

		$this->db->insert('tabel_bahan_tambahan_pangan', $data);
	}

	function select_komposisi($id)
	{
		$this->db->where('no_urut_btp', $id);
		return $this->db->get('tabel_bahan_tambahan_pangan');
	}

	function edit_komposisi($id, $kode_btp, $kode_r_grup_btp, $nama_bahan_tambahan_pangan)
	{
		$data = array(
			'kode_btp' => $kode_btp
			,'kode_r_grup_btp' => $kode_r_grup_btp
			,'nama_bahan_tambahan_pangan' => $nama_bahan_tambahan_pangan
		);

		$this->db->where('no_urut_btp', $id);
		$this->db->update('tabel_bahan_tambahan_pangan', $data);
	}

}

/* End of file btp_model.php */
/* Location: ./application/models/btp_model.php */