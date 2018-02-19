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

	//get all users  
	public function get_all($tgl = ""){
		$date = $tgl;
		$query_result = $this->db->query("SELECT a.*, b.nama_branch, c.nama as 'nama_tl', d.nama_paket, e.sub_channel, f.nama as 'aktivator', g.nama as 'validator', e.sales_channel
			FROM new_psb a
			LEFT JOIN branch b ON b.branch_id = a.branch_id
			LEFT JOIN app_users c ON c.username = a.TL
			LEFT JOIN paket d ON d.paket_id = a.paket_id
			LEFT JOIN sales_channel e ON e.id_channel = a.sub_sales_channel
			LEFT JOIN app_users f ON f.username = a.username 
			LEFT JOIN app_users g ON g.username = a.validasi_by 
			where DATE_FORMAT(a.tanggal_aktif,'%Y-%m-%d') between ADDDATE(LAST_DAY(SUBDATE('".$date."',
		INTERVAL 1 MONTH)), 1) AND '".$date."'
			ORDER BY a.tanggal_update DESC");  
		$result=$query_result->result();
		return $result;

	}

	//get all users  
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

	//get sales by account 
	public function get_sales_by_fa($fa_id){
		$query_result=$this->db->query("select count(psb_id) jumlah from new_psb where fa_id='".$fa_id."'");
		$result=$query_result->row();
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