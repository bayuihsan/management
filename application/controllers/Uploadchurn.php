<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadmsisdn extends CI_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->library(array('PHPExcel','PHPExcel2/IOFactory'));
  }

  public function index()
  { 
    $data['title']='<title> Upload axcel -> mysql</title>';
  }

  public function update()
  {
    $fileName = $this->input->post('file', TRUE);

    $config['upload_path'] = './assets/excel/'; 
    $config['file_name'] = $fileName;
    $config['allowed_types'] = '*';
    $config['encrypt_name']= TRUE;
    $config['max_size'] = 10000000;

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
      $no=0;
      for ($row = 2; $row <= $highestRow; $row++){  
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                        NULL,
                                        TRUE,
                                        FALSE);
   $data = array(
     "branch_id"=>$rowData[0][0],
     "tanggal_churn"=>$rowData[0][1],
     "nilai_churn"=>$rowData[0][2],
     "updated_by"=>$this->input->post('id_users_up'),
     "tanggal_update"=>date('Y-m-d h:i:s')
   );
      $insert = $this->db->insert("churn",$data);
    } 
      unlink($inputFileName); // hapus file temp
      $count = $no;
      $this->session->set_flashdata('pesan','Upload Data Berhasil, Total: <b>'.$count.'</b> data.'); 
      redirect('msisdn/view');
    }
  }
}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */