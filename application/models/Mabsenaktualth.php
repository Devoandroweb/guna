<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mabsenaktualth extends CI_Model {
    var $table = 'vabsen_tidak_hadir';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }
}
