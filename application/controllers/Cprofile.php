<?php 

/**
 * 
 */
class Cprofile extends CI_Controller
{
	
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->helper('permalink_helper');
	}
	function index(){
		$this->header();
		$this->load->view("profile_view");
	}
	function header(){
		$data['active_accordion'] = "";
        $data['aria_expanded'] = "";
        $data['sub_menu_show'] = "";
        $data['active_menu'] = 9999;
        $this->load->view('header',$data);
	}
}