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
          "msisdn"=>$rowData[0][0],
          "tipe"=>$rowData[0][1],
          "id_users"=>$this->input->post('id_users_up'),
          "branch_id"=>$this->input->post('branch_id_up'),
          "tanggal"=>date('Y-m-d h:i:s')
        );
        $msisdn = $rowData[0][0];
        $cek_msisdn =  $this->db->query("select count(msisdn) jml from msisdn where msisdn='$msisdn'")->row();
        if($cek_msisdn->jml<1){
          $insert = $this->db->insert("msisdn",$data);     
        }
        $no = $no+1;
      } 
      unlink($inputFileName); // hapus file temp
      $count = $no;
      $this->session->set_flashdata('pesan','Upload berhasil, Total: <b>'.$count.'</b> data.'); 
      redirect('msisdn/view');
    }
  }
}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */