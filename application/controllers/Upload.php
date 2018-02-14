<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
	}
  
public function update()
{
  $fileName = $this->input->post('file', TRUE);

  $config['upload_path'] = './assets/excel/'; 
  $config['file_name'] = $fileName;
  $config['allowed_types'] = '*';
  $config['encrypt_name']= TRUE;
  $config['max_size'] = 10000000000;

  $this->load->library('upload', $config);
  $this->upload->initialize($config); 
  
  if (!$this->upload->do_upload('file')) {
   $error = $this->upload->display_errors();
   $this->session->set_flashdata('pesan',$error); 
   redirect(''); 
 } else {
   $media = $this->upload->data();
   $inputFileName = './assets/excel/'.$media['file_name'];
   
   try {
    $inputFileType = IOFactory::identify($inputFileName);
    $objReader = IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
  } catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
  }

  $sheet = $objPHPExcel->getSheet(0);
  $highestRow = $sheet->getHighestRow();
  $highestColumn = $sheet->getHighestColumn();

  for ($row = 2; $row <= $highestRow; $row++){  
   $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
     NULL,
     TRUE,
     FALSE);
   $data = array(
     "msisdn"=> trim(preg_replace("/[^a-zA-Z0-9]/", "", $rowData[0][0])), // opsional hapus spasi depan
     "tipe"=> $rowData[0][1],
     "id_users"=> $rowData[0][2],
     "status"=> $rowData[0][3]);
   $this->db->insert("msisdn",$data);
 } 
   unlink($inputFileName); // hapus file temp
   $count = $highestRow;
   $this->session->set_flashdata('pesan','Upload berhasil, Total: <b>'.$count.'</b> data.'); 
   redirect('');

 }
}
}