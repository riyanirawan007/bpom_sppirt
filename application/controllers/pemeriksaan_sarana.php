<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pemeriksaan_sarana extends APP_Controller {

	public function __construct()
	{
		parent::__construct();
		//load library dan helper yang dibutuhkan
		// $this->load->library('lib_irtp');
		$this->load->helper('url');
		$this->load->model('pemeriksaan_sarana_model','',TRUE);
		$this->load->model('irtp_model','',TRUE);
		$this->load->model('menu_model');	
		//$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
		return view_dashboard('irtp/pemeriksaan_sarana/input_pemeriksaan_sarana');
	}
	
	public function output_pemeriksaan()
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
		
		if($provinsi!=""){ $q_provinsi = "and PP.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and KK.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		if($tanggal_awal!=""){
			$tanggal_awal = date('Y-m-d', strtotime($this->input->post('tanggal_awal')));
		}
		if($tanggal_akhir!=""){
			$tanggal_akhir = date('Y-m-d', strtotime($this->input->post('tanggal_akhir')));
		}
		
		//load data permohonan
		//$datas = $this->db->get('tabel_periksa_sarana_produksi_tahap_1')->result();
		$datas = $this->irtp_model->pemeriksaan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();		
		$data['datas'] = $datas;
		
		/* $this->load->library('table');
		$this->table->set_empty("&nbsp;");
		
		$tmpl = array ( 'table_open'  => '<table class="table table-bordered">' );
		$this->table->set_template($tmpl);
		
		$this->table->set_heading(
			'No.',
			'Nomor Permohonan',
			'Nama IRTP',
			'Alamat',
			'Nama Jenis Pangan',
			'Nama Produk Pangan',
			'Nama Dagang',
			'Jenis Kemasan',
			'Jumlah Ketidaksesuain',
			'Level IRTP',
			'Frekuensi Audit Internal',
			'Tanggal Pemeriksaan'
		);

		
		//isi
		$i = 0;
		foreach($datas as $field){
			
			$this->table->add_row(++$i,
				$field->nomor_permohonan,
				$field->nama_perusahaan,
				$field->alamat_irtp,
				$field->jenis_pangan,
				$field->deskripsi_pangan,
				$field->nama_dagang,
				$field->jenis_kemasan,
				$this->db->query("select * from tabel_periksa_sarana_produksi where nomor_r_sert_pangan = '".$field->nomor_permohonan."'")->num_rows(),
				$field->level_irtp,
				$field->frekuensi_audit,
				date("d F Y", strtotime($field->tanggal_pemeriksaan))
				
			);
		}
		
		$data['table'] = $this->table->generate(); */
		
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
			
			
		}
		
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
		
		$data['list_jumlah_irtp_kriteria'] = array();
		foreach(array('Mayor', 'Minor', 'Serius', 'Kritis') as $kriteria) {
			$data['list_jumlah_irtp_kriteria'][$kriteria] = $this->irtp_model->get_jumlah_irtp_by_kriteria($kriteria,$q_provinsi,$q_kabupaten,$tanggal_awal,$tanggal_akhir);
		}

		$data['list_jumlah_irtp_level'] = array();
		foreach(array('Level I', 'Level II', 'Level III', 'Level IV') as $level) {
			$data['list_jumlah_irtp_level'][$level] = $this->irtp_model->get_jumlah_irtp_by_level($level,$q_provinsi,$q_kabupaten,$tanggal_awal,$tanggal_akhir);
		}

		if(count(@$prov)>0){
			$data['data_select'] = @$prov;
		} else {
			$data['data_select'] = $this->db->get('tabel_propinsi')->result();
		}
		return view_dashboard('irtp/pemeriksaan_sarana/output_pemeriksaan', $data);
	}
	
	public function input()
	{

		if (isset($_POST['submit'])) {
			if($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3){
				$provinsi = $this->session->userdata('code');
			}

			if($this->session->userdata('user_segment')==5){
				$kabupaten = $this->session->userdata('code');
			} 

			if(@$provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
			if(@$kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }

			$data['no_sert'] = $this->db->query('SELECT distinct nomor_permohonan, nama_perusahaan, nama_pemilik, nama_dagang FROM 
				tabel_pen_pengajuan_spp, 
				tabel_daftar_perusahaan, 
				tabel_ambil_penyuluhan, 
				tabel_kabupaten_kota, 
				tabel_propinsi WHERE 
				tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND 
				tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
				tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and 
				tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'')->result();

			$data['nomor_ketidaksesuaian'] = $this->db->get('tabel_ketidaksesuaian')->result();
			$data['plor'] = $this->db->get('tabel_plor')->result();
			$data['sesuaian'] = $this->db->get('tabel_kriteria_ketidaksesuaian')->result();
			$data['js_kabupaten'] = $this->db->get('tabel_kabupaten_kota')->result();
			$data['js_propinsi'] = $this->db->get('tabel_propinsi')->result();
			$data['js_pengawas'] = $this->irtp_model->data_dfi()->result();

			$dp = $this->input->post('data_penyuluhan');
			if ($dp == "belum_penyuluhan") {
				$data['data_penyuluhan'] = "Belum Penyuluhan";
				$data['alasan_belum_penyuluhan'] = $this->input->post('alasan_belum_penyuluhan');
			} else {
				$data['data_penyuluhan'] = "Sudah Penyuluhan";
				$data['alasan_belum_penyuluhan'] = "-";
			}

			$form = $this->input->post('data_form');
			if ($form == 'form_baku') {
				$data['data_form'] = "Form Baku";
				$data['alasan_form_tidak_baku'] = "-";
				return view_dashboard('irtp/pemeriksaan_sarana/input_pemeriksaan_sarana', $data);
			} else {
				$data['data_form'] = "Form Tidak Baku";
				$data['alasan_form_tidak_baku'] = $this->input->post('alasan_form_tidak_baku');
				return view_dashboard('irtp/pemeriksaan_sarana/form_tidak_baku', $data);
			}
		} else {
			return view_dashboard('irtp/pemeriksaan_sarana/form_tanya');

		}
	}

	public function input2()
	{

		if($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3){
			$provinsi = $this->session->userdata('code');
			
		}
		
		if($this->session->userdata('user_segment')==5){
			
			$kabupaten = $this->session->userdata('code');
			
		} 
		
		if(@$provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if(@$kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		
		$data['no_sert'] = $this->db->query('SELECT distinct nomor_permohonan, nama_perusahaan, nama_pemilik, nama_dagang FROM 
			tabel_pen_pengajuan_spp, 
			tabel_daftar_perusahaan, 
			tabel_ambil_penyuluhan, 
			tabel_kabupaten_kota, 
			tabel_propinsi WHERE 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND 
			tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and 
			tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'')->result();
		
		
		$data['nomor_ketidaksesuaian'] = $this->db->get('tabel_ketidaksesuaian')->result();
		$data['plor'] = $this->db->get('tabel_plor')->result();
		$data['sesuaian'] = $this->db->get('tabel_kriteria_ketidaksesuaian')->result();
		$data['js_kabupaten'] = $this->db->get('tabel_kabupaten_kota')->result();
		$data['js_propinsi'] = $this->db->get('tabel_propinsi')->result();
		$data['js_pengawas'] = $this->irtp_model->data_dfi()->result();


		return view_dashboard('irtp/pemeriksaan_sarana/input_pemeriksaan_sarana_bak', $data);
		
		
	}

	function pemeriksaan_lanjut()
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
		
		if($provinsi!=""){ $q_provinsi = "and PP.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if($kabupaten!=""){ $q_kabupaten = "and KK.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		if($tanggal_awal!=""){
			$tanggal_awal = date('Y-m-d', strtotime($this->input->post('tanggal_awal')));
		}
		if($tanggal_akhir!=""){
			$tanggal_akhir = date('Y-m-d', strtotime($this->input->post('tanggal_akhir')));
		}
		
		//load data permohonan
		$datas = $this->irtp_model->pemeriksaan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();		
		$data['datas'] = $datas;
		
		
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
			
			
		}
		
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
		
		
		if(count(@$prov)>0){
			$data['data_select'] = @$prov;
		} else {
			$data['data_select'] = $this->db->get('tabel_propinsi')->result();
		}
		return view_dashboard('irtp/pemeriksaan_sarana/output_pemeriksaan_lanjut' ,$data);
		// $this->lib_irtp->display('output_pemeriksaan_lanjut',$data);
		
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

	public function set_data_irtp_pemeriksaan(){
		$list_level_ketidaksesuaian = $this->input->post('level_ketidaksesuaian');
		
		//list($level_irtp, $frekuensi_audit) = $this->get_frekuensi_audit($list_level_ketidaksesuaian);
		$level_irtp = $this->input->post('level');
		$frekuensi_audit = $this->input->post('freq');
		$nomor_permohonan = $this->input->post('nomor_permohonan');
		$tujuan_pemeriksaan = $this->input->post('tujuan_pemeriksaan');
		$tanggal_pemeriksaan = $this->input->post('tanggal_pemeriksaan');
		$kode_kepala_pengawas = $this->input->post('nip_pengawas');
		
		// $jabatan_pengawas = $this->input->post('jabatan_pengawas');
		$get_pengawas = $this->irtp_model->data_dfi("and kode_narasumber = '".$kode_kepala_pengawas."'")->result_array();
		$nama_pengawas = $get_pengawas[0]['nama_narasumber'];
		$nip_pengawas = $get_pengawas[0]['nip_pkp_dfi'];
		
		$kode_anggota_pengawas = $this->input->post('nip_anggota_pengawas');
		$temp_nip_anggota = array();
		$temp_nama_anggota = array();
		foreach ($kode_anggota_pengawas as $key => $value) {
			$get_pengawas = $this->irtp_model->data_dfi("and kode_narasumber = '".$value."'")->result_array();
			array_push($temp_nip_anggota, $get_pengawas[0]['nip_pkp_dfi']);
			array_push($temp_nama_anggota, $get_pengawas[0]['nama_narasumber']);
		}
		$nip_anggota_pengawas = implode('|', $temp_nip_anggota);
		$nama_anggota_pengawas = implode('|', $temp_nama_anggota);
		$nama_observer_pengawas = implode('|', $this->input->post('nip_observer_pengawas'));


		$kode_verifikator = $this->input->post('nip_verifikator');
		$get_verifikator = $this->irtp_model->data_dfi("and kode_narasumber = '".$kode_verifikator."'")->result_array();
		if(count($get_verifikator>0)){
			$nama_verifikator = $get_verifikator[0]['nama_narasumber'];
			$nip_verifikator = $get_verifikator[0]['nip_pkp_dfi'];
		} else {
			$nama_verifikator = "";
			$nip_verifikator = "";
		}
		
		$list_no_ketidaksesuaian = $this->input->post('no_ketidaksesuaian');
		$list_nama_ketidaksesuaian = $this->input->post('nama_ketidaksesuaian');
		$list_tindakan_perbaikan = $this->input->post('tindakan_perbaikan');
		$list_status_verifikasi = $this->input->post('status_verifikasi');
		$list_plor = $this->input->post('plor');
		$list_tanggal_batas_waktu = $this->input->post('tanggal_batas_waktu');
		

		$penyuluhan = $this->input->post('penyuluhan');
		$alasan_bp = $this->input->post('alasan_bp');
		$form = $this->input->post('form');
		$alasan_tb = $this->input->post('alasan_tb');
		//Insert header pemeriksaan
		$data = array(
			'nomor_r_permohonan' => $nomor_permohonan,
			'tujuan_pemeriksaan' => $tujuan_pemeriksaan,
			'tanggal_pemeriksaan' => date('Y-m-d', strtotime($tanggal_pemeriksaan)),
			'frekuensi_audit' => $frekuensi_audit,
			'level_irtp' => $level_irtp,
			'nip_pengawas' => $nip_pengawas,
			'nama_pengawas' => $nama_pengawas,
			'jabatan_pengawas' => 'Kepala',
			'nip_anggota_pengawas'=>$nip_anggota_pengawas,
			'nama_anggota_pengawas'=>$nama_anggota_pengawas,
			'nama_observer_pengawas'=>$nama_observer_pengawas,
			'nip_verifikator' => $nip_verifikator,
			'nama_verifikator' => $nama_verifikator,
			'penyuluhan' => $penyuluhan,
			'alasan_bp' => $alasan_bp,
			'form' => $form,
			'alasan_tb' => $alasan_tb
		);
		$insert = $this->db->insert('tabel_periksa_sarana_produksi', $data);
		
		
		//Insert detail pemeriksaan
		$insert_batch = array();
		$id_r_urut_periksa_sarana_produksi = $this->db->insert_id();
		foreach($list_nama_ketidaksesuaian as $i => $nama_ketidaksesuaian) {
			//echo $list_no_ketidaksesuaian[$i]."- ".$list_nama_ketidaksesuaian[$i]."<br>";
			$insert_batch_data[] = array(
				'id_r_urut_periksa_sarana_produksi' => $id_r_urut_periksa_sarana_produksi,
				'nomor_r_permohonan' => $nomor_permohonan,
				'no_ketidaksesuaian' => $list_no_ketidaksesuaian[$i],
				'nama_ketidaksesuaian' => $list_nama_ketidaksesuaian[$i],
				'level' => $list_level_ketidaksesuaian[$i],
				'tindakan' => $list_tindakan_perbaikan[$i],
				'status' => $list_status_verifikasi[$i],
				'plor' => $list_plor[$i],
				'tanggal_batas_waktu' => $list_tanggal_batas_waktu[$i]
			);
		}
		if(count($insert_batch_data)>0){
			$insert_batch = $this->db->insert_batch('tabel_periksa_sarana_produksi_detail', $insert_batch_data);
		} else $insert_batch = true;

		//buat history
		$data = array(
			'id_r_urut_periksa_sarana_produksi' => $id_r_urut_periksa_sarana_produksi,
			'nomor_r_permohonan' => $nomor_permohonan,
			'tujuan_pemeriksaan' => $tujuan_pemeriksaan,
			'tanggal_pemeriksaan' => date('Y-m-d', strtotime($tanggal_pemeriksaan)),
			'frekuensi_audit' => $frekuensi_audit,
			'level_irtp' => $level_irtp,
			'nip_pengawas' => $nip_pengawas,
			'nama_pengawas' => $nama_pengawas,
			'jabatan_pengawas' => $jabatan_pengawas,
			'nip_verifikator' => $nip_verifikator,
			'nama_verifikator' => $nama_verifikator
		);
		
		$insert2 = $this->db->insert('tabel_periksa_sarana_produksi_record', $data);
		$id_record = $this->db->insert_id();
		foreach($list_nama_ketidaksesuaian as $i => $nama_ketidaksesuaian) {
			//echo $list_no_ketidaksesuaian[$i]."- ".$list_nama_ketidaksesuaian[$i]."<br>";
			$insert_batch_data2[] = array(
				'id_record' => $id_record,
				'id_r_urut_periksa_sarana_produksi' => $id_r_urut_periksa_sarana_produksi,
				'nomor_r_permohonan' => $nomor_permohonan,
				'no_ketidaksesuaian' => $list_no_ketidaksesuaian[$i],
				'nama_ketidaksesuaian' => $list_nama_ketidaksesuaian[$i],
				'level' => $list_level_ketidaksesuaian[$i],
				'tindakan' => $list_tindakan_perbaikan[$i],
				'status' => $list_status_verifikasi[$i],
				'plor' => $list_plor[$i],
				'tanggal_batas_waktu' => $list_tanggal_batas_waktu[$i]
			);
		}
		if(count($insert_batch_data2)>0){
			$insert_batch2 = $this->db->insert_batch('tabel_periksa_sarana_produksi_record_detail', $insert_batch_data2);
		} else $insert_batch2 = true;
		
		if($insert==true and $insert2==true and $insert_batch==true and $insert_batch2==true){
			$this->session->set_flashdata('status', '<div class="alert alert-info"><strong>Selamat!</strong> Data Pemeriksaan Sarana Produksi berhasil disimpan</div>');
		} else {
			$this->session->set_flashdata('status', '<div class="alert alert-danger"><strong>Maaf, </strong> Data Pemeriksaan Sarana Produksi gagal disimpan</div>');
		}
		redirect('pemeriksaan_sarana/output_pemeriksaan');
	}
	
	public function set_data_irtp_pemeriksaan_lanjut(){
		$list_level_ketidaksesuaian = $this->input->post('level');
		
		//list($level_irtp, $frekuensi_audit) = $this->get_frekuensi_audit($list_level_ketidaksesuaian);
		$level_irtp = $this->input->post('level_irtp');
		$frekuensi_audit = $this->input->post('freq_irtp');
		$nomor_permohonan = $this->input->post('nomor_permohonan');
		$tujuan_pemeriksaan = $this->input->post('tujuan_pemeriksaan');
		$tanggal_pemeriksaan = $this->input->post('tanggal_pemeriksaan');
		$nip_pengawas = $this->input->post('nip_pengawas');
		$nama_pengawas = $this->input->post('nama_pengawas');
		$jabatan_pengawas = $this->input->post('jabatan_pengawas');
		$nip_anggota_pengawas = $this->input->post('nip_anggota_pengawas');
		$nama_anggota_pengawas = $this->input->post('nama_anggota_pengawas');
		$nama_observer_pengawas = $this->input->post('nama_observer_pengawas');
		
		
		$kode_verifikator = $this->input->post('nip_verifikator');
		$get_verifikator = $this->irtp_model->data_dfi("and kode_narasumber = '".$kode_verifikator."'")->result_array();
		if(count($get_verifikator)>0){
			$nama_verifikator = $get_verifikator[0]['nama_narasumber'];
			$nip_verifikator = $get_verifikator[0]['nip_pkp_dfi'];
		} else {
			$nama_verifikator = "";
			$nip_verifikator = "";
		}
		
		$list_status_ketidaksesuaian = $this->input->post('status_ketidaksesuaian');
		$list_no_ketidaksesuaian = $this->input->post('no_ketidaksesuaian');
		$list_nama_ketidaksesuaian = $this->input->post('nama_ketidaksesuaian');
		$list_tindakan_perbaikan = $this->input->post('tindakan_perbaikan');
		$list_status_verifikasi = $this->input->post('status_verifikasi');
		$list_plor = $this->input->post('plor');
		$list_tanggal_batas_waktu = $this->input->post('tanggal_batas_waktu');
		
		//RECORD
		//Insert header pemeriksaan
		$id_r_urut_periksa_sarana_produksi = $this->input->post('id_r_urut_periksa_sarana_produksi');
		$data = array(
			'id_r_urut_periksa_sarana_produksi' => $id_r_urut_periksa_sarana_produksi,
			'nomor_r_permohonan' => $nomor_permohonan,
			'tujuan_pemeriksaan' => $tujuan_pemeriksaan,
			'tanggal_pemeriksaan' => date('Y-m-d', strtotime($tanggal_pemeriksaan)),
			'frekuensi_audit' => $frekuensi_audit,
			'level_irtp' => $level_irtp,
			'nip_pengawas' => $nip_pengawas,
			'nama_pengawas' => $nama_pengawas,
			'jabatan_pengawas' => $jabatan_pengawas,
			'nip_anggota_pengawas'=>$nip_anggota_pengawas,
			'nama_anggota_pengawas'=>$nama_anggota_pengawas,
			'nama_observer_pengawas'=>$nama_observer_pengawas,
			'nip_verifikator' => $nip_verifikator,
			'nama_verifikator' => $nama_verifikator
		);
		$insert = $this->db->insert('tabel_periksa_sarana_produksi_record', $data);
		
		//Insert detail pemeriksaan
		if($list_no_ketidaksesuaian!=""){
			$insert_batch_data = array();
			$id_record = $this->db->insert_id();
			foreach($list_no_ketidaksesuaian as $i => $no_ketidaksesuaian) {
				if($list_status_ketidaksesuaian[$i]==1){
					$insert_batch_data[] = array(
						'id_record' => $id_record,
						'id_r_urut_periksa_sarana_produksi' => $id_r_urut_periksa_sarana_produksi,
						'nomor_r_permohonan' => $nomor_permohonan,
						'no_ketidaksesuaian' => $list_no_ketidaksesuaian[$i],
						'nama_ketidaksesuaian' => $list_nama_ketidaksesuaian[$i],
						'level' => $list_level_ketidaksesuaian[$i],
						'tindakan' => $list_tindakan_perbaikan[$i],
						'status' => $list_status_verifikasi[$i],
						'plor' => $list_plor[$i],
						'tanggal_batas_waktu' => $list_tanggal_batas_waktu[$i]
					);
				} else {
					$max_id = $this->db->query("select max(id_record) max_id from tabel_periksa_sarana_produksi_record_detail where nomor_r_permohonan = '".$nomor_permohonan."'")->result_array();
					@$this->db->query("update tabel_periksa_sarana_produksi_record_detail set tindakan = '".$list_tindakan_perbaikan[$i]."' where id_record = '".$max_id[0]['max_id']."' and no_ketidaksesuaian = '". $list_no_ketidaksesuaian[$i]."' and nomor_r_permohonan = '".$nomor_permohonan."'");
					
				}
			}
			if(count($insert_batch_data)>0){
				$insert_batch = $this->db->insert_batch('tabel_periksa_sarana_produksi_record_detail', $insert_batch_data);
			}
			
		}
		
		$this->db->delete('tabel_periksa_sarana_produksi_detail', array('id_r_urut_periksa_sarana_produksi' => $id_r_urut_periksa_sarana_produksi)); 
		
		if($list_no_ketidaksesuaian!=""){
			$insert_batch_data2 = array();
			foreach($list_no_ketidaksesuaian as $i => $no_ketidaksesuaian) {
				if($list_status_ketidaksesuaian[$i]==1){
					$insert_batch_data2[] = array(
						'id_r_urut_periksa_sarana_produksi' => $id_r_urut_periksa_sarana_produksi,
						'nomor_r_permohonan' => $nomor_permohonan,
						'no_ketidaksesuaian' => $list_no_ketidaksesuaian[$i],
						'nama_ketidaksesuaian' => $list_nama_ketidaksesuaian[$i],
						'level' => $list_level_ketidaksesuaian[$i],
						'tindakan' => $list_tindakan_perbaikan[$i],
						'status' => $list_status_verifikasi[$i],
						'plor' => $list_plor[$i],
						'tanggal_batas_waktu' => $list_tanggal_batas_waktu[$i]
					);
				}
			}
			if(count($insert_batch_data2)>0){
				$insert_batch2 = $this->db->insert_batch('tabel_periksa_sarana_produksi_detail', $insert_batch_data2);
			}
		}
		//HEADER
		$data_header = array(
			'nomor_r_permohonan' => $nomor_permohonan,
			'tujuan_pemeriksaan' => $tujuan_pemeriksaan,
			'tanggal_pemeriksaan' => date('Y-m-d', strtotime($tanggal_pemeriksaan)),
			'frekuensi_audit' => $frekuensi_audit,
			'level_irtp' => $level_irtp,
			'nip_pengawas' => $nip_pengawas,
			'nama_pengawas' => $nama_pengawas,
			'jabatan_pengawas' => $jabatan_pengawas,
			'nip_verifikator' => $nip_verifikator,
			'nama_verifikator' => $nama_verifikator
		);
		$this->db->where('id_urut_periksa_sarana_produksi', $id_r_urut_periksa_sarana_produksi);
		$update = $this->db->update('tabel_periksa_sarana_produksi', $data_header);
		
		
		
		if($insert==true and $update==true){
			$this->session->set_flashdata('status', '<div class="alert alert-info"><strong>Selamat!</strong> Data Pemeriksaan Sarana Produksi berhasil disimpan</div>');
		} else {
			$this->session->set_flashdata('status', '<div class="alert alert-danger"><strong>Maaf, </strong> Data Pemeriksaan Sarana Produksi gagal disimpan</div>');
		}
		
		redirect('pemeriksaan_sarana/pemeriksaan_lanjut/');
	}

	public function output_pemeriksaan_history($id_r_urut_periksa_sarana_produksi)
	{
		
		$header = $this->db->query("
			SELECT *
			FROM 
			tabel_periksa_sarana_produksi, 
			tabel_pen_pengajuan_spp,
			tabel_daftar_perusahaan,
			tabel_jenis_pangan,
			tabel_kemasan,
			tabel_kabupaten_kota,
			tabel_propinsi
			WHERE 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
			tabel_periksa_sarana_produksi.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
			tabel_periksa_sarana_produksi.id_urut_periksa_sarana_produksi = '".$id_r_urut_periksa_sarana_produksi."' and 
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
			tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND
			tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan
			order by tanggal_pemeriksaan desc
			")->result_array();
		$data['header'] = $header;
		
		$datas = $this->db->query("
			SELECT *
			FROM 
			tabel_periksa_sarana_produksi_record, 
			tabel_pen_pengajuan_spp,
			tabel_daftar_perusahaan,
			tabel_jenis_pangan,
			tabel_kemasan,
			tabel_kabupaten_kota,
			tabel_propinsi
			WHERE 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
			tabel_periksa_sarana_produksi_record.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
			tabel_periksa_sarana_produksi_record.id_r_urut_periksa_sarana_produksi = '".$id_r_urut_periksa_sarana_produksi."' and 
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
			tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND
			tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan
			order by tanggal_pemeriksaan desc
			")->result();		
		$data['datas'] = $datas;
		return view_dashboard('irtp/pemeriksaan_sarana/output_pemeriksaan_history' ,$data);
		// $this->lib_irtp->display('output_pemeriksaan_history',$data);
	}

	public function form_pemeriksaan_lanjut($id_urut_periksa_sarana_produksi)
	{
		if($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3){
			$provinsi = $this->session->userdata('code');
			
		}
		
		if($this->session->userdata('user_segment')==5){
			
			$kabupaten = $this->session->userdata('code');
			
		} 
		
		if(@$provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if(@$kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		
		$data['no_sert'] = $this->db->query('SELECT distinct nomor_permohonan, nama_perusahaan, nama_pemilik, nama_dagang FROM 
			tabel_pen_pengajuan_spp, 
			tabel_daftar_perusahaan, 
			tabel_ambil_penyuluhan, 
			tabel_kabupaten_kota, 
			tabel_propinsi WHERE 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND 
			tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and 
			tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'')->result();
		
		
		$data['nomor_ketidaksesuaian'] = $this->db->get('tabel_ketidaksesuaian')->result();
		$data['plor'] = $this->db->get('tabel_plor')->result();
		$data['sesuaian'] = $this->db->get('tabel_kriteria_ketidaksesuaian')->result();
		$data['js_kabupaten'] = $this->db->get('tabel_kabupaten_kota')->result();
		$data['js_propinsi'] = $this->db->get('tabel_propinsi')->result();
		$data['js_pengawas'] = $this->db->get('tabel_auditor')->result();
		$data['id_urut_periksa_sarana_produksi'] = $id_urut_periksa_sarana_produksi;
		$data['record'] = $this->db->query("SELECT *
			FROM 
			tabel_periksa_sarana_produksi, 
			tabel_pen_pengajuan_spp,
			tabel_daftar_perusahaan,
			tabel_jenis_pangan,
			tabel_kemasan,
			tabel_kabupaten_kota,
			tabel_propinsi
			WHERE 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and 
			tabel_periksa_sarana_produksi.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and 
			tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi and 
			tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
			tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND
			tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND
			tabel_periksa_sarana_produksi.id_urut_periksa_sarana_produksi = '".$id_urut_periksa_sarana_produksi."'
			ORDER BY id_urut_periksa_sarana_produksi desc")->result_array();
		$data['js_pengawas'] = $this->irtp_model->data_dfi()->result();
		return view_dashboard('irtp/pemeriksaan_sarana/irtp_pemeriksaan_form_lanjut1' ,$data);

		// $this->lib_irtp->display('irtp_pemeriksaan_form_lanjut1',$data);
	}

	public function irtp_pemeriksaan_lanjut2()
	{
		$record = $this->db->query("select * from tabel_periksa_sarana_produksi_detail where id_r_urut_periksa_sarana_produksi = '".$this->input->post('id_r_urut_periksa_sarana_produksi')."'")->result_array();
		//print_r($record); exit;
		$data['id_r_urut_periksa_sarana_produksi'] = $this->input->post('id_r_urut_periksa_sarana_produksi');
		$data['record'] = $record;
		$data['no_ketidaksesuaian'] = $this->input->post('no_ketidaksesuaian');
		
		//Data Hidden
		$data['level_irtp'] = $this->input->post('level_irtp');
		$data['freq_irtp'] = $this->input->post('freq_irtp');
		$data['nomor_permohonan'] = $this->input->post('nomor_permohonan');
		$data['tujuan_pemeriksaan'] = $this->input->post('tujuan_pemeriksaan');
		$data['tanggal_pemeriksaan'] = $this->input->post('tanggal_pemeriksaan');
		$data['jabatan_pengawas'] = "Kepala";
		
		$kode_kepala_pengawas = $this->input->post('nip_pengawas');
		
		// $jabatan_pengawas = $this->input->post('jabatan_pengawas');
		$get_pengawas = $this->irtp_model->data_dfi("and kode_narasumber = '".$kode_kepala_pengawas."'")->result_array();
		$data['nama_pengawas'] = $get_pengawas[0]['nama_narasumber'];
		$data['nip_pengawas'] = $get_pengawas[0]['nip_pkp_dfi'];
		
		$kode_anggota_pengawas = $this->input->post('nip_anggota_pengawas');
		$temp_nip_anggota = array();
		$temp_nama_anggota = array();
		foreach ($kode_anggota_pengawas as $key => $value) {
			$get_pengawas = $this->irtp_model->data_dfi("and kode_narasumber = '".$value."'")->result_array();
			array_push($temp_nip_anggota, $get_pengawas[0]['nip_pkp_dfi']);
			array_push($temp_nama_anggota, $get_pengawas[0]['nama_narasumber']);
		}
		$data['nip_anggota_pengawas'] = implode('|', $temp_nip_anggota);
		$data['nama_anggota_pengawas'] = implode('|', $temp_nama_anggota);
		$data['nama_observer_pengawas'] = implode('|', $this->input->post('nip_observer_pengawas'));
		return view_dashboard('irtp/pemeriksaan_sarana/irtp_pemeriksaan_form_lanjut2' ,$data);
		
		// $this->lib_irtp->display('irtp_pemeriksaan_form_lanjut2',$data);
	}

	public function irtp_pemeriksaan_lanjut3()
	{
		$data['id_r_urut_periksa_sarana_produksi'] = $this->input->post('id_r_urut_periksa_sarana_produksi');
		$data['plor'] = $this->input->post('plor');
		$data['tanggal_batas_waktu'] = $this->input->post('batas_waktu_pemeriksaan');
		$data['status_ketidaksesuaian'] = $this->input->post('status_ketidaksesuaian');
		$data['level'] = $this->input->post('level');
		$data['no_ketidaksesuaian'] = $this->input->post('no_ketidaksesuaian');
		$data['nama_ketidaksesuaian'] = $this->input->post('nama_ketidaksesuaian');
		$data['tindakan_sebelumnya'] = $this->input->post('tindakan_sebelumnya');
		$data['status_sebelumnya'] = $this->input->post('status_sebelumnya');
		$data['js_pengawas'] = $this->irtp_model->data_dfi()->result();
		
		//Data Hidden
		$data['level_irtp'] = $this->input->post('level_irtp');
		$data['freq_irtp'] = $this->input->post('freq_irtp');
		$data['nomor_permohonan'] = $this->input->post('nomor_permohonan');
		$data['tujuan_pemeriksaan'] = $this->input->post('tujuan_pemeriksaan');
		$data['tanggal_pemeriksaan'] = $this->input->post('tanggal_pemeriksaan');
		$data['nip_pengawas'] = $this->input->post('nip_pengawas');
		$data['nama_pengawas'] = $this->input->post('nama_pengawas');
		$data['jabatan_pengawas'] = $this->input->post('jabatan_pengawas');
		$data['nip_anggota_pengawas'] = $this->input->post('nip_anggota_pengawas');
		$data['nama_anggota_pengawas'] = $this->input->post('nama_anggota_pengawas');
		$data['nama_observer_pengawas'] = $this->input->post('nama_observer_pengawas');
		return view_dashboard('irtp/pemeriksaan_sarana/irtp_pemeriksaan_form_lanjut3' ,$data);
		
		// $this->lib_irtp->display('irtp_pemeriksaan_form_lanjut3',$data);
	}

	public function input_form_tidak_baku(){
		$list_level_ketidaksesuaian = $this->input->post('level_ketidaksesuaian');

		//list($level_irtp, $frekuensi_audit) = $this->get_frekuensi_audit($list_level_ketidaksesuaian);
		$level_irtp = $this->input->post('level');
		$frekuensi_audit = $this->input->post('freq');
		$nomor_permohonan = $this->input->post('nomor_permohonan');
		$tujuan_pemeriksaan = $this->input->post('tujuan_pemeriksaan');
		$tanggal_pemeriksaan = $this->input->post('tanggal_pemeriksaan');
		$kode_kepala_pengawas = $this->input->post('nip_pengawas');

		// $jabatan_pengawas = $this->input->post('jabatan_pengawas');
		$get_pengawas = $this->irtp_model->data_dfi("and kode_narasumber = '".$kode_kepala_pengawas."'")->result_array();
		$nama_pengawas = $get_pengawas[0]['nama_narasumber'];
		$nip_pengawas = $get_pengawas[0]['nip_pkp_dfi'];

		$kode_anggota_pengawas = $this->input->post('nip_anggota_pengawas');
		$temp_nip_anggota = array();
		$temp_nama_anggota = array();
		foreach ($kode_anggota_pengawas as $key => $value) {
			$get_pengawas = $this->irtp_model->data_dfi("and kode_narasumber = '".$value."'")->result_array();
			array_push($temp_nip_anggota, $get_pengawas[0]['nip_pkp_dfi']);
			array_push($temp_nama_anggota, $get_pengawas[0]['nama_narasumber']);
		}
		$nip_anggota_pengawas = implode('|', $temp_nip_anggota);
		$nama_anggota_pengawas = implode('|', $temp_nama_anggota);
		$nama_observer_pengawas = implode('|', $this->input->post('nip_observer_pengawas'));


		$kode_verifikator = $this->input->post('nip_verifikator');
		$get_verifikator = $this->irtp_model->data_dfi("and kode_narasumber = '".$kode_verifikator."'")->result_array();
		if(count($get_verifikator>0)){
			$nama_verifikator = $get_verifikator[0]['nama_narasumber'];
			$nip_verifikator = $get_verifikator[0]['nip_pkp_dfi'];
		} else {
			$nama_verifikator = "";
			$nip_verifikator = "";
		}

		$list_no_ketidaksesuaian = $this->input->post('no_ketidaksesuaian');
		$list_nama_ketidaksesuaian = $this->input->post('nama_ketidaksesuaian');
		$list_tindakan_perbaikan = $this->input->post('tindakan_perbaikan');
		$list_status_verifikasi = $this->input->post('status_verifikasi');
		$list_plor = $this->input->post('plor');
		$list_tanggal_batas_waktu = $this->input->post('tanggal_batas_waktu');


		$penyuluhan = $this->input->post('penyuluhan');
		$alasan_bp = $this->input->post('alasan_bp');
		$form = $this->input->post('form');
		$alasan_tb = $this->input->post('alasan_tb');
		
		$config['upload_path']          = './dok_pemeriksaan/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 13000;

		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('dokumen')){
			$this->session->set_flashdata('errors',  $this->upload->display_errors());
			redirect('pemeriksaan_sarana/input');

		}else{
			$this->upload->data();
			$dokumen = str_replace(" ","_",$_FILES['dokumen']['name']);
		//Insert header pemeriksaan
			$data = array(
				'nomor_r_permohonan' => $nomor_permohonan,
				'tujuan_pemeriksaan' => $tujuan_pemeriksaan,
				'tanggal_pemeriksaan' => date('Y-m-d', strtotime($tanggal_pemeriksaan)),
				'frekuensi_audit' => $frekuensi_audit,
				'level_irtp' => $level_irtp,
				'nip_pengawas' => $nip_pengawas,
				'nama_pengawas' => $nama_pengawas,
				'jabatan_pengawas' => 'Kepala',
				'nip_anggota_pengawas'=>$nip_anggota_pengawas,
				'nama_anggota_pengawas'=>$nama_anggota_pengawas,
				'nama_observer_pengawas'=>$nama_observer_pengawas,
				'nip_verifikator' => $nip_verifikator,
				'nama_verifikator' => $nama_verifikator,
				'penyuluhan' => $penyuluhan,
				'alasan_bp' => $alasan_bp,
				'form' => $form,
				'alasan_tb' => $alasan_tb,
				'dokumen' => $dokumen
			);
			$insert = $this->db->insert('tabel_periksa_sarana_produksi', $data);
		}
		if($insert==true){
			$this->session->set_flashdata('status', '<div class="alert alert-info"><strong>Selamat!</strong> Data Pemeriksaan Sarana Produksi berhasil disimpan</div>');
		} else {
			$this->session->set_flashdata('status', '<div class="alert alert-danger"><strong>Maaf, </strong> Data Pemeriksaan Sarana Produksi gagal disimpan</div>');
		}
		redirect('pemeriksaan_sarana/output_pemeriksaan');
	}
}