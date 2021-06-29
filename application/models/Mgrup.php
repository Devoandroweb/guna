<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mgrup extends CI_Model {
	var $table = 'content_grup';

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

	public function delete_by_id($grup){
		$this->db->where('grup', $grup);
		$this->db->delete($this->table);
	}
	public function delete_by_id_grup($id_grup){
		$this->db->where('id_grup', $id_grup);
		$this->db->delete($this->table);
	}
}
