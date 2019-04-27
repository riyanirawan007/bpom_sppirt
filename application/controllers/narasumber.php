<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Narasumber extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('lib_admin');
		$this->load->model('narasumber_model');
		$this->load->model('narasumber_master_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('menu_model');	
		//$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
		if($this->session->userdata('user_token')){
			$data['narasumber'] = $this->narasumber_model->view()->result();
			$this->lib_admin->display('narasumber/view',$data);
		} else {
			return redirect('home/index');
		}
	}

	public function narasumber_list()
	{
		$list = $this->narasumber_model->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $narasumber_model) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $narasumber_model->nama_narasumber;
			$row[] = $narasumber_model->nm_jabatan;
			$row[] = $narasumber_model->nama_instansi;
			$row[] = $narasumber_model->no_tlp_kantor;
			$row[] = $narasumber_model->alamat_kantor;
			$row[] = $narasumber_model->alamat_pribadi;

			$row[] = '<a class="green" href="'.base_url().'narasumber/edit/'.$narasumber_model->kode_narasumber.'" title="Edit"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
			<a class="red" href="'.base_url().'narasumber/delete/'.$narasumber_model->kode_narasumber.'" title="Hapus" onclick="return confirm(\'Hapus data ini?\')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->narasumber_model->count_all(),
			"recordsFiltered" => $this->narasumber_model->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function data()
	{
		$narasumber = $this->narasumber_model->get_all();
		$arr = array();
		$arr['data'] = array();
		if(!empty($narasumber)):
			$no = 1;
			foreach($narasumber as $row):
				$aksi = '<a class="green" href="'.base_url().'narasumber/edit/'.$row['kode_narasumber'].'" title="Edit"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
				<a class="red" href="'.base_url().'narasumber/delete/'.$row['kode_narasumber'].'" title="Hapus" onclick="return confirm(\'Hapus data ini?\')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
				$arr['data'][] = array(
					$no,
					$row['nama_narasumber'],
					$row['nm_jabatan'],
					$row['nama_instansi'],
					$row['no_tlp_kantor'],
					$row['alamat_kantor'],
					$row['status'],
					$aksi
				);
				$no++;
			endforeach;
		endif;
		$json = json_encode($arr);
		echo $json;
	}

	function add()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('nama_narasumber', 'nama_narasumber', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('narasumber/add');
				} else {
					$config['allowed_types'] = 'png|gif|jpg|jpeg|docx|doc|pdf';
					$config['upload_path']  = './dok_narasumber/';
					$config['remove_space'] = TRUE;
					$this->load->library('upload', $config);
					
					if ($_FILES['ket_lulus_tdk'] != "") {
						$this->upload->do_upload('ket_lulus_tdk');
						$ket_lulus_tdk = $_FILES['ket_lulus_tdk']['name'];
					} else {
						$ket_lulus_tdk = "";
					}

					if ($_FILES['file_sertifikat'] != "") {
						$this->upload->do_upload('file_sertifikat');
						$file_sertifikat = $_FILES['file_sertifikat']['name'];
					} else {
						$file_sertifikat = "";
					}
					$idb = $this->input->post('idb');
					$tot = $this->input->post('tot');
					$nama_narasumber = $this->input->post('nama_narasumber');
					$nip_pkp_dfi = $this->input->post('nip_pkp_dfi');
					$tpt_tgl_lahir = $this->input->post('tpt_tgl_lahir');
					$tingkat_pendidikan = $this->input->post('tingkat_pendidikan');
					$nm_jabatan = $this->input->post('nm_jabatan');
					$nm_golongan = $this->input->post('nm_golongan');
					$nama_instansi = $this->input->post('nama_instansi');
					$idk = $this->input->post('idk');
					$alamat_kantor = $this->input->post('alamat_kantor');
					$alamat_pribadi = $this->input->post('alamat_pribadi');
					$no_tlp_kantor = $this->input->post('no_tlp_kantor');
					$no_fax_kantor = $this->input->post('no_fax_kantor');
					$no_tlp_pribadi = $this->input->post('no_tlp_pribadi');
					$hp = $this->input->post('hp');
					$email_kantor = $this->input->post('email_kantor');
					$th = $this->input->post('th');
					$kompetensi = $this->input->post('kompetensi');
					$sertifikat = $this->input->post('sertifikat');

					$status = $this->input->post('status');
					$selected_status = "";
					foreach ($status as $t) {
						$selected_status .=  $t.", ";
					}
					$selected_status = substr($selected_status,0,-2);

					$data = array(
						'idb' => $idb
						,'tot' => $tot
						,'nama_narasumber' => $nama_narasumber
						,'nip_pkp_dfi' => $nip_pkp_dfi
						,'tpt_tgl_lahir' => $tpt_tgl_lahir
						,'tingkat_pendidikan' => $tingkat_pendidikan
						,'nm_jabatan' => $nm_jabatan
						,'nm_golongan' => $nm_golongan
						,'nama_instansi' => $nama_instansi
						,'idk' => $idk
						,'alamat_kantor' => $alamat_kantor
						,'alamat_pribadi' => $alamat_pribadi
						,'no_tlp_kantor' => $no_tlp_kantor
						,'no_fax_kantor' => $no_fax_kantor
						,'no_tlp_pribadi' => $no_tlp_pribadi
						,'hp' => $hp
						,'email_kantor' => $email_kantor
						,'th' => $th
						,'kompetensi' => $kompetensi
						,'ket_lulus_tdk' => $ket_lulus_tdk
						,'sertifikat' => $sertifikat
						,'file_sertifikat' => $file_sertifikat
						,'status' => $selected_status
					);
					$insert = $this->db->insert('tabel_narasumber', $data);
					redirect('narasumber','refresh');
				}
				
			} else {
				$data['tp'] = $this->narasumber_master_model->tingkat_pendidikan_view()->result();
				$data['j'] = $this->narasumber_master_model->jabatan_view()->result();
				$data['g'] = $this->narasumber_master_model->golongan_view()->result();
				$data['i'] = $this->narasumber_master_model->instansi_view()->result();
				$this->lib_admin->display('narasumber/add',$data);
			}
			
		} else {
			return redirect('home/index');
		}	
	}

	function edit()
	{
		if($this->session->userdata('user_token')){
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('nama_narasumber', 'nama_narasumber', 'required');
				if ($this->form_validation->run() == FALSE)	{
					$this->session->set_flashdata('errors', validation_errors());
					redirect('narasumber/edit');
				} else {
					$config['allowed_types'] = 'png|gif|jpg|jpeg|docx|doc|pdf';
					$config['upload_path']  = './dok_narasumber/';
					$config['remove_space'] = TRUE;
					$this->load->library('upload', $config);

					if ($_FILES['ket_lulus_tdk'] != "") {
						$this->upload->do_upload('ket_lulus_tdk');
						$ket_lulus_tdk = $_FILES['ket_lulus_tdk']['name'];
					} else {
						$ket_lulus_tdk = $this->input->post('old_ket_lulus_tdk');
					}

					if ($_FILES['file_sertifikat'] != "") {
						$this->upload->do_upload('file_sertifikat');
						$file_sertifikat = $_FILES['file_sertifikat']['name'];
					} else {
						$file_sertifikat = $this->input->post('old_file_sertifikat');
					}
					$kode_narasumber = $this->input->post('kode_narasumber');
					$idb = $this->input->post('idb');
					$tot = $this->input->post('tot');
					$nama_narasumber = $this->input->post('nama_narasumber');
					$nip_pkp_dfi = $this->input->post('nip_pkp_dfi');
					$tpt_tgl_lahir = $this->input->post('tpt_tgl_lahir');
					$tingkat_pendidikan = $this->input->post('tingkat_pendidikan');
					$nm_jabatan = $this->input->post('nm_jabatan');
					$nm_golongan = $this->input->post('nm_golongan');
					$nama_instansi = $this->input->post('nama_instansi');
					$idk = $this->input->post('idk');
					$alamat_kantor = $this->input->post('alamat_kantor');
					$alamat_pribadi = $this->input->post('alamat_pribadi');
					$no_tlp_kantor = $this->input->post('no_tlp_kantor');
					$no_fax_kantor = $this->input->post('no_fax_kantor');
					$no_tlp_pribadi = $this->input->post('no_tlp_pribadi');
					$hp = $this->input->post('hp');
					$email_kantor = $this->input->post('email_kantor');
					$th = $this->input->post('th');
					$kompetensi = $this->input->post('kompetensi');
					$sertifikat = $this->input->post('sertifikat');

					$status = $this->input->post('status');
					$selected_status = "";
					foreach ($status as $t) {
						$selected_status .=  $t.", ";
					}
					$selected_status = substr($selected_status,0,-2);

					$data = array(
						'idb' => $idb
						,'tot' => $tot
						,'nama_narasumber' => $nama_narasumber
						,'nip_pkp_dfi' => $nip_pkp_dfi
						,'tpt_tgl_lahir' => $tpt_tgl_lahir
						,'tingkat_pendidikan' => $tingkat_pendidikan
						,'nm_jabatan' => $nm_jabatan
						,'nm_golongan' => $nm_golongan
						,'nama_instansi' => $nama_instansi
						,'idk' => $idk
						,'alamat_kantor' => $alamat_kantor
						,'alamat_pribadi' => $alamat_pribadi
						,'no_tlp_kantor' => $no_tlp_kantor
						,'no_fax_kantor' => $no_fax_kantor
						,'no_tlp_pribadi' => $no_tlp_pribadi
						,'hp' => $hp
						,'email_kantor' => $email_kantor
						,'th' => $th
						,'kompetensi' => $kompetensi
						,'ket_lulus_tdk' => $ket_lulus_tdk
						,'sertifikat' => $sertifikat
						,'file_sertifikat' => $file_sertifikat
						,'status' => $selected_status
					);
					$this->db->where('kode_narasumber', $kode_narasumber);
					$this->db->update('tabel_narasumber', $data);
					redirect('narasumber','refresh');
				}

			} else {
				$id = $this->uri->segment(3);
				$data['narasumber'] = $this->narasumber_model->select($id)->row();
				$data['tp'] = $this->narasumber_master_model->tingkat_pendidikan_view()->result();
				$data['j'] = $this->narasumber_master_model->jabatan_view()->result();
				$data['g'] = $this->narasumber_master_model->golongan_view()->result();
				$data['i'] = $this->narasumber_master_model->instansi_view()->result();
				$this->lib_admin->display('narasumber/edit',$data);
			}

		} else {
			return redirect('home/index');
		}	
	}

	function delete()
	{
		$id = $this->uri->segment(3);
		$this->db->where('kode_narasumber', $id);
		$delete = $this->db->delete('tabel_narasumber');
		if ($delete) {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Berhasil diproses</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-info">Gagal diproses</div>');
		}
		redirect('narasumber');	
	}


}

/* End of file narasumber.php */
/* Location: ./application/controllers/narasumber.php */