<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmuser extends CI_Model {
	var $table = 'tbl_user';

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function add_record($data) {
        $this->db->insert($this->table, $data);
        return;
    }

    public function delete_by_id($id){
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }
}
