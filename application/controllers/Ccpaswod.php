<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ccpaswod extends CI_Controller {
    private $sTable = 't_admin';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mlogin');
	}

    function index(){
        $this->load->helper('url');
        $this->load->view('header');
        $this->load->view('cpaswod');
    }

    public function gpaswod(){
        $nama   = $this->session->userdata('nama');
        $p1     = md5($this->input->post('p1'));
        $p2     = md5($this->input->post('p2'));
        $p3     = md5($this->input->post('p3'));
        $cek_password_lama  = $this->db->query("SELECT * FROM t_admin WHERE username = '$nama'")->row();
        if ($cek_password_lama->password != $p1) {
            $this->session->set_flashdata('k', '<div id="alert" class="alert alert-danger" role="alert">Password Lama tidak sama</div>');
            redirect('ccpaswod');
        } else if ($p2 != $p3) {
            $this->session->set_flashdata('k', '<div id="alert" class="alert alert-error role="alert"">Password Baru 1 dan 2 tidak cocok</div>');
            redirect('ccpaswod');
        } else {
            $this->db->query("UPDATE t_admin SET password = '$p3' WHERE username = '$nama'");
            $this->session->set_flashdata('k', '<div id="alert" class="alert alert-success role="alert"">Password berhasil diperbaharui</div>');
            redirect('ccpaswod');
        }
        
    }
}
