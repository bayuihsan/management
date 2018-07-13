<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_paket extends CI_Controller {

    // db2 digunakan untuk mengakses database ke-2
    private $db2;

    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in')==FALSE){
            redirect('User');    
        }
        // $this->db2 = $this->load->database('hvc',TRUE);
        $this->load->model(array('Kategoripaketmodel'));
    }
    
    public function index(){
        redirect('Kategori_paket/view');
    }
    
	public function view($action='')
	{
        checkPermission(4);
        $data=array();
        $data['kategori_paket']=$this->Kategoripaketmodel->get_all(); 
        if($action=='asyn'){
            $this->load->view('content/kategori_paket/list',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/kategori_paket/list',$data);
            $this->load->view('theme/include/footer');
        }
	}
    
    /** Method For Add New Account and Account Page View **/ 	
    public function add($action='',$param1='')
	{
        checkPermission(4);
        if($action=='asyn'){
        $this->load->view('content/kategori_paket/add');
        }else if($action==''){
            $this->load->view('theme/include/header');
    		$this->load->view('content/kategori_paket/add');
    		$this->load->view('theme/include/footer');
        }
        //----End Page Load------//
        //----For Insert update and delete-----// 
        if($action=='insert'){
            $data=array();
            $do=$this->input->post('action',true);     
            $data['nama_kategori']  =addslashes($this->input->post('nama_kategori',true)); 
            $data['harga_kategori'] =addslashes($this->input->post('harga_kategori',true));   
       
            //-----Validation-----//   
            $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'trim|required|xss_clean|min_length[4]');
            $this->form_validation->set_rules('harga_kategori', 'Harga Kategori', 'trim|required|xss_clean|min_length[3]');


            if (!$this->form_validation->run() == FALSE)
            {
                if($do=='insert'){ 

                    $this->db->insert('kategori_paket',$data); 
                    
                    echo "true";    
                    
                }else if($do=='update'){
                    $id=addslashes($this->input->post('id_kategori',true));
                    
                    $this->db->where('id_kategori', $id);
                    $this->db->update('kategori_paket', $data);

                    echo "true";
                    
                }         
            }else{
                //echo "All Field Must Required With Valid Length !";
                echo validation_errors('<span class="ion-android-alert failedAlert2"> ','</span>');

            }
            //----End validation----//         
        }
        else if($action=='remove'){    
            $this->db->delete('kategori_paket', array('id_kategori' => $param1));       
        }
	}

    /** Method For get branch information for Branch Edit **/ 
    public function edit($id_kategori,$action='')
    {
        checkPermission(4);
        $data=array();
        $data['edit_kategori']=getOld("id_kategori",$id_kategori,"kategori_paket"); 
        if($action=='asyn'){
            $this->load->view('content/kategori_paket/add',$data);
        }else if($action==''){
            $this->load->view('theme/include/header');
            $this->load->view('content/kategori_paket/add',$data);
            $this->load->view('theme/include/footer');
        }    
    }

    public function export($action=""){
        if($action=="asyn"){
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("ID", "NAMA_KATEGORI", "HARGA_KATEGORI", "UPDATED");

            $column = 0;

            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $kategori_paket = $this->Kategoripaketmodel->get_all();

            $excel_row = 2;

            foreach($kategori_paket as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, strtoupper($row->id_kategori));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->nama_kategori));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, get_current_setting('currency_code')." ".number_format($row->harga_kategori));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($row->last_update));
                $excel_row++;
            }

            $filename = "KategoriPaket-Exported-on-".date("Y-m-d-H-i-s").".xls";

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");;
            header("Content-Disposition: attachment;filename=$filename");
            $object_writer->save('php://output');
        }
        
    }
}