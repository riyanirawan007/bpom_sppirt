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

		$data['datas'] = $this->irtp_model->permohonan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();
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
			// echo 'as '.anchor().' as'
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

	public function output_laporan_permohonan_unduh($nomor_permohonan){
		$query =  $this->db->query("SELECT distinct * FROM tabel_pen_pengajuan_spp, tabel_daftar_perusahaan, tabel_propinsi, tabel_kabupaten_kota, tabel_jenis_pangan, tabel_kemasan, tabel_teknologi_pengolahan WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan and tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.nomor_permohonan = '".$nomor_permohonan."' ");

		//if($query->num_rows >= 1){
		$data['query'] = $query->result();
		$this->load->view('irtp/pengajuan_sppirt/print_1', $data);
		//}
	}

	public function detail_permohonan($nomor_permohonan){
		$query =  $this->db->query("SELECT distinct * FROM tabel_pen_pengajuan_spp, tabel_daftar_perusahaan, tabel_propinsi, tabel_kabupaten_kota, tabel_jenis_pangan, tabel_kemasan, tabel_teknologi_pengolahan WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah and tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan and tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan AND tabel_pen_pengajuan_spp.nomor_permohonan = '".$nomor_permohonan."' ");

		//if($query->num_rows >= 1){
		$data['query'] = $query->result();
		return view_dashboard('irtp/pengajuan_sppirt/output_permohonan_detail', $data);
			// $this->load->view('irtp/pengajuan_sppirt/output_permohonan_detail', $data);
		//}
	}

	public function editPengajuan()
	{
		$nomor_permohonan=$this->input->get('nomor_permohonan');
		
		$result=array();
		$pemilik=array();
		$produk_pangan=array();
		$pengajuan=array();

		if($nomor_permohonan!=null)
		{
			//Pengajuan
			$pengajuan=$perusahaan=$this->db->select('a.*')
			->from('tabel_pen_pengajuan_spp a')
			->where('a.nomor_permohonan',$nomor_permohonan)
			->get()->row();

			// Perusahaan
			$pemilik=$this->db->select('b.*,c.*,d.*')
			->from('tabel_pen_pengajuan_spp a')
			->join('tabel_daftar_perusahaan b','b.kode_perusahaan=a.kode_r_perusahaan')
			->join('tabel_kabupaten_kota d','d.id_urut_kabupaten=b.id_r_urut_kabupaten')
			->join('tabel_propinsi c','c.no_kode_propinsi=d.no_kode_propinsi')
			->where('a.nomor_permohonan',$nomor_permohonan)
			->get()->row();

			$surat_izin=$this->db
			->where('kode_r_perusahaan',$pemilik->kode_perusahaan)
			->get('tabel_scan_data_pengajuan_siup')->row();
			$pemilik->surat_izin=$surat_izin;

			//Produk
			$kemasan=$this->db->select('b.*')
			->from('tabel_pen_pengajuan_spp a')
			->join('tabel_kemasan b','b.kode_kemasan=a.kode_r_kemasan')
			->where('a.nomor_permohonan',$nomor_permohonan)
			->get()->row();

			$grup_jenis_pangan=$this->db->select('c.*')
			->from('tabel_pen_pengajuan_spp a')
			->join('tabel_jenis_pangan b','b.id_urut_jenis_pangan=a.id_urut_jenis_pangan')
			->join('tabel_grup_jenis_pangan c','b.kode_r_grup_jenis_pangan=c.kode_grup_jenis_pangan')
			->where('a.nomor_permohonan',$nomor_permohonan)
			->limit(1)
			->get()->row();
			
			$teknologi=$this->db->select('b.*')
			->from('tabel_pen_pengajuan_spp a')
			->join('tabel_teknologi_pengolahan b','b.kode_tek_olah=a.kode_r_tek_olah')
			->where('a.nomor_permohonan',$nomor_permohonan)
			->get()->row();

			
			$file_rl=$this->db->select('b.*')
			->from('tabel_pen_pengajuan_spp a')
			->join('tabel_scan_data_pengajuan_rl b','b.nomor_r_permohonan=a.nomor_permohonan')
			->where('a.nomor_permohonan',$nomor_permohonan)
			->get()->row();

			$file_ap=$this->db->select('b.*')
			->from('tabel_pen_pengajuan_spp a')
			->join('tabel_scan_data_pengajuan_ap b','b.kode_r_perusahaan=a.kode_r_perusahaan')
			->where('a.nomor_permohonan',$nomor_permohonan)
			->get()->row();

			$komposisi_tambahan=$this->db->select('b.*')
			->from('tabel_pen_pengajuan_spp a')
			->join('tabel_ambil_komposisi_tambahan b','b.nomor_r_permohonan=a.nomor_permohonan')
			->where('a.nomor_permohonan',$nomor_permohonan)
			->get()->result();

			$produk_pangan['kemasan']=$kemasan;
			$produk_pangan['teknologi']=$teknologi;
			$produk_pangan['grup_jenis_pangan']=$grup_jenis_pangan;
			$produk_pangan['file_rl']=$file_rl;
			$produk_pangan['file_ap']=$file_ap;
			$produk_pangan['komposisi_tambahan']=$komposisi_tambahan;
			
			$result['success']=true;

		}
		else{
			$result['success']=false;
		}

		$result['pengajuan']=$pengajuan;
		$result['pemilik']=$pemilik;
		$result['produk_pangan']=$produk_pangan;

		echo json_encode($result);
	}

	public function edit($nomor_permohonan){
		$statusPengajuan = $this->input->get('status_pengajuan');
		$statusPerusahaan = $this->input->get('status_perus');
		$provinsi = $this->session->userdata('code');
		$kabupaten = $this->session->userdata('code');

		$query =  $this->db->query("SELECT distinct * FROM tabel_pen_pengajuan_spp
		, tabel_daftar_perusahaan, tabel_propinsi
		, tabel_kabupaten_kota, tabel_jenis_pangan
		, tabel_kemasan
		, tabel_teknologi_pengolahan 
		WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan
		and tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten
		and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi
		and tabel_pen_pengajuan_spp.kode_r_tek_olah = tabel_teknologi_pengolahan.kode_tek_olah
		and tabel_pen_pengajuan_spp.kode_r_kemasan = tabel_kemasan.kode_kemasan
		and tabel_pen_pengajuan_spp.id_urut_jenis_pangan = tabel_jenis_pangan.id_urut_jenis_pangan
		AND tabel_pen_pengajuan_spp.nomor_permohonan = '".$nomor_permohonan."' ");

		//if($query->num_rows >= 1){
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
		$data['query'] = $query->result();
		return view_dashboard('irtp/pengajuan_sppirt/output_edit', $data);
			// $this->load->view('irtp/pengajuan_sppirt/output_permohonan_detail', $data);
		//}
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

		if($this->irtp_model->add_data_irtp_spp()){
			//$data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Informasi penyuluhan terbaru telah dimasukkan</div>');
			echo json_encode(array('success'=>true));
		}
		else
		{
			echo json_encode(array('success'=>false));
		}

		// if ($this->form_validation->run() == FALSE)
		// {
		// 	$data['status'] = $this->session->set_flashdata('<div class="alert alert-warning">Data tidak tersubmit karena terjadi kesalahan. Harap ulangi</div>');
		// 	redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_baru');
		// }
		// else
		// {
		// 	$this->irtp_model->add_data_irtp_spp(); // ambil dari file models/irtp_model.php
		// 	//$this->load->view('formsuccess');
		// 	$data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Pengajuan IRTP telah berhasil di-upload</div>');
		// 	return view_dashboard('irtp/output_permohonan');
		// }
	}

	public function set_data_irtp_spp_baru(){
		
		$this->irtp_model->add_data_irtp_spp();
		$this->set_upload_rl();
		$this->set_upload_ap();
	}

	public function get_kabupaten(){
		$provinsi = $this->input->post('provinsi');
		$result = $this->irtp_model->get_kabupaten($provinsi)->result();

		echo json_encode($result);
	}

	public function get_perusahaan_raw(){
		$kode = $this->input->post('kode');
		$result = $this->irtp_model->get_perusahaan($kode)->result();

		echo json_encode($result);
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
		return view_dashboard('irtp/pengajuan_sppirt/irtp_perpanjangan', $data);
	}

	public function get_no_permohonan_by_pirt(){
		$nomor = $this->input->post('nomor');
		$data = $this->db->query('SELECT * FROM tabel_pen_pengajuan_spp, tabel_daftar_perusahaan, tabel_penerbitan_sert_pirt WHERE tabel_penerbitan_sert_pirt.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan AND tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and nomor_pirt = "'.$nomor.'" ORDER BY nama_perusahaan ASC');
		
		echo json_encode($data->result());
	}
	public function set_data_irtp_perpanjangan(){
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
			redirect('irtp/irtp_perpanjangan');
		}
		else
		{
			$config['upload_path'] = './uploads/irtp_perpanjangan/';
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
				redirect('irtp/irtp_perpanjangan');
			}
			else
			{
				$userdata = $this->upload->data();
				$filename = $userdata['file_name'];
				$data = array('upload_data' => $userdata);
				if($this->irtp_model->add_data_irtp_perpanjangan($filename)){
					$data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Nomor sertifikat telah diperpanjang</div>');

					redirect('irtp/irtp_perpanjangan');
				} // ambil dari file models/irtp_model.php
				
			}
			
		}
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
		} else if(($statusPengajuan == 'baru') & ($statusPerusahaan == 'perus_yang_baru')){	
			//$data['js_perus'] = $this->db->get('tabel_daftar_perusahaan')->result();
			
			$data['js_perus'] = $this->db->query("select * from tabel_daftar_perusahaan where id_r_urut_kabupaten = '".$kabupaten."'")->result();
			$data['perus_id'] = $this->input->get('perus_id');
			
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
			

			return view_dashboard('irtp/pengajuan_sppirt/irtp_baru_2', $data);
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
	function cetak()
	{
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()->setCreator('SPPIRT')
		->setLastModifiedBy('SPPIRT')
		->setTitle("Laporan Data Permohonan Pengajuan SPP-IRT")
		->setSubject("Pengajuan SPPIRT")
		->setDescription("Laporan Data Permohonan Pengajuan SPP-IRT")
		->setKeywords("Data Permohonan Baru");

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
	    $excel->setActiveSheetIndex(0)->setCellValue('A1', "LAPORAN DATA PERMOHONAN PENGAJUAN SPP-IRT"); // Set kolom A1 dengan tulisan "DATA SISWA"
	    $excel->getActiveSheet()->mergeCells('A1:M1'); // Set Merge Cell pada kolom A1 sampai E1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
	    $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

	    // Buat header tabel nya pada baris ke 3
	    $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO PERMOHONAN"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA IRTP"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('D3', "NAMA PEMILIK IRTP"); 
	    $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA PENANGGUNG JAWAB");
	    $excel->setActiveSheetIndex(0)->setCellValue('F3', "NO TELP");
	    $excel->setActiveSheetIndex(0)->setCellValue('G3', "PROVINSI");
	    $excel->setActiveSheetIndex(0)->setCellValue('H3', "NAMA PRODUK PANGAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('I3', "KOMPOSISI UTAMA BAHAN PANGAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('J3', "NAMA DAGANG");
	    $excel->setActiveSheetIndex(0)->setCellValue('K3', "JENIS KEMASAN");
	    $excel->setActiveSheetIndex(0)->setCellValue('L3', "BERAT BERSIH");
	    $excel->setActiveSheetIndex(0)->setCellValue('M3', "TANGGAL PENGAJUAN"); 

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
	    $excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
	    $excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);


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

	    $datas = $this->irtp_model->permohonan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();
	    $data['datas'] = $this->irtp_model->permohonan_view($provinsi,$kabupaten,$tanggal_awal,$tanggal_akhir)->result();
	    $no = 1;
	    $numrow = 4;
	    foreach ($datas as $field)
	    {
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

		// isi excel
	    	$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
	    	$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $field->nomor_permohonan);
	    	$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $field->nama_perusahaan);
	    	$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $field->nama_pemilik);
	    	$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $field->nama_penanggung_jawab);
	    	$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $field->nomor_telefon_irtp);
	    	$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $field->nama_propinsi);
	    	$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $field->deskripsi_pangan);
	    	$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $field->komposisi_utama);
	    	$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $field->nama_dagang);
	    	$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $jenis_kemasan);
	    	$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $field->berat_bersih);
	    	$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $field->tanggal_pengajuan);

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
	    	$excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
	    	$excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);

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
	    $excel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
	    $excel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
	    
	    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	    $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
	    // Set orientasi kertas jadi LANDSCAPE
	    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	    // Set judul file excel nya
	    $excel->getActiveSheet(0)->setTitle("LAPORAN DATA");
	    $excel->setActiveSheetIndex(0);
	    // Proses file excel
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="LAPORAN DATA PERMOHONAN PENGAJUAN SPP-IRT.xlsx"'); // Set nama file excel nya
	    header('Cache-Control: max-age=0');
	    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	    $write->save('php://output');
	}

	public function set_perusahaan(){
		$perusahaan = $this->irtp_model->add_perusahaan();	
		
		// $this->session->set_flashdata('status', '<div class="alert alert-info"><strong>Perhatian! </strong>Data usaha teregistrasi dengan nomor ID '.$data.'.</div>');
		$this->set_upload_siup($perusahaan);
		// redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_lama');
	}

	public function set_upload_siup($perusahaan){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$config['upload_path'] = './uploads/siup';
		$config['allowed_types'] = 'jpg|png|pdf|jpeg';
		$config['max_size']	= '20000';
		$config['overwrite'] = TRUE;
		$dataUp = "file_pdf";

		$this->load->library('upload');
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload($dataUp))
		{
			$error = "<div class='alert alert-danger'><strong>Perhatian! </strong>Gagal upload Rancangan Label. Format file salah atau file berukuran lebih dari 2MB</div>";
			$this->session->set_flashdata('status', $error);
			redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_baru');
		}
		else
		{
			$userdata = $this->upload->data();
			$filename = $userdata['file_name'];
			$data = array('upload_data' => $userdata);
			if($this->irtp_model->add_data_irtp_permohonan_perusahaan_siup($perusahaan,$filename)){
				// $this->session->set_flashdata('status', '<div class="alert alert-info"><strong>Perhatian! </strong>Data usaha teregistrasi dengan nomor ID '.$data.'.</div>');
				// $data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> SIUP telah berhasil di-upload</div>');
				redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_yang_baru&perus_id='.$perusahaan.'');

				// redirect('irtp/irtp_upload_permohonan_siup');
			} 
		}
	}

	public function set_upload_rl(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$config['upload_path'] = './uploads/rancangan_label';
		$config['allowed_types'] = 'jpg|png|pdf|jpeg';
		$config['max_size']  = '20000';
		$config['overwrite'] = TRUE;
		$dataUp = "rancangan_label";
		$perusahaan = $this->input->post('pemilik_usaha');
		$this->load->library('upload');
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload($dataUp))
		{

			$error = "<div class='alert alert-danger'><strong>Perhatian! </strong>Gagal upload Rancangan Label. Format file salah atau file berukuran lebih dari 2MB</div>";
			$this->session->set_flashdata('status', $error);
			// redirect('irtp/irtp_upload_permohonan_rl');
			$perusahaan = $this->input->post('pemilik_usaha');
			redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_yang_baru&perus_id='.$perusahaan.'');
		}
		else
		{
			$userdata = $this->upload->data();
			$filename = $userdata['file_name'];
			$data = array('upload_data' => $userdata);
			if($this->irtp_model->add_data_irtp_permohonan_perusahaan_rl($filename)){
				// $data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Rancangan Label telah berhasil di-upload</div>');

				// redirect('irtp/irtp_upload_permohonan_rl');
			} 
		}
	}

	public function set_upload_ap(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$config['upload_path'] = './uploads/alur_produksi';
		$config['allowed_types'] = 'jpg|png|pdf|jpeg';
		$config['max_size']  = '20000';
		$config['overwrite'] = TRUE;
		$dataUp = "alur_produksi";

		$this->load->library('upload');
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload($dataUp))
		{
			$error = "<div class='alert alert-danger'><strong>Perhatian! </strong>Gagal upload Rancangan Label. Format file salah atau file berukuran lebih dari 2MB</div>";
			$this->session->set_flashdata('status', $error);
			$perusahaan = $this->input->post('pemilik_usaha');
			redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_yang_baru&perus_id='.$perusahaan.'');
		}
		else
		{
			$userdata = $this->upload->data();
			$filename = $userdata['file_name'];
			$data = array('upload_data' => $userdata);
			if($this->irtp_model->add_data_irtp_permohonan_perusahaan_ap($filename)){
				// $data['message'] = $this->session->set_flashdata('error', '<div class="alert alert-info"><strong>Selamat!</strong> Dokumen Alur Produksi telah berhasil di-upload</div>');

				redirect('irtp/output_permohonan');
			} 
		}
		//}
	}

	function update_irtp_lama() {
		$pemilik_usaha = $this->input->post('pemilik_usaha');
		$no_nik = $this->input->post('no_nik');
		$penanggung_jawab = $this->input->post('penanggung_jawab');
		$alamat = $this->input->post('alamat_irtp');	
		$telepon = $this->input->post('telepon_irtp');

		$file_pdf = $_FILES['file_pdf']['name'];

		$data = array(
			'nama_penanggung_jawab' => $penanggung_jawab,
			'no_nik' => $no_nik
		);
		$this->db->where('kode_perusahaan', $pemilik_usaha);
		$update = $this->db->update('tabel_daftar_perusahaan', $data);
		if ($update) {
			if ($file_pdf != "") {
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				$config['upload_path'] = './uploads/siup';
				$config['allowed_types'] = 'jpg|png|pdf|jpeg';
				$config['max_size']	= '20000';
				$config['overwrite'] = TRUE;
				$dataUp = "file_pdf";

				$this->load->library('upload');
				$this->upload->initialize($config);

				if ( ! $this->upload->do_upload($dataUp))
				{
					$error = "<div class='alert alert-danger'><strong>Perhatian! </strong>Gagal upload Rancangan Label. Format file salah atau file berukuran lebih dari 2MB</div>";
					$this->session->set_flashdata('status', $error);
					redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_baru');
				}
				else
				{
					$userdata = $this->upload->data();
					$filename = $userdata['file_name'];
					$data = array('upload_data' => $userdata);
					if($this->irtp_model->update_data_irtp_permohonan_perusahaan_siup($pemilik_usaha,$filename)){
						redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_yang_baru&perus_id='.$pemilik_usaha.'');
					} 
				}
			} else {
				redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_yang_baru&perus_id='.$pemilik_usaha.'');

			}
		} else {
			$error = "<div class='alert alert-danger'><strong>Perhatian! </strong>Gagal upload Rancangan Label. Format file salah atau file berukuran lebih dari 2MB</div>";
			$this->session->set_flashdata('status', $error);
			redirect('irtp/irtp_openStat?status_pengajuan=baru&status_perus=perus_lama');
		}
	}

	function proccess_edit()
	{

			 // siup
			 $config['upload_path'] = './uploads/siup';
			 $config['allowed_types'] = 'jpg|png|pdf|jpeg';
			 $config['max_size']	= '20000';
			 $config['overwrite'] = TRUE;
			 $dataUp = "file_siup";
	 
			 $this->load->library('upload');
			 $this->upload->initialize($config);
	 
			 $error=null;
			 if($this->upload->do_upload($dataUp))
			 {
				$userdata = $this->upload->data();
				$filename = $userdata['file_name'];
				$data = array('upload_data' => $userdata);

				$is_new=$this->db->where('kode_r_perusahaan', $this->input->post('kode_r_perusahaan'))
				->get('tabel_scan_data_pengajuan_siup')->num_rows() <= 0?true:false;
				if($is_new)
				{
					$this->db->insert(
						'tabel_scan_data_pengajuan_siup'
						,array(
							'kode_r_perusahaan'=>$this->input->post('kode_r_perusahaan')
							,'path_scan_data'=>$filename
						)
					);
				}else{
					$this->db->where('kode_r_perusahaan', $this->input->post('kode_r_perusahaan'));
					$update = $this->db->update('tabel_scan_data_pengajuan_siup',
					array('path_scan_data'=>$filename));
				}
			 }
			 else
			 {
				$error=$this->upload->display_errors();
			 }

			 // rl
			 $config['upload_path'] = './uploads/rancangan_label';
			 $config['allowed_types'] = 'jpg|png|pdf|jpeg';
			 $config['max_size']	= '20000';
			 $config['overwrite'] = TRUE;
			 $dataUp = "file_rl";
	 
			 $this->load->library('upload');
			 $this->upload->initialize($config);
	 
			 $error=null;
			 if($this->upload->do_upload($dataUp))
			 {
				$userdata = $this->upload->data();
				$filename = $userdata['file_name'];
				$data = array('upload_data' => $userdata);

				$is_new=$this->db->where('nomor_r_permohonan', $this->input->post('nomor_permohonan'))
				->get('tabel_scan_data_pengajuan_rl')->num_rows() <= 0?true:false;
				if($is_new)
				{
					$this->db->insert(
						'tabel_scan_data_pengajuan_rl'
						,array(
							'nomor_r_permohonan'=>$this->input->post('nomor_permohonan')
							,'path_scan_data'=>$filename
						)
					);
				}else{
				
				$this->db->where('nomor_r_permohonan', $this->input->post('nomor_permohonan'));
				$update = $this->db->update('tabel_scan_data_pengajuan_rl',
				array('path_scan_data'=>$filename));
				}
			 }
			 else
			 {
				$error=$this->upload->display_errors();
			 }

			 // ap
			 $config['upload_path'] = './uploads/alur_produksi';
			 $config['allowed_types'] = 'jpg|png|pdf|jpeg';
			 $config['max_size']	= '20000';
			 $config['overwrite'] = TRUE;
			 $dataUp = "file_ap";
	 
			 $this->load->library('upload');
			 $this->upload->initialize($config);
	 
			 $error=null;
			 if($this->upload->do_upload($dataUp))
			 {
				$userdata = $this->upload->data();
				$filename = $userdata['file_name'];
				$data = array('upload_data' => $userdata);

				$is_new=$this->db->where('kode_r_perusahaan', $this->input->post('kode_r_perusahaan'))
				->get('tabel_scan_data_pengajuan_ap')->num_rows() <= 0?true:false;
				if($is_new)
				{
					$this->db->insert(
						'tabel_scan_data_pengajuan_ap'
						,array(
							'kode_r_perusahaan'=>$this->input->post('kode_r_perusahaan')
							,'path_scan_data'=>$filename
						)
					);
				}else{
					
				$this->db->where('kode_r_perusahaan', $this->input->post('kode_r_perusahaan'));
				$update = $this->db->update('tabel_scan_data_pengajuan_ap',
				array('path_scan_data'=>$filename));
				}
				
			 }
			 else
			 {
				$error=$this->upload->display_errors();
			 }

		if($this->irtp_model->edit_data_pengajuan())
		{
			echo json_encode(array('error'=>$error,'success'=>true));
		}
		else
		{
			echo json_encode(array('error'=>$error,'success'=>false));
		}
		
	}

	/* creates a compressed zip file */
	function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file,$file);
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			
			//close the zip -- done!
			$zip->close();
			
			//check to make sure the file exists
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}

	function download_zip()
	{
		$nomor_permohonan  = $this->uri->segment(3);

		// alur_produksi
		$this->db->select('tabel_pen_pengajuan_spp.nomor_permohonan, tabel_scan_data_pengajuan_ap.path_scan_data');
		$this->db->from('tabel_pen_pengajuan_spp');
		$this->db->join('tabel_scan_data_pengajuan_ap', 'tabel_scan_data_pengajuan_ap.kode_r_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan', 'inner');
		$this->db->where('tabel_pen_pengajuan_spp.nomor_permohonan', $nomor_permohonan);
		$ap = $this->db->get()->row();

		// rancangan_label
		$this->db->select('tabel_pen_pengajuan_spp.nomor_permohonan, tabel_scan_data_pengajuan_rl.path_scan_data');
		$this->db->from('tabel_pen_pengajuan_spp');
		$this->db->join('tabel_scan_data_pengajuan_rl', 'tabel_scan_data_pengajuan_rl.nomor_r_permohonan = tabel_pen_pengajuan_spp.nomor_permohonan', 'inner');
		$this->db->where('tabel_pen_pengajuan_spp.nomor_permohonan', $nomor_permohonan);
		$rl = $this->db->get()->row();

		// siup
		$this->db->select('tabel_pen_pengajuan_spp.nomor_permohonan, tabel_scan_data_pengajuan_siup.path_scan_data');
		$this->db->from('tabel_pen_pengajuan_spp');
		$this->db->join('tabel_scan_data_pengajuan_siup', 'tabel_scan_data_pengajuan_siup.kode_r_perusahaan = tabel_pen_pengajuan_spp.kode_r_perusahaan', 'inner');
		$this->db->where('tabel_pen_pengajuan_spp.nomor_permohonan', $nomor_permohonan);
		$siup = $this->db->get()->row();



		$ap_folder = "./uploads/alur_produksi/".$ap->path_scan_data;
		$rl_folder = "./uploads/rancangan_label/".$rl->path_scan_data;
		$siup_folder = "./uploads/siup/".$siup->path_scan_data;

		$files_to_zip = array(
			"uploads/alur_produksi/".$ap->path_scan_data,
			"uploads/rancangan_label/".$rl->path_scan_data,
			"uploads/siup/".$siup->path_scan_data
		);
		$result = $this->create_zip($files_to_zip,$nomor_permohonan.'.zip');


		$zipFilePath = './'.$nomor_permohonan.'.zip';



		$zipBaseName = basename($zipFilePath);

		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=$zipBaseName");
		header("Content-Length: " . filesize($zipFilePath));

		readfile($zipFilePath);
        unlink($zipFilePath);

	}

	function delete()
	    {
		    $param=array("id_pengajuan"=>$this->uri->segment(3));
    		$this->irtp_model->delete_pengajuan($param);
		    echo "
		    <script>
		    alert('Pengajuan Berhasil di Hapus.');
		    window.location.href='".base_url()."irtp/output_permohonan';
		    </script>";
	    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */