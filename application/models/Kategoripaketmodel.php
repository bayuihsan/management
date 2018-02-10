<?php
class Kategoripaketmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc', TRUE);
	}

	//get all Branch  
	public function get_all(){
		$this->db2->select('*');
		$this->db2->from('kategori_paket'); 
		$this->db2->order_by("id_kategori", "desc");    
		$query_result=$this->db2->get();
		$result=$query_result->result();
		return $result;
	}

	//get branch by id  
	public function get_kategori_paket_by_id($id_kategori){
		$this->db2->select('*');
		$this->db2->from('kategori_paket');
		$this->db2->where('id_kategori',$id_kategori);    
		$query_result=$this->db2->get();
		$result=$query_result->row();
		return $result;
	}
}