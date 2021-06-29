<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clogo extends CI_Controller {
    private $sTable = 'master_perusahaan';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mlogo','logo');
	}

	public function index(){
        $this->load->helper('url');
        $this->load->view('header');
        $this->load->view('logo_view');
	}

    function list_logo() {
        $aColumns = array('id','nama_perusahaan','alamat_perusahaan','logo_perusahaan','status');
        $sIndexColumn = 'id';
        $input =& $_POST;
        $sQuery = "SELECT COUNT(`".$sIndexColumn."`) AS row_count FROM `".$this->sTable."`";
        $rResultTotal = $this->db->query( $sQuery );
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->row_count;
        $sLimit = "";
        if ( isset( $input['iDisplayStart'] ) && $input['iDisplayLength'] != '-1' ) {
            $sLimit = " LIMIT ".intval( $input['iDisplayStart'] ).", ".intval( $input['iDisplayLength'] );
        }
            
            $aOrderingRules = array();
            if ( isset( $input['iSortCol_0'] ) ) {
                    $iSortingCols = intval( $input['iSortingCols'] );
                    for ( $i=0 ; $i<$iSortingCols ; $i++ ) {
                            if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == 'true' ) {
                                    $aOrderingRules[] =
                    "`".$aColumns[ intval( $input['iSortCol_'.$i] ) ]."` "
                    .($input['sSortDir_'.$i]==='asc' ? 'asc' : 'desc');
                            }
                    }
            }

            if (!empty($aOrderingRules)) {
                    $sOrder = " ORDER BY ".implode(", ", $aOrderingRules);
                    } else {
                    $sOrder = "";
            }
            
            $iColumnCount = count($aColumns);
    
            if ( isset($input['sSearch']) && $input['sSearch'] != "" ) {
                $aFilteringRules = array();
                for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                    if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' ) {
                        $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$this->db->real_escape_string( $input['sSearch'] )."%'";
                        }
                    }
                    if (!empty($aFilteringRules)) {
                        $aFilteringRules = array('('.implode(" OR ", $aFilteringRules).')');
                    }
            }
            
            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' && $input['sSearch_'.$i] != '' ) {
                    $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$this->db->real_escape_string($input['sSearch_'.$i])."%'";
                }
            }

            if (!empty($aFilteringRules)) {
                $sWhere = " WHERE ".implode(" AND ", $aFilteringRules);
                } else {
                $sWhere = "";
            }

            $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }
            
                $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
                FROM `".$this->sTable."`".$sWhere.$sOrder.$sLimit;
                $rResult = $this->db->query( $sQuery );
                $sQuery = "SELECT FOUND_ROWS() AS length_count";
                $rResultFilterTotal = $this->db->query($sQuery);
                $aResultFilterTotal = $rResultFilterTotal->row();
                $iFilteredTotal = $aResultFilterTotal->length_count;

                $output = array(
                        "iTotalRecords"        => $iTotal,
                        "iTotalDisplayRecords" => $iFilteredTotal,
                        "aaData"               => array(),
                    );
                    foreach ($rResult->result_array() as $aRow) {
                        $row = array();
                            $btn = '<a class="btn btn-smd btn-primary" href="clogo/edit/'.$aRow['id'].'"><i class="glyphicon glyphicon-pencil"></i></a> 
                                <a class="btn btn-smd btn-danger" href="clogo/delete/'.$aRow['id'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn,$aRow['id'],$aRow['nama_perusahaan'],$aRow['alamat_perusahaan'],
                                $aRow['logo_perusahaan'],$aRow['status']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($id){
        $this->db->where('id',$id);
        $data['query'] = $this->db->get('master_perusahaan');
        $data['id'] = $id;
        $this->load->view('header');
        $this->load->view('logo_edit', $data);
    }

	public function delete($id){
        $this->logo->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
        redirect('clogo');
	}

    function tambah(){
        $this->load->view('header');
        $this->load->view('logo_add');
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $config['upload_path']      = './logo';
        $config['allowed_types']    = '*';
        $config['max_size']         = '30000';
        $config['max_width']        = '30000';
        $config['max_height']       = '30000';
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('logo_perusahaan')) {
            $up_data = $this->upload->data();
            $data = array(
                'nama_perusahaan' => $this->input->post('nama_perusahaan'),
                'alamat_perusahaan' => $this->input->post('alamat_perusahaan'),
                'logo_perusahaan' => $up_data['file_name'],
                'status' => $this->input->post('status')
            );
        } else {
            $data = array(
                'nama_perusahaan' => $this->input->post('nama_perusahaan'),
                'alamat_perusahaan' => $this->input->post('alamat_perusahaan'),
                'status' => $this->input->post('status')
            );
        }

        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->update($this->sTable, $data);
        redirect('clogo');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $config['upload_path']      = './logo';
        $config['allowed_types']    = '*';
        $config['max_size']         = '30000';
        $config['max_width']        = '30000';
        $config['max_height']       = '30000';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('logo_perusahaan')) {
            $up_data = $this->upload->data();
            $data = array(
                'nama_perusahaan' => $this->input->post('nama_perusahaan'),
                'alamat_perusahaan' => $this->input->post('alamat_perusahaan'),
                'logo_perusahaan' => $up_data['file_name'],
                'status' => $this->input->post('status')
            );
        } else {
            $data = array(
                'nama_perusahaan' => $this->input->post('nama_perusahaan'),
                'alamat_perusahaan' => $this->input->post('alamat_perusahaan'),
                'status' => $this->input->post('status')
            );
        }
        $this->logo->add_record($data);
        redirect('clogo');
    }
}
