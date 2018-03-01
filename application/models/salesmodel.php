<?php

class salesModel extends CI_Model{
 
	// db2 digunakan untuk mengakses database ke-2
	private $db2;
	var $table = 'new_psb';
	var $column = array('a.psb_id','a.msisdn','a.nama_pelanggan','a.alamat','a.alamat2','a.no_hp','a.ibu_kandung','a.branch_id','b.nama_branch','a.tanggal_masuk','a.tanggal_validasi','a.tanggal_aktif','a.paket_id','c.nama_paket','a.discount','a.periode','a.bill_cycle','a.fa_id','a.account_id','a.sales_channel','a.sub_sales_channel','d.sub_channel','a.detail_sub','a.sales_person','a.TL','e.nama','a.jenis_event','a.nama_event','a.validasi_by','a.username','a.tanggal_input','a.tanggal_update','a.status','a.deskripsi');
	var $order = array('a.psb_id' => 'desc');

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc', TRUE);
	    $this->search = '';
	}

	//get all sales  
	public function get_all($tgl = ""){
		$date = $tgl;
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', DATEDIFF(a.tanggal_aktif, a.tanggal_masuk) as sla
			FROM new_psb a
			LEFT JOIN branch b ON b.branch_id = a.branch_id
			LEFT JOIN app_users c ON c.username = a.TL
			LEFT JOIN paket d ON d.paket_id = a.paket_id
			LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
			LEFT JOIN app_users f ON f.username = a.username 
			LEFT JOIN app_users g ON g.username = a.validasi_by 
			where DATE_FORMAT(a.tanggal_masuk,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',
		INTERVAL 1 MONTH)), 1) AND '".$date."'
			ORDER BY a.tanggal_masuk DESC");  
		$result=$query_result->result();
		return $result;

	}

	//get all sales  
	public function get_all_cari($branch_id,$tanggal,$status,$from_date,$to_date){
		$sess_level = $this->session->userdata('level');
		$user_tl = $this->session->userdata('username');
		
		if($sess_level == 3){ //data for TL
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', DATEDIFF(a.$tanggal, a.tanggal_masuk) as sla
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
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', DATEDIFF(a.$tanggal, a.tanggal_masuk) as sla
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
			 
		$result=$query_result->result();
		return $result;

	}

	public function get_all_cari_all($branch_id,$tanggal,$status,$from_date,$to_date){
		$sess_level = $this->session->userdata('level');
		$user_tl = $this->session->userdata('username');
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', DATEDIFF(a.$tanggal, a.tanggal_masuk) as sla
			FROM new_psb a
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

	//get all sales per branch
	public function get_all_branch($tgl = "", $branch_id = ""){
		$date = $tgl;
		$user_tl = $this->session->userdata('username');
		if($this->session->userdata('level')==3){
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', DATEDIFF(a.tanggal_aktif, a.tanggal_masuk) as sla
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
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', DATEDIFF(a.tanggal_aktif, a.tanggal_masuk) as sla
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
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', DATEDIFF(a.tanggal_aktif, a.tanggal_masuk) as sla
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
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, DATEDIFF(a.tanggal_aktif, a.tanggal_masuk) as sla
			FROM new_psb a
			LEFT JOIN branch b ON b.branch_id = a.branch_id
			where a.msisdn like '%".$cek."%' ");  
		$result=$query_result->result();
		return $result;

	}

	//get all sales  
	public function getMSISDNDetail($msisdn){
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', DATEDIFF(a.tanggal_aktif, a.tanggal_masuk) as sla
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

	public function getBASTDetail($no_bast){
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', DATEDIFF(a.tanggal_aktif, a.tanggal_masuk) as sla
			FROM new_psb a
			LEFT JOIN branch b ON b.branch_id = a.branch_id
			LEFT JOIN app_users c ON c.username = a.TL
			LEFT JOIN paket d ON d.paket_id = a.paket_id
			LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
			LEFT JOIN app_users f ON f.username = a.username 
			LEFT JOIN app_users g ON g.username = a.validasi_by 
			where a.no_bast = '".$no_bast."' ");  
		$result=$query_result->result();
		return $result;

	}

	//get all sales belum di proses $level = array(1=>'Cek MSISDN', 2=>'Validasi', 3=>'TL', 4=>'Administrator', 5=>'Aktivasi / FOS', 6=>'FOS CTP', 7=>'Admin CTP');
	public function getAllSalesBy($sess_level, $sess_branch){
		if($sess_level == 2 || $sess_level==6){ //validasi
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', DATEDIFF(current_date(), a.tanggal_masuk) as selisih
				FROM new_psb a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				WHERE a.status = 'masuk' and a.branch_id='".$sess_branch."' 
				ORDER BY selisih, a.tanggal_masuk DESC");  
		}else if($sess_level == 3){ // TL
			$user_tl = $this->session->userdata('username');
			$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', DATEDIFF(current_date(), a.tanggal_masuk) as selisih
				FROM new_psb a
				LEFT JOIN branch b ON b.branch_id = a.branch_id
				LEFT JOIN app_users c ON c.username = a.TL
				WHERE a.status = 'masuk' and a.branch_id='".$sess_branch."' and a.TL='".$user_tl."' 
				ORDER BY selisih, a.tanggal_masuk DESC");  
		}else if($sess_level == 5 || $sess_level == 7){ //FOS
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

	//get all sales  
	public function _get_datatables_query(){
		//Current Month PSB
		$this->db->select($this->column);
		$this->db->from($this->table.' a');  
		$this->db->join('branch b', 'b.branch_id = a.branch_id');
		$this->db->join('paket c', 'c.paket_id = a.paket_id');
		$this->db->join('sales_channel d', 'd.id_channel = a.sub_sales_channel');
		$this->db->join('app_users e', 'e.username = a.TL');

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}

	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
}