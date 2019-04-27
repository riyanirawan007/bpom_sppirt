<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materi_penyuluhan_model extends CI_Model {

	function view()
	{
		$this->db->select('tabel_materi_penyuluhan.*, tabel_propinsi.nama_propinsi, tabel_kabupaten_kota.nm_kabupaten');
		$this->db->from('tabel_materi_penyuluhan');
		$this->db->join('tabel_propinsi', 'tabel_propinsi.no_kode_propinsi = tabel_materi_penyuluhan.no_kode_propinsi', 'left');
		$this->db->join('tabel_kabupaten_kota', 'tabel_kabupaten_kota.id_urut_kabupaten = tabel_materi_penyuluhan.id_urut_kabupaten', 'left');
		return $this->db->get();
	}

	function jenis_materi()
	{
		$this->db->group_by('jenis');
		return $this->db->get('tabel_materi_penyuluhan');
	}

	function propinsi()
	{
		return $this->db->get('tabel_propinsi');
	}

	function get_kabupaten_kota($id){
		$hasil=$this->db->query("SELECT * FROM tabel_kabupaten_kota WHERE no_kode_propinsi='$id'");
		return $hasil->result();
	}

	function kabupaten_kota()
	{
		return $this->db->get('tabel_kabupaten_kota');
	}

	function select($id)
	{
		$this->db->select('tabel_materi_penyuluhan.*, tabel_propinsi.nama_propinsi, tabel_kabupaten_kota.nm_kabupaten');
		$this->db->from('tabel_materi_penyuluhan');
		$this->db->join('tabel_propinsi', 'tabel_propinsi.no_kode_propinsi = tabel_materi_penyuluhan.no_kode_propinsi', 'left');
		$this->db->join('tabel_kabupaten_kota', 'tabel_kabupaten_kota.id_urut_kabupaten = tabel_materi_penyuluhan.id_urut_kabupaten', 'left');
		$this->db->where('tabel_materi_penyuluhan.kode_materi_penyuluhan', $id);
		return $this->db->get();
	}



}

/* End of file materi_penyuluhan_model.php */
/* Location: ./application/models/materi_penyuluhan_model.php */