<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mspl extends CI_Model {
	var $table = 'trans_spl';

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

    public function delete_by_id($id){
        $this->db->where('id_spl', $id);
        $this->db->delete($this->table);
    }

    function getkodeunik($table) {
        $tgl = date('Ym');
        $q = $this->db->query("SELECT MAX(RIGHT(id_spl,4)) AS idmax FROM ".$table);
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->idmax)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else{
            $kd = "0001";
        }
        $kar = "SPL-".$tgl."-";
        return $kar.$kd;
   }
}
