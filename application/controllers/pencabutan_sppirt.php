<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pencabutan_sppirt extends APP_Controller {

public function __construct()
	{
		parent::__construct();
		//load library dan helper yang dibutuhkan
		// $this->load->library('lib_irtp');
		$this->load->helper('url');
		$this->load->model('pencabutan_sppirt_model','',TRUE);
		$this->load->model('irtp_model','',TRUE);
		$this->load->model('menu_model');	
		//$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
		return view_dashboard('irtp/pencabutan_sppirt/input_pencabutan_sppirt');
	}
	
	public function get_perusahaan_by_pirt()
	{
		$nomor = $this->input->post('nomor');

		$data = $this->db->query('SELECT * FROM tabel_penerbitan_sert_pirt WHERE id_urut_penerbitan_sert = "'.$nomor.'" ');
		//echo $nomor;
		//print_r($data);
		if($data->num_rows == 1){
			foreach($data->result() as $data){ 
				$nomor_permohonan = $data->nomor_r_permohonan;
			}
		}else{
			$data->num_rows;
		}

		$data = $this->db->query('SELECT * FROM tabel_penerbitan_sert_pirt, tabel_pen_pengajuan_spp, tabel_daftar_perusahaan
								WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
								AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan
								AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = "'.$nomor_permohonan.'" ');
		
		echo json_encode($data->result());
	}
	
	public function output_pencabutan()
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
		//$datas = $this->db->get('tabel_pencabutan_pirt')->result();
		$datas = $this->irtp_model->pencabutan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();
		$data['datas'] = $this->irtp_model->pencabutan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();
		
		
		//generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		
		//head
		$tmpl = array ( 'table_open'  => '<table class="table table-bordered" id="data_table">' );
		$this->table->set_template($tmpl);
		
		$this->table->set_heading(
			'No.',
			'Nomor PIRT',
			'Nama IRTP',
			'Jenis Pangan',
			'Pemilik Perusahaan',
			'Penanggung Jawab Perusahaan',
			'Provinsi',
			'Kabupaten/Kota',
			'Alasan Pencabutan',
			'Tanggal Pencabutan',
			'Scan Berita Acara Pencabutan'
		);
		
		//isi
		$i = 0;
		foreach($datas as $field){
			$label_type = strtolower(pathinfo($field->path_scan_pencabutan, PATHINFO_EXTENSION));
			$label_url = base_url('uploads/pencabutan/'.$field->path_scan_pencabutan);
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

			if($field->kode_alasan_pencabutan!=0){
				$alasan_pencabutan = $field->alasan_pencabutan;
			} else {
				$alasan_pencabutan = $field->alasan_pencabutan_lain;
			}
			$this->table->add_row(++$i,
			$field->nomor_pirt,
			$field->nama_perusahaan,
			$field->jenis_pangan,
			$field->nama_pemilik,
			$field->nama_penanggung_jawab,
			$field->nama_propinsi,
			$field->nm_kabupaten,
			$alasan_pencabutan,

			date('d-m-Y', strtotime($field->tanggal_pencabutan)),
			$label
			#anchor('pb2kp/permohonan_output_detail/'.$field->nomor_pirt,'Lihat Detail')
			);
		}
		
		$data['table'] = $this->table->generate();
		
		$data['jumlah_pencabutan_irtp'] = $i;
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
				$data['jml_alasan'] = $this->db->query("SELECT count(alasan_pencabutan) as count_alasan, alasan_pencabutan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt, tabel_alasan_pencabutan, tabel_pen_pengajuan_spp, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and tanggal_pencabutan>='".$tanggal_awal."' and tanggal_pencabutan<='".$tanggal_akhir."' GROUP BY alasan_pencabutan")->result();
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt,tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten and tanggal_pencabutan>='".$tanggal_awal."' and tanggal_pencabutan<='".$tanggal_akhir."' GROUP BY jenis_pangan")->result();
			} else {
				$data['jml_alasan'] = $this->db->query("SELECT count(alasan_pencabutan) as count_alasan, alasan_pencabutan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt, tabel_alasan_pencabutan, tabel_pen_pengajuan_spp, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten GROUP BY alasan_pencabutan")->result();
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt,tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten GROUP BY jenis_pangan")->result();
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
				$data['jml_alasan'] = $this->db->query("SELECT count(alasan_pencabutan) as count_alasan, alasan_pencabutan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt, tabel_alasan_pencabutan, tabel_pen_pengajuan_spp, tabel_daftar_perusahaan WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pencabutan>='".$tanggal_awal."' and tanggal_pencabutan<='".$tanggal_akhir."' GROUP BY alasan_pencabutan")->result();
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt,tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pencabutan>='".$tanggal_awal."' and tanggal_pencabutan<='".$tanggal_akhir."' GROUP BY jenis_pangan")->result();
			} else {
				$data['jml_alasan'] = $this->db->query("SELECT count(alasan_pencabutan) as count_alasan, alasan_pencabutan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt, tabel_alasan_pencabutan, tabel_pen_pengajuan_spp, tabel_daftar_perusahaan WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert AND tabel_pencabutan_pirt.kode_alasan_pencabutan = tabel_alasan_pencabutan.kode_alasan_pencabutan AND tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan GROUP BY alasan_pencabutan")->result();
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pencabutan_pirt, tabel_penerbitan_sert_pirt,tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan WHERE tabel_pencabutan_pirt.id_r_urut_penerbitan_sert_pirt = tabel_penerbitan_sert_pirt.id_urut_penerbitan_sert and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan GROUP BY jenis_pangan")->result();
			}	
		}
		
		if(count(@$prov)>0){
			$data['data_select'] = @$prov;
		} else {
			$data['data_select'] = $this->db->get('tabel_propinsi')->result();
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
		return view_dashboard('irtp/pencabutan_sppirt/output_pencabutan', $data);
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
		
		$data['irtp_lama'] = $this->db->query('SELECT * FROM 
		tabel_pen_pengajuan_spp, 
		tabel_daftar_perusahaan, 
		tabel_penerbitan_sert_pirt, 
		tabel_propinsi, 
		tabel_kabupaten_kota
		WHERE 
		tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND 
		tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND
		tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
		and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.'
		ORDER BY nama_perusahaan ASC')->result();
		
		
		$data['js_pencabutan'] = $this->db->get('tabel_alasan_pencabutan')->result();
		return view_dashboard('irtp/pencabutan_sppirt/input_pencabutan_sppirt', $data);
	}
	
	function cetak()
	{
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()->setCreator('SPPIRT')
                 ->setLastModifiedBy('SPPIRT')
                 ->setTitle("Laporan Data Pencabutan SPP-IRT")
                 ->setSubject("Pengajuan SPPIRT")
                 ->setDescription("Laporan Data Pencabutan SPP-IRT")
                 ->setKeywords("Data Pencabutan");

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
	    $excel->setActiveSheetIndex(0)->setCellValue('A1', "LAPORAN DATA PENCABUTAN SPP-IRT"); // Set kolom A1 dengan tulisan "DATA SISWA"
	    $excel->getActiveSheet()->mergeCells('A1:J1'); // Set Merge Cell pada kolom A1 sampai E1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

	    // Buat header tabel nya pada baris ke 3
	    $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO IRTP"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA IRTP"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('D3', "NAMA JENIS PANGAN"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA PEMILIK PERUSAHAAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('F3', "NAMA PENANGGUNG JAWAB PERUSAHAAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('G3', "PROVINSI");
	    $excel->setActiveSheetIndex(0)->setCellValue('H3', "KABUPATEN/KOTA");
	    $excel->setActiveSheetIndex(0)->setCellValue('I3', "ALASAN PENCABUTAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('J3', "TANGGAL PENCABUTAN"); 

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
		//$datas = $this->db->get('tabel_pencabutan_pirt')->result();
		$datas = $this->irtp_model->pencabutan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();		
		$data['datas'] = $datas;
		
	    $no = 1;
	    $numrow = 4;
	    foreach ($datas as $field)
	    {
								$label_type = strtolower(pathinfo($field->path_scan_pencabutan, PATHINFO_EXTENSION));
							$label_url = base_url('uploads/pencabutan/'.$field->path_scan_pencabutan);
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

							if($field->kode_alasan_pencabutan!=0){
								$alasan_pencabutan = $field->alasan_pencabutan;
							} else {
								$alasan_pencabutan = $field->alasan_pencabutan_lain;
							}
		// isi excel
	   $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
       $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $field->nomor_pirt);
       $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $field->nama_perusahaan);
       $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $field->jenis_pangan);
       $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $field->nama_pemilik);
       $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $field->nama_penanggung_jawab);
       $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $field->nama_propinsi);
       $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $field->nm_kabupaten);
       $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $$alasan_pencabutan);
       $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $field->tanggal_pencabutan);

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

       $no++;
       $numrow++;
		}

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
	    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30); // Set width kolom B
	    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
	    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
	    $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30); // Set width kolom E
	    $excel->getActiveSheet()->getColumnDimension('F')->setWidth(30); // Set width kolom E
	    $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30); // Set width kolom E
	    $excel->getActiveSheet()->getColumnDimension('H')->setWidth(30); // Set width kolom E
	    $excel->getActiveSheet()->getColumnDimension('I')->setWidth(30); // Set width kolom E
	    $excel->getActiveSheet()->getColumnDimension('J')->setWidth(30); // Set width kolom E
	    
	    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	    $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
	    // Set orientasi kertas jadi LANDSCAPE
	    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	    // Set judul file excel nya
	    $excel->getActiveSheet(0)->setTitle("LAPORAN DATA");
	    $excel->setActiveSheetIndex(0);
	    // Proses file excel
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="LAPORAN DATA PENCABUTAN SPP-IRT.xlsx"'); // Set nama file excel nya
	    header('Cache-Control: max-age=0');
	    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	    $write->save('php://output');
	}
	
	public function add(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$config = array(
			array(
				'field' => 'nomor_pirt',
				'label'	=> 'Nomor PIRT', 
                'rules'	=> 'trim|required|xss_clean'
			),
			array(
				'field' => 'nomor_berita_acara',
				'label'	=> 'Nomor Berita Acara', 
                'rules'	=> 'trim|required|xss_clean'
			),
			array(
				'field' => 'alasan_pencabutan',
				'label'	=> 'Alasan Pencabutan', 
                'rules'	=> 'required|xss_clean'
			)
			
		);

		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-danger">'.validation_errors().'</div>');
			redirect('pencabutan_sppirt/input');
		}
		else
		{
			$config['upload_path'] = './uploads/pencabutan/';
			$config['allowed_types'] = 'jpg|png|pdf|jpeg';
			$config['max_size']  = '200000';
			$config['overwrite'] = TRUE;
			$dataUp = "file_foto";
	
			$this->load->library('upload');
			$this->upload->initialize($config);
			
			if ( ! $this->upload->do_upload($dataUp))
			{
				//$error = $this->upload->display_errors("<div class='alert alert-danger'><strong>Perhatian! </strong>Gagal upload Rancangan Label. ", "</div>");
				$error = "<div class='alert alert-danger'><strong>Perhatian! </strong>Gagal upload Scan Berita Acara Pencabutan. Format file salah atau file berukuran lebih dari 2MB</div>";
				$this->session->set_flashdata('status', $error);
				redirect('pencabutan_sppirt/input');
			}
			else
			{
				if($this->irtp_model->add_data_irtp_pencabutan()){
					$this->session->set_flashdata('message', '<div class="alert alert-info"><strong>Selamat! </strong>Data PIRT sudah tercabut</div>');
				
					redirect('pencabutan_sppirt/output_pencabutan');
				} // ambil dari file models/irtp_model.php
			}
		}
		
		
		}

		function delete()
	    {
		    $param=array("id_urut_pencabutan_pirt"=>$this->uri->segment(3));
    		$this->irtp_model->delete_pencabutan($param);
		    echo "
		    <script>
		    alert('Pencabutan Berhasil di Hapus.');
		    window.location.href='".base_url()."pencabutan_sppirt/output_pencabutan';
		    </script>";
	    }

	    function edit()
	    {
	    	return view_dashboard('irtp/pencabutan_sppirt/edit');
	    }
	    
	
}