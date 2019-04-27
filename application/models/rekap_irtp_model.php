<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_irtp_model extends CI_Model {
	
	function get_pengajuan($params=array()){
		$sql="SELECT a.no_kode_propinsi,a.nama_propinsi,b.id_urut_kabupaten,b.no_kabupaten,b.nm_kabupaten
		,(
			SELECT COUNT(tabel_pen_pengajuan_spp.id_pengajuan)
						FROM
						tabel_pen_pengajuan_spp
						left join tabel_scan_data_pengajuan_rl on tabel_scan_data_pengajuan_rl.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan,
						tabel_teknologi_pengolahan,
						tabel_jenis_pangan,
						tabel_kemasan,
						tabel_propinsi,
						tabel_kabupaten_kota,
						tabel_daftar_perusahaan
						left join tabel_scan_data_pengajuan_siup on tabel_scan_data_pengajuan_siup.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
						left join tabel_scan_data_pengajuan_ap on tabel_scan_data_pengajuan_ap.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
						WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah 
						AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan 
						AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan 
						AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan 
						and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi
						and tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi
						and tabel_kabupaten_kota.id_urut_kabupaten=b.id_urut_kabupaten
						and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten
						order by tanggal_pengajuan desc
		) as jumlah_per_kab
		,(
			SELECT COUNT(tabel_pen_pengajuan_spp.id_pengajuan)
						FROM
						tabel_pen_pengajuan_spp
						left join tabel_scan_data_pengajuan_rl on tabel_scan_data_pengajuan_rl.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan,
						tabel_teknologi_pengolahan,
						tabel_jenis_pangan,
						tabel_kemasan,
						tabel_propinsi,
						tabel_kabupaten_kota,
						tabel_daftar_perusahaan
						left join tabel_scan_data_pengajuan_siup on tabel_scan_data_pengajuan_siup.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
						left join tabel_scan_data_pengajuan_ap on tabel_scan_data_pengajuan_ap.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
						WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah 
						AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan 
						AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan 
						AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan 
						and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi
						and tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi
						and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten
						order by tanggal_pengajuan desc
		) as jumlah_per_prov
		FROM tabel_propinsi a
		INNER JOIN tabel_kabupaten_kota b ON a.no_kode_propinsi=b.no_kode_propinsi";

		if(isset($params['prov']))
		{
			$sql.=" AND a.no_kode_propinsi=".$params['prov'];
		}
		if(isset($params['kab']))
		{
			$sql.=" AND b.id_urut_kabupaten=".$params['kab'];
		}
		if(isset($params['per_prov'])){
			$sql.=" GROUP BY a.no_kode_propinsi";
		}

		$data=$this->db->query($sql)->result_array();
		return $data;
	}

	function get_penerbitan($params=array())
	{
		$sql="SELECT a.no_kode_propinsi,a.nama_propinsi,b.id_urut_kabupaten,b.no_kabupaten,b.nm_kabupaten
		,(SELECT COUNT(tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert) FROM 
						tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt, 
						tabel_pen_pengajuan_spp,
						tabel_daftar_perusahaan,
						tabel_propinsi,
						tabel_kabupaten_kota WHERE 
						tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
						tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
						tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi 
						  and tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi
						and tabel_kabupaten_kota.id_urut_kabupaten=b.id_urut_kabupaten
						  and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND 
						tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null
		) as jumlah_per_kab
		,(SELECT COUNT(tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert) FROM 
						tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt, 
						tabel_pen_pengajuan_spp,
						tabel_daftar_perusahaan,
						tabel_propinsi,
						tabel_kabupaten_kota WHERE 
						tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
						tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
						tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi 
						  and tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi
						  and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND 
						tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null
		) as jumlah_per_prov
		FROM tabel_propinsi a
		INNER JOIN tabel_kabupaten_kota b ON a.no_kode_propinsi=b.no_kode_propinsi";

		if(isset($params['prov']))
		{
			$sql.=" AND a.no_kode_propinsi=".$params['prov'];
		}
		if(isset($params['kab']))
		{
			$sql.=" AND b.id_urut_kabupaten=".$params['kab'];
		}
		if(isset($params['per_prov'])){
			$sql.=" GROUP BY a.no_kode_propinsi";
		}

		$data=$this->db->query($sql)->result_array();
		return $data;
	}
	
	function get_irtp($params=array()){
		
		$sql="
		SELECT a.*,b.*,
		(
		SELECT COUNT(tabel_daftar_perusahaan.kode_perusahaan) FROM `tabel_daftar_perusahaan`
		INNER JOIN tabel_kabupaten_kota on tabel_kabupaten_kota.id_urut_kabupaten=tabel_daftar_perusahaan.id_r_urut_kabupaten
		INNER JOIN tabel_propinsi on tabel_propinsi.no_kode_propinsi=tabel_kabupaten_kota.no_kode_propinsi
		WHERE tabel_kabupaten_kota.id_urut_kabupaten=b.id_urut_kabupaten
		) as jumlah_per_kab,
		(
		SELECT COUNT(tabel_daftar_perusahaan.kode_perusahaan) FROM `tabel_daftar_perusahaan`
		INNER JOIN tabel_kabupaten_kota on tabel_kabupaten_kota.id_urut_kabupaten=tabel_daftar_perusahaan.id_r_urut_kabupaten
		INNER JOIN tabel_propinsi on tabel_propinsi.no_kode_propinsi=tabel_kabupaten_kota.no_kode_propinsi
		WHERE tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi
		) as jumlah_per_prov
		FROM tabel_propinsi as a
		INNER JOIN tabel_kabupaten_kota as b ON a.no_kode_propinsi=b.no_kode_propinsi
		";

		if(isset($params['prov']))
		{
			$sql.=" AND a.no_kode_propinsi=".$params['prov'];
		}
		if(isset($params['kab']))
		{
			$sql.=" AND b.id_urut_kabupaten=".$params['kab'];
		}
		if(isset($params['per_prov']))
		{
			$sql.=" GROUP BY a.no_kode_propinsi";
		}
		
		$data=$this->db->query($sql)->result_array();
		return $data;
		
	}

	function get_pelaksanaan_pkp($params=array())
	{
		$sql="SELECT a.no_kode_propinsi,a.nama_propinsi,b.*
		,(SELECT COUNT(tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan) FROM tabel_penyelenggara_penyuluhan,
			tabel_propinsi,tabel_kabupaten_kota WHERE
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi
			and tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi
		";
		if(isset($params['tahun']))
		{
			$sql.=" and ( YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_awal)=".$params['tahun']
			." OR YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_akhir)= ".$params['tahun']
			.")";
		}
		$sql.="	and tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
		) as jumlah_per_prov,
		(SELECT COUNT(tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan) FROM tabel_penyelenggara_penyuluhan,
			tabel_propinsi,tabel_kabupaten_kota WHERE
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi
			and tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi
		";
		if(isset($params['tahun']))
		{
			$sql.=" and ( YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_awal)=".$params['tahun']
			." OR YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_akhir)= ".$params['tahun']
			.")";
		}
		$sql.="	and tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
		and tabel_kabupaten_kota.id_urut_kabupaten=b.id_urut_kabupaten
		) as jumlah_per_kab,
		(
			SELECT COUNT(tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan) FROM 
						tabel_penyelenggara_penyuluhan, 
						tabel_daftar_perusahaan, 
						tabel_pen_pengajuan_spp, 
						tabel_ambil_penyuluhan,
						tabel_propinsi,
						tabel_kabupaten_kota WHERE 
						tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan and 
						tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND
						tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan and 
						tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi and 
						tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi and
						tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
						tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
		";
		if(isset($params['tahun']))
		{
			$sql.=" and ( YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_awal)=".$params['tahun']
			." OR YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_akhir)= ".$params['tahun']
			.")";
		}
		$sql.=" ) as jumlah_peserta_per_prov,
		(
			SELECT COUNT(tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan) FROM 
						tabel_penyelenggara_penyuluhan, 
						tabel_daftar_perusahaan, 
						tabel_pen_pengajuan_spp, 
						tabel_ambil_penyuluhan,
						tabel_propinsi,
						tabel_kabupaten_kota WHERE 
						tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan and 
						tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND
						tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan and 
						tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi and 
						tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi and
						tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
						tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
		";
		if(isset($params['tahun']))
		{
			$sql.=" and ( YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_awal)=".$params['tahun']
			." OR YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_akhir)= ".$params['tahun']
			.")";
		}
		$sql.=" and tabel_kabupaten_kota.id_urut_kabupaten=b.id_urut_kabupaten ) as jumlah_peserta_per_kab

		FROM tabel_propinsi a
		INNER JOIN tabel_kabupaten_kota b ON b.no_kode_propinsi=a.no_kode_propinsi";
		if(isset($params['prov']))
		{
			$sql.=" and a.no_kode_propinsi=".$params['prov'];
		}
		if(isset($params['kab']))
		{
			$sql.=" and b.id_urut_kabupaten=".$params['kab'];
		}
		if(isset($params['per_prov']))
		{
			$sql.=" GROUP BY a.no_kode_propinsi";
		}
		

		$data=$this->db->query($sql)->result_array();

		return $data;
	}

	function get_tahun_pkp()
	{
		$sql="SELECT YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_awal) as tahun FROM tabel_penyelenggara_penyuluhan,
		tabel_propinsi,tabel_kabupaten_kota WHERE
		tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi
		and tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
		and tabel_penyelenggara_penyuluhan.tanggal_pelatihan_awal>0
		GROUP BY YEAR(tabel_penyelenggara_penyuluhan.tanggal_pelatihan_awal)";

		return $this->db->query($sql)->result_array();
	}

	function get_grup_jenis_pangan($params=array()){
		$sql="SELECT * FROM tabel_grup_jenis_pangan";
		$i=0;
		foreach($params as $key=>$val)
		{
			$sql.=" ".( ($i==0)?"WHERE ":"AND " );
			$sql.=$key."=".$val;
			$i++;
		}
		return $this->db->query($sql)->result_array();
	}

	function get_jenis_pangan($params=array()){
		$sql="SELECT * FROM tabel_jenis_pangan";
		$i=0;
		foreach($params as $key=>$val)
		{
			$sql.=" ".( ($i==0)?"WHERE ":"AND " );
			$sql.=$key."=".$val;
			$i++;
		}
		return $this->db->query($sql)->result_array();
	}

	function get_sppirt_jenis_pangan($params=array())
	{
		$sql="SELECT a.no_kode_propinsi,a.nama_propinsi,b.id_urut_kabupaten,b.no_kabupaten,b.nm_kabupaten
		,(
			SELECT COUNT(tabel_pen_pengajuan_spp.id_pengajuan)
						FROM
						tabel_pen_pengajuan_spp
						left join tabel_scan_data_pengajuan_rl on tabel_scan_data_pengajuan_rl.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan,
						tabel_teknologi_pengolahan,
						tabel_jenis_pangan,
						tabel_kemasan,
						tabel_propinsi,
						tabel_kabupaten_kota,
						tabel_daftar_perusahaan
						left join tabel_scan_data_pengajuan_siup on tabel_scan_data_pengajuan_siup.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
						left join tabel_scan_data_pengajuan_ap on tabel_scan_data_pengajuan_ap.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
						WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah 
						AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan 
						AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan 
						AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan 
						and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi
						and tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi
						and tabel_kabupaten_kota.id_urut_kabupaten=b.id_urut_kabupaten
						and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten
						";
		if(isset($params['jenis']))
		{
			$sql.=" and tabel_jenis_pangan.id_urut_jenis_pangan=".$params['jenis'];
		}
		if(isset($params['grup']))
		{
			$sql.=" and tabel_jenis_pangan.kode_r_grup_jenis_pangan=".$params['grup'];
		}

		$sql.=" order by tanggal_pengajuan desc
		) as jumlah_per_kab
		,(
			SELECT COUNT(tabel_pen_pengajuan_spp.id_pengajuan)
						FROM
						tabel_pen_pengajuan_spp
						left join tabel_scan_data_pengajuan_rl on tabel_scan_data_pengajuan_rl.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan,
						tabel_teknologi_pengolahan,
						tabel_jenis_pangan,
						tabel_kemasan,
						tabel_propinsi,
						tabel_kabupaten_kota,
						tabel_daftar_perusahaan
						left join tabel_scan_data_pengajuan_siup on tabel_scan_data_pengajuan_siup.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
						left join tabel_scan_data_pengajuan_ap on tabel_scan_data_pengajuan_ap.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
						WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah 
						AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan 
						AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan 
						AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan 
						and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi
						and tabel_propinsi.no_kode_propinsi=a.no_kode_propinsi
						and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten";
		
						if(isset($params['jenis']))
						{
							$sql.=" and tabel_jenis_pangan.id_urut_jenis_pangan=".$params['jenis'];
						}
						if(isset($params['grup']))
						{
							$sql.=" and tabel_jenis_pangan.kode_r_grup_jenis_pangan=".$params['grup'];
						}
						
		$sql.=" order by tanggal_pengajuan desc
		) as jumlah_per_prov
		FROM tabel_propinsi a
		INNER JOIN tabel_kabupaten_kota b ON a.no_kode_propinsi=b.no_kode_propinsi";

		if(isset($params['prov'])){
			$sql.=" AND a.no_kode_propinsi=".$params['prov'];
		}
		if(isset($params['kab'])){
			$sql.=" AND b.id_urut_kabupaten=".$params['kab'];
		}
		if(isset($params['per_prov'])){
			$sql.=" GROUP BY a.no_kode_propinsi";
		}
		$data=$this->db->query($sql)->result_array();
		return $data;
	}
	
	function get_prov($params=array())
	{
		foreach($params as $key=>$val)
		{
			if($val!=null)
			{
				$this->db->where($key,$val);
			}
		}
		return $this->db->get('tabel_propinsi')->result_array();
	}
	
	function get_kab($params=array()){
		foreach($params as $key=>$val)
		{
			if($val!=null)
			{
				$this->db->where($key,$val);
			}
		}
		return $this->db->get('tabel_kabupaten_kota')->result_array();
	}
}