<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cperiode extends CI_Controller {
    private $sTable = 'trans_periode';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mperiode','periode');
	}

	public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('periode_view');
	}

    function list_periode() {
        $aColumns = array('periode','periode_penggajian','segmen','mulai','selesai','thr','status','lastupdate','user_id');
        $sIndexColumn = 'periode';
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
                            $btn = '<a class="text-dark mr-1" title="Edit" href="cperiode/edit/'.$aRow['periode'].'/'.$aRow['periode_penggajian'].'/'.$aRow['segmen'].'"><i class="fa fa-pencil"></i></a> 
                            <a class="text-dark mr-2" title="Delete" href="cperiode/delete/'.$aRow['periode'].'/'.$aRow['periode_penggajian'].'/'.$aRow['segmen'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>
                            <a class="text-dark" title="Detail" href="cperiode/detail/'.$aRow['periode'].'/'.$aRow['periode_penggajian'].'/'.$aRow['segmen'].'"><i class="fa fa-info"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['periode'], $aRow['periode_penggajian'], $aRow['segmen'], 
                                $this->fungsi->dateToIndo($aRow['mulai']), $this->fungsi->dateToIndo($aRow['selesai']), $aRow['thr'], $aRow['status'], $this->fungsi->dateToIndo($aRow['lastupdate']),
                                $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($periode,$periode_penggajian,$segmen){
        $this->db->where('periode',$periode);
        $this->db->where('periode_penggajian',$periode_penggajian);
        $this->db->where('segmen',$segmen);
        $data['query'] = $this->db->get($this->sTable);
        $data['periode'] = $periode;
        $data['periode_penggajian'] = $periode_penggajian;
        $data['segmen'] = $segmen;
        $this->header();
        $this->load->view('periode_edit', $data);
    }

	public function delete($periode,$periode_penggajian,$segmen){
        $this->periode->delete_by_id($periode,$periode_penggajian,$segmen);
        $this->periode->dele($periode);
        echo json_encode(array("status" => TRUE));
        redirect('cperiode');
	}

    function tambah(){
        $data = array();
        if($query = $this->periode->get_records()){
            $data['records'] = $query;
        }
        $data['query4'] = $this->db->get('trans_periode');
        $this->header();
        $this->load->view('periode_add', $data);
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $data = array(
            'mulai'     => $this->input->post('mulai'),
            'selesai'   => $this->input->post('selesai'),
            'thr'       => $this->input->post('thr'),
            'status'    => $this->input->post('status'),
            'lastupdate' => $last,
            'user_id'   => $this->session->userdata('level')
        );
        $periode = $this->input->post('periode');
        $periode_penggajian = $this->input->post('periode_penggajian');
        $segmen = $this->input->post('segmen');
        $this->db->where('periode', $periode);
        $this->db->where('periode_penggajian', $periode_penggajian);
        $this->db->where('segmen', $segmen);
        $this->db->update($this->sTable, $data);
        redirect('cperiode');
    }

    function create(){
        $ot = $this->session->userdata('periode');
        $per = $this->input->post('periode');
        $last = date("Y-m-d h:i:s");
        $data = array(
            'periode'           => $this->input->post('periode'),
            'periode_penggajian'=> $this->input->post('periode_penggajian'),
            'segmen'            => $this->input->post('segmen'),
            'mulai'             => $this->input->post('mulai'),
            'selesai'           => $this->input->post('selesai'),
            'thr'               => $this->input->post('thr'),
            'status'            => $this->input->post('status'),
            'lastupdate'        => $last,
            'user_id'           => $this->session->userdata('level')
        );
        $this->periode->add_record($data);
        $string_query = "INSERT INTO master_gaji_karyawan_periode (nik,kode_gaji,periode,nilai_gaji,lastupdate,user_id)
        SELECT nik,kode_gaji,'$per',nilai_gaji,lastupdate,user_id
        FROM master_gaji_karyawan";
        $query = $this->db->query($string_query);
        redirect('cperiode');
    }

    public function detail($periode){
        $aColumns = array('periode','periode_penggajian','segmen','mulai','selesai','thr','status','lastupdate','user_id');
        $sIndexColumn = 'periode';
        $input =& $_POST;
        $sWhere = "where periode='".$periode."'";

        $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }

        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
        FROM `".$this->sTable."`".$sWhere;
        $rResult = $this->db->query( $sQuery );

        $data['query'] = $rResult;
        $this->header();
        $this->load->view('periode_detail',$data);
    }

    public function excel(){
        $dataResult = array();
        $aColumns = array('periode','periode_penggajian','segmen','mulai','selesai','thr','status','lastupdate','user_id');
        $sIndexColumn = 'periode';
        $input =& $_POST;


        $iColumnCount = count($aColumns);

        $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }
            
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
        FROM `".$this->sTable."`";
        $rResult = $this->db->query( $sQuery );

      
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
                
                for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                    $row[] = $aRow[ $aColumns[$i] ];
                }
                $row = array($aRow['periode'], $aRow['periode_penggajian'], $aRow['segmen'], 
                    $aRow['mulai'], $aRow['selesai'], $aRow['thr'], $aRow['status'], $aRow['lastupdate'],
                    $aRow['user_id']);
                $dataResult = $row;
        }

        $header = array('Periode','Periode Penggajian','Segmen','Mulai','Selesai','THR','Status','Last Update','User ID');

        $data[] = $dataResult;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"TSALARY-".date("Ymdhis").".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        

        $handle = fopen('php://output', 'w');

        fputcsv($handle, $header); 

        foreach ($data as $data_array) {
            fputcsv($handle, $data_array);
        }
            fclose($handle);
        exit;
    }
    function header(){
        $data['active_accordion'] = "h";
        $data['aria_expanded'] = "h";
        $data['sub_menu_show'] = "h";
        $data['active_menu'] = 34;
        $this->load->view('header',$data);
    }

}
