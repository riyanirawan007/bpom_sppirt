<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class irtp extends APP_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
		//load library dan helper yang dibutuhkan
		// $this->load->library('lib_irtp');
		$this->load->helper('url');
		$this->load->model('irtp_model','',TRUE);
	}
	
	//all display
	public function index()
	{
		return view_dashboard('irtp/pengajuan_sppirt/irtp_permohonan');
	}
	
	public function output_permohonan()
	{
		/*if(isset($_POST['filter'])):
			$provinsi = $this->input->post('no_kode_propinsi');
			$tgl_awal = substr($this->input->post('tanggal_awal'), 0, 10);
			$tgl_akhir = substr($this->input->post('tanggal_akhir'), 0, 10);
			
			if(empty($tgl_awal)){
				
			}
		endif;*/
		
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
		//$datas = $this->db->get('tabel_pen_pengajuan_spp')->result();
		$datas = $this->irtp_model->permohonan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();
		
		//generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		
		//head
		$tmpl = array ( 'table_open'  => '<table class="table table-striped table-bordered table-hover" id="data_table">',
	);
		$this->table->set_template($tmpl);
		
		$this->table->set_heading(
			'No.',
			'No. Permohonan',
			'Nama IRTP',
			'Nama Pemilik IRTP',
			'Nama Penanggung Jawab IRTP',
			// 'Alamat IRTP',
			'Provinsi',
			// 'Kabupaten/Kota',
			// 'Kode Pos',
			'No. Telepon',
			// 'Nama Jenis Pangan',
			'Nama Produk Pangan',
			// 'Nama Dagang',
			// 'Jenis Kemasan',
			// 'Berat Bersih',
			// 'Komposisi',
			// 'Komposisi Tambahan',
			// 'Proses Produksi',
			// 'Masa Simpan',
			// 'Kode Produksi',
			'Tanggal Pengajuan',
			'Aksi'
		);
		
		//isi
		$i = 0;
		foreach($datas as $field){
			
			$get_tambahan = $this->db->query("select * from tabel_ambil_komposisi_tambahan a left join tabel_bahan_tambahan_pangan b on a.kode_r_komposisi = b.no_urut_btp where nomor_r_permohonan = '".$field->nomor_permohonan."'")->result_array();
			$tambahan = "";
			foreach($get_tambahan as $val){
				if($val['berat']==""){ $berat=0; } else { $berat = $val['berat']; }
				$tambahan .=$val['nama_bahan_tambahan_pangan']." : ".$berat."<br><br>";
			}
			
			//scan alur_produksi
			$label_type = strtolower(pathinfo($field->path_ap, PATHINFO_EXTENSION));
			$label_url = base_url('uploads/alur_produksi/'.$field->path_ap);
			$label_ap = "-";
			switch ($label_type) {
				case 'png':
				case 'gif':
				case 'jpg':
				case 'jpeg':
					$label_ap = "<a href='{$label_url}' target='_blank'><img src='{$label_url}' style='width:80px;'/></a>";
					break;

				case 'pdf':
					$label_ap = "<a href='{$label_url}' target='_blank'>Unduh</a>";
					break;
			}
			
			//scan siup
			$label_type = strtolower(pathinfo($field->path_siup, PATHINFO_EXTENSION));
			$label_url = base_url('uploads/siup/'.$field->path_siup);
			$label_siup = "-";
			switch ($label_type) {
				case 'png':
				case 'gif':
				case 'jpg':
				case 'jpeg':
					$label_siup = "<a href='{$label_url}' target='_blank'><img src='{$label_url}' style='width:80px;'/></a>";
					break;

				case 'pdf':
					$label_siup = "<a href='{$label_url}' target='_blank'>Unduh</a>";
					break;
			}
			
			//scan siup
			$label_type = strtolower(pathinfo($field->path_rl, PATHINFO_EXTENSION));
			$label_url = base_url('uploads/rancangan_label/'.$field->path_rl);
			$label_rl = "-";
			switch ($label_type) {
				case 'png':
				case 'gif':
				case 'jpg':
				case 'jpeg':
					$label_rl = "<a href='{$label_url}' target='_blank'><img src='{$label_url}' style='width:80px;'/></a>";
					break;

				case 'pdf':
					$label_rl = "<a href='{$label_url}' target='_blank'>Unduh</a>";
					break;
			}
			
			//Jenis Kemasan
			if($field->kode_r_kemasan==6){
				$jenis_kemasan = $field->jenis_kemasan_lain;
			} else {
				$jenis_kemasan = $field->jenis_kemasan;
			}
			
			//Proses Produksi
			if($field->kode_r_tek_olah==11){
				$proses_produksi = $field->proses_produksi_lain;
			} else {
				$proses_produksi = $field->tek_olah;
			}
			
			$this->table->add_row(++$i,
			$field->nomor_permohonan,
			$field->nama_perusahaan,
			$field->nama_pemilik,
			// $field->nama_penanggung_jawab,$field->alamat_irtp,
			$field->nama_propinsi,
			// $field->nm_kabupaten,
			// $field->kode_pos_irtp,
			$field->nomor_telefon_irtp,
			$field->jenis_pangan,
			$field->deskripsi_pangan,
			// $field->nama_dagang,
			// $jenis_kemasan,
			// $field->berat_bersih,
			// $field->komposisi_utama,
			// $tambahan,
			// $proses_produksi,
			// $field->masa_simpan,
			// $field->info_kode_produksi,
			date('d-m-Y', strtotime($field->tanggal_pengajuan)),
			anchor('irtp/output_laporan_permohonan_unduh/'.$field->nomor_permohonan, 'Unduh Laporan', array('class' => 'btn btn-info col-md-12', 'target' => '_blank'))
			#anchor('pb2kp/permohonan_output_detail/'.$field->nomor_permohonan,'Lihat Detail')
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
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pengajuan>='$tanggal_awal' and tanggal_pengajuan<='$tanggal_akhir' and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten GROUP BY jenis_pangan")->result();
				$data['jml_kemasan'] = $this->db->query("SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pengajuan>='$tanggal_awal' and tanggal_pengajuan<='$tanggal_akhir' and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten GROUP BY jenis_kemasan")->result();
				$data['jml_komposisi'] = $this->db->query("SELECT COUNT(kode_r_komposisi) AS jml_komposisi_perusahaan, nama_bahan_tambahan_pangan 
				FROM tabel_propinsi, tabel_kabupaten_kota, `tabel_pen_pengajuan_spp`
				LEFT JOIN `tabel_daftar_perusahaan` ON kode_perusahaan = kode_r_perusahaan
				LEFT JOIN `tabel_ambil_komposisi_tambahan` ON nomor_permohonan = nomor_r_permohonan
				LEFT JOIN tabel_bahan_tambahan_pangan ON kode_r_komposisi = no_urut_btp
				where tanggal_pengajuan>='$tanggal_awal' and tanggal_pengajuan<='$tanggal_akhir'
				and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten
				GROUP BY kode_r_komposisi
				ORDER BY no_urut_btp ASC")->result();
			} else {
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten GROUP BY jenis_pangan")->result();
				$data['jml_kemasan'] = $this->db->query("SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan,tabel_propinsi,tabel_kabupaten_kota WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten GROUP BY jenis_kemasan")->result();
				$data['jml_komposisi'] = $this->db->query("SELECT COUNT(kode_r_komposisi) AS jml_komposisi_perusahaan, nama_bahan_tambahan_pangan 
				FROM tabel_propinsi, tabel_kabupaten_kota, `tabel_pen_pengajuan_spp`
				LEFT JOIN `tabel_daftar_perusahaan` ON kode_perusahaan = kode_r_perusahaan
				LEFT JOIN `tabel_ambil_komposisi_tambahan` ON nomor_permohonan = nomor_r_permohonan
				LEFT JOIN tabel_bahan_tambahan_pangan ON kode_r_komposisi = no_urut_btp
				where tabel_propinsi.no_kode_propinsi = tabel_kabupaten_kota.no_kode_propinsi $q_provinsi $q_kabupaten and tabel_daftar_perusahaan.id_r_urut_kabupaten=tabel_kabupaten_kota.id_urut_kabupaten
				GROUP BY kode_r_komposisi
				ORDER BY no_urut_btp ASC")->result();
				
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
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pengajuan>='$tanggal_awal' and tanggal_pengajuan<='$tanggal_akhir' GROUP BY jenis_pangan")->result();
				$data['jml_kemasan'] = $this->db->query("SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tanggal_pengajuan>='$tanggal_awal' and tanggal_pengajuan<='$tanggal_akhir' GROUP BY jenis_kemasan")->result();
				$data['jml_komposisi'] = $this->db->query("SELECT COUNT(kode_r_komposisi) AS jml_komposisi_perusahaan, nama_bahan_tambahan_pangan FROM `tabel_pen_pengajuan_spp`
				LEFT JOIN `tabel_daftar_perusahaan` ON kode_perusahaan = kode_r_perusahaan
				LEFT JOIN `tabel_ambil_komposisi_tambahan` ON nomor_permohonan = nomor_r_permohonan
				LEFT JOIN tabel_bahan_tambahan_pangan ON kode_r_komposisi = no_urut_btp
				where tanggal_pengajuan>='$tanggal_awal' and tanggal_pengajuan<='$tanggal_akhir'
				GROUP BY kode_r_komposisi
				ORDER BY no_urut_btp ASC")->result();
			} else {
				$data['jml_pangan'] = $this->db->query("SELECT count(jenis_pangan) as count_jenis, jenis_pangan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan GROUP BY jenis_pangan")->result();
				$data['jml_kemasan'] = $this->db->query("SELECT count(jenis_kemasan) as count_kemasan, jenis_kemasan FROM tabel_pen_pengajuan_spp, tabel_teknologi_pengolahan, tabel_jenis_pangan, tabel_kemasan, tabel_daftar_perusahaan WHERE tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah AND tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan AND tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan GROUP BY jenis_kemasan")->result();
				$data['jml_komposisi'] = $this->db->query("SELECT COUNT(kode_r_komposisi) AS jml_komposisi_perusahaan, nama_bahan_tambahan_pangan FROM `tabel_pen_pengajuan_spp`
				LEFT JOIN `tabel_daftar_perusahaan` ON kode_perusahaan = kode_r_perusahaan
				LEFT JOIN `tabel_ambil_komposisi_tambahan` ON nomor_permohonan = nomor_r_permohonan
				LEFT JOIN tabel_bahan_tambahan_pangan ON kode_r_komposisi = no_urut_btp
				GROUP BY kode_r_komposisi
				ORDER BY no_urut_btp ASC")->result();
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
		return view_dashboard('irtp/pengajuan_sppirt/output_permohonan', $data);
	}
	public function irtp_lama()
	{
		//return view_dashboard('irtp/pengajuan_sppirt/irtp_lama');
		$kode = $this->input->post('kode');
		$result = $this->irtp_model->get_perusahaan($kode)->result();
				
		echo json_encode($result);
	}
	public function set_data_irtp_spp(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$config = array(
			array(
				'field' => 'grup_jenis_pangan',
				'label'   => 'Jenis Pangan', 
                'rules'   => 'trim|required|xss_clean'
			),
			array(
				'field' => 'jenis_pangan',
				'label'   => 'Jenis Pangan', 
                'rules'   => 'trim|required|xss_clean'
			),
			/*array(
				'field' => 'nama_dagang',
				'label'   => 'Nama Dagang', 
                'rules'   => 'trim|required|xss_clean'
			),
			array(
				'field' => 'kode_kemasan',
				'label'   => 'Kode Kemasan', 
                'rules'   => 'trim|required|xss_clean'
			),
			array(
				'field' => 'berat_bersih',
				'label'   => 'Berat Bersih', 
                'rules'   => 'trim|required|xss_clean'
			),*/
			
		);

		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE)
		{
			$data['status'] = $this->session->set_flashdata('<div class="alert alert-warning">Data tidak tersubmit karena terjadi kesalahan. Harap ulangi</div>');
			redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_baru');
		}
		else
		{
			$this->irtp_model->add_data_irtp_spp(); // ambil dari file models/irtp_model.php
			//$this->load->view('formsuccess');
			$data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Pengajuan IRTP telah berhasil di-upload</div>');
			redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_lama');
		}
	}

	public function chek()
	{
		return view_dashboard('irtp/main');
	}

	public function irtp_baru()
	{
		$data = $this->irtp_model->add_perusahaan();	
		
		$this->session->set_flashdata('status', '<div class="alert alert-info"><strong>Perhatian! </strong>Data usaha teregistrasi dengan nomor ID '.$data.'.</div>');
		redirect('irtp/chek');
	}

	public function irtp_perpanjangan()
	{
		return view_dashboard('irtp/pengajuan_sppirt/irtp_perpanjangan');
	}
	
	public function get_grup_jenis_pangan(){
		$grup_jenis_pangan = $this->input->post('grup_jenis_pangan');
		$this->db->where('kode_grup_jenis_pangan', $grup_jenis_pangan);
		$result = $this->db->get('tabel_grup_jenis_pangan')->result_array();
		$result = $result[0]['nama_grup_jenis_pangan'];		
		echo json_encode($result);
	}
	
	public function get_jenis_pangan(){
		$grup_jenis_pangan = $this->input->post('grup_jenis_pangan');
		$this->db->where('kode_r_grup_jenis_pangan', $grup_jenis_pangan);
		$result = $this->db->get('tabel_jenis_pangan')->result();
				
		echo json_encode($result);
	}

	
	public function irtp_openStat(){
		$statusPengajuan = $this->input->get('status_pengajuan');
		$statusPerusahaan = $this->input->get('status_perus');
		$provinsi = $this->session->userdata('code');
		$kabupaten = $this->session->userdata('code');
			
		if(($statusPengajuan == 'baru') & ($statusPerusahaan == 'perus_baru')){			
			
			$data['js_perus'] = $this->db->query("select * from tabel_daftar_perusahaan where id_r_urut_kabupaten = '".$kabupaten."'")->result();
			
			$this->db->select('*');
			$this->db->from('tabel_grup_jenis_pangan');
			$this->db->order_by('kode_grup_jenis_pangan', 'ASC');
			$data['js_grup_pangan'] = $this->db->get()->result();
			
			$data['js_pangan'] = $this->db->query('SELECT * FROM tabel_jenis_pangan, tabel_grup_jenis_pangan WHERE tabel_jenis_pangan.kode_r_grup_jenis_pangan = tabel_grup_jenis_pangan.kode_grup_jenis_pangan')->result();
			$data['js_kemasan'] = $this->db->get('tabel_kemasan')->result();			
			
			$data['js_komp_tmbh'] = $this->db->get('tabel_bahan_tambahan_pangan')->result();
			$data['js_tek_olah'] = $this->db->get('tabel_teknologi_pengolahan')->result();
			$data['js_kabupaten'] = $this->db->get('tabel_kabupaten_kota')->result();
			$data['js_satuan'] = $this->db->get('tabel_satuan')->result();
			
			
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
			
			if(count(@$prov)>0){
					$data['data_select'] = @$prov;
			} else {
				$data['data_select'] = $this->db->get('tabel_propinsi')->result();
			}

			return view_dashboard('irtp/pengajuan_sppirt/irtp_baru', $data);
			//$this->lib_irtp->display('irtp_permohonan_perusahaan', $data);
			
			// start status pengajuan baru 
		} else if(($statusPengajuan == 'baru') & ($statusPerusahaan == 'perus_lama')){	
			//$data['js_perus'] = $this->db->get('tabel_daftar_perusahaan')->result();
			
			$data['js_perus'] = $this->db->query("select * from tabel_daftar_perusahaan where id_r_urut_kabupaten = '".$kabupaten."'")->result();
			
			$this->db->select('*');
			$this->db->from('tabel_grup_jenis_pangan');
			$this->db->order_by('kode_grup_jenis_pangan', 'ASC');
			$data['js_grup_pangan'] = $this->db->get()->result();
			
			$data['js_pangan'] = $this->db->query('SELECT * FROM tabel_jenis_pangan, tabel_grup_jenis_pangan WHERE tabel_jenis_pangan.kode_r_grup_jenis_pangan = tabel_grup_jenis_pangan.kode_grup_jenis_pangan')->result();
			$data['js_kemasan'] = $this->db->get('tabel_kemasan')->result();			
			
			$data['js_komp_tmbh'] = $this->db->get('tabel_bahan_tambahan_pangan')->result();
			$data['js_tek_olah'] = $this->db->get('tabel_teknologi_pengolahan')->result();
			$data['js_kabupaten'] = $this->db->get('tabel_kabupaten_kota')->result();
			$data['js_satuan'] = $this->db->get('tabel_satuan')->result();
			

			return view_dashboard('irtp/pengajuan_sppirt/irtp_lama', $data);
			//$this->lib_irtp->display('irtp_permohonan_1', $data);	
		} else {
			$this->irtp_perpanjangan();
			/* $data['js_grup_pangan'] = $this->db->get('tabel_grup_jenis_pangan')->result();
			$data['js_pangan'] = $this->db->query('SELECT * FROM tabel_jenis_pangan, tabel_grup_jenis_pangan WHERE tabel_jenis_pangan.kode_r_grup_jenis_pangan = tabel_grup_jenis_pangan.kode_grup_jenis_pangan')->result();
			$data['js_kemasan'] = $this->db->get('tabel_kemasan')->result();			
			$data['js_komp_tmbh'] = $this->db->get('tabel_bahan_tambahan_pangan')->result();
			$data['js_tek_olah'] = $this->db->get('tabel_teknologi_pengolahan')->result();
			$this->lib_irtp->display('irtp_perpanjangan',$data); */
		}
	}
	
	public function process()
	{
	    $statusPengajuan = $this->input->get('status_pengajuan');
		$statusPerusahaan = $this->input->get('status_perus');
		
		if(($statusPengajuan == 'baru') & ($statusPerusahaan == 'perus_baru')){			
			
			
			return view_dashboard('irtp/irtp_permohonan_perusahaan', $data);
			
			// start status pengajuan baru 
		} else if(($statusPengajuan == 'baru') & ($statusPerusahaan == 'perus_lama')){	
			
			return view_dashboard('irtp/irtp_permohonan_1', $data);	
		} else {
			$this->irtp_perpanjangan();
		
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */