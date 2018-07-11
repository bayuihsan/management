<?php

class Targetsalesmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all sales target  
	public function get_all(){
		$this->db->select('a.*, b.nama_branch, c.nama');
		$this->db->join('branch b','b.branch_id=a.branch_id','left');
		$this->db->join('app_users c','c.id_users=a.updated_by','left');
		$this->db->order_by("b.nama_branch, a.bulan_target", "asc");    
		$query_result=$this->db->get('target_sales a');
		$result=$query_result->result();
		return $result;
	}

	//get sales target by id  
	public function get_target_by_id($id_target){
		$this->db->select('*');
		$this->db->from('target_sales');
		$this->db->where('id_target',$id_target);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 

	//get sales target by id  
	public function get_all_by($branch_id){
		$this->db->select('a.*, b.nama_branch, c.nama');
		$this->db->join('branch b','b.branch_id=a.branch_id','left');
		$this->db->join('app_users c','c.id_users=a.updated_by','left');
		$this->db->where('a.branch_id',$branch_id); 
		$this->db->order_by("b.nama_branch, a.bulan_target", "asc");    
		$query_result=$this->db->get('target_sales a');
		$result=$query_result->result();
		return $result;
		 
	} 

	public function cek_target($branch_id,$tahun_target,$bulan_target)
	{
		$this->db->select('*');
		$this->db->from('target_sales');
		$this->db->where('branch_id',$branch_id);
		$this->db->where('tahun_target',$tahun_target);
		$this->db->where('bulan_target',$bulan_target);
		$query=$this->db->get();
		$row_count=$query->num_rows();
		
		return $row_count;
	}	
}