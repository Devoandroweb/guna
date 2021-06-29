<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                  <div class="row mb-2">
                        <div class="col">
                          <h4 class="card-title">Master PPH-21 Komponen</h4>
                        </div>
                        <div class="col text-right">
                          <a href="<?= base_url() ?>cpphk/tambah" class="btn btn-primary ">
                            <i class="fa fa-plus"></i> 
                            Tambah
                          </a>
                        </div>
                      </div>
                      <hr>
                  <div class="table-responsive">
                    
                    <table id="t" class="table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Kode Gaji</th>
                                <th>Last Update</th>
                                <th>User ID</th>
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
        //     "sAjaxSource": "<?php echo site_url('cpphk/list_pphk')?>",
        //     "sServerMethod": "POST",
        //     lengthChange: true,
        //     dom: '<"top">BC<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
        //     colVis: {
        //         exclude: [0],
        //         buttonText: 'Pilih kolom'
        //     },
        //     buttons: [
        //         {
        //         action: function (e, dt, node, config) {
        //         window.location.href = "cpphk/tambah";
        //         },
        //         text: '<div class="input-sm glyphicons glyphicon-plus"></div>',
        //         titleAttr: "Tambah data"
        //         }
        //     ],
        //         "language": {
        //         "lengthMenu": '_MENU_',
        //         "zeroRecords": "-kosong-",
        //         "infoEmpty": "-kosong-",
        //         "infoFiltered": "(_MAX_ total)",
        //         "search": " Cari: "
        //     },
        //     scrollY:        "440px",
        //     scrollX:        true,
        //     scrollCollapse: true
        //     });
        //     $("div.toolbar").html('<div class="text-center"><strong>Master PPH-21 Komponen</strong></div>');
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
        
        function reload_table(){
          table.ajax.reload(null,false); //reload datatable ajax
        }
        (function($) {
          'use strict';
          $(function() {
            $('#t').DataTable({
              "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
              ],
              "sAjaxSource": "<?php echo site_url('cpphk/list_pphk')?>",
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
          });
        })(jQuery);

    });
  </script>
  <?php include "footer.php" ?>
</body>
</html>