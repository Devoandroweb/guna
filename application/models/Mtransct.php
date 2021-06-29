<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mtransct extends CI_Model {
    var $table = 'master_mesin_clear';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }
    public function select_by_nik($nik,$table){

        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($nik);
        return $this->db->get();

    }
}
