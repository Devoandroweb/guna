<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Magama extends CI_Model {
	var $table = 'content_agama';

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

	public function delete_by_id($agama){
		$this->db->where('agama', $agama);
		$this->db->delete($this->table);
	}
	public function delete_by_id_agama($id_agama){
		$this->db->where('id_agama', $id_agama);
		$this->db->delete($this->table);
	}
}
