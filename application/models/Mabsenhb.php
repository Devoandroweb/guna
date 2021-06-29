<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mabsenhb extends CI_Model {
	var $table = 'master_absen_hari_besar';

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

	public function delete_by_id($bank){
		$this->db->where('bank', $bank);
		$this->db->delete($this->table);
	}

	Public function getEvents(){
		$sql = "SELECT * FROM master_absen_hari_besar ORDER BY master_absen_hari_besar.date ASC";
		return $this->db->query($sql)->result();
	}

	Public function addEvent(){
		$sql = "INSERT INTO master_absen_hari_besar (title,tanggal,master_absen_hari_besar.date,user_id) VALUES (?,?,?,?)";
		$this->db->query($sql, array($_POST['title'],$_POST['date'],$_POST['date'],''));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	Public function updateEvent(){
		$sql = "UPDATE master_absen_hari_besar SET title = ?, tanggal = ?, master_absen_hari_besar.date = ?, user_id = ? WHERE id = ?";
		$this->db->query($sql, array($_POST['title'], $_POST['date'], $_POST['date'], '', $_POST['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	Public function deleteEvent(){
		$sql = "DELETE FROM master_absen_hari_besar WHERE id = ?";
		$this->db->query($sql, array($_GET['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	Public function dragUpdateEvent(){
		$date=date('Y-m-d h:i:s',strtotime($_POST['date']));
		$sql = "UPDATE master_absen_hari_besar SET master_absen_hari_besar.date = ? WHERE id = ?";
		$this->db->query($sql, array($date, $_POST['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}
}
