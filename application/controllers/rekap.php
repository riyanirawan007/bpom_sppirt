<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require './helper/excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rekap extends CI_Controller 
{
    function __construct(){
      parent::__construct();
        $this->load->model('menu_model');	
        $this->load->model('rekap_irtp_model','rekap');
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
    } 
	
	function getPengajuan(){
		
		$prov=$this->input->get('prov');
		$kab=$this->input->get('kab');
		$per_prov=$this->input->get('per_prov');
		
		$params=array();
		if($prov!=null)$params['prov']=$prov;
		if($per_prov!=null)$params['per_prov']=$per_prov;
		if($kab!=null)$params['kab']=$kab;
		
        $this->json_response($this->rekap->get_pengajuan($params));
	}
	
    function pengajuan(){
        return view_dashboard('rekap/pengajuan');
    }
    
    function export_pengajuan(){
        $prov_name=$this->input->get('prov_name');
		$prov=$this->input->get('prov');
		
		$filename='Rekap Pengajuan IRTP';
		if($prov_name!=''){
			$filename.=' '.$prov_name;
		}
		
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1',$filename);
		$sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->mergeCells('A1:E1');
		
        $sheet->setCellValue('A3','No');
        $sheet->setCellValue('B3','Provinsi');
        $sheet->setCellValue('C3','Kabupaten');
        $sheet->setCellValue('D2','Jumlah Pengajuan SPP-IRTP');
		$sheet->mergeCells('D2:E2');
		$sheet->getStyle('D2:E2')->getFont()->setBold(true);
        $sheet->getStyle('D2:E2')->getFont()->setSize(12);
        $sheet->getStyle('D2:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('D3','Per Kabupaten');
        $sheet->setCellValue('E3','Per Provinsi');
		$sheet->getStyle('A3:E3')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getFont()->setSize(12);
        $sheet->getStyle('A3:E3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
		$start_row=4;
		$current_row=$start_row;
		$same_row=$start_row;
		$params=array();
		if($prov!=null) $params['prov']=$prov;
		
		$data=$this->rekap->get_pengajuan($params);
		$i=0;
		$same_row_start=$start_row;
		$same_row_end=$start_row;
		foreach($data as $d)
		{
			$sheet->setCellValue('A'.$current_row,($i+1));
			
			$prov1=$d['nama_propinsi'];
			$jum_prov=(int)$d['jumlah_per_prov'];
			if($prov!='')
			{
				if($i!=0)
				{
					$prov1='';
					$jum_prov='';
					$same_row_end+=1;
				}
			}
			else{
				if($i!=0)
				{
					if($data[$i-1]['nama_propinsi']==$d['nama_propinsi']){
						$prov1='';
						$jum_prov='';
						$same_row_end+=1;
					}
					else
					{
						$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
						$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
						$same_row_end=$current_row;
						$same_row_start=$current_row;
					}
				}
				else if($i==count($data))
				{
					$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
					$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
					$same_row_end=$current_row;
					$same_row_start=$current_row;
				}
			}
			
			$sheet->setCellValue('B'.$current_row,$prov1);
			$sheet->setCellValue('E'.$current_row,$jum_prov);
			
			$sheet->getStyle('B'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('B'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('B'.$current_row)->getFont()->setBold(true);
			
			$sheet->getStyle('E'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('E'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('E'.$current_row)->getFont()->setBold(true);
			
			$sheet->setCellValue('C'.$current_row,$d['nm_kabupaten']);
			$sheet->setCellValue('D'.$current_row,$d['jumlah_per_kab']);
			
			$current_row+=1;
			$i+=1;
		}
		
		$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
		$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
		
		$sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
		
		$sheet->getStyle('A2:E'.($current_row-1))
        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
		
		$writer = new Xlsx($spreadsheet);        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
	}
	
	function getPenerbitan(){
		
		$prov=$this->input->get('prov');
		$kab=$this->input->get('kab');
		$per_prov=$this->input->get('per_prov');
		
		$params=array();
		if($prov!=null)$params['prov']=$prov;
		if($per_prov!=null)$params['per_prov']=$per_prov;
		if($kab!=null)$params['kab']=$kab;
		
        $this->json_response($this->rekap->get_penerbitan($params));
	}
	
    function penerbitan(){
        return view_dashboard('rekap/penerbitan');
    }

    function export_penerbitan(){
		$prov_name=$this->input->get('prov_name');
		$prov=$this->input->get('prov');
		
		$filename='Rekap Penerbitan SPP-IRT' ;
		if($prov_name!=''){
			$filename.=' '.$prov_name;
		}
		
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

		
        $sheet->setCellValue('A1',$filename);
		$sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->mergeCells('A1:E1');
		
        $sheet->setCellValue('A3','No');
        $sheet->setCellValue('B3','Provinsi');
        $sheet->setCellValue('C3','Kabupaten');
        $sheet->setCellValue('D2','Jumlah Penerbitan SPP-IRTP');
		$sheet->mergeCells('D2:E2');
		$sheet->getStyle('D2:E2')->getFont()->setBold(true);
        $sheet->getStyle('D2:E2')->getFont()->setSize(12);
        $sheet->getStyle('D2:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('D3','Per Kabupaten');
        $sheet->setCellValue('E3','Per Provinsi');
		$sheet->getStyle('A3:E3')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getFont()->setSize(12);
        $sheet->getStyle('A3:E3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
		$start_row=4;
		$current_row=$start_row;
		$same_row=$start_row;
		
		$params=array();
		if($prov!=null) $params['prov']=$prov;
		
		$data=$this->rekap->get_penerbitan($params);
		$i=0;
		$same_row_start=$start_row;
		$same_row_end=$start_row;
		foreach($data as $d)
		{
			$sheet->setCellValue('A'.$current_row,($i+1));
			
			$prov1=$d['nama_propinsi'];
			$jum_prov=$d['jumlah_per_prov'];
			if($prov!='')
			{
				if($i!=0)
				{
					$prov1='';
					$jum_prov='';
					$same_row_end+=1;
				}
			}
			else{
				if($i!=0){
					if($data[$i-1]['nama_propinsi']==$d['nama_propinsi']){
						$prov1='';
						$jum_prov='';
						$same_row_end+=1;
					}
					else{
						$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
						$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
						$same_row_end=$current_row;
						$same_row_start=$current_row;
					}
				}
			}
			
			$sheet->setCellValue('B'.$current_row,$prov1);
			$sheet->setCellValue('E'.$current_row,$jum_prov);
			$sheet->getStyle('B'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('B'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('B'.$current_row)->getFont()->setBold(true);
			$sheet->getStyle('E'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('E'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('E'.$current_row)->getFont()->setBold(true);
			
			$sheet->setCellValue('C'.$current_row,$d['nm_kabupaten']);
			$sheet->setCellValue('D'.$current_row,$d['jumlah_per_kab']);
			
			$current_row+=1;
			$i+=1;
		}
		
		$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
		$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
		
		$sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
		
		$sheet->getStyle('A2:E'.($current_row-1))
        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
		
		$writer = new Xlsx($spreadsheet);        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
	}
	
	function getIRTP(){
		$prov=$this->input->get('prov');
		$kab=$this->input->get('kab');
		$per_prov=$this->input->get('per_prov');
		
		$params=array();
		if($prov!=null)$params['prov']=$prov;
		if($per_prov!=null)$params['per_prov']=$per_prov;
		if($kab!=null)$params['kab']=$kab;

        $this->json_response($this->rekap->get_irtp($params));
	}

    function irtp(){   
        return view_dashboard('rekap/irtp');
    }

    function export_irtp(){
		$prov_name=$this->input->get('prov_name');
		$prov=$this->input->get('prov');
		
		$filename='Rekap IRTP SPP-IRTP' ;
		if($prov_name!=''){
			$filename.=' '.$prov_name;
		}
		
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

		
        $sheet->setCellValue('A1',$filename);
		$sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->mergeCells('A1:E1');
		
        $sheet->setCellValue('A3','No');
        $sheet->setCellValue('B3','Provinsi');
        $sheet->setCellValue('C3','Kabupaten');
        $sheet->setCellValue('D2','Jumlah IRTP SPP-IRTP');
		$sheet->mergeCells('D2:E2');
		$sheet->getStyle('D2:E2')->getFont()->setBold(true);
        $sheet->getStyle('D2:E2')->getFont()->setSize(12);
        $sheet->getStyle('D2:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('D3','Per Kabupaten');
        $sheet->setCellValue('E3','Per Provinsi');
		$sheet->getStyle('A3:E3')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getFont()->setSize(12);
        $sheet->getStyle('A3:E3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
		$start_row=4;
		$current_row=$start_row;
		$same_row=$start_row;

		$params=array();
		if($prov!=null) $params['prov']=$prov;
		
		$data=$this->rekap->get_irtp($params);
		$i=0;
		$same_row_start=$start_row;
		$same_row_end=$start_row;
		foreach($data as $d)
		{
			$sheet->setCellValue('A'.$current_row,($i+1));
			
			$prov1=$d['nama_propinsi'];
			$jum_prov=$d['jumlah_per_prov'];
			if($prov!='')
			{
				if($i!=0)
				{
					$prov1='';
					$jum_prov='';
					$same_row_end+=1;
				}
			}
			else{
				if($i!=0){
					if($data[$i-1]['nama_propinsi']==$d['nama_propinsi']){
						$prov1='';
						$jum_prov='';
						$same_row_end+=1;
					}
					else{
						$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
						$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
						$same_row_end=$current_row;
						$same_row_start=$current_row;
					}
				}
			}
			
			$sheet->setCellValue('B'.$current_row,$prov1);
			$sheet->setCellValue('E'.$current_row,$jum_prov);
			$sheet->getStyle('B'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('B'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('B'.$current_row)->getFont()->setBold(true);
			$sheet->getStyle('E'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('E'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('E'.$current_row)->getFont()->setBold(true);
			
			$sheet->setCellValue('C'.$current_row,$d['nm_kabupaten']);
			$sheet->setCellValue('D'.$current_row,$d['jumlah_per_kab']);
			
			$current_row+=1;
			$i+=1;
		}
		
		$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
		$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
		
		$sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
		
		$sheet->getStyle('A2:E'.($current_row-1))
        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
		
		$writer = new Xlsx($spreadsheet);        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
	}


	function pelaksanaan_pkp(){
		return view_dashboard('rekap/pelaksanaan_pkp');
	}

    function getPelaksanaanPKP(){
		$params=array();
		$tahun=$this->input->get('tahun')!=null?$this->input->get('tahun'):null;
		$prov=$this->input->get('prov')!=null?$this->input->get('prov'):null;
		$kab=$this->input->get('kab')!=null?$this->input->get('kab'):null;
		$per_prov=$this->input->get('per_prov')!=null?$this->input->get('per_prov'):null;
		if($tahun!=null)
		{
			$params['tahun']=$tahun;
		}
		if($prov!=null)
		{
			$params['prov']=$prov;
		}
		if($kab!=null)
		{
			$params['kab']=$kab;
		}
		if($per_prov!=null)
		{
			$params['per_prov']=$per_prov;
		}

		$this->json_response($this->rekap->get_pelaksanaan_pkp($params));
	}
	
	function getTahunPKP(){
		$this->json_response($this->rekap->get_tahun_pkp());
	}

    function export_pkp(){
		$prov_name=$this->input->get('prov_name');
		$prov=$this->input->get('prov');
		$tahun_name=$this->input->get('tahun_name');
		$tahun=$this->input->get('tahun');
		
		$filename='Rekap Pelaksanaan PKP' ;
		if($prov!=''){
			$filename.=' '.$prov_name;
		}
		if($tahun_name!=''){
			$filename.=' '.$tahun_name;
		}
		
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

		
        $sheet->setCellValue('A1',$filename);
		$sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->mergeCells('A1:E1');
		
        $sheet->setCellValue('A3','No');
        $sheet->setCellValue('B3','Provinsi');
        $sheet->setCellValue('C3','Kabupaten/Kota');
        $sheet->setCellValue('D2','Jumlah Pelaksanaan');
		$sheet->mergeCells('D2:E2');
        $sheet->setCellValue('F2','Jumlah Peserta');
		$sheet->mergeCells('F2:G2');
		$sheet->getStyle('D2:G2')->getFont()->setBold(true);
        $sheet->getStyle('D2:G2')->getFont()->setSize(12);
        $sheet->getStyle('D2:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('D3','Per Kabupaten');
		$sheet->setCellValue('E3','Per Provinsi');
		$sheet->setCellValue('F3','Per Kabupaten');
        $sheet->setCellValue('G3','Per Provinsi');
		$sheet->getStyle('A3:G3')->getFont()->setBold(true);
        $sheet->getStyle('A3:G3')->getFont()->setSize(12);
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
		$start_row=4;
		$current_row=$start_row;
		$same_row=$start_row;
		$params=array();

		if($prov!='' && $prov!=null)
		{
			$params['prov']=$prov;
		}
		
		if($tahun!='' && $tahun!=null)
		{
			$params['tahun']=$tahun;
		}
		
		$data=$this->rekap->get_pelaksanaan_pkp($params);
		$i=0;
		$same_row_start=$start_row;
		$same_row_end=$start_row;
		foreach($data as $d)
		{
			$sheet->setCellValue('A'.$current_row,($i+1));
			
			$prov1=$d['nama_propinsi'];
			$jum_prov=$d['jumlah_per_prov'];
			$jum_prov_peserta=$d['jumlah_peserta_per_prov'];
			if($prov!='')
			{
				if($i!=0)
				{
					$prov1='';
					$jum_prov='';
					$jum_prov_peserta='';
					$same_row_end+=1;
				}
			}
			else{
				if($i!=0){
					if($data[$i-1]['nama_propinsi']==$d['nama_propinsi']){
						$prov1='';
						$jum_prov='';
						$jum_prov_peserta='';
						$same_row_end+=1;
					}
					else{
						$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
						$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
						$sheet->mergeCells('G'.$same_row_start.':G'.$same_row_end);
						$same_row_end=$current_row;
						$same_row_start=$current_row;
					}
				}
			}
			
			$sheet->setCellValue('B'.$current_row,$prov1);
			$sheet->setCellValue('E'.$current_row,$jum_prov);
			$sheet->setCellValue('G'.$current_row,$jum_prov_peserta);
			$sheet->getStyle('B'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('B'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('B'.$current_row)->getFont()->setBold(true);
			$sheet->getStyle('E'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('E'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('E'.$current_row)->getFont()->setBold(true);
			$sheet->getStyle('G'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('G'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('G'.$current_row)->getFont()->setBold(true);
			
			
			$sheet->setCellValue('C'.$current_row,$d['nm_kabupaten']);
			$sheet->setCellValue('D'.$current_row,$d['jumlah_per_kab']);
			$sheet->setCellValue('F'.$current_row,$d['jumlah_peserta_per_kab']);
			
			$current_row+=1;
			$i+=1;
		}
		
		$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
		$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
		$sheet->mergeCells('G'.$same_row_start.':G'.$same_row_end);
		
		$sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
		
		$sheet->getStyle('A2:G'.($current_row-1))
        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
		
		$writer = new Xlsx($spreadsheet);        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
	}


	function getGrupJenisPangan()
	{
		$id=$this->input->get('id');
		$params=array();
		if($id!=null){
			$params['kode_grup_jenis_pangan']=$id;
		}

		$this->json_response($this->rekap->get_grup_jenis_pangan($params));
	}

	function getJenisPangan()
	{
		$id=$this->input->get('id');
		$grup=$this->input->get('grup');
		$params=array();
		if($id!=null){
			$params['id_urut_jenis_pangan']=$id;
		}
		if($grup!=null){
			$params['kode_r_grup_jenis_pangan']=$grup;
		}

		$this->json_response($this->rekap->get_jenis_pangan($params));
	}

	function getJenisPanganSPPIRT(){

		$grup=$this->input->get('grup');
		$jenis=$this->input->get('jenis');
		
		$grup=($grup!=null)?$grup:01;
		
        $prov=$this->input->get('prov');
		$kab=$this->input->get('kab');
		$per_prov=$this->input->get('per_prov');

		$params=array();

		if($jenis!=null) $params['jenis']=$jenis;
		if($grup!=null) $params['grup']=$grup;
		if($prov!=null) $params['prov']=$prov;
		if($kab!=null) $params['kab']=$kab;
		if($per_prov!=null) $params['per_prov']=$per_prov;
		$data=array();

		$data['grup']=$this->rekap->get_grup_jenis_pangan(array('kode_grup_jenis_pangan'=>$grup));
		$params1=array();
		$params1['kode_r_grup_jenis_pangan']=$grup;
		$params1['id_urut_jenis_pangan']=($jenis==null?'null':$jenis);
		$data['jenis']=$this->rekap->get_jenis_pangan($params1);
		
		$this->json_response($this->rekap->get_sppirt_jenis_pangan($params),$data);
	}
	
    function sppirt_jenis_pangan(){
		return view_dashboard('rekap/jenis_pangan');
	}
	
	function export_jenis_pangan(){

		$prov_name=$this->input->get('prov_name');
		$prov=$this->input->get('prov');
		$grup_name=$this->input->get('grup_name');
		$grup=$this->input->get('grup');
		$jenis_name=$this->input->get('jenis_name');
		$jenis=$this->input->get('jenis');
		
		$filename='Rekap SPP-IRTP Per Jenis Pangan';
		if($prov!=''){
			$filename.=' '.$prov_name;
		}
		if($grup!=''){
			$filename.=' '.$grup_name;
		}
		if($jenis_name!=''){
			$filename.=' - '.$jenis_name;
		}
		
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1',$filename);
		$sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->mergeCells('A1:E1');
		
        $sheet->setCellValue('A3','No');
        $sheet->setCellValue('B3','Provinsi');
        $sheet->setCellValue('C3','Kabupaten');
        $sheet->setCellValue('D2','Jumlah');
		$sheet->mergeCells('D2:E2');
		$sheet->getStyle('D2:E2')->getFont()->setBold(true);
        $sheet->getStyle('D2:E2')->getFont()->setSize(12);
        $sheet->getStyle('D2:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('D3','Per Kabupaten');
        $sheet->setCellValue('E3','Per Provinsi');
		$sheet->getStyle('A3:E3')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getFont()->setSize(12);
        $sheet->getStyle('A3:E3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
		$start_row=4;
		$current_row=$start_row;
		$same_row=$start_row;
		$params=array();
		if($jenis!=null) $params['jenis']=$jenis;
		if($grup!=null) $params['grup']=$grup;
		if($prov!=null) $params['prov']=$prov;
		
		$data=$this->rekap->get_sppirt_jenis_pangan($params);
		$i=0;
		$same_row_start=$start_row;
		$same_row_end=$start_row;
		foreach($data as $d)
		{
			$sheet->setCellValue('A'.$current_row,($i+1));
			
			$prov1=$d['nama_propinsi'];
			$jum_prov=(int)$d['jumlah_per_prov'];
			if($prov!='')
			{
				if($i!=0)
				{
					$prov1='';
					$jum_prov='';
					$same_row_end+=1;
				}
			}
			else{
				if($i!=0)
				{
					if($data[$i-1]['nama_propinsi']==$d['nama_propinsi']){
						$prov1='';
						$jum_prov='';
						$same_row_end+=1;
					}
					else
					{
						$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
						$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
						$same_row_end=$current_row;
						$same_row_start=$current_row;
					}
				}
				else if($i==count($data))
				{
					$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
					$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
					$same_row_end=$current_row;
					$same_row_start=$current_row;
				}
			}
			
			$sheet->setCellValue('B'.$current_row,$prov1);
			$sheet->setCellValue('E'.$current_row,$jum_prov);
			
			$sheet->getStyle('B'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('B'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('B'.$current_row)->getFont()->setBold(true);
			
			$sheet->getStyle('E'.$current_row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
			$sheet->getStyle('E'.$current_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('E'.$current_row)->getFont()->setBold(true);
			
			$sheet->setCellValue('C'.$current_row,$d['nm_kabupaten']);
			$sheet->setCellValue('D'.$current_row,$d['jumlah_per_kab']);
			
			$current_row+=1;
			$i+=1;
		}
		
		$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
		$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
		
		$sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
		
		$sheet->getStyle('A2:E'.($current_row-1))
        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
		
		$writer = new Xlsx($spreadsheet);        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
	}


    function getProv()
    {
        $id=$this->input->get('id')!=''?$this->input->get('id'):null;
        $params=array(
            'no_kode_propinsi'=>$id
        );
        $this->json_response($this->rekap->get_prov($params));
    }

    function getKabKota(){
        $id_prov=$this->input->get('prov')!=''?$this->input->get('prov'):'null';
        $params=array(
            'no_kode_propinsi'=>$id_prov
        );
        $this->json_response($this->rekap->get_kab($params));
    }

    function json_response($data=array(),$head=array(),$success=true,$status=200)
    {
        $response=array(
            'success'=>$success,
            'status'=>$status,
			'count_data'=>count($data),
			'head'=>$head,
            'data'=>$data
        );
        
        echo json_encode($response);
    }
}