<?php
class Mlaporangajipdf extends CI_Model {
    //put your code here
    function __construct(){
        parent::__construct();
    }
    
    function select_data() {
        $query = $this->db->get('master_karyawan');
        return $query->result();
    }

    function select_gaji() {
    	
    }
}
