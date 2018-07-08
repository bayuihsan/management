<?php

class Branchmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all Branch  
	public function get_all(){
		$this->db->select('a.*, b.nama_region');
		$this->db->from('branch a');
		$this->db->join('region b', 'b.id_region = a.id_region','LEFT');  
		$this->db->where('a.status','1');  
		$this->db->order_by("a.nama_branch", "asc");    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;

	} 

	//get branch by id  
	public function get_all_by($branch_id){
		$this->db->select('*');
		$this->db->from('branch');
		$this->db->where('status','1'); 
		$this->db->where('branch_id',$branch_id);    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 

	//get branch by id  
	public function get_branch_by_id($branch_id){
		$this->db->select('*');
		$this->db->from('branch');
		$this->db->where('status','1'); 
		$this->db->where('branch_id',$branch_id);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}