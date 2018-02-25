<?php

class bastmodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc', TRUE);
	}

	//get all bast 
	public function get_all(){
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, d.nama, count(c.psb_id) jumlah
			FROM bast_header a 
			JOIN branch b ON a.branch_id=b.branch_id
			LEFT JOIN new_psb c ON a.no_bast=c.no_bast
			LEFT JOIN app_users d ON a.id_users=d.id_users
			GROUP BY a.no_bast
			ORDER BY a.tanggal_masuk DESC");  
		$result=$query_result->result();
		return $result;

	}

	//get all bast 
	public function get_all_by($branch_id){
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, d.nama, count(c.psb_id) jumlah
			FROM bast_header a 
			JOIN branch b ON a.branch_id=b.branch_id
			LEFT JOIN new_psb c ON a.no_bast=c.no_bast
			LEFT JOIN app_users d ON a.id_users=d.id_users
			where a.branch_id='".$branch_id."'
			GROUP BY a.no_bast
			ORDER BY a.tanggal_masuk DESC");  
		$result=$query_result->result();
		return $result;

	}

	public function get_all_by_no_bast($no_bast){
		$query_result = $this->db->query("SELECT *
			FROM bast_header
			where no_bast='".$no_bast."'
			ORDER BY tanggal_masuk DESC");  
		$result=$query_result->row();
		return $result;
	}

	//get all bast by branch  
	public function get_all_bydate($branch_id){
		$tgl = date('Y-m-d');
		if(!empty($branch_id)){
			$query_result = $this->db->query("SELECT a.*, b.nama_branch
				FROM bast_header a 
				JOIN branch b ON a.branch_id=b.branch_id
				WHERE a.branch_id = '".$branch_id."' and DATE_FORMAT(a.tanggal_masuk,'%Y-%m-%d')='".$tgl."'
				ORDER BY a.tanggal_masuk DESC");  	
		}else{
			$query_result = $this->db->query("SELECT a.*, b.nama_branch
				FROM bast_header a 
				JOIN branch b ON a.branch_id=b.branch_id
				WHERE DATE_FORMAT(a.tanggal_masuk,'%Y-%m-%d')='".$tgl."'
				ORDER BY a.tanggal_masuk DESC");
		}
		
		$result=$query_result->result();
		return $result;

	}
	
}