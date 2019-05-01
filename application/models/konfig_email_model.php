<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfig_email_model extends CI_Model {

	function view()
	{
		return $this->db->get('tabel_konfig_email');
	}

}

/* End of file konfig_email_model.php */
/* Location: ./application/models/konfig_email_model.php */