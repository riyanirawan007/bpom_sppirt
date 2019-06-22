<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perpanjangan_sertifikat extends APP_Controller {

public function __construct()
	{
		parent::__construct();
		//load library dan helper yang dibutuhkan
		// $this->load->library('lib_irtp');
		$this->load->helper('url');
		$this->load->model('perpanjangan_sertifikat_model','',TRUE);
		$this->load->model('irtp_model','',TRUE);
		$this->load->model('menu_model');	
		//$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
		return view_dashboard('irtp/perpanjangan_sertifikat/input_perpanjangan_sertifikat');
	}
    
    public function output_perpanjangan()
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
		//$datas = $this->db->get('tabel_perpanjangan_sert_pirt')->result();
		$datas = $this->irtp_model->perpanjangan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();		
		$data['datas'] = $datas;
		//generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		
		//head
		$tmpl = array ( 'table_open'  => '<table class="table table-bordered" id="data_table">' );
		$this->table->set_template($tmpl);
		
		$this->table->set_heading(
			'No.',
			'Nomor PIRT Lama',
			'Nomor PIRT Baru',
			'Nama IRTP',
			'Nama Jenis Pangan',
			'Nama Produk Pangan',
			'Nama Dagang',
			'Tanggal Perpanjangan',
			'Nomor Permohonan',
			'Label Baru'
			#'Laporan'
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
			
			//$nomor_pirt_infix = substr($field->nomor_pirt, 15);
			//$nomor_pirt_prefix = substr($field->nomor_pirt, 0, 14);
			$this->table->add_row(++$i,
			//$nomor_pirt_prefix.'-'.($nomor_pirt_infix - 5),
			$field->nomor_pirt,
			$field->nomor_pirt_baru,
			$field->nama_perusahaan,
			$field->jenis_pangan,
			$field->deskripsi_pangan,
			$field->nama_dagang,
			date('d-m-Y', strtotime($field->tanggal_pengajuan_perpanjangan)),
			$field->nomor_permohonan,
			$label
			
			#anchor('pb2kp/permohonan_output_detail/'.$field->nomor_pirt_baru,'Lihat Detail')
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
				$penerbitan = $this->db->query("SELECT * FROM tabel_penerbitan_sert_pirt, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten")->num_rows();
			} else {
				$penerbitan = $this->db->query("SELECT * FROM tabel_penerbitan_sert_pirt, tabel_pen_pengajuan_spp,tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten")->num_rows();
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
				$penerbitan = $this->db->query("SELECT * FROM tabel_penerbitan_sert_pirt, tabel_pen_pengajuan_spp WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan")->num_rows();
			} else {
				$penerbitan = $this->db->query("SELECT * FROM tabel_penerbitan_sert_pirt, tabel_pen_pengajuan_spp WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan")->num_rows();
			}
		}
		
		
		
		if(count(@$prov)>0){
			$data['data_select'] = @$prov;
		} else {
			$data['data_select'] = $this->db->get('tabel_propinsi')->result();
		}
		$data['jumlah_irtp'] = $i;
		$data['jumlah_irtp_tidak'] = $penerbitan-$data['jumlah_irtp'];
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
		return view_dashboard('irtp/perpanjangan_sertifikat/output_perpanjangan', $data);
	}
	
	public function get_no_permohonan_by_pirt(){
		$nomor = $this->input->post('nomor');
		$data = $this->db->query('SELECT * FROM tabel_pen_pengajuan_spp, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and nomor_pirt = "'.$nomor.'" ORDER BY nama_perusahaan ASC');
		
		echo json_encode($data->result());
	}
	
	function add()
	{
	 
	    $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$config = array(
			array(
				'field' => 'nomor_pirt_baru',
				'label'   => 'Nomor P-IRT baru', 
                'rules'   => 'trim|required|xss_clean'
			),
		);

		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE)
		{
			$data['status'] = $this->session->set_flashdata('status','<div class="alert alert-warning">Data tidak tersubmit karena terjadi kesalahan. Harap ulangi</div>');
			$data['error'] = $this->session->set_flashdata('error', '<div class="alert alert-danger"><strong>Warning!</strong> '.validation_errors().'</div>');
			redirect('perpanjangan_sertifikat/input');
		}
		else
		{
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'png|jpg|pdf';
			$config['max_size']	= '20000';
			$config['overwrite'] = TRUE;
			$dataUp = "file_foto";
	
			$this->load->library('upload');
			$this->upload->initialize($config);
			
			if ( ! $this->upload->do_upload($dataUp))
			{
				$error = $this->upload->display_errors("<div class='alert alert-danger'><strong>Perhatian! </strong>Gagal Upload Label. ", "</div>");
				$this->session->set_flashdata('status', $error);
				$this->session->set_flashdata('inputs', $_POST);
				redirect('perpanjangan_sertifikat/input');
			}
			else
			{
				$userdata = $this->upload->data();
				$filename = $userdata['file_name'];
				$data = array('upload_data' => $userdata);
				if($this->irtp_model->add_data_irtp_perpanjangan($filename)){
					$data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Nomor sertifikat telah diperpanjang</div>');

					redirect('perpanjangan_sertifikat/output_perpanjangan');
				
			}
			
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
		
		
		$data['js_grup_pangan'] = $this->db->get('tabel_grup_jenis_pangan')->result();
		$data['js_pangan'] = $this->db->query('SELECT * FROM tabel_jenis_pangan, tabel_grup_jenis_pangan WHERE tabel_jenis_pangan.kode_r_grup_jenis_pangan = tabel_grup_jenis_pangan.kode_grup_jenis_pangan')->result();
		$data['js_kemasan'] = $this->db->get('tabel_kemasan')->result();			
		$data['js_komp_tmbh'] = $this->db->get('tabel_bahan_tambahan_pangan')->result();
		$data['js_tek_olah'] = $this->db->get('tabel_teknologi_pengolahan')->result();
		
		$data['irtp_lama'] = $this->db->query('
		SELECT * FROM 
		tabel_pen_pengajuan_spp, 
		tabel_daftar_perusahaan, 
		tabel_penerbitan_sert_pirt, 
		tabel_propinsi, 
		tabel_kabupaten_kota WHERE 
		tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND 
		tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND
		tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
		and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.' 
		/*and (year(tanggal_pemberian_pirt)+5-year(NOW())) = 1*/
		ORDER BY nama_perusahaan ASC')->result();
		
		return view_dashboard('irtp/perpanjangan_sertifikat/input_perpanjangan_sertifikat', $data);
	}
	
	function cetak()
	{
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()->setCreator('SPPIRT')
                 ->setLastModifiedBy('SPPIRT')
                 ->setTitle("Laporan Data Perpanjangan Sertifikat SPP-IRT")
                 ->setSubject("Pengajuan SPPIRT")
                 ->setDescription("Laporan Data Perpanjangan Sertifikat SPP-IRT")
                 ->setKeywords("Data Perpanjangan");

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
	    $excel->setActiveSheetIndex(0)->setCellValue('A1', "LAPORAN DATA PERPANJANGAN SERTIFIKAT SPP-IRT"); // Set kolom A1 dengan tulisan "DATA SISWA"
	    $excel->getActiveSheet()->mergeCells('A1:I1'); // Set Merge Cell pada kolom A1 sampai E1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

	    // Buat header tabel nya pada baris ke 3
	    $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO PIRT LAMA"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('C3', "NO PIRT BARU"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('D3', "NAMA IRTP"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA JENIS PANGAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('F3', "NAMA PRODUK PANGAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('G3', "NAMA DAGANG");
	    $excel->setActiveSheetIndex(0)->setCellValue('H3', "TANGGAL PERPANJANGAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('I3', "NOMOR PERMOHONAN"); 

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
		//$datas = $this->db->get('tabel_perpanjangan_sert_pirt')->result();
		$datas = $this->irtp_model->perpanjangan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();		
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
       $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $field->nomor_pirt);
       $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $field->nomor_pirt_baru);
       $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $field->nama_perusahaan);
       $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $field->jenis_pangan);
       $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $field->deskripsi_pangan);
       $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $field->nama_dagang);
       $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $field->tanggal_pengajuan_perpanjangan);
       $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $field->nomor_permohonan);

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
	    
	    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	    $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
	    // Set orientasi kertas jadi LANDSCAPE
	    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	    // Set judul file excel nya
	    $excel->getActiveSheet(0)->setTitle("LAPORAN DATA");
	    $excel->setActiveSheetIndex(0);
	    // Proses file excel
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="LAPORAN DATA PERPANJANGAN SERTIFIKAT SPP-IRT.xlsx"'); // Set nama file excel nya
	    header('Cache-Control: max-age=0');
	    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	    $write->save('php://output');
	}

	function delete()
	    {
		    $param=array("id_urut_penerbitan_sert"=>$this->uri->segment(3));
    		$this->irtp_model->delete_perpanjangan($param);
		    echo "
		    <script>
		    alert('Perpanjangan Berhasil di Hapus.');
		    window.location.href='".base_url()."perpanjangan_sertifikat/output_perpanjangan';
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
			
			
			$data['js_grup_pangan'] = $this->db->get('tabel_grup_jenis_pangan')->result();
			$data['js_pangan'] = $this->db->query('SELECT * FROM tabel_jenis_pangan, tabel_grup_jenis_pangan WHERE tabel_jenis_pangan.kode_r_grup_jenis_pangan = tabel_grup_jenis_pangan.kode_grup_jenis_pangan')->result();
			$data['js_kemasan'] = $this->db->get('tabel_kemasan')->result();			
			$data['js_komp_tmbh'] = $this->db->get('tabel_bahan_tambahan_pangan')->result();
			$data['js_tek_olah'] = $this->db->get('tabel_teknologi_pengolahan')->result();
			
			$data['irtp_lama'] = $this->db->query('
			SELECT * FROM 
			tabel_pen_pengajuan_spp, 
			tabel_daftar_perusahaan, 
			tabel_penerbitan_sert_pirt, 
			tabel_propinsi, 
			tabel_kabupaten_kota WHERE 
			tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND 
			tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan AND
			tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
			and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi '.$q_provinsi.''.$q_kabupaten.' 
			/*and (year(tanggal_pemberian_pirt)+5-year(NOW())) = 1*/
			ORDER BY nama_perusahaan ASC')->result();

			$param=array("id_urut_penerbitan_sert"=>$this->uri->segment(3));
      		$data['perpanjangan'] = $this->irtp_model->edit_perpanjangan()->row_array();

	    	return view_dashboard('irtp/perpanjangan_sertifikat/edit', $data);
	    }

  function action_edit_perpanjangan()
  {
  	$id = $this->uri->segment(3);
  	$id_pirt = $this->input->post('id');
    $nomor_pirt = $this->input->post('nomor_pirt');
    $nomor_pirt_baru = $this->input->post('nomor_pirt_baru');
    $tanggal_pengajuan_perpanjangan = $this->input->post('tanggal_pengajuan_perpanjangan');
    $no_permohonan = $this->input->post('no_permohonan');


 	$_image = $this->db->get_where('tabel_perpanjangan_sert_pirt',['id_urut_penerbitan_sert' => $id_pirt])->row();
 	$config['upload_path'] = "./uploads/irtp_perpanjangan/";
	$config['allowed_types'] = "pdf|jpg|png|jpeg";
	$this->load->library('upload', $config);

	$this->upload->do_upload('file_foto');
    $hasil  = $this->upload->data();

    if($hasil['file_name']!=NULL || $hasil['file_name']!="")
    {

	$file_url=FCPATH.'uploads/irtp_perpanjangan/'.$hasil['file_name'];
	if(is_file($file_url)!=1)
    {
          $result=true;
          echo "<script>
          alert('Pastikan semua field terisi !');
          window.location.href='".base_url('perpanjangan_sertifikat/edit/'.$id_pirt)."';
          </script>";
    }
     else
    {

    $file_ext= explode('.',$hasil['file_name']);
	$file_rename=FCPATH.'uploads/irtp_perpanjangan/perpanjangan-'.Date("Y-m-d-his").'.'.$file_ext[1];
	rename($file_url,$file_rename);
                
    $data = array('nomor_r_permohonan'=> $no_permohonan,
                  'label_final' => 'perpanjangan-'.Date("Y-m-d-his").'.'.$file_ext[1],
                  'nomor_pirt_baru' => $nomor_pirt_baru,
                  'tanggal_pengajuan_perpanjangan' => $tanggal_pengajuan_perpanjangan
                 );
    $query = $this->db->update('tabel_perpanjangan_sert_pirt', $data, array('id_urut_penerbitan_sert' => $id_pirt));
    if($query){
    unlink("uploads/irtp_perpanjangan/".$_image->label_final);
    }
    echo "<script>
	alert('Data behasil di ubah!');
	window.location.href='".base_url('perpanjangan_sertifikat/output_perpanjangan')."';
	</script>";
    }
    }
    else
    {
    	$data = array('nomor_r_permohonan'=> $no_permohonan,
                  'nomor_pirt_baru' => $nomor_pirt_baru,
                  'tanggal_pengajuan_perpanjangan' => $tanggal_pengajuan_perpanjangan
                 );
	    $query = $this->db->update('tabel_perpanjangan_sert_pirt', $data, array('id_urut_penerbitan_sert' => $id_pirt));
	    if($query)
	    {
	    	echo "<script>
			alert('Data behasil di ubah!');
			window.location.href='".base_url('perpanjangan_sertifikat/output_perpanjangan')."';
			</script>";
	    }
	    
    }       
          
  }

}