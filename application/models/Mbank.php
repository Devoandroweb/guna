<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mbank extends CI_Model {
	var $table = 'content_bank';

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

	public function delete_by_id($bank){
		$this->db->where('bank', $bank);
		$this->db->delete($this->table);
	}
	public function delete_by_id_bank($id_bank){
		$this->db->where('id_bank', $id_bank);
		$this->db->delete($this->table);
	}
}
