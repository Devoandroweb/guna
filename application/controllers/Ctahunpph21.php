<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctahunpph21 extends CI_Controller {
    private $sTable = 'bukti_potong_pph21';
    private $sTabless = 'trans_periode';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mtahunpph21','tpph21');
        $this->load->helper('permalink_helper');
    }

    public function index(){
        $data['query'] = $this->db->query("SELECT * FROM trans_periode WHERE periode like '2016%'");
        $this->load->view('header');
        $this->load->view('tahunpph21_view', $data);
    }

    function list_tahunpph21() {
        $ot = $this->session->userdata('periode');
        $sql = "CALL bukti_potong_pph21 ('2016%')";
        $aColumns = array();
        $parameters = array();
        $query = $this->db->query($sql, $aColumns);
        $sIndexColumn = 'nik';
        $input =& $_POST;
            
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
            $output = "";
            $this->db->reconnect();
            foreach ($query->result_array() as $aRow) {
                $p = $this->session->userdata('periode');
                $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();

                    
                        for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                            $row[] = $aRow[ $aColumns[$i] ];
                        }
                    $row = array_values($aRow);
                    
                    $data[] = array_merge($row);
                    $output['aaData'] = $data;
                
                $row = array();
                    
            }
            echo json_encode( $output );
    }

    public function delete($periode,$periode_penggajian,$segmen,$nik){
        $this->tpph21->delete_by_id($periode,$periode_penggajian,$segmen,$nik);
        echo json_encode(array("status" => TRUE));
        redirect('ctahunpph21');
    }
}
