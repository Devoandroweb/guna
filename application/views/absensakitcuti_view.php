<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                          <h4 class="card-title">Absen Sakit/Cuti</h4>
                        </div>
                        <div class="col text-right">
                          <a href="<?= base_url() ?>cabsensakitcuti/tambah" class="btn btn-primary mr-1">
                            <i class="fa fa-plus"></i> 
                            Tambah
                          </a>
                          <a href="<?= base_url() ?>cabsensakitcuti/excel" class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i> 
                            Excel
                          </a>
                        </div>
                      </div>
                      <hr>
                    <div class="table-responsive">
                        <table id="t" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Opsi</th>
                                    <th>No</th>
                                    <th>Periode</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Dari Tanggal</th>
                                    <th>Sampai Tanggal</th>
                                    <!-- <th>status</th>
                                    <th>keterangan</th>
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
        //     } else if ($(this).text() == "nik"){
        //         $(this).html( '' );
        //     } else if ($(this).text() == "periode"){
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
        //     "sAjaxSource": "<?php echo site_url('cabsensakitcuti/list_absensakitcuti')?>",
        //     "sServerMethod": "POST",
        //     lengthChange: true,

        //     dom: '<"top">BC<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
        //     colVis: {
        //         exclude: [ 0,1,2 ],
        //         buttonText: 'Pilih kolom'
        //     },
        //     buttons: [
        //         {
        //             action: function (e, dt, node, config) {
        //             window.location.href = "cabsensakitcuti/tambah";
        //             },
        //             text: '<div class="input-sm glyphicons glyphicon-plus"></div>',
        //             titleAttr: "Tambah data"
        //         },
        //         {
        //             extend:    'excelHtml5',
        //             text:      '<div class="input-sm glyphicons glyphicon-plus-3"></div>',
        //             titleAttr: 'Download excel file',
        //             exportOptions: {
        //                 columns: [ 1,2,3,4,5,6,7 ]
        //             }
        //         }
        //     ],

        //     columnDefs: [
        //         {
        //             targets: [-1,1],
        //             visible: false
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
        //     paging:         true,
        //     fixedColumns:   {
        //         leftColumns: 3
        //     }
        //     });
        //     $("div.toolbar").html('<div class="text-center"><strong>Transaksi Absen</strong></div>');
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
        // function reload_table(){
        //   table.ajax.reload(null,false);
        // }
        (function($) {
              'use strict';
              $(function() {
                var table = $('#t').DataTable({
                  "aLengthMenu": [
                    [5, 10, 15, -1],
                    [5, 10, 15, "All"]
                  ],
                  "sAjaxSource": "<?php echo site_url('cabsensakitcuti/list_absensakitcuti')?>",
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
                $("#t tr th").addClass("font-weight-bold");
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