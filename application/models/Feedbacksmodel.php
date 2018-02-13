<?php

class Feedbacksmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all Branch  
	public function get_all(){
		$this->db->select('*');
		$this->db->from('feedback');    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	}

	public function get_datapaket(){
		$this->db->select('*');
		$this->db->from('kategori_paket');     
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 

	//get branch by id  
	public function get_paket_by_id($paket_id){
		$this->db->select('*');
		$this->db->from('paket');
		$this->db->where('paket_id',$paket_id);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}