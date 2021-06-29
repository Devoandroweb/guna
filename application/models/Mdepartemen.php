<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdepartemen extends CI_Model {
	var $table = 'content_departemen';

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

	public function delete_by_id($departemen){
		$this->db->where('departemen', $departemen);
		$this->db->delete($this->table);
	}
	public function delete_by_id_departemen($id_departemen){
		$this->db->where('id_departemen', $id_departemen);
		$this->db->delete($this->table);
	}
}
