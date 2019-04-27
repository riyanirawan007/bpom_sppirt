<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Narasumber_model extends CI_Model {

	var $table = 'tabel_narasumber';
	var $column_order = array(null, 'nama_narasumber','nm_jabatan','nama_instansi','no_tlp_kantor','alamat_kantor','alamat_pribadi'); 
	var $column_search = array('nama_narasumber','nm_jabatan','nama_instansi','no_tlp_kantor','alamat_kantor','alamat_pribadi');  
    var $order = array('kode_narasumber' => 'asc'); // default order 
    
    public function __construct()
    {
    	parent::__construct();
    	$this->load->database();
    }

    function get_all() {
        $this->db->order_by('kode_narasumber', 'asc');
        $narasumber = $this->db->get('tabel_narasumber')->result_array();
        return $narasumber;
    }

    function view()
    {
    	return $this->db->get('tabel_narasumber');
    }

    function select($id)
    {
    	$this->db->where('kode_narasumber', $id);
    	return $this->db->get('tabel_narasumber');
    }

    private function _get_datatables_query()
    {
    	$this->db->from($this->table);

    	$i = 0;

    	foreach ($this->column_search as $item)
    	{
    		if($_POST['search']['value'])
    		{

    			if($i===0) 
    			{
    				$this->db->group_start(); 
    				$this->db->like($item, $_POST['search']['value']);
    			}
    			else
    			{
    				$this->db->or_like($item, $_POST['search']['value']);
    			}

    			if(count($this->column_search) - 1 == $i) 
    				$this->db->group_end();
    		}
    		$i++;
    	}

    	if(isset($_POST['order']))
    	{
    		$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
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

/* End of file narasumber_model.php */
/* Location: ./application/models/narasumber_model.php */