<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mjabatan extends CI_Model {
	var $table = 'content_jabatan';

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

	public function delete_by_id($jabatan){
		$this->db->where('jabatan', $jabatan);
		$this->db->delete($this->table);
	}
	public function delete_by_id_jabatan($id_jabatan){
		$this->db->where('id_jabatan', $id_jabatan);
		$this->db->delete($this->table);
	}
}
