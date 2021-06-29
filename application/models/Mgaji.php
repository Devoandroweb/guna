<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mgaji extends CI_Model {
	var $table = 'master_gaji';
    var $tables = 'master_periode_penggajian_komponen';

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function add_record($data,$data2) {

        
        $this->db->insert($this->table, $data);
        $this->db->insert($this->tables, $data2);
        
        return;
    }

    public function delete_by_id($kodegaji){
        $this->db->where('kode_gaji', $kodegaji);
        $this->db->delete($this->table);
    }

    public function delete($kodegaji){
        $this->db->where('kode_gaji', $kodegaji);
        $this->db->delete($this->tables);
    }

}
