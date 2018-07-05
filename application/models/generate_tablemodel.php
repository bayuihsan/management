<?php

class Generate_tablemodel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc',TRUE);
	}

	//get all Branch  
	public function get_all(){
		$this->db->select('a.*, b.nama');
		$this->db->from('new_psb_temp a');
		$this->db->join('app_users b','b.id_users=a.id_users','LEFT');
		$this->db->where('a.status','Aktif');  
		$this->db->order_by("a.nama_table", "asc");    
		$query_result=$this->db->get();
		$result=$query_result->result();
		return $result;

	} 

	//get branch by id  
	public function get_generate_by_id($id_temp){
		$this->db->select('*');
		$this->db->from('new_psb_temp');
		$this->db->where('status','Aktif'); 
		$this->db->where('id_temp',$id_temp);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 

	//get all sales  
	public function get_all_table($nama_table = ""){
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
			FROM $nama_table a
			LEFT JOIN branch b ON b.branch_id = a.branch_id
			LEFT JOIN app_users c ON c.username = a.TL
			LEFT JOIN paket d ON d.paket_id = a.paket_id
			LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
			LEFT JOIN app_users f ON f.username = a.username 
			LEFT JOIN app_users g ON g.username = a.validasi_by 
			ORDER BY a.tanggal_aktif DESC");  
		$result=$query_result->result();
		return $result;

	}

	//get all sales  
	public function get_all_cari($nama_table,$branch_id,$tanggal,$status,$from_date,$to_date){
		$sess_level = $this->session->userdata('level');
		$user_tl = $this->session->userdata('username');
		
		if($sess_level == 3){ //data for TL
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
				FROM $nama_table a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				LEFT JOIN paket d ON d.paket_id = a.paket_id
				LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
				LEFT JOIN app_users f ON f.username = a.username 
				LEFT JOIN app_users g ON g.username = a.validasi_by 
				where a.branch_id='".$branch_id."' AND a.status='".$status."' AND DATE_FORMAT(a.$tanggal,'%Y-%m-%d') between '".$from_date."' AND '".$to_date."' and a.TL='".$user_tl."'
					ORDER BY a.$tanggal DESC");  
		}else{
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
				FROM $nama_table a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				LEFT JOIN paket d ON d.paket_id = a.paket_id
				LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
				LEFT JOIN app_users f ON f.username = a.username 
				LEFT JOIN app_users g ON g.username = a.validasi_by 
				where a.branch_id='".$branch_id."' AND a.status='".$status."' AND DATE_FORMAT(a.$tanggal,'%Y-%m-%d') between '".$from_date."' AND '".$to_date."'
					ORDER BY a.$tanggal DESC"); 
		}
			 
		$result=$query_result->result();
		return $result;

	}

	public function get_all_cari_all($nama_table,$branch_id,$tanggal,$status,$from_date,$to_date){
		$sess_level = $this->session->userdata('level');
		$user_tl = $this->session->userdata('username');
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
			FROM $nama_table a
			LEFT JOIN branch b ON b.branch_id = a.branch_id
			LEFT JOIN app_users c ON c.username = a.TL
			LEFT JOIN paket d ON d.paket_id = a.paket_id
			LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
			LEFT JOIN app_users f ON f.username = a.username 
			LEFT JOIN app_users g ON g.username = a.validasi_by 
			where a.status='".$status."' AND DATE_FORMAT(a.$tanggal,'%Y-%m-%d') between '".$from_date."' AND '".$to_date."'
				ORDER BY a.$tanggal DESC");  
		
		$result=$query_result->result();
		return $result;

	}
}