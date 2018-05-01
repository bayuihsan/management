<?php

class graparimodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all sales grapari  
	public function get_all(){
		$this->db->select('a.*, b.nama_branch');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');
		$this->db->order_by("a.nama_grapari", "asc");    
		$query_result=$this->db->get('grapari a');
		$result=$query_result->result();
		return $result;
	}

	//get sales grapari by id  
	public function get_grapari_by_id($id_grapari){
		$this->db->select('*');
		$this->db->from('grapari');
		$this->db->where('id_grapari',$id_grapari);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 

	//get sales grapari by id  
	public function get_all_by($branch_id){
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('grapari a');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');
		$this->db->where('a.branch_id',$branch_id);    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 
}