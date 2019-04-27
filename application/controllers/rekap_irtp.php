<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require './helper/excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Rekap_irtp extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('menu_model');	
		$this->load->model('rekap_irtp_model','rekap_irtp');
		//$this->preventCache();
		// Automatic detection for user logged in and menu authority
		if(checkUserAuthorize()) checkMenuAuthority();
	}

	public function index()
	{
			//$data['rekap_pengajuan'] = $this->rekap_pengajuan_model->view()->result();
			return view_dashboard('rekap_irtp/view');
		
	}


	public function get(){
		$prov=$this->input->get('prov')!=''?$this->input->get('prov'):null;
		$kab=$this->input->get('kab')!=''?$this->input->get('kab'):null;
		$jenis_pangan=$this->input->get('jenis_pangan')!=''?$this->input->get('jenis_pangan'):null;
		$per_prov=$this->input->get('per_prov')!=''?$this->input->get('per_prov'):null;
		$params=array(
		'prov'=>$prov,
		'kab'=>$kab,
		'jenis_pangan'=>$jenis_pangan,
		'per_prov'=>$per_prov
		);
		
		echo json_encode($this->rekap_irtp->get_irtp($params));
	}

	public function rekap(){
		$prov_name=$this->input->get('prov_name');
		$prov=$this->input->get('prov');
		$kab_name=$this->input->get('kab_name');
		$kab=$this->input->get('kab');
		$jenis_pangan=null;
		
		$filename='Rekap IRTP IRTP' ;
		if($prov!=''){
			$filename.=' '.$prov_name;
		}
		if($kab!=''){
			$filename.=' '.$kab_name;
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
		$params=array(
		'prov'=>$prov,
		'kab'=>$kab,
		'jenis_pangan'=>$jenis_pangan
		);
		
		$data=$this->rekap_irtp->get_irtp($params);
		$i=0;
		$same_row_start=$start_row;
		$same_row_end=$start_row;
		foreach($data as $d)
		{
			$sheet->setCellValue('A'.$current_row,$d['no']);
			
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
			$sheet->setCellValue('D'.$current_row,$d['jumlah']);
			
			$current_row+=1;
			$i+=1;
		}
		
		if($prov!=''){
			
			$sheet->mergeCells('B'.$same_row_start.':B'.$same_row_end);
			$sheet->mergeCells('E'.$same_row_start.':E'.$same_row_end);
		}
		
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


}

/* End of file Rekap_pengajuan.php */
/* Location: ./application/controllers/Rekap_pengajuan.php */