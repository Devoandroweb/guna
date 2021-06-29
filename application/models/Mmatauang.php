<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmatauang extends CI_Model {
	var $table = 'content_mata_uang';

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

	public function delete_by_id($mata_uang){
		$this->db->where('mata_uang', $mata_uang);
		$this->db->delete($this->table);
	}
	public function delete_by_id_mata_uang($id_mata_uang){
		$this->db->where('id_mata_uang', $id_mata_uang);
		$this->db->delete($this->table);
	}
}
