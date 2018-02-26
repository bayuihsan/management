<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->library(array('PHPExcel','PHPExcel2/IOFactory'));
  }
 public function index()
 { $data['title']='<title> Upload axcel -> mysql</title>';
}
public function update()
{
  $fileName = $this->input->post('file', TRUE);

  $config['upload_path'] = './assets/excel/'; 
  $config['file_name'] = $fileName;
  $config['allowed_types'] = '*';
  $config['encrypt_name']= TRUE;
  $config['max_size'] = 0;

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
     "order_id"=>$rowData[0][0],
     "order_submit_date"=>str_replace("'", "", $rowData[0][1]),
     "order_completed_date"=>str_replace("'", "", $rowData[0][2]),
     "msisdn_ctp"=>$rowData[0][3],
     "nama_pelanggan_ctp"=>$rowData[0][4],
     "order_type_name"=>$rowData[0][5],
     "user_id"=>$rowData[0][6],
     "employee_name"=>$rowData[0][7],
     "paket_id"=>$rowData[0][8],
     "id_users"=>$this->input->post('id_users_up'),
     "branch_id"=>$this->input->post('branch_id_up'),
     "tgl_upload"=>date('Y-m-d h:i:s'));
        $msisdn_ctp = $rowData[0][3];
        $cek_msisdn =  $this->db->query("select count(msisdn_ctp) jml from ctp where msisdn_ctp='$msisdn_ctp'")->row();
        if($cek_msisdn->jml<1){
        $insert = $this->db->insert("ctp",$data);
    }
 } 
   unlink($inputFileName); // hapus file temp
   $count = $highestRow;
   $this->session->set_flashdata('pesan','Upload berhasil, Total: <b>'.$count.'</b> data.'); 
   redirect('ctp/view');

 }
}
}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */