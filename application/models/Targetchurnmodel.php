<?php

class Targetchurnmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all churn target  
	public function get_all(){
		$this->db->select('a.*, b.nama_region, c.nama');
		$this->db->join('region b','b.id_region=a.id_region','left');
		$this->db->join('app_users c','c.id_users=a.updated_by','left');
		$this->db->order_by("b.nama_region, a.bulan_target_churn", "asc");    
		$query_result=$this->db->get('target_churn a');
		$result=$query_result->result();
		return $result;
	}

	//get churn target by id  
	public function get_target_by_id($id_target){
		$this->db->select('*');
		$this->db->from('target_churn');
		$this->db->where('id_target_churn',$id_target);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 

	//get churn target by id  
	public function get_all_by($id_region){
		$this->db->select('a.*, b.nama_region, c.nama');
		$this->db->join('region b','b.id_region=a.id_region','left');
		$this->db->join('app_users c','c.id_users=a.updated_by','left');
		$this->db->where('a.id_region',$id_region); 
		$this->db->order_by("b.nama_region, a.bulan_target_churn", "asc");    
		$query_result=$this->db->get('target_churn a');
		$result=$query_result->result();
		return $result;
		 
	} 

	public function cek_target($id_region,$tahun_target,$bulan_target)
	{
		$this->db->select('*');
		$this->db->from('target_churn');
		$this->db->where('id_region',$id_region);
		$this->db->where('tahun_target_churn',$tahun_target);
		$this->db->where('bulan_target_churn',$bulan_target);
		$query=$this->db->get();
		$row_count=$query->num_rows();
		
		return $row_count;
	}	
}