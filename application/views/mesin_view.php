  <div class="container">
    <br /><br /><br />
    <table id="ta" class="stripe row-border order-column table-hover table table-condensed table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Act</th>
                <th>no_urut</th>
                <th>no_akun</th>
                <th>no</th>
                <th>nama</th>
                <th>waktu</th>
                <th>kondisi</th>
                <th>kondisi_baru</th>
                <th>status</th>
                <th>operasi</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Act</th>
                <th>no_urut</th>
                <th>no_akun</th>
                <th>no</th>
                <th>nama</th>
                <th>waktu</th>
                <th>kondisi</th>
                <th>kondisi_baru</th>
                <th>status</th>
                <th>operasi</th>
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
            } else if ($(this).text() == "no_urut"){
                $(this).html( '' );
            } else {
                var title = $('#ta thead th').eq( $(this).index() ).text();
                $(this).html('<input type="text" placeholder="Cari '+title+'" class="form-control input-sm" />');
            }
        } );
        
        var table = $('#ta').DataTable({
            oLanguage: {
              sLoadingRecords: '<img src="assets/ajax-loader.gif">'
            },
            "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
            "iDisplayLength": 25,
            "sAjaxSource": "<?php echo site_url('cmesin/list_absenaktual_temp')?>",
            "sServerMethod": "POST",
            lengthChange: true,

            dom: '<"top">BC<"toolbar">frt<"bottom">i<"clear">pl<"clear">',
            colVis: {
                exclude: [ 0,1,2 ],
                buttonText: 'Pilih kolom'
            },
            buttons: [
                {
                    action: function (e, dt, node, config) {
                    window.location.href = "cmesin/upload";
                    },
                    text: '<div class="input-sm glyphicons glyphicon-plus-2"></div>',
                    titleAttr: "Upload csv file"
                },
                {
                    action: function (e, dt, node, config) {
                    window.location.href = "cmesin/tambah";
                    },
                    text: '<div class="input-sm glyphicons glyphicon-plus"></div>',
                    titleAttr: "Tambah data"
                }
            ],

            columnDefs: [
                {
                    targets: [-1],
                    visible: false
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
            scrollCollapse: true,
            paging:         true,
            fixedColumns:   {
                leftColumns: 3
            }
            });
            $("div.toolbar").html('<div class="text-center"><strong>Transaksi Absen Aktual</strong></div>');
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