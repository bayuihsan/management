<?php

class Regionmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    // $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all region  
	public function get_all(){
		$this->db->select('a.*, b.nama');
		$this->db->from('region a');  
		$this->db->join('app_users b', 'b.id_users = a.update_by', 'left');
		$this->db->where('a.status_region','1');  
		$this->db->order_by("a.nama_region", "asc");    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;

	} 

	//get region by id  
	public function get_all_by($region_id){
		$this->db->select('*');
		$this->db->from('region');
		$this->db->where('status_region','1'); 
		$this->db->where('id_region',$region_id);    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;
	} 

	//get region by id  
	public function get_region_by_id($region_id){
		$this->db->select('*');
		$this->db->from('region');
		$this->db->where('status_region','1'); 
		$this->db->where('id_region',$region_id);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 
}