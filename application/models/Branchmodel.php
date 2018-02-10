<?php

class BranchModel extends CI_Model{
 
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
		$this->db2->from('branch');  
		$this->db2->order_by("branch_id", "desc");    
		$query_result=$this->db2->get();
		$result=$query_result->result();
		return $result;

	} 

	//get branch by id  
	public function get_branch_by_id($branch_id){
		$this->db2->select('*');
		$this->db2->from('branch');
		$this->db2->where('branch_id',$branch_id);    
		$query_result=$this->db2->get();
		$result=$query_result->row();
		return $result;
	} 
}