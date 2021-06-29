
  <div class="container">
    <br /><br /><br />
    <table id="ta" class="stripe row-border order-column table-hover table table-condensed table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>periode</th>
                <th>periode_penggajian</th>
                <th>segmen</th>
                <th>nik</th>
                <th>nama</th>
                <th>pph21_gaji_sebulan</th>
                <th>pph21_thr_bonus</th>
                <th>tambahan_non_npwp</th>
                <th>pph21_nett</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody></tbody>
    </table>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
        $('#ta tfoot th').each( function () {
            if($(this).text() == "Act"){
                $(this).html( '' );
            } else if($(this).text() == "periode"){
                $(this).html( '' );
            } else if($(this).text() == "periode_penggajian"){
                $(this).html( '' );
            } else if($(this).text() == "segmen"){
                $(this).html( '' );
            } else if($(this).text() == "nik"){
                $(this).html( '' );
            } 
        } );
        
        var table = $('#ta').DataTable({
            "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
                    var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display;
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    if (api.column(5).data().length){
                    var total = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                    },0 ) }
                    else{ total = 0};
                    if (api.column(5).data().length){
                    var pageTotal = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    },0 ) }
                    else{ pageTotal = 0};
                    $( api.column(5).footer() ).html(''+numFormat(pageTotal));

                    if (api.column(6).data().length){
                    var total = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                    },0 ) }
                    else{ total = 0};
                    if (api.column(6).data().length){
                    var pageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    },0 ) }
                    else{ pageTotal = 0};
                    $( api.column(6).footer() ).html(''+numFormat(pageTotal));

                    if (api.column(7).data().length){
                    var total = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                    },0 ) }
                    else{ total = 0};
                    if (api.column(7).data().length){
                    var pageTotal = api
                    .column( 7, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    },0 ) }
                    else{ pageTotal = 0};
                    $( api.column(7).footer() ).html(''+numFormat(pageTotal));

                    if (api.column(8).data().length){
                    var total = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                    },0 ) }
                    else{ total = 0};
                    if (api.column(8).data().length){
                    var pageTotal = api
                    .column( 8, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    },0 ) }
                    else{ pageTotal = 0};
                    $( api.column(8).footer() ).html(''+numFormat(pageTotal));
                },
                
            oLanguage: {
              sLoadingRecords: '<img src="assets/ajax-loader.gif">'
            },
            "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
            "iDisplayLength": 25,
            "sAjaxSource": "<?php echo site_url('ctpph21/list_tpph21')?>",
            "sServerMethod": "POST",
            lengthChange: true,
            dom: '<"top">BC<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
            colVis: {
                exclude: [0,1,2],
                buttonText: 'Pilih kolom'
            },
            buttons: [
                {
                    extend:    'copyHtml5',
                    text:      'Copy',
                    titleAttr: 'Copy to clipboard',
                    footer: true,
                    exportOptions: {
                        columns: [ 0,3,4,5,6,7,8, ':visible' ]
                    }
                },
                {
                    extend:    'excelHtml5',
                    text:      'Excel',
                    titleAttr: 'Download excel file',
                    footer: true,
                    exportOptions: {
                        columns: [ 0,3,4,5,6,7,8, ':visible' ]
                    }
                },
                {
                    extend:    'pdfHtml5',
                    text:      'Pdf',
                    titleAttr: 'Download pdf file',
                    footer: true,
                    pageSize: 'A3',
                    exportOptions: {
                        columns: [ 0,3,4,5,6,7,8, ':visible' ]
                    }
                }
            ],
            "language": {
                "lengthMenu": '_MENU_',
                "zeroRecords": "-kosong-",
                "infoEmpty": "-kosong-",
                "infoFiltered": "(_MAX_ total)",
                "search": " Cari: "
            },
            scrollY:        "440px",
            scrollX:        true,
            scrollCollapse: true
            });
            
            $("div.toolbar").html('<div class="text-center"><strong>Periode PPH21</strong></div>');
            $('div.dataTables_filter input').addClass('input-smx');
            table.buttons().container().appendTo('#ta_wrapper .col-sm-6:eq(0)');
            table.columns().indexes().each(function (idx){
            $('input', table.column(idx).footer()).on('keyup change', function (){
                table
                    .column(idx)
                    .search(this.value)
                    .draw();
            });
        });
        
        function reload_table(){
          table.ajax.reload(null,false);
        }

    });
  </script>
  <?php include "footer.php" ?>
</body>
</html>