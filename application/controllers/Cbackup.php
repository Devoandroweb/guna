<?php
ini_set('memory_limit', '-1');
defined('BASEPATH') OR exit('No direct script access allowed');

class Cbackup extends CI_Controller {
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
	}

    function index(){
        $this->header();
        $this->load->view("backup_view");
    }
    function header(){
        $data['active_accordion'] = "h";
        $data['aria_expanded'] = "h";
        $data['sub_menu_show'] = "h";
        $data['active_menu'] = 35;
        $this->load->view('header',$data);
    }
	// public function index(){
 //        $this->load->dbutil();
 //        $prefs = array(     
 //                'format'      => 'zip',             
 //                'filename'    => 'gunapayroll_db_backup.sql'
 //              );

 //        $backup =& $this->dbutil->backup($prefs); 
 //        $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
 //        $save = 'pathtobkfolder/'.$db_name;
 //        $this->load->helper('file');
 //        write_file($save, $backup); 
 //        $this->load->helper('download');
 //        force_download($db_name, $backup); 
 //    }
}
