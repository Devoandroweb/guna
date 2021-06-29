<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_m extends CI_Model {

	public function load_form_rules() {
		$form_rules = array(
			array(
				'field' => 'u_name',
				'label' => 'username',
				'rules' => 'required'
				),
			array(
				'field' => 'pass_word',
				'label' => 'password',
				'rules' => 'required'
				),
			);
		return $form_rules;
	}

	public function validasi() {
		$form = $this->load_form_rules();
		$this->form_validation->set_rules($form);
		if ($this->form_validation->run()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function cek_user() {
		$u_name 	= $this->input->post('u_name');
		$pass_word 	= md5($this->input->post('pass_word'));
		// $periode	= $this->input->post('periode');

		$query = $this->db->where('u_name', $u_name)
		->where('pass_word', $pass_word)
		->where('aktif', 'Y')
		->where('level', 'SYS')
		->limit(1)
		->get('tbl_user');

		if ($query->num_rows() == 1) {
			$row 			= $query->row();
			$level 			= $row->level;
			$periode 		= "";
			// SELECT * FROM `gaji` WHERE id IN (SELECT MAX(id) FROM `gaji`)
			$query ="SELECT * FROM `trans_periode` ORDER BY id_periode DESC LIMIT 1";
			$resultPeriode = $this->db->query($query);
			// var_dump($resultPeriode->result());
			foreach ($resultPeriode->result() as $key) {
				$periode = $key->periode;
			}
			// die();
			
			$data = array(
				'login'			=> TRUE,
				'u_name' 		=> $u_name,
				'user_id' 		=> $level,
				'level'			=> $level,
				'nama'			=> $level,
				'periode'		=> $periode,
				'status' 		=> 'login'
			);
			$this->session->set_userdata($data);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function logout() {
		$this->session->sess_destroy();
        redirect(base_url('clogin'));
	}
}
