<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerbitan_sertifikat extends APP_Controller {

public function __construct()
	{
		parent::__construct();
		//load library dan helper yang dibutuhkan
		// $this->load->library('lib_irtp');
		$this->load->helper('url');
		$this->load->model('penerbitan_sertifikat_model','',TRUE);
		$this->load->model('irtp_model','',TRUE);
		$this->load->model('menu_model');	
		//$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
		return view_dashboard('irtp/penerbitan_sertifikat/input_penerbitan_sertifikat');
	}
	
	public function output_penerbitan()
	{
		if($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3){
			if($this->input->post('no_kode_propinsi')!=""){
				$provinsi = $this->input->post('no_kode_propinsi');
			} else {
				$provinsi = $this->session->userdata('code');
			}
		} else {
			$provinsi = $this->input->post('no_kode_propinsi');
		}
		
		if($this->session->userdata('user_segment')==5){
			if($this->input->post('nama_kabupaten')!=""){
				$kabupaten = $this->input->post('nama_kabupaten');
			} else {
				$kabupaten = $this->session->userdata('code');
			}
		} else {
			$kabupaten = $this->input->post('nama_kabupaten');
		}
		
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		if($tanggal_awal!=""){
			$tanggal_awal = date('Y-m-d', strtotime($this->input->post('tanggal_awal')));
		}
		if($tanggal_akhir!=""){
			$tanggal_akhir = date('Y-m-d', strtotime($this->input->post('tanggal_akhir')));
		}
		//load data permohonan
		//$datas = $this->db->get('tabel_penerbitan_sert_pirt')->result();
		$datas = $this->irtp_model->penerbitan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();		
		$data['datas'] = $datas;
		//generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		
		//head
		$tmpl = array ( 'table_open'  => '<table class="table table-bordered" id="data_table">' );
		$this->table->set_template($tmpl);
		
		$this->table->set_heading(
			'No.',
			'Nomor Permohonan',
			'Tanggal Pemberian PIRT',
			'Nomor PIRT',
			'Nomor HK',
			'Nama Kepala Dinas',
			'NIP',
			'Label Final',
			'Laporan'
		);
		
		//isi
		$i = 0;
		foreach($datas as $field){
			$label_type = strtolower(pathinfo($field->label_final, PATHINFO_EXTENSION));
			$label_url = base_url('uploads/'.$field->label_final);
			$label = "-";
			switch ($label_type) {
				case 'png':
				case 'gif':
				case 'jpg':
				case 'jpeg':
					$label = "<a href='{$label_url}' target='_blank'><img src='{$label_url}' style='width:80px;'/></a>";
					break;

				case 'pdf':
					$label = "<a href='{$label_url}' target='_blank'>Unduh</a>";
					break;
			}

			$this->table->add_row(
				++$i,
				$field->nomor_permohonan,
				date('d-m-Y', strtotime($field->tanggal_pemberian_pirt)),
				$field->nomor_pirt,
				$field->nomor_hk,
				$field->nama_kepala_dinas,
				$field->nip,
				$label,
				anchor('irtp/output_laporan_penerbitan_unduh/'.$field->id_urut_penerbitan_sert, 'Unduh Laporan', array('class' => 'btn btn-info col-md-12', 'target' => '_blank'))
				#anchor('pb2kp/permohonan_output_detail/'.$field->nomor_pirt,'Lihat Detail')
			);
		}
		
		$data['table'] = $this->table->generate();
		
		if($provinsi!='' or $kabupaten!=''){
			if($this->session->userdata('user_segment')==5){
				$kab = $this->db->query("select * from tabel_propinsi join tabel_kabupaten_kota on tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi where id_urut_kabupaten in (".$this->session->userdata('code').")")->result();
				
				$data['filter_kab'] = "";
				foreach($kab as $r){
					$prov_arr[] = $r->no_kode_propinsi;
					if($r->id_urut_kabupaten==$kabupaten){
						$data['filter_kab'] = $data['filter_kab']."<option value='$r->id_urut_kabupaten' selected>$r->no_kabupaten. $r->nm_kabupaten</option>";
					} else {
						$data['filter_kab'] = $data['filter_kab']."<option value='$r->id_urut_kabupaten'>$r->no_kabupaten. $r->nm_kabupaten</option>";
					}
				}
				$prov = $this->db->query("select * from tabel_propinsi where no_kode_propinsi in (".implode(",",$prov_arr).")")->result();
			} else {
				$prov = $this->db->query("select * from tabel_propinsi where no_kode_propinsi='$provinsi'")->result();
				foreach($prov as $r){
					$kode_propinsi = $r->no_kode_propinsi;
					$nama_propinsi = $r->nama_propinsi;
				}
				$data['filter'] = "<option value='$kode_propinsi' selected>$nama_propinsi</option><option disabled>- Pilih Provinsi -</option>";
				
				$kab = $this->db->query("select * from tabel_kabupaten_kota where no_kode_propinsi='$provinsi'")->result();
				$data['filter_kab'] = "";
				foreach($kab as $r){
					if($r->id_urut_kabupaten==$kabupaten){
						$data['filter_kab'] = $data['filter_kab']."<option value='$r->id_urut_kabupaten' selected>$r->no_kabupaten. $r->nm_kabupaten</option>";
					} else {
						$data['filter_kab'] = $data['filter_kab']."<option value='$r->id_urut_kabupaten'>$r->no_kabupaten. $r->nm_kabupaten</option>";
					}
				}
			}
			
			if($tanggal_awal!='' and $tanggal_akhir!=''){
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_pangan")->result();
				$data['jml_kemasan'] = $this->db->query("SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_kemasan")->result();
			} else {
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_pangan")->result();
				$data['jml_kemasan'] = $this->db->query("SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_kemasan")->result();
			}
		} else {
			if($this->session->userdata('user_segment')==5){
				$kab = $this->db->query("select * from tabel_propinsi join tabel_kabupaten_kota on tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi where id_urut_kabupaten in (".$this->session->userdata('code').")")->result();
				
				$data['filter_kab'] = "";
				foreach($kab as $r){
					$prov_arr[] = $r->no_kode_propinsi;
					if($r->id_urut_kabupaten==$kabupaten){
						$data['filter_kab'] = $data['filter_kab']."<option value='$r->id_urut_kabupaten' selected>$r->no_kabupaten. $r->nm_kabupaten</option>";
					} else {
						$data['filter_kab'] = $data['filter_kab']."<option value='$r->id_urut_kabupaten'>$r->no_kabupaten. $r->nm_kabupaten</option>";
					}
				}
				//print_r(); exit;
				$prov = $this->db->query("select * from tabel_propinsi where no_kode_propinsi in (".implode(",",$prov_arr).")")->result();
			} else {
				$data['filter'] = "<option disabled selected>- Pilih Provinsi -</option>";
				$data['filter_kab'] = "<option disabled selected>- Pilih Provinsi Terlebih Dahulu -</option>";
			}
			
			if($tanggal_awal!='' and $tanggal_akhir!=''){
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_pangan")->result();
				$data['jml_kemasan'] = $this->db->query("SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pemberian_pirt>='$tanggal_awal' and tanggal_pemberian_pirt<='$tanggal_akhir' AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_kemasan")->result();
			} else {
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_pangan")->result();
				$data['jml_kemasan'] = $this->db->query("SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt left join tabel_pencabutan_pirt on tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt is null GROUP BY jenis_kemasan")->result();
			}
		}
		
		
		if(count(@$prov)>0){
			$data['data_select'] = @$prov;
		} else {
			$data['data_select'] = $this->db->get('tabel_propinsi')->result();
		}
		
		$data['jumlah_irtp'] = $i;
		$data['js_pangan'] = $this->db->get('tabel_jenis_pangan')->result();
		
		//download
		if($provinsi!='' or $kabupaten!=''){
			if($provinsi==""){ $data['propinsi'] = 0; } else { $data['propinsi'] = $provinsi; }
			
			$data['kabupaten'] = $kabupaten;
		} else {
			$data['propinsi'] = 0;
			$data['kabupaten'] = 0;
		}
		$data['tanggal_awal'] = $tanggal_awal;
		$data['tanggal_akhir'] = $tanggal_akhir;
		
		//$data['jml_komposisi'] = $this->db->query("SELECT count(jenis_komposisi) as count_komposisi, jenis_komposisi FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan, tabel_komposisi_tambahan WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan GROUP BY jenis_komposisi")->result();
		//$data['jumlah_irtp_komposisi'] = $jumlah_irtp_komposisi;
		
		return view_dashboard('irtp/penerbitan_sertifikat/output_penerbitan', $data);
	}

	public function output_laporan_penerbitan_unduh($id_urut_penerbitan_sert){
		/* $query =  $this->db->query("SELECT * FROM 
		tabel_kemasan, tabel_penyelenggara_penyuluhan, 
		tabel_ambil_penyuluhan, 
		tabel_penerbitan_sert_pirt, 
		tabel_daftar_perusahaan, 
		tabel_pen_pengajuan_spp, 
		tabel_kabupaten_kota, 
		tabel_propinsi, 
		tabel_jenis_pangan WHERE 
		tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan AND 
		tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten AND 
		tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi AND 
		tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
		tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = '".$id_urut_penerbitan_sert."' and 
		tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan and 
		tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan and 
		tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and 
		tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
		tabel_kemasan.kode_kemasan = tabel_pen_pengajuan_spp.kode_r_kemasan"); */
		$query =  $this->db->query("SELECT * FROM 
		tabel_kemasan,  
		tabel_penerbitan_sert_pirt, 
		tabel_daftar_perusahaan, 
		tabel_pen_pengajuan_spp, 
		tabel_kabupaten_kota, 
		tabel_propinsi, 
		tabel_jenis_pangan WHERE 
		tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan AND 
		tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten AND 
		tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi AND 
		tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
		tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert = '".$id_urut_penerbitan_sert."' and 
		tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan and 
		tabel_kemasan.kode_kemasan = tabel_pen_pengajuan_spp.kode_r_kemasan");
		//print_r($this->db->last_query());
		
		//if($query->num_rows >= 1){
			$data['query'] = $query->result();
			$this->load->view('irtp/penerbitan_sertifikat/print_3', $data);
		//}
	}
	
	public function get_kabupaten(){
		$provinsi = $this->input->post('provinsi');
		$result = $this->irtp_model->get_kabupaten($provinsi)->result();
				
		echo json_encode($result);
	}
	
	public function get_irtp_raw(){
		$nomor = $this->input->post('nomor');	
		$mode = $this->input->post('mode');
		if($mode == "NULL"){
			$query = '
			SELECT * FROM 
			tabel_pen_pengajuan_spp, 
			tabel_daftar_perusahaan , tabel_kabupaten_kota, tabel_propinsi, tabel_jenis_pangan
			WHERE 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND 
			tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten AND
			tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi AND
			tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND
			tabel_pen_pengajuan_spp.nomor_permohonan = "'.$nomor.'"
			';
			
			$data = $this->db->query($query)->result();
		}else{
			$query = 'SELECT * FROM tabel_pen_pengajuan_spp, tabel_daftar_perusahaan, tabel_kabupaten_kota, tabel_propinsi, tabel_jenis_pangan, tabel_kemasan
			WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan 
			AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan 
			AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan 
			AND tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten 
			AND tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi 
			AND tabel_pen_pengajuan_spp.nomor_permohonan = "'.$nomor.'" LIMIT 1';	
			// print_r($query); exit;
			$data = $this->db->query($query)->result();
			foreach($data as $data){
				//$kemasan = str_pad($data->kode_kemasan, 2, '0', STR_PAD_LEFT);
				$kemasan = $data->kode_kemasan;
				$jen_pangan = str_pad($data->kode_r_grup_jenis_pangan, 2, '0', STR_PAD_LEFT);
				$nama_kabupaten = $data->nm_kabupaten;	
				$nama_propinsi = $data->nama_propinsi;	
				$no_kabupaten = $data->no_kabupaten;
				$no_kode_propinsi = $data->no_kode_propinsi;
				$kode_perusahaan = $data->kode_perusahaan;
			} # End foreach
			
			//Digit 8 dan 9
			$q_jml_spprit = $this->db->query("select * from tabel_penerbitan_sert_pirt, tabel_pen_pengajuan_spp, tabel_daftar_perusahaan, tabel_kabupaten_kota, tabel_propinsi
				WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan
				AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
				AND tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
				AND tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi
				AND tabel_kabupaten_kota.no_kode_propinsi = '".$no_kode_propinsi."'
				AND tabel_kabupaten_kota.no_kabupaten = '".$no_kabupaten."'
				AND tabel_daftar_perusahaan.kode_perusahaan = '".$kode_perusahaan."'
				order by tanggal_pemberian_pirt asc")->result_array();
			$jml_spprit = str_pad((count($q_jml_spprit)+1), 2, '0', STR_PAD_LEFT);

			if(count($q_jml_spprit)==0){
				$match = $this->db->query("SELECT LPAD( IFNULL( SUBSTRING( nomor_pirt, 11, 4 ) +1, 1 ) , 4,  '0' ) AS id FROM tabel_penerbitan_sert_pirt 
				WHERE SUBSTRING( nomor_pirt, 4, 2 ) ='".$no_kode_propinsi."'
				AND SUBSTRING( nomor_pirt, 6, 2 ) ='".$no_kabupaten."'
				ORDER BY SUBSTRING( nomor_pirt, 11, 4 ) DESC 
				LIMIT 1")->result_array();
				$getNum = $match[0]['id'];
			} else {
				$getNum = substr($q_jml_spprit[0]['nomor_pirt'], 9, 4);

			}
			
			$temp = $kemasan.$jen_pangan.$no_kode_propinsi.$no_kabupaten.$jml_spprit.$getNum.'-'.date("y", strtotime("+ 1825 day"));
			// print_r($temp); exit;
			$data = array('no_pirt' => $temp, 'provinsi' => $nama_propinsi, 'kabupaten' => $nama_kabupaten);
		}	# End if
		
		echo(json_encode($data));
	} # End function
    function add()
    {
        $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$config = array(
			array(
				'field' => 'tanggal_pemberian_pirt',
				'label'   => 'Tanggal Pemberian Nomor PIRT', 
                'rules'   => 'trim|required|xss_clean'
			),
			array(
				'field' => 'nomor_pirt',
				'label'   => 'Nomor PIRT', 
                'rules'   => 'trim|required|xss_clean'
			),
			array(
				'field' => 'nama_kepala_dinas',
				'label'   => 'Nama Kepala Dinas', 
                'rules'   => 'trim|required|xss_clean'
			),
			array(
				'field' => 'nip',
				'label'   => 'NIP Kepala Dinas', 
                'rules'   => 'trim|required|xss_clean'
			),
			
		);

		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE)
		{
			$data['status'] = $this->session->set_flashdata('<div class="alert alert-warning">Data tidak tersubmit karena terjadi kesalahan. Harap ulangi</div>');
			$this->session->set_flashdata('inputs', $_POST);
			redirect('penerbitan_sertifikat/output_penerbitan');
		}
		else
		{
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'jpg|png|pdf|jpeg';
			$config['max_size']	= '20000';
			$config['overwrite'] = TRUE;
			$dataUp = "file_foto";
	
			$this->load->library('upload');
			$this->upload->initialize($config);
			
			if ( ! $this->upload->do_upload($dataUp))
			{
				$error = $this->upload->display_errors("<div class='alert alert-danger'><strong>Perhatian! </strong>Gagal Upload Label final. ", "</div>");
				$this->session->set_flashdata('status', $error);
				$this->session->set_flashdata('inputs', $_POST);
				redirect('penerbitan_sertifikat/output_penerbitan');
			}
			else
			{
				$userdata = $this->upload->data();
				$filename = $userdata['file_name'];
				$data = array('upload_data' => $userdata);
				$this->irtp_model->add_data_irtp_penerbitan($filename); // ambil dari file models/irtp_model.php
				//$this->load->view('formsuccess');
				$data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Informasi Penerbitan IRTP telah dimasukkan</div>');

				redirect('penerbitan_sertifikat/output_penerbitan');
			}			
		}
    }
	public function input()
	{
		if($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3){
			$provinsi = $this->session->userdata('code');
			
		}
		
		if($this->session->userdata('user_segment')==5){
			
			$kabupaten = $this->session->userdata('code');
			
		} 
		
		if(@$provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if(@$kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		
		$data['js_kabupaten'] = $this->db->get('tabel_kabupaten_kota')->result();
		$data['js_propinsi'] = $this->db->get('tabel_propinsi')->result();
		$data['no_irtp'] = $this->db->query('SELECT distinct nomor_permohonan, nama_perusahaan, nama_pemilik, nama_dagang FROM 
		tabel_pen_pengajuan_spp, 
		tabel_daftar_perusahaan, 
		tabel_ambil_penyuluhan, 
		tabel_kabupaten_kota,
		tabel_propinsi,
		tabel_periksa_sarana_produksi WHERE 
		tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND 
		tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and
		tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_periksa_sarana_produksi.nomor_r_permohonan and 
		tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and 
		tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'')->result();
		
		$data['old_inputs'] = (array) $this->session->flashdata('inputs');
		return view_dashboard('irtp/penerbitan_sertifikat/input_penerbitan_sertifikat', $data);
	}
	
	function cetak()
	{
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()->setCreator('SPPIRT')
                 ->setLastModifiedBy('SPPIRT')
                 ->setTitle("Laporan Data Penerbitan Sertifikat SPP-IRT")
                 ->setSubject("Pengajuan SPPIRT")
                 ->setDescription("Laporan Data Penerbitan Sertifikat SPP-IRT")
                 ->setKeywords("Data Penerbitan Sertifikat");

        $style_col = array(
	      'font' => array('bold' => true), // Set font nya jadi bold
	      'alignment' => array(
	        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
	        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
	      ),
	      'borders' => array(
	        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
	        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
	        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
	        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
	      )
	    );
	    // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
	    $style_row = array(
	      'alignment' => array(
	        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
	      ),
	      'borders' => array(
	        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
	        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
	        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
	        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
	      )
	    );
	    $excel->setActiveSheetIndex(0)->setCellValue('A1', "LAPORAN DATA PENERBITAN SERTIFIKAT SPP-IRT"); // Set kolom A1 dengan tulisan "DATA SISWA"
	    $excel->getActiveSheet()->mergeCells('A1:K1'); // Set Merge Cell pada kolom A1 sampai E1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

	    // Buat header tabel nya pada baris ke 3
	    $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO PERMOHONAN"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA PERUSAHAAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('D3', "NAMA PEMILIK"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA KABUPATEN"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('F3', "NAMA PROVINSI"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('G3', "TANGGAL PEMBERIAN PIRT"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('H3', "NOMOR PIRT"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('I3', "NOMOR HK");
	    $excel->setActiveSheetIndex(0)->setCellValue('J3', "NAMA KEPALA DINAS");
	    $excel->setActiveSheetIndex(0)->setCellValue('K3', "NIP");

	    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
	    $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);


	    if($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3){
			if($this->input->post('no_kode_propinsi')!=""){
				$provinsi = $this->input->post('no_kode_propinsi');
			} else {
				$provinsi = $this->session->userdata('code');
			}
		} else {
			$provinsi = $this->input->post('no_kode_propinsi');
		}
		
		if($this->session->userdata('user_segment')==5){
			if($this->input->post('nama_kabupaten')!=""){
				$kabupaten = $this->input->post('nama_kabupaten');
			} else {
				$kabupaten = $this->session->userdata('code');
			}
		} else {
			$kabupaten = $this->input->post('nama_kabupaten');
		}
		
		if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		if($tanggal_awal!=""){
			$tanggal_awal = date('Y-m-d', strtotime($this->input->post('tanggal_awal')));
		}
		if($tanggal_akhir!=""){
			$tanggal_akhir = date('Y-m-d', strtotime($this->input->post('tanggal_akhir')));
		}
		//load data permohonan
		//$datas = $this->db->get('tabel_penerbitan_sert_pirt')->result();
		$datas = $this->irtp_model->penerbitan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();		
		$data['datas'] = $datas;
		
	    $no = 1;
	    $numrow = 4;
	    foreach ($datas as $field)
	    {
						    $label_type = strtolower(pathinfo($field->label_final, PATHINFO_EXTENSION));
							$label_url = base_url('uploads/'.$field->label_final);
							$label = "-";
							switch ($label_type) {
								case 'png':
								case 'gif':
								case 'jpg':
								case 'jpeg':
									$label = "<a href='{$label_url}' target='_blank'><img src='{$label_url}' style='width:80px;'/></a>";
									break;

								case 'pdf':
									$label = "<a href='{$label_url}' target='_blank'>Unduh</a>";
									break;
							}
		
		// isi excel
	   $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
       $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $field->nomor_permohonan);
       $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $field->nama_perusahaan);
       $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $field->nama_pemilik);
       $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $field->nm_kabupaten);
       $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $field->nama_propinsi);
       $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $field->tanggal_pemberian_pirt);
       $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $field->nomor_pirt);
       $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $field->nomor_hk);
       $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $field->nama_kepala_dinas);
       $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $field->nip);

       $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);

       $no++;
       $numrow++;
		}

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	    $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	    $excel->getActiveSheet()->getColumnDimension('F')->setWidth(30); 
	    $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30); 
	    $excel->getActiveSheet()->getColumnDimension('H')->setWidth(30); 
	    $excel->getActiveSheet()->getColumnDimension('I')->setWidth(30); 
	    $excel->getActiveSheet()->getColumnDimension('J')->setWidth(30); 
	    $excel->getActiveSheet()->getColumnDimension('K')->setWidth(30); 
	    
	    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	    $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
	    // Set orientasi kertas jadi LANDSCAPE
	    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	    // Set judul file excel nya
	    $excel->getActiveSheet(0)->setTitle("LAPORAN DATA");
	    $excel->setActiveSheetIndex(0);
	    // Proses file excel
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="LAPORAN DATA PENERBITAN SERTIFIKAT SPP-IRT.xlsx"'); // Set nama file excel nya
	    header('Cache-Control: max-age=0');
	    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	    $write->save('php://output');
	}

	function delete()
	    {
		    $param=array("id_urut_penerbitan_sert"=>$this->uri->segment(3));
    		$this->irtp_model->delete_penerbitan_sertifikat($param);
		    echo "
		    <script>
		    alert('Penerbitan Berhasil di Hapus.');
		    window.location.href='".base_url()."penerbitan_sertifikat/output_penerbitan';
		    </script>";
	    }

	    function edit()
	    {
	    	if($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3){
			$provinsi = $this->session->userdata('code');
			
			}
			
			if($this->session->userdata('user_segment')==5){
				
				$kabupaten = $this->session->userdata('code');
				
			} 
			
			if(@$provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
			if(@$kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
			
			
			$data['js_kabupaten'] = $this->db->get('tabel_kabupaten_kota')->result();
			$data['js_propinsi'] = $this->db->get('tabel_propinsi')->result();
			$data['no_irtp'] = $this->db->query('SELECT distinct nomor_permohonan, nama_perusahaan, nama_pemilik, nama_dagang FROM 
			tabel_pen_pengajuan_spp, 
			tabel_daftar_perusahaan, 
			tabel_ambil_penyuluhan, 
			tabel_kabupaten_kota,
			tabel_propinsi,
			tabel_periksa_sarana_produksi WHERE 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND 
			tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and
			tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_periksa_sarana_produksi.nomor_r_permohonan and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and 
			tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'')->result();
			
			$data['old_inputs'] = (array) $this->session->flashdata('inputs');

			$param=array("id_urut_penerbitan_sert"=>$this->uri->segment(3));
      		$data['penerbitan'] = $this->irtp_model->edit_penerbitan($param)->row_array();

	    	return view_dashboard('irtp/penerbitan_sertifikat/edit', $data);
	    }
}