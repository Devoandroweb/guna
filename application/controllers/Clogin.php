<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clogin extends CI_Controller {

    public $data = array ('pesan' => '');
    
    public function __construct () {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('Login_m','login', TRUE);
    }
    
    public function index() {
        if ($this->session->userdata('login') == TRUE && $this->session->userdata('level') == 'SYS'){
            redirect('cdashboard');
        } else {
            if($this->login->validasi()) {
                if($this->login->cek_user()) {
                    if($this->session->userdata('level') == 'SYS'){
                        redirect('cdashboard');
                    } 
                } else {
                    $this->data['pesan'] = '<div class="alert alert-danger mt-2">Username atau Password salah.</div>';
                }
            } 
         $this->data['jenis'] = 'SYS';
         $this->load->view('login', $this->data);
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url('clogin'));
    }

    function changePeriode($value){
        
        $this->db->select("*");
        $this->db->from("trans_periode");
        $this->db->where("periode",$value);
        $this->db->limit(1);
        $result = $this->db->get();

        foreach ($result->result() as $key) {
            $this->session->set_userdata('periode',$key->periode);
        }
        
        redirect('cdashboard');

    }
}