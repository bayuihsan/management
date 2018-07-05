<?php

class Datamodel extends CI_Model{

		function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('hvc',TRUE);
    }

    //Backup Database
	function backup($table)
	{
		$this->load->dbutil();
			
		$options = array(
                'format'      => 'txt',            
                'add_drop'    => TRUE,           
                'add_insert'  => TRUE,              
                'newline'     => "\n"              
              );
				 
		if($table == 'all')
		{
			$tables = array('');
			$file_name	=	'system_backup';
		}
		else 
		{
			$tables = array('tables'	=>	array($table));
			$file_name	=	'backup_'.$table;
		}

		$backup =$this->dbutil->backup(array_merge($options , $tables)); 


		$this->load->helper('download');
		force_download($file_name.'.sql', $backup);
	}
	
	
	//RESTORE Database
	function restoreDatabase()
	{
		move_uploaded_file($_FILES['restore-file']['tmp_name'], 'uploads/backup.sql');
		$this->load->dbutil();
		
		
		$prefs = array(
            'filepath'						=> 'uploads/backup.sql',
			'delete_after_upload'			=> TRUE,
			'delimiter'						=> ';'
        );
		$restore =& $this->dbutil->restore($prefs); 
		unlink($prefs['filepath']);
	}

	function truncate($type)
	{
		if($type == 'all')
		{
			$this->db->truncate('app_users');
			$this->db->truncate('branch');
			$this->db->truncate('ctp');
			$this->db->truncate('custom_fields');
			$this->db->truncate('feedback');
			$this->db->truncate('kategori_paket');
			$this->db->truncate('msisdn');
			$this->db->truncate('new_psb');
			$this->db->truncate('paket');
			$this->db->truncate('sales_channel');
			$this->db->truncate('sales_person');
			$this->db->truncate('settings');
		}
		else
		{	
			$this->db->truncate($type);
		}
	}


}