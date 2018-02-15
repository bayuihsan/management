<?php

class Ctpmodel extends CI_Model{
 
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
		$this->db->from('ctp');  
		$this->db->order_by("id_ctp", "desc");    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	}

	public function get_datafeedback(){
		$this->db->select('*');
		$this->db->from('ctp');     
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 

	//get branch by id  
	public function get_ctp_by_id($id_ctp){
		$this->db->select('*');
		$this->db->from('ctp');
		$this->db->where('id_ctp',$id_ctp);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}