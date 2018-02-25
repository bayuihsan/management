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
		$query_result = $this->db->query("SELECT a.*, b.nama_branch
			FROM bast_header a 
			JOIN branch b ON a.branch_id=b.branch_id
			ORDER BY a.tanggal_masuk DESC");  
		$result=$query_result->result();
		return $result;

	}

	//get all bast by branch  
	public function get_all_by($branch_id){
		$query_result = $this->db->query("SELECT a.*, b.nama_branch
			FROM bast_header a 
			JOIN branch b ON a.branch_id=b.branch_id
			WHERE a.branch_id = '".$branch_id."'
			ORDER BY a.tanggal_masuk DESC");  
		$result=$query_result->result();
		return $result;

	}

	//get all sales  
	public function get_all_cari($branch_id,$tanggal,$status,$from_date,$to_date){
		$sess_level = $this->session->userdata('level');
		$user_tl = $this->session->userdata('username');
		if($branch_id == 17){ //ALL
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
				FROM new_psb a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				LEFT JOIN paket d ON d.paket_id = a.paket_id
				LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
				LEFT JOIN app_users f ON f.username = a.username 
				LEFT JOIN app_users g ON g.username = a.validasi_by 
				where a.status='".$status."' AND DATE_FORMAT(a.$tanggal,'%Y-%m-%d') between '".$from_date."' AND '".$to_date."'
					ORDER BY a.$tanggal DESC");  
		}else{
			if($sess_level == 3){ //data for TL
				$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
					FROM new_psb a
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
					FROM new_psb a
					LEFT JOIN branch b ON b.branch_id = a.branch_id
					LEFT JOIN app_users c ON c.username = a.TL
					LEFT JOIN paket d ON d.paket_id = a.paket_id
					LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
					LEFT JOIN app_users f ON f.username = a.username 
					LEFT JOIN app_users g ON g.username = a.validasi_by 
					where a.branch_id='".$branch_id."' AND a.status='".$status."' AND DATE_FORMAT(a.$tanggal,'%Y-%m-%d') between '".$from_date."' AND '".$to_date."'
						ORDER BY a.$tanggal DESC"); 
			}
			 
		}
		
		$result=$query_result->result();
		return $result;

	}

	//get all sales per branch
	public function get_all_branch($tgl = "", $branch_id = ""){
		$date = $tgl;
		$user_tl = $this->session->userdata('username');
		if($this->session->userdata('level')==3){
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
				FROM new_psb a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				LEFT JOIN paket d ON d.paket_id = a.paket_id
				LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
				LEFT JOIN app_users f ON f.username = a.username 
				LEFT JOIN app_users g ON g.username = a.validasi_by 
				where DATE_FORMAT(a.tanggal_masuk,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',
			INTERVAL 1 MONTH)), 1) AND '".$date."' AND a.branch_id='".$branch_id."' and a.TL='".$user_tl."'
				ORDER BY a.tanggal_masuk DESC");  
		}else{
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
				FROM new_psb a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				LEFT JOIN paket d ON d.paket_id = a.paket_id
				LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
				LEFT JOIN app_users f ON f.username = a.username 
				LEFT JOIN app_users g ON g.username = a.validasi_by 
				where DATE_FORMAT(a.tanggal_masuk,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',
			INTERVAL 1 MONTH)), 1) AND '".$date."' AND a.branch_id='".$branch_id."'
				ORDER BY a.tanggal_masuk DESC");  
		}
		
		$result=$query_result->result();
		return $result;

	}

	//get all sales by TL  
	public function get_all_tl(){
		$date = $tgl;
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
			FROM new_psb a
			JOIN branch b ON b.branch_id = a.branch_id
			JOIN app_users c ON c.username = a.TL
			JOIN paket d ON d.paket_id = a.paket_id
			JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
			JOIN app_users f ON f.username = a.username 
			JOIN app_users g ON g.username = a.validasi_by 
			where DATE_FORMAT(a.tanggal_aktif,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',
		INTERVAL 1 MONTH)), 1) AND '".$date."'
			ORDER BY a.tanggal_update DESC");  
		$result=$query_result->result();
		return $result;
	}

	//get sales by id  
	public function get_sales_by_id($sales_id){
		$this->db->select('*');
		$this->db->from('new_psb');
		$this->db->where('psb_id',$sales_id);    
		$query_result=$this->db->get();
		$result=$query_result->row();
		return $result;
	} 

	//get sales by account 
	public function get_sales_by_account($account_id){
		$query_result=$this->db->query("select count(psb_id) jumlah from new_psb where account_id='".$account_id."'");
		$result=$query_result->row();
		return $result;
	}

	//get sales by fa 
	public function get_sales_by_fa($fa_id){
		$query_result=$this->db->query("select count(psb_id) jumlah from new_psb where fa_id='".$fa_id."'");
		$result=$query_result->row();
		return $result;
	}

	//get MSISDN   
	public function getMSISDN($cek = ""){
		$query_result = $this->db->query("SELECT a.*, b.nama_branch
			FROM new_psb a
			LEFT JOIN branch b ON b.branch_id = a.branch_id
			where a.msisdn like '%".$cek."%' ");  
		$result=$query_result->result();
		return $result;

	}

	//get all sales  
	public function getMSISDNDetail($msisdn){
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator'
			FROM new_psb a
			LEFT JOIN branch b ON b.branch_id = a.branch_id
			LEFT JOIN app_users c ON c.username = a.TL
			LEFT JOIN paket d ON d.paket_id = a.paket_id
			LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
			LEFT JOIN app_users f ON f.username = a.username 
			LEFT JOIN app_users g ON g.username = a.validasi_by 
			where a.msisdn = '".$msisdn."' ");  
		$result=$query_result->row();
		return $result;

	}

	//get all sales belum di proses $level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'Aktivasi / FOS', 6=>'FOS CTP', 7=>'Admin CTP');
	public function getAllSalesBy($sess_level, $sess_branch){
		if($sess_level == 2){ //validasi
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', DATEDIFF(current_date(), a.tanggal_masuk) as selisih
				FROM new_psb a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				WHERE a.status = 'masuk' and a.branch_id='".$sess_branch."' 
				ORDER BY selisih, a.tanggal_masuk DESC");  
		}else if($sess_level == 3){
			$user_tl = $this->session->userdata('username');
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', DATEDIFF(current_date(), a.tanggal_masuk) as selisih
				FROM new_psb a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				WHERE a.status = 'masuk' and a.branch_id='".$sess_branch."' and a.TL='".$user_tl."' 
				ORDER BY selisih, a.tanggal_masuk DESC");  
		}else if($sess_level == 5){
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', DATEDIFF(current_date(), a.tanggal_validasi) as selisih
				FROM new_psb a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				WHERE a.status = 'valid' and a.branch_id='".$sess_branch."' 
				ORDER BY selisih, a.tanggal_validasi DESC");  
		}
		
		$result=$query_result->result();
		return $result;
	}
	
}