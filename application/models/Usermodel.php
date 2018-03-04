<?php

class Usermodel extends CI_Model{

// db2 digunakan untuk mengakses database ke-2
	private $db2;

	public function __construct()
	{
		parent::__construct();
	    $this->db2 = $this->load->database('hvc', TRUE);
	}
	
	public function login($username,$password)
	{
		$this->db->select('a.*, b.nama_branch');
		$this->db->from('app_users a');
		$this->db->join('branch b', 'b.branch_id = a.branch_id');
		$this->db->where('a.username',$username);
		$this->db->where('a.password',$password);
		$this->db->where('a.keterangan','Aktif');
		$query=$this->db->get();
		$row_count=$query->num_rows();
		if($row_count>0){
			$userdata=$query->row();	
			$newdata = array(
				'id_users'  	=> $userdata->id_users,	
				'username'  	=> $userdata->username,
				'nama'  		=> $userdata->nama,
				'no_hp'     	=> $userdata->no_hp,
				'branch_id' 	=> $userdata->branch_id,
				'nama_branch' 	=> $userdata->nama_branch,
				'channel' 		=> $userdata->channel,
				'level' 		=> $userdata->level,
				'last_login' 	=> $userdata->last_login,
				'keterangan' 	=> $userdata->keterangan,
				'logged_in' => TRUE
			);
			$this->session->set_userdata($newdata);	
			$this->setLoginTime($userdata->id_users);
			return true;	
		}
		return false;
	}	

public function logout(){
	$newdata = array(
		'id_users'  	=> '',	
		'username'  	=> '',
		'nama'  		=> '',
		'no_hp'     	=> '',
		'branch_id' 	=> '',
		'nama_branch' 	=> '',
		'channel' 		=> '',
		'level' 		=> '',
		'last_login' 	=> '',
		'keterangan' 	=> '',
		'logged_in' => FALSE
	);
	$this->session->set_userdata($newdata);	

}

public function setLoginTime($user_id){
	$data['last_login']=date("Y-m-d H:i:s");
	$this->db->where('id_users',$user_id);
	$this->db->update('app_users',$data);
}

}