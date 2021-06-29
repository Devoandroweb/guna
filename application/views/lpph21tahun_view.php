  <div class="container">
    <br /><br /><br />
    <table id="ta" class="stripe row-border order-column table-hover table table-condensed table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Act</th>
                <th>periode</th>
                <th>nik</th>
                <th>nama</th>
                <th>pph21_metode</th>
                <th>status</th>
                <th>masa_pajak</th>
                <th>base_salary</th>
                <th>car_allow</th>
                <th>insentives_meals_others</th>
                <th>gaji</th>
                <th>tunjangan_pph</th>
                <th>premi_asuransi</th>
                <th>thr</th>
                <th>jumlah_bruto</th>
                <th>biaya_jabatan</th>
                <th>iuran_pensiun</th>
                <th>jumlah_pengurang</th>
                <th>penghasilan_netto</th>
                <th>ptkp</th>
                <th>pkp</th>
                <th>pph21</th>
                <th>lastupdate</th>
                <th>user_id</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Act</th>
                <th>periode</th>
                <th>nik</th>
                <th>nama</th>
                <th>pph21_metode</th>
                <th>status</th>
                <th>masa_pajak</th>
                <th>base_salary</th>
                <th>car_allow</th>
                <th>insentives_meals_others</th>
                <th>gaji</th>
                <th>tunjangan_pph</th>
                <th>premi_asuransi</th>
                <th>thr</th>
                <th>jumlah_bruto</th>
                <th>biaya_jabatan</th>
                <th>iuran_pensiun</th>
                <th>jumlah_pengurang</th>
                <th>penghasilan_netto</th>
                <th>ptkp</th>
                <th>pkp</th>
                <th>pph21</th>
                <th>lastupdate</th>
                <th>user_id</th>
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
            } else if ($(this).text() == "periode"){
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
            "sAjaxSource": "<?php echo site_url('clpph21tahun/list_lpph21tahun')?>",
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
                    window.location.href = "clpph21tahun/upload";
                    },
                    text: '<div class="input-sm glyphicons glyphicon-plus-2"></div>',
                    titleAttr: "Upload csv file"
                },
                
                {
                    extend:    'excelHtml5',
                    text:      '<div class="input-sm glyphicons glyphicon-plus-3"></div>',
                    titleAttr: 'Download excel file',
                    exportOptions: {
                        columns: [ 2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21 ]
                    }
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
            $("div.toolbar").html('<div class="text-center"><strong>PPH21 Tahunan</strong></div>');
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