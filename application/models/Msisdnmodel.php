<?php

class Msisdnmodel extends CI_Model{
 
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
		$this->db->from('msisdn');  
		$this->db->order_by("id_haloinstan", "desc");    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 

	//get branch by id  
	public function get_msisdn_by_id($id_haloinstan){
		$this->db->select('*');
		$this->db->from('msisdn');
		$this->db->where('id_haloinstan',$id_haloinstan);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}