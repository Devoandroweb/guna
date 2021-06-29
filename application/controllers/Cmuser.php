<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cmuser extends CI_Controller {
    private $sTable = 'tbl_user';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mmuser','muser');
	}

	public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('muser_view');
	}

    function list_muser() {
        $aColumns = array('id','u_name','pass_word','aktif','level');
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
                            $btn = '<a class="text-dark mr-1" title="Edit" href="cmuser/edit/'.$aRow['id'].'"><i class="fa fa-pencil"></i></a> 
                                <a class="text-dark" title="Delete" href="cmuser/delete/'.$aRow['id'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn,$aRow['id'],$aRow['u_name'],"**********",$aRow['aktif'],$aRow['level']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($id){
        $this->db->where('id',$id);
        $data['query'] = $this->db->get('tbl_user');
        $data['id'] = $id;
        $this->header();
        $this->load->view('muser_edit', $data);
    }

	public function delete($id){
        $this->muser->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
        redirect('cmuser');
	}

    function tambah(){
        $this->header();
        $this->load->view('muser_add');
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('u_name');
        $data = array(
            'u_name'    => $this->input->post('username'),
            'aktif'     => $this->input->post('aktif'),
            'lastupdate'    => $last,
            'user_id'   => $nm
        );
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->update($this->sTable, $data);
        redirect('cmuser');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('u_name');
        $data = array(
            'u_name'    => $this->input->post('username'),
            'pass_word' => md5($this->input->post('password')),
            'text_pass' => $this->input->post('username'),
            'aktif'     => $this->input->post('aktif'),
            'level'     => $this->input->post('level'),
            'lastupdate'    => $last,
            'user_id'   => $nm
        );
        $this->muser->add_record($data);
        redirect('cmuser');
    }
    function header(){
        $data['active_accordion'] = "h";
        $data['aria_expanded'] = "h";
        $data['sub_menu_show'] = "h";
        $data['active_menu'] = 33;
        $this->load->view('header',$data);
    }
}
