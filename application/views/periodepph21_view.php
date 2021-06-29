d<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                          <h4 class="card-title">Master PPH-21</h4>
                        </div>
                        <div class="col text-right">
                          <a href="<?= base_url() ?>cperiodepph21/excel" class="btn btn-success ">
                            <i class="fa fa-file-excel-o"></i> 
                            Export
                          </a>
                        </div>
                      </div>
                      <hr>
                        <div class="table-responsive">
                            <table id="t" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Opsi</th>
                                        <th>Periode</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Status Pernikahan</th>
                                        <th>NPWP</th>
                                        <th>Departemen</th>
                                        <th>Jabatan</th>
                                        <!-- <th>over_time</th>
                                        <th>base</th>
                                        <th>tunjangan_transport</th>
                                        <th>tunjangan_kendaraan</th>
                                        <th>tunjangan_makan</th>
                                        <th>tunjangan_anak</th>
                                        <th>sales_incentive</th>
                                        <th>bonus</th>
                                        <th>adjustment_plus</th>
                                        <th>jkm_perusahaan</th>
                                        <th>jkk_perusahaan</th>
                                        <th>jht_perusahaan</th>
                                        <th>jkn_perusahaan</th>
                                        <th>jpn_perusahaan</th>
                                        <th>gross</th>
                                        <th>jht_karyawan</th>
                                        <th>jpn_karyawan</th>
                                        <th>biaya_jabatan</th>
                                        <th>total_pengurang</th>
                                        <th>netto</th>
                                        <th>netto_setahun</th>
                                        <th>ptkp_gaji</th>
                                        <th>penghasilan_kena_pajak</th>
                                        <th>pph21_gaji_setahun</th>
                                        <th>pph21_gaji_sebulan</th>
                                        <th>tambahan_non_npwp</th>
                                        <th>metode_pph21</th>
                                        <th>pph21_nett</th>
                                        <th>lastupdate</th>
                                        <th>user_id</th> -->
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                          </div>
                      </div>
                  </div>

  <script type="text/javascript">
    $(document).ready(function() {
        // $('#ta tfoot th').each( function () {
        //     if($(this).text() == "Act"){
        //         $(this).html( '' );
        //     } else if($(this).text() == "periode"){
        //         $(this).html( '' );
        //     } else if($(this).text() == "periode_penggajian"){
        //         $(this).html( '' );
        //     } else if($(this).text() == "segmen"){
        //         $(this).html( '' );
        //     } else if($(this).text() == "nik"){
        //         $(this).html( '' );
        //     } else if($(this).text() == "nama"){
        //         $(this).html( '' );
        //     } else {
        //         var title = $('#ta thead th').eq( $(this).index() ).text();
        //         $(this).html('<input type="text" placeholder="Cari '+title+'" class="form-control input-sm" />');
        //     }
        // } );
        
        // var table = $('#ta').DataTable({
        //     oLanguage: {
        //       sLoadingRecords: '<img src="assets/ajax-loader.gif">'
        //     },
        //     "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        //     "iDisplayLength": 25,
        //     "sAjaxSource": "<?php echo site_url('cperiodepph21/list_periodepph21')?>",
        //     "sServerMethod": "POST",
        //     lengthChange: true,
        //     dom: '<"top">BC<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
        //     colVis: {
        //         exclude: [0,1,2],
        //         buttonText: 'Pilih kolom'
        //     },
        //     buttons: [
        //         {
        //             extend:    'excelHtml5',
        //             text:      '<div class="input-sm glyphicons glyphicon-plus-3"></div>',
        //             titleAttr: 'Download excel file',
        //             exportOptions: {
        //                 columns: [ 1,2,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34 ]
        //             }
        //         }
        //     ],
        //     "language": {
        //         "lengthMenu": '_MENU_',
        //         "zeroRecords": "-kosong-",
        //         "infoEmpty": "-kosong-",
        //         "infoFiltered": "(_MAX_ total)",
        //         "search": " Cari: "
        //     },
        //     scrollY:        "440px",
        //     scrollX:        true,
        //     scrollCollapse: true,
        //     fixedColumns:   {
        //         leftColumns: 3
        //     }
        //     });
            
        //     $("div.toolbar").html('<div class="text-center"><strong>Transaksi Periode PPH-21</strong></div>');
        //     $('div.dataTables_filter input').addClass('input-smx');
        //     table.buttons().container().appendTo('#ta_wrapper .col-sm-6:eq(0)');
        //     table.columns().indexes().each(function (idx){
        //     $('input', table.column(idx).footer()).on('keyup change', function (){
        //         table
        //             .column(idx)
        //             .search(this.value)
        //             .draw();
        //     });
        // });
        
        
        (function($) {
              'use strict';
              $(function() {
                var table = $('#t').DataTable({
                  "aLengthMenu": [
                    [5, 10, 15, -1],
                    [5, 10, 15, "All"]
                  ],
                  "sAjaxSource": "<?php echo site_url('cperiodepph21/list_periodepph21')?>",
                  "iDisplayLength": 10,
                  "language": {
                    search: ""
                  }
                });
                $('#t').each(function() {
                  var datatable = $(this);
                  // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                  var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                  search_input.attr('placeholder', 'Search');
                  search_input.removeClass('form-control-sm');
                  // LENGTH - Inline-Form control
                  var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                  length_sel.removeClass('form-control-sm');
                });
                function reload_table(){
                  table.ajax.reload(null,false);
                }

              });
            })(jQuery);

    });
  </script>
  <?php include "footer.php" ?>
</body>
</html>