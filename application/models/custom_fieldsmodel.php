<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento admin_model model
 *
 * This class handles admin_model management related functionality
 *
 * @package		Admin
 * @subpackage	admin_model
 * @author		propertyjar
 * @link		#
 */

class custom_fieldsmodel extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('hvc', TRUE);
	}
	
	function get_all()
	{
			$this->db2->select('a.*, b.nama');
			$this->db2->join('app_users b', 'b.id_users = a.id_users');
			return $this->db2->get('custom_fields a')->result();
	}
	
}