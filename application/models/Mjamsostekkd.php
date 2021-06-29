<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mjamsostekkd extends CI_Model {
	var $table = 'master_jamsostek_komponen_dasar';

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function add_record($data){
        $this->db->insert($this->table, $data);
        return;
    }

    public function delete_by_id($kode_gaji){
        $this->db->where('kode_gaji', $kode_gaji);
        $this->db->delete($this->table);
    }

}
