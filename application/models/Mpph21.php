<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpph21 extends CI_Model {
	var $table = 'content_pph21_metode';

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

    public function delete_by_id($pph21_metode){
        $this->db->where('pph21_metode', $pph21_metode);
        $this->db->delete($this->table);
    }

}
