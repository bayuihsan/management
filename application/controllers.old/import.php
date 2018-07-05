<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Author : Ismo Broto : git @ismo1106
 */

class Import extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        if (!logged_in())
            redirect('auth/login');
        
        $this->load->library('PHPExcel');
    }

    function index() {
        $msg    = $this->uri->segment(3);
        $alert  = '';
        if($msg == 'success'){
            $alert  = 'Success!!';
        }
        $data['_alert'] = $alert;
        $this->load->view('import-index',$data);
    }

    function upload() {
        $fileName = time() . $_FILES['fileImport']['name'];                     // Sesuai dengan nama Tag Input/Upload

        $config['upload_path'] = './fileExcel/';                                // Buat folder dengan nama "fileExcel" di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['max_size'] = 10000;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('fileImport'))
            $this->upload->display_errors();

        $media = $this->upload->data('fileImport');
        $inputFileName = './fileExcel/' . $media['file_name'];

        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 2; $row <= $highestRow; $row++) {                           // Read a row of data into an array                 
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            
            $data = array(                                                      // Sesuaikan sama nama kolom tabel di database
                "nama" => $rowData[0][1],
                "alamat" => $rowData[0][2],
                "kontak" => $rowData[0][3]
            );
            
            $insert = $this->db->insert("data_orang", $data);                   // Sesuaikan nama dengan nama tabel untuk melakukan insert data
            delete_files($media['file_path']);                                  // menghapus semua file .xls yang diupload
        }
        
        redirect(base_url('import/index/success'));
    }

}
