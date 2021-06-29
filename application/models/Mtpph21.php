<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mtpph21 extends CI_Model {
	var $table = 'vtotal_pph21';

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

    public function delete_by_id($periode,$periode_penggajian,$segmen,$nik){
		$this->db->where('periode', $periode);
		$this->db->where('periode_penggajian', $periode_penggajian);
		$this->db->where('segmen', $segmen);
		$this->db->where('nik', $nik);
		$this->db->delete($this->table);
	}
}
