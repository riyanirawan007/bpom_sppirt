<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pelaksanaan_pkp extends APP_Controller {

public function __construct()
	{
		parent::__construct();
		//load library dan helper yang dibutuhkan
		// $this->load->library('lib_irtp');
		$this->load->helper('url', 'form');
		$this->load->library('form_validation');
		$this->load->model('pelaksanaan_pkp_model','',TRUE);
		$this->load->model('irtp_model','',TRUE);
		$this->load->model('auth','',TRUE);
	}

	public function index()
	{
		return view_dashboard('irtp/pelaksanaan_pkp/input_pkp');
	}
	
	public function output_penyelenggaraan()
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
		$datas = $this->irtp_model->penyelenggaraan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();	
		$data['datas'] = $this->irtp_model->penyelenggaraan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();
		
		//generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		
		//head
		$tmpl = array ( 'table_open'  => '<table class="table table-bordered" id="data_table">' );
		$this->table->set_template($tmpl);
		
		$this->table->set_heading(
			'No.',
			'No. Permohonan Penyuluhan',
			'Tanggal Awal Penyuluhan',
			'Tanggal Akhir Penyuluhan',
			'Provinsi',
			'Kabupaten/Kota',
			#'Nama Narasumber PKP',
			'Nama Narasumber Non PKP',
			'Narasumber Berdasarkan Materi Penyuluhan',
			'Materi Penyuluhan Tambahan',
			'Materi Penyuluhan Lainnya',
			'Daftar Peserta',
			'Laporan Penyelenggaraan'
		);
		
		//isi
		$i = 0;
		foreach($datas as $field){
		
			$get_materi = $this->db->query("select * from tabel_ambil_materi_penyuluhan ambil
			left join tabel_materi_penyuluhan materi on ambil.kode_r_materi_penyuluhan = materi.kode_materi_penyuluhan
			left join tabel_narasumber nara on ambil.kode_r_narasumber = nara.kode_narasumber
			where ambil.nomor_r_permohonan_penyuluhan = '".$field->nomor_permohonan_penyuluhan."'")->result();
			
			/* $no_materi = 0;
			$materi_penyuluhan = "";
			 */
			$arr_materi = array();
			foreach($get_materi as $row){
				//$no_materi++;
				$arr_materi[] = $row->kode_r_materi_penyuluhan;
				//$materi_penyuluhan .= $no_materi.". ".$row->nama_materi_penyuluhan." oleh : ".$row->nama_narasumber."<br>";
				
			}
			$materi_penyuluhan = implode(',', $arr_materi);
			
			
			$xplod_tambahan = explode(",",$field->materi_tambahan);
			$no_materi = 0;
			$materi_tambahan = "";
			foreach($xplod_tambahan as $row_tambahan){
				$get_materi = $this->db->query("select * from tabel_materi_penyuluhan
				where kode_materi_penyuluhan = '".$row_tambahan."'")->result();
				
				foreach($get_materi as $row){
					$no_materi++;
					$materi_tambahan .= $no_materi.". ".$row->nama_materi_penyuluhan."<br>";
				}
			}
			
			$this->table->add_row(++$i,
			$field->nomor_permohonan_penyuluhan,
			date('d-m-Y', strtotime($field->tanggal_pelatihan_awal)),
			date('d-m-Y', strtotime($field->tanggal_pelatihan_akhir)),
			$field->nama_propinsi,
			$field->nm_kabupaten,
			$field->nama_narasumber_non_pkp,
			$materi_penyuluhan,
			$materi_tambahan,
			$field->materi_pelatihan_lainnya,
			anchor('irtp/output_laporan_daftar_peserta/'.$field->nomor_permohonan_penyuluhan, 'Daftar Peserta', array('class' => 'btn btn-info', 'target' => '_blank')),
			anchor('irtp/output_laporan_penyelenggaraan_unduh/'.$field->nomor_permohonan_penyuluhan, 'Laporan', array('class' => 'btn btn-info col-md-12', 'target' => '_blank'))
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
			
			$data['jumlah_industri_pangan'] = $this->db->query("SELECT * FROM tabel_penyelenggara_penyuluhan, tabel_daftar_perusahaan, tabel_pen_pengajuan_spp, tabel_ambil_penyuluhan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan AND tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten")->num_rows();
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
			
			$data['jumlah_industri_pangan'] = $this->db->query("SELECT (SELECT COUNT(tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan) FROM tabel_ambil_penyuluhan WHERE tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan=a.nomor_permohonan_penyuluhan) as banyak_peserta, a.*,b.*,c.*
		FROM tabel_penyelenggara_penyuluhan a
		INNER JOIN tabel_kabupaten_kota b ON a.id_r_urut_kabupaten=b.id_urut_kabupaten
		INNER JOIN tabel_propinsi c ON c.no_kode_propinsi=b.no_kode_propinsi")->num_rows();
		}
		
		//download
		if($provinsi!='' or $kabupaten!=''){
			if($provinsi==""){ $data['propinsi'] = 0; } else { $data['propinsi'] = $provinsi; }
			
			$data['kabupaten'] = $kabupaten;
		} else {
			$data['propinsi'] = 0;
			$data['kabupaten'] = 0;
		}
		
		if(count(@$prov)>0){
			$data['data_select'] = @$prov;
		} else {
			$data['data_select'] = $this->db->get('tabel_propinsi')->result();
		}
		$data['tanggal_awal'] = $tanggal_awal;
		$data['tanggal_akhir'] = $tanggal_akhir;
		
		$data['jumlah_peserta_pelatihan'] = $i;
		
		//$this->lib_irtp->display('output_peserta',$data);
		return view_dashboard('irtp/pelaksanaan_pkp/output_penyelenggaraan', $data);
	}

	public function output_laporan_daftar_peserta($nomor_permohonan_penyuluhan){
		$query =  $this->db->query("SELECT * FROM 
		tabel_penyelenggara_penyuluhan, 
		tabel_daftar_perusahaan, 
		tabel_pen_pengajuan_spp, 
		tabel_ambil_penyuluhan,
		tabel_propinsi, tabel_kabupaten_kota WHERE 
		tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan and
		tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND
		tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan
		and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi and 
		tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and 
		tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
		and tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = '".$nomor_permohonan_penyuluhan."'
		");
		//print_r($query->result()); exit;
		
		$data['cek_penerbitan'] = $this->db->query("select * from tabel_penyelenggara_penyuluhan 
							join tabel_kabupaten_kota on id_urut_kabupaten = id_r_urut_kabupaten 
							join tabel_propinsi on tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi 
							where nomor_permohonan_penyuluhan = '".$nomor_permohonan_penyuluhan."' limit 1")->result_array();

		//if($query->num_rows >= 1){
			$data['query'] = $query->result();
			//return view_dashboard('irtp/pelaksanaan_pkp/print_5', $data);
			$this->load->view('irtp/pelaksanaan_pkp/print_5', $data);
		//}
	}

	public function output_laporan_penyelenggaraan_unduh($nomor_permohonan_penyuluhan){
		/* $query =  $this->db->query("SELECT * FROM 
		tabel_penyelenggara_penyuluhan, 
		tabel_daftar_perusahaan, 
		tabel_pen_pengajuan_spp, 
		tabel_ambil_penyuluhan, 
		tabel_kabupaten_kota, 
		tabel_propinsi WHERE 
		nomor_permohonan = nomor_r_permohonan and 
		tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = tabel_ambil_penyuluhan.nomor_r_permohonan_penyuluhan AND 
		tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan AND 
		tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten AND 
		tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten AND 
		tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi AND 
		tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = '".$nomor_permohonan_penyuluhan."' and 
		tabel_ambil_penyuluhan.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan"); */
		
		$query =  $this->db->query("SELECT * FROM 
		tabel_penyelenggara_penyuluhan, 
		tabel_daftar_perusahaan, 
		tabel_pen_pengajuan_spp, 
		tabel_kabupaten_kota, 
		tabel_propinsi WHERE 
		tabel_daftar_perusahaan.kode_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan AND 
		tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten AND 
		tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten AND 
		tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi AND 
		tabel_penyelenggara_penyuluhan.nomor_permohonan_penyuluhan = '".$nomor_permohonan_penyuluhan."'
		");
		foreach($query->result() as $row){
			$data['jumlah_peserta'] = $this->db->query("select * from tabel_ambil_penyuluhan where nomor_r_permohonan_penyuluhan = '".$row->nomor_permohonan_penyuluhan."'")->num_rows();
		}
		
		$data['cek_penerbitan'] = $this->db->query("select * from tabel_penyelenggara_penyuluhan 
							join tabel_kabupaten_kota on id_urut_kabupaten = id_r_urut_kabupaten 
							join tabel_propinsi on tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi 
							where nomor_permohonan_penyuluhan = '".$nomor_permohonan_penyuluhan."' limit 1")->result_array();
		
		//if($query->num_rows >= 1){
			$data['query'] = $query->result();
			//return view_dashboard('irtp/pelaksanaan_pkp/print_4', $data);
			$this->load->view('irtp/pelaksanaan_pkp/print_4', $data);
		//}
	}

	public function input()
	{
		// data peserta pkp
		if($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3){
			$provinsi = $this->session->userdata('code');
		}
		
		if($this->session->userdata('user_segment')==5){
			$kabupaten = $this->session->userdata('code');
		} 
		if(@$provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
		if(@$kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
		
		$data['js_materi'] = $this->db->get('tabel_materi_penyuluhan')->result();
		$data['js_materi_utama'] = $this->db->query('SELECT * FROM tabel_materi_penyuluhan WHERE status_materi="utama"')->result();
		$data['js_materi_pendukung'] = $this->db->query('SELECT * FROM tabel_materi_penyuluhan WHERE status_materi="pendukung"')->result();
		$data['js_narasumber'] = $this->irtp_model->data_pkp()->result();
		$data['jumlah_peserta'] = $this->input->post('jumlah_peserta');
		$data['provinsi'] = $this->auth->get_provinsi();
	  	$data['kota'] = $this->auth->get_kota();
		
		//$data['no_penyuluhan'] = $this->db->get('tabel_penyelenggara_penyuluhan')->result();
		$data['no_penyuluhan'] = $this->db->query('SELECT * from tabel_penyelenggara_penyuluhan, tabel_kabupaten_kota, tabel_propinsi where tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'')->result();
		
		$data['no_irtp'] = $this->db->query('SELECT * FROM tabel_pen_pengajuan_spp, tabel_daftar_perusahaan, tabel_kabupaten_kota, tabel_propinsi WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'')->result();

		return view_dashboard('irtp/pelaksanaan_pkp/input_pkp', $data);
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
	}

	public function jumlah_peserta() {
		return view_dashboard('irtp/pelaksanaan_pkp/jumlah_peserta');

	}

	public function add_irtp_peserta(){
		// if($this->irtp_model->add_irtp_peserta()){
		// 	$this->session->set_flashdata('status', "<div class='alert alert-info'><strong>Info! </strong>Data IRTP peserta telah berhasil dimasukkan</div>");
		// }	
		// $nomor_penyuluhan = $this->input->post('nomor_permohonan_penyuluhan');
		$query = $this->db->query("SELECT MAX(nomor_permohonan_penyuluhan) AS last_id FROM tabel_penyelenggara_penyuluhan")->row();
		$nomor_penyuluhan = $query->last_id;
		$nomor_irtp = $this->input->post('nomor_permohonan_irtp');
		$nama_peserta = $this->input->post('nama_peserta');
		$status_peserta = $this->input->post('status_peserta');
		$no_sert_pangan = $this->input->post('no_sert_pangan');
		$pre_test = $this->input->post('nilai_pre_test');
		$post_test = $this->input->post('nilai_post_test');	
		$jumlah_peserta = $this->input->post('jumlah_peserta');
		
		$data = array();
		for ($i=1; $i <= $jumlah_peserta; $i++) { 
			if($post_test[$i] > 60){
				$status = 1; # Lulus
			}else{
				$status = 0; # Tidak Lulus
			}
			$data[$i] = array(
				'nomor_r_permohonan_penyuluhan' => $nomor_penyuluhan,
				'nomor_r_permohonan' => $nomor_irtp[$i],
				'nilai_post_test' => $post_test[$i],
				'nilai_pre_test' => $pre_test[$i],
				'no_sert_pangan' => $no_sert_pangan[$i],
				'nama_peserta' => $nama_peserta[$i],
				'status_peserta' => $status_peserta[$i],
				'status_uji' => $status
			);
			
		}
		$set = $this->db->insert_batch('tabel_ambil_penyuluhan', $data);
		if($set){
			return 1;
		}
		return 0;
		// redirect('pelaksanaan_pkp/output_penyelenggaraan');
	}
	
	public function proccess_edit()
	{
		// echo json_encode(array('success'=>true
		// 	,'id'=>$id,'id_urut'=>$id_urut
		// 	,'id_materi'=>$id_materi
		// 	,'kd'=>$kd_narsum
		// 	, 'peserta'=>$data)
		// );
		
		if($this->irtp_model->edit_data_pkp())
		{
			echo json_encode(array('success'=>true));
		}
		else
		{
			echo json_encode(array('success'=>false));
		}

	}

	public function add()
	{
		$config = array(
			array(
				'field' => 'tanggal_pelatihan_awal',
				'label'   => 'Tanggal Pelatihan Awal', 
                'rules'   => 'trim|required|xss_clean'
			),			
			array(
				'field' => 'tanggal_pelatihan_akhir',
				'label'   => 'Tanggal Pelatihan Akhir', 
                'rules'   => 'trim|required|xss_clean'
			),			
		);
		$this->form_validation->set_rules($config);
		
		if($this->irtp_model->add_data_pkp()){
			//$data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Informasi penyuluhan terbaru telah dimasukkan</div>');
			echo json_encode(array('success'=>true));
		}
		else
		{
			echo json_encode(array('success'=>false));
		}
		

// 		if ($this->form_validation->run() == FALSE)
// 		{
// 			$data['error'] = $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Warning!</strong> '.validation_errors().'</div>');
// 			$data['status'] = $this->session->set_flashdata('<div class="alert alert-warning">Data tidak tersubmit karena terjadi kesalahan. Harap ulangi</div>');
// 			redirect('pelaksanaan_pkp/input_pkp');
// 		}
// 		else
// 		{
// 			if($this->irtp_model->add_data_irtp_penyelenggaraan()){
// 				$data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Informasi penyuluhan terbaru telah dimasukkan</div>');
// 				redirect('pelaksanaan_pkp/output_penyelenggaraan');
// 			} // ambil dari file models/irtp_model.php
			
// 		}
		$this->add_irtp_peserta();
	}
	
	function cetak()
	{
	    include APPPATH.'third_party/PHPExcel/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()->setCreator('SPPIRT')
                 ->setLastModifiedBy('SPPIRT')
                 ->setTitle("Laporan Data Peelaksanaan PKP")
                 ->setSubject("Data Peelaksanaan PKP")
                 ->setDescription("LaporanData Peelaksanaan PKP")
                 ->setKeywords("Data Peelaksanaan PKP");

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
	    $excel->setActiveSheetIndex(0)->setCellValue('A1', "LAPORAN DATA PELAKSANAAN PKP"); // Set kolom A1 dengan tulisan "DATA SISWA"
	    $excel->getActiveSheet()->mergeCells('A1:I1'); // Set Merge Cell pada kolom A1 sampai E1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

	    // Buat header tabel nya pada baris ke 3
	    $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO. PERMOHONAN PENYULUHAN"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('C3', "TANGGAL AWAL PENYULUHAN"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('D3', "TANGGAL AKHIR PENYULUHAN"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('E3', "PROVINSI");
	    $excel->setActiveSheetIndex(0)->setCellValue('F3', "KABUPATEN/KOTA");
	    $excel->setActiveSheetIndex(0)->setCellValue('G3', "NARASUMBER BERDASARKAN MATERI PENYULUHAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('H3', "MATERI PENYULUHAN TAMBAHAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('I3', "MATERI PENYULUHAN LAINNYA"); 

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
		$datas = $this->irtp_model->penyelenggaraan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();	
		$data['datas'] = $this->irtp_model->penyelenggaraan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();
	    
	    $no = 1;
	    $numrow = 4;
	    
	    foreach ($datas as $field) {
							$get_materi = $this->db->query("select * from tabel_ambil_materi_penyuluhan ambil
							left join tabel_materi_penyuluhan materi on ambil.kode_r_materi_penyuluhan = materi.kode_materi_penyuluhan
							left join tabel_narasumber nara on ambil.kode_r_narasumber = nara.kode_narasumber
							where ambil.nomor_r_permohonan_penyuluhan = '".$field->nomor_permohonan_penyuluhan."'")->result();
							
							/* $no_materi = 0;
							$materi_penyuluhan = "";
							 */
							$arr_materi = array();
							foreach($get_materi as $row){
								//$no_materi++;
								$arr_materi[] = $row->kode_r_materi_penyuluhan;
								//$materi_penyuluhan .= $no_materi.". ".$row->nama_materi_penyuluhan." oleh : ".$row->nama_narasumber."<br>";
								
							}
							$materi_penyuluhan = implode(',', $arr_materi);
							
							
							$xplod_tambahan = explode(",",$field->materi_tambahan);
							$no_materi = 0;
							$materi_tambahan = "";
							foreach($xplod_tambahan as $row_tambahan)
							{
								$get_materi = $this->db->query("select * from tabel_materi_penyuluhan
								where kode_materi_penyuluhan = '".$row_tambahan."'")->result();
								
								foreach($get_materi as $row)
								{
									$no_materi++;
									$materi_tambahan .= $no_materi.". ".$row->nama_materi_penyuluhan."<br>";
								}
							}
							// isi excel
	   $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
       $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $field->nomor_permohonan_penyuluhan);
       $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $field->tanggal_pelatihan_awal);
       $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $field->tanggal_pelatihan_akhir);
       $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $field->nama_propinsi);
       $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $field->nm_kabupaten);
       $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $materi_penyuluhan);
       $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $materi_tambahan);
       $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $field->materi_pelatihan_lainnya);

       $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
       $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);

       $no++;
       $numrow++;
		} // end foreach
		
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
	    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30); // Set width kolom B
	    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
	    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
	    $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30); // Set width kolom E
	    $excel->getActiveSheet()->getColumnDimension('F')->setWidth(30); // Set width kolom E
	    $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30); // Set width kolom E
	    $excel->getActiveSheet()->getColumnDimension('H')->setWidth(30); // Set width kolom E
	    $excel->getActiveSheet()->getColumnDimension('I')->setWidth(30); // Set width kolom E
	    
	    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	    $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
	    // Set orientasi kertas jadi LANDSCAPE
	    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	    // Set judul file excel nya
	    $excel->getActiveSheet(0)->setTitle("LAPORAN DATA");
	    $excel->setActiveSheetIndex(0);
	    // Proses file excel
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="LAPORAN DATA PELAKSANAAN PKP.xlsx"'); // Set nama file excel nya
	    header('Cache-Control: max-age=0');
	    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	    $write->save('php://output');
	}

	function delete()
	    {
		    $param=array("nomor_permohonan_penyuluhan"=>$this->uri->segment(3));
    		$this->irtp_model->delete_pelaksanaan_pkp($param);
		    echo "
		    <script>
		    alert('PKP Berhasil di Hapus.');
		    window.location.href='".base_url()."pelaksanaan_pkp/output_penyelenggaraan';
		    </script>";
	    }

	function edit()
	{
		    $data['js_materi'] = $this->db->get('tabel_materi_penyuluhan')->result();
			$data['js_materi_utama'] = $this->db->query('SELECT * FROM tabel_materi_penyuluhan WHERE status_materi="utama"')->result();
			$data['js_materi_pendukung'] = $this->db->query('SELECT * FROM tabel_materi_penyuluhan WHERE status_materi="pendukung"')->result();
			$data['js_narasumber'] = $this->irtp_model->data_pkp()->result();
			$data['jumlah_peserta'] = $this->input->post('jumlah_peserta');
			$data['provinsi'] = $this->auth->get_provinsi();
	  		$data['kota'] = $this->auth->get_kota();
			
			// data peserta pkp
			if($this->session->userdata('user_segment')==4 or $this->session->userdata('user_segment')==3){
				$provinsi = $this->session->userdata('code');
				
			}
			
			if($this->session->userdata('user_segment')==5){
				
				$kabupaten = $this->session->userdata('code');
				
			} 
			if(@$provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
			if(@$kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
			
			
			//$data['no_penyuluhan'] = $this->db->get('tabel_penyelenggara_penyuluhan')->result();
			$data['no_penyuluhan'] = $this->db->query('SELECT * from tabel_penyelenggara_penyuluhan, tabel_kabupaten_kota, tabel_propinsi where tabel_penyelenggara_penyuluhan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'')->result();
			
			$data['no_irtp'] = $this->db->query('SELECT * FROM tabel_pen_pengajuan_spp, tabel_daftar_perusahaan, tabel_kabupaten_kota, tabel_propinsi WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'')->result();
			$data['nomor'] = $this->uri->segment('3');
		   return view_dashboard('irtp/pelaksanaan_pkp/edit', $data);
	}
}