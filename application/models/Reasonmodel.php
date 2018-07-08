<?php

class Reasonmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all Branch  
	public function get_all(){
		$this->db->select('*');
		$this->db->from('reason');    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;

	} 

	//get branch by id  
	public function get_all_by($id_reason){
		$this->db->select('*');
		$this->db->from('reason'); 
		$this->db->where('id_reason',$id_reason);    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 
}