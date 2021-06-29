<!DOCTYPE html>
<html lang="en">
    <head> 
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= $this->fungsi->title($active_accordion,$active_menu);?></title>



    <script src="<?= base_url('assets/bootstrap/js/jquery_1.11.3_jquery.min.js')?>"></script>
    
    <!-- <link href="<?= base_url('assets/datepicker/css/datepicker.css')?>" rel="stylesheet">
   
    <script src="<?= base_url('assets/datepicker/js/bootstrap-datepicker.js')?>"></script> -->
    
    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url('assets/datetimepicker/css/jquery.datetimepicker.css')?>"/> -->

    <!-- <script src="<?= base_url('assets/datetimepicker/js/jquery.datetimepicker.full.js')?>"></script> -->
    
    <!-- <link href="<?= base_url('assets/bootstrap/css/bootstrap.css')?>" rel="stylesheet"> -->
    
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
    <script type='text/javascript' src='<?= base_url();?>assets/autocomplete/js/jquery.autocomplete.js'></script>
    
    <link href='<?= base_url();?>assets/autocomplete/css/jquery.autocomplete.css' rel='stylesheet' />

    <script type='text/javascript'>
        var site = "<?= site_url();?>";
        $(function(){
            $('.autocomplete').autocomplete({
                serviceUrl: site+'/Cgajikaryawan/search',
                onSelect: function (suggestion) {
                    $('#v_nama').val(''+suggestion.nama);
                    $('#v_enroll').val(''+suggestion.enroll);
                }
            });
        });
    </script>
    <link type="text/css" href="<?= base_url('assets/timepicker/css/bootstrap-timepicker.min.css')?>" />
    
    <script type="text/javascript" src="<?= base_url('assets/timepicker/js/bootstrap-timepicker.min.js')?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/timepicker/js/bootstrap-timepicker.js')?>"></script>
    

    <script src="<?= base_url('assets/datatables/js/buttons.colVis.min.js')?>"></script>


    <script src="<?= base_url('assets/datatables/js/buttons.html5.min.js')?>"></script>
    <script src="<?= base_url('assets/datatables/js/jszip.min.js')?>"></script>
    <script src="<?= base_url('assets/datatables/js/pdfmake.min.js')?>"></script>
    <script src="<?= base_url('assets/datatables/js/vfs_fonts.js')?>"></script>
    <script src="<?= base_url('assets/datatables/js/currency.js')?>"></script>

    <!-- <link href="<?= base_url();?>assets/kalender/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
    <link href="<?= base_url();?>assets/kalender/css/bootstrap-timepicker.min.css" rel="stylesheet" /> -->
    <link href='<?= base_url('assets/kalender/css/fullcalendar.min.css')?>' rel='stylesheet' />
    <link href='<?= base_url('assets/kalender/css/fullcalendar.print.css')?>' rel='stylesheet' media='print' />
    <link href="<?= base_url('assets/kalender/css/bootstrapValidator.min.css')?>" rel="stylesheet" /> 
    

    <script src='<?= base_url('assets/kalender/js/moment.min.js')?>'></script>
    <script type="text/javascript" src="<?= base_url('assets/kalender/js/bootstrapValidator.min.js')?>"></script>
    <script src="<?= base_url('assets/kalender/js/fullcalendar.min.js')?>"></script>
    <script src='<?= base_url();?>assets/kalender/js/main.js'></script>
<!--     <script src='<?= base_url();?>assets/kalender/js/bootstrap-colorpicker.min.js'></script>
    <script src='<?= base_url();?>assets/kalender/js/bootstrap-timepicker.min.js'></script> -->


    <script type="text/javascript">
        $(document).ready(function(){
            // Sembunyikan alert validasi kosong
            $("#kosong").hide();
        });
    </script>

    <style>
            body {
                padding: 0;
                font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
                font-size: 14px;
            }

            .scroll{
                width: 100%;
                padding: 10px;
                margin-left: 5px;
                overflow: scroll;
              
              /*script tambahan khusus untuk IE */
                scrollbar-face-color: #CE7E00; 
                scrollbar-shadow-color: #FFFFFF; 
                scrollbar-highlight-color: #6F4709; 
                scrollbar-3dlight-color: #11111; 
                scrollbar-darkshadow-color: #6F4709; 
                scrollbar-track-color: #FFE8C1; 
                scrollbar-arrow-color: #6F4709;
            }

            .scrolls{
                width: 100%;
                height: 400px;
                padding: 10px;
                margin-left: 5px;
                overflow: scroll;
              
              /*script tambahan khusus untuk IE */
                scrollbar-face-color: #CE7E00; 
                scrollbar-shadow-color: #FFFFFF; 
                scrollbar-highlight-color: #6F4709; 
                scrollbar-3dlight-color: #11111; 
                scrollbar-darkshadow-color: #6F4709; 
                scrollbar-track-color: #FFE8C1; 
                scrollbar-arrow-color: #6F4709;
            }

            .fc th {
                padding: 10px 0px;
                vertical-align: middle;
                background:#F2F2F2;
            }
            .fc-day-grid-event>.fc-content {
                padding: 4px;
            }
            #calendar {
                max-width: 900px;
                margin: 0 auto;
            }
            .error {
                color: #ac2925;
                margin-bottom: 15px;
            }
            .event-tooltip {
                width:150px;
                background: rgba(0, 0, 0, 0.25);
                color:#000;
                padding:10px;
                position:absolute;
                z-index:10001;
                -webkit-border-radius: 4px;
                -moz-border-radius: 4px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 11px;
            }

    </style>

    <style type="text/css" media="screen">
        label {display: block;}
        body {font-family: Verdana;}
        .form-control {
            border: 1px solid #777676 !important;
        }
        .dataTables_wrapper .dataTable thead th {
            font-weight: bold !important;
        }
        select.form-control{
            color: #777676 !important;
        }
        .h45{
            height: 45px !important;
        }
        .show-img:hover{
            cursor: pointer;
            box-shadow: 0rem 0rem 1rem #ccc;
        }
        .btn-change-periode{
            padding: 1rem;
            display: flex;
            width: 100%;
            text-decoration: none;
            color: #434a54;
        }
        .btn-change-periode:hover{
            display: flex;
            width: 100%;
            text-decoration: none;
            background: #6610f2;
            color: white;
            box-shadow: 0rem 0.5rem 0.5rem #b9aeae;
        }
        .btn-change-periode-select{
            background: #6610f2 !important;
            color: white !important;
            box-shadow: 0rem 0.5rem 0.5rem #b9aeae !important;
        }
        .btn-periode a{
            left: 0;
        }
       
        .change-periode-left{
            width: 50%;
            text-align: left;
            display: flex;
            align-items: center;
        }
        .change-periode-right{
            width: 50%;
            text-align: right;
            display: flex;
            align-items: center;
        }
         .change-periode-right i{
            float: left;
            margin-left: auto;
        }
        .vl {
              border-left: 1px solid #aeacac;
              height: 40px;
            }
    </style>
    <script type="text/javascript">
        var windowSizeArray = [ "width=200,height=200","width=300,height=400,scrollbars=yes" ];
        $(document).ready(function(){
            $('.newWindow').click(function (event){
                var url = $(this).attr("href");
                var windowName = "popUp";//$(this).attr("name");
                var windowSize = windowSizeArray[$(this).attr("rel")];
                window.open(url, windowName, windowSize);
                event.preventDefault();
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
                $(function() {
            $("#tgl").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy/mm/dd'
            });
        });
                
        $(function() {
            $("#tgla").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy/mm/dd'
            });
            $("#tgls").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy/mm/dd'
            });
            $("#tglm").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy/mm/dd'
            });
          });
        });
    </script>

<!-- plugins:css -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/serein/vendors/iconfonts/mdi/font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/serein/vendors/iconfonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/serein/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/serein/vendors/css/vendor.bundle.addons.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/serein/css/style.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/serein/vendors/css/perfect-scrollbar.min.css">
    <link rel="stylesheet" href="path-to/node_modules/dropify/dist/css/dropify.min.css">

        

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url(); ?>assets/serein/vendors/js/vendor.bundle.base.js"></script>
    <script src="<?= base_url(); ?>assets/serein/vendors/js/vendor.bundle.addons.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="<?= base_url(); ?>assets/serein/js/off-canvas.js"></script>
    <script src="<?= base_url(); ?>assets/serein/js/hoverable-collapse.js"></script>
    <script src="<?= base_url(); ?>assets/serein/js/template.js"></script>
    <script src="<?= base_url(); ?>assets/serein/js/settings.js"></script>
    <script src="<?= base_url(); ?>assets/serein/js/todolist.js"></script>
    <script src="<?= base_url(); ?>assets/serein/js/tooltips.js"></script>
    <script src="<?= base_url(); ?>assets/serein/js/popover.js"></script>
    <script src="<?= base_url(); ?>assets/serein/js/dashboard.js"></script>
    <script type="text/javascript">
        // $.fn.datepicker.defaults.format = "yyyy-mm-dd";
        // $(".datepicker-popup").datepicker();
    </script>
  </head> 
  <body>
     <!--  <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand" href="<?= site_url()?>ckaryawan">guna Payroll System</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li><a href="<?= site_url()?>ckaryawan">Home</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Content <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="<?= site_url()?>cagama">Agama</a></li>
                    <li><a href="<?= site_url()?>cbank">Bank</a></li>
                    <li><a href="<?= site_url()?>cdepartemen">Departemen</a></li>
                    <li><a href="<?= site_url()?>cgrup">Grup</a></li>
                    <li><a href="<?= site_url()?>cjabatan">Jabatan</a></li>
                    <li><a href="<?= site_url()?>cmatauang">Mata Uang</a></li>
                    <li role="presentation" class="divider"></li>
                    <li><a href="<?= site_url()?>cperiodepenggajiank">Komponen Gaji Periode</a></li>
                    <li role="presentation" class="divider"></li>
                    <li><a href="<?= site_url()?>cstatusaktual">Status Aktual</a></li>
                    <li><a href="<?= site_url()?>cstatuskaryawan">Status Karyawan</a></li>
                    <li><a href="<?= site_url()?>cstatusperkawinan">Status Perkawinan</a></li>
                  </ul>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Master <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="<?= site_url()?>ckaryawan">Karyawan</a></li>
                    <li><a href="<?= site_url()?>cgajikaryawan">Gaji Karyawan</a></li>
                    <li><a href="<?= site_url()?>ctunjangananak">Tunjangan Anak</a></li>
                    <li><a href="<?= site_url()?>covertime">Overtime</a></li>
                    <li role="presentation" class="divider"></li>
                    <li><a href="<?= site_url()?>cgshift">Grup Shift</a></li>
                    <li role="presentation" class="divider"></li>
                    <li><a href="<?= site_url()?>cjamsostek">Jamsostek</a></li>
                    <li><a href="<?= site_url()?>cjamsostekkd">Jamsostek Komponen Dasar</a></li>
                    <li role="presentation" class="divider"></li>
                    <li><a href="<?= site_url()?>cgaji">Gaji</a></li>
                    <li><a href="<?= site_url()?>cpphk">PPH 21 Komponen</a></li>
                    <li><a href="<?= site_url()?>cpphp">PPH 21 PTKP</a></li>
                    <li><a href="<?= site_url()?>cppht">PPH 21 Tarif</a></li>
                  </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Transaksi <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= site_url()?>cspl">Overtime</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="<?= site_url()?>cgajikaryawanperiode">Gaji Karyawan Periode</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="<?= site_url()?>cthr">PPH 21 - THR</a></li>
                        <li><a href="<?= site_url()?>cbonus">PPH 21 - Bonus Tahunan</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="<?= site_url()?>ctpgajikaryawan">Periode Gaji Karyawan</a></li>
                        <li><a href="<?= site_url()?>cperiodej">Periode Jamsostek</a></li>
                        <li><a href="<?= site_url()?>cperiodepph21">Periode PPH21</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="<?= site_url()?>ctsalary">Lap. Total Salary</a></li>
                        <li><a href="<?= site_url()?>cperiodepph21clear">PPH 21 Clear</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Absensi <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= site_url()?>cabsen">Absen</a></li>
                        <li><a href="<?= site_url()?>ctransct">Absen - Commited</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="<?= site_url()?>cabsensakitcuti">Absen - Sakit/Cuti</a></li>
                    </ul>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administrator <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= site_url()?>cmuser">Manage User</a></li>
                        <li><a href="<?= site_url()?>cbackup">Backup</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="<?= base_url('cperiode'); ?>">Periode</a></li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="<?= base_url('clogin/logout'); ?>">Logout</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Template <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= base_url('download/karyawan.csv'); ?>">Karyawan</a></li>
                        <li><a href="<?= site_url()?>download/absensi.csv">Absensi</a></li>
                    </ul>
                </li>
              </ul>
            </div>
          </div>
    </nav> -->
    <div class="modal fade" id="modalChageFotoProfile" style="display: none;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-3" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Ganti Foto</h5>
              <button type="button" class="close"  data-dismiss="modal">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body m-0 p-0">
             <?php echo form_open_multipart('cdashboard/update_user_image',array('id'=>'formUploadImg'));?>
                <!-- <form id="formUploadImg" method="post" enctype="multipart/form-data"> -->
                    <!-- <input id="inputProfileImg" type="hidden" name="user_img" value="" /> -->
                    <input type="file" name="user_img" class="dropify"/>
                    
                </div>
                <div class="modal-footer">
                  <input  type="submit" class="btn btn-success" value="Save">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
                <!-- </form> -->
                <?= form_close(); ?>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modalChangePassword" style="display: none;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-3" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Ganti Password</h5>
              <button type="button" class="close"  data-dismiss="modal">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
             <?php echo form_open_multipart('cdashboard/change_password',array('id'=>'changePassword'));?>
                <!-- <form id="formUploadImg" method="post" enctype="multipart/form-data"> -->
                    <!-- <input id="inputProfileImg" type="hidden" name="user_img" value="" /> -->
                    <div class="form-group">
                        <label for="password">Password Lama</label>
                        <input id="password_lama" class="form-control valid" name="password_lama" type="password" aria-invalid="false" required="" aria-invalid="true">
                        <p class="text-danger mt-1 text-small alert-pass-lama"></p>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input id="password_baru" class="form-control valid" name="password_baru" type="password" aria-invalid="false" required="" aria-invalid="true">
                        <p class="text-danger mt-1 text-small alert-pass-baru"></p>
                    </div>
                    <div class="form-group">
                        <label for="password">Confirm Password Baru</label>
                        <input id="password_confirm" class="form-control valid" name="password_confirm" type="password" aria-invalid="false" required="" aria-invalid="true">
                        <p class="text-danger mt-1 text-small alert-pass-confirm"></p>
                    </div>
                    
                </div>
                <div class="modal-footer">
                  <input  type="submit" class="btn btn-success" value="Save">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
                <!-- </form> -->
                <?= form_close(); ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#password_lama').keyup(function(event) {
                                var pass = $(this).val();
                                $.ajax({
                                    url: '<?= base_url() ?>cdashboard/get_old_pass',
                                    type: 'POST',
                                    dataType: 'json',
                                    data : {password_lama:pass},
                                })
                                .done(function(data) {
                                    if (data['kode'] == true) {
                                        $('.alert-pass-lama').text('');
                                    }else{
                                        $('.alert-pass-lama').text('Password Lama salah !!!');
                                    }
                                })
                                .fail(function() {
                                    console.log("error");
                                })
                                .always(function() {
                                    console.log("complete");
                                });});

                        $('#password_confirm').keyup(function(event) {
                            var passConfirm = $(this).val();
                            var passBaru = $("#password_baru").val();

                            if (passBaru != passConfirm) {
                                $('.alert-pass-confirm').text('Harus sama dengan Password Baru !!');
                            }else{
                                $('.alert-pass-confirm').text('');
                            }

                        });
                    });
                </script>
                <!-- <script type="text/javascript" src="<?= base_url() ?>assets/ganti-password.js"></script> -->
          </div>
        </div>
      </div>
    <script>
            $('.dropify').dropify();
    </script>
    <div class="modal fade" id="modalChangePriode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-3" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel-3">Ganti Periode</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body m-0 p-0">
                <?php echo form_open('clogin/changePeriode', array('id' => 'form_periode'));?>
                <?php 
                    $query = "SELECT * FROM trans_periode";
                    $result = $this->db->query($query);
                    $no1 = 1;
                    $no2 = 1;
                    $bulan = "";
                    foreach ($result->result() as $key) :
                        $bulan = substr($key->periode, -2);
                        $tahun = substr($key->periode, 0,4);
                        $resultBulan = $this->fungsi->setBulan($bulan);
                ?>
                <a class="btn-change-periode" href="#" id="periode-<?= $no1 ?>" >
                    <div class="change-periode-left">
                        <div class="btn-periode"><?= $resultBulan." ".$tahun?></div>
                    </div>
                    <div class="change-periode-right">
                        <i class="fa fa-angle-right fa-2x"></i>
                    </div>
                </a>
                <?php $no1++; endforeach; ?>
                
            </div>
            <div class="modal-footer">
              <input type="submit" class="btn btn-success disabled btn-submit" value="Pilih" disabled="" />
              <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    <script type="text/javascript">
        $(document).ready(function () {
            <?php foreach ($result->result() as $key) : ?>
            $("#periode-<?= $no2; ?>").click(function () {
                $(this).toggleClass('btn-change-periode-select');
                $('.btn-submit').removeClass('disabled');
                $('.btn-submit').removeAttr('disabled');
                $('#form_periode').attr('action','<?= base_url() ?>clogin/changePeriode/<?= $key->periode; ?>');

            })
            <?php $no2++; endforeach; ?>
        })
    </script>
   <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo w-100" href="index.html"><img src="<?= base_url('assets/serein/images/brand-logo-example.png') ?>" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="<?= site_url()?>cdashboard"><img src="<?= base_url('assets/serein/images/brand-logo-example.png') ?>" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button id="menu_minimze" class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-menu"></span>
        </button>
        <div class="d-flext w-100">
            <div class="dropdown float-right">
              <button type="button" class="btn btn-outline-info" id="dropdownMenuIconButton3" data-toggle="modal" data-target="#modalChangePriode" aria-haspopup="true" aria-expanded="false">
                <?php 
                    $periode = $this->session->userdata['periode'];

                    if ($periode != "") {
                        $bulan = substr($periode, -2);
                        $tahun = substr($periode, 0,4);

                        $resultBulan = $this->fungsi->setBulan($bulan);

                        echo "Periode : ".$resultBulan." ".$tahun;
                    }else{
                        echo "Periode : Kosong !!!";
                    }
                    
                ?>

              </button>
              
            </div>

            
        </div>
        <!-- Dummy Modal Starts -->
              
              <!-- Dummy Modal Ends -->
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <div class="vl mr-3"></div>
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <?php 
                    $image_profile = "";
                    $username = $this->session->userdata('u_name');
                    
                    $this->db->select("foto");
                    $this->db->from('tbl_user');
                    $this->db->where('u_name',$username);
                    $this->db->limit(1);
                    $query = $this->db->get();

                    foreach ($query->result() as $key) {
                        $image_profile = $key->foto;
                    }

                ?>
              <img src="<?= base_url('uploads/users/'.$image_profile) ?>" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalChageFotoProfile">
                <i class="fa  fa-camera-retro  text-primary"></i>
                Ganti Foto
              </a>
              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalChangePassword">
                <i class="fa fa-key text-primary"></i>
                Ubah Password
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?= base_url('clogin/logout'); ?>">
                <i class="mdi mdi-logout text-primary"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
            <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
            <div id="theme-settings" class="settings-panel">
          <i class="settings-close mdi mdi-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles primary"></div>
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles light"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
        <div id="right-sidebar" class="settings-panel">
            <div class="tab-content" id="setting-content">
          <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
            <div class="add-items d-flex px-3 mb-0">
              <form class="form w-100">
                <div class="form-group d-flex">
                  <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                  <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                </div>
              </form>
            </div>
            <div class="list-wrapper px-3">
              <ul class="d-flex flex-column-reverse todo-list">
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Team review meeting at 3.00 PM
                    </label>
                  </div>
                  <i class="remove mdi mdi-close-circle-outline"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Prepare for presentation
                    </label>
                  </div>
                  <i class="remove mdi mdi-close-circle-outline"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Resolve all the low priority tickets due today
                    </label>
                  </div>
                  <i class="remove mdi mdi-close-circle-outline"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Schedule meeting for next week
                    </label>
                  </div>
                  <i class="remove mdi mdi-close-circle-outline"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Project review
                    </label>
                  </div>
                  <i class="remove mdi mdi-close-circle-outline"></i>
                </li>
              </ul>
            </div>
            <div class="events py-4 border-bottom px-3">
              <div class="wrapper d-flex mb-2">
                <i class="mdi mdi-circle-outline text-primary mr-2"></i>
                <span>Feb 11 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Creating component page</p>
              <p class="text-gray mb-0">build a js based app</p>
            </div>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="mdi mdi-circle-outline text-primary mr-2"></i>
                <span>Feb 7 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
              <p class="text-gray mb-0 ">Call Sarah Graves</p>
            </div>
          </div>
          <!-- To do section tab ends -->
          <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
            <div class="d-flex align-items-center justify-content-between border-bottom">
              <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
              <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 font-weight-normal">See All</small>
            </div>
            <ul class="chat-list">
              <li class="list active">
                <div class="profile"><img src="https://via.placeholder.com/40x40" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Thomas Douglas</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">19 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="https://via.placeholder.com/40x40" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <div class="wrapper d-flex">
                    <p>Catherine</p>
                  </div>
                  <p>Away</p>
                </div>
                <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                <small class="text-muted my-auto">23 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="https://via.placeholder.com/40x40" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Daniel Russell</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">14 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="https://via.placeholder.com/40x40" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <p>James Richardson</p>
                  <p>Away</p>
                </div>
                <small class="text-muted my-auto">2 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="https://via.placeholder.com/40x40" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Madeline Kennedy</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">5 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="https://via.placeholder.com/40x40" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Sarah Graves</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">47 min</small>
              </li>
            </ul>
          </div>
          <!-- chat tab ends -->
        </div>
      </div>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item <?= $this->fungsi->active_menu("a",$active_accordion); ?>">
            <a class="nav-link" href="<?= site_url()?>cdashboard">
              <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item mt-3">
                <small class="nav-item font-weight-bold text-small pb-0 text-muted" style="font-size: small; padding-left: 1.25rem">Data Utama</small>
            </li>
          <li class="nav-item <?= $this->fungsi->active_menu("b",$active_accordion); ?> mt-0">
            <a class="nav-link" data-toggle="collapse" href="#referensi" aria-expanded="<?= $this->fungsi->aria_expanded("b",$aria_expanded); ?>" aria-controls="referensi">
              <i class="mdi mdi-puzzle-outline menu-icon"></i>
              <span class="menu-title">Referensi</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= $this->fungsi->sub_menu_show("b",$sub_menu_show); ?>" id="referensi">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(1,$active_menu); ?>" href="<?= site_url()?>cagama">Agama</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(2,$active_menu); ?>" href="<?= site_url()?>cbank">Bank</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(3,$active_menu); ?>" href="<?= site_url()?>cdepartemen">Departemen</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(4,$active_menu); ?>" href="<?= site_url()?>cgrup">Grup</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(5,$active_menu); ?>" href="<?= site_url()?>cjabatan">Jabatan</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(6,$active_menu); ?>" href="<?= site_url()?>cmatauang">Mata Uang</a></li>
                
                <!-- <li class="nav-item border-bottom border-top pb-1 pt-1"> <a class="nav-link" href="<?= site_url()?>cperiodepenggajiank">Komponen Gaji Periode</a></li> -->
                
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(7,$active_menu); ?>" href="<?= site_url()?>cstatusaktual">Status Absen</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(8,$active_menu); ?>" href="<?= site_url()?>cstatuskaryawan">Status Karyawan</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(9,$active_menu); ?>" href="<?= site_url()?>cstatusperkawinan">Status Perkawinan</a></li>
                
              </ul>
            </div>
          </li>
          <li class="nav-item <?= $this->fungsi->active_menu("c",$active_accordion); ?>">
            <a class="nav-link" data-toggle="collapse" href="#master" aria-expanded="<?= $this->fungsi->aria_expanded("c",$aria_expanded); ?>" aria-controls="master">
              <i class="mdi mdi-bullseye-arrow menu-icon"></i>
              <span class="menu-title">Master</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= $this->fungsi->sub_menu_show("c",$sub_menu_show); ?>" id="master">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(10,$active_menu); ?>" href="<?= site_url()?>ckaryawan">Karyawan</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(11,$active_menu); ?>" href="<?= site_url()?>cgajikaryawan">Komponen Gaji</a></li>
               <!--  <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(36,$active_menu); ?>" href="<?= site_url()?>cperiodepenggajiank">Komponen Gaji Periode</a></li> -->
                <!-- <li class="nav-item"> <a class="nav-link" href="<?= site_url()?>ctunjangananak">Tunjangan Anak</a></li> -->
                <!-- <li class="nav-item"> <a class="nav-link" href="<?= site_url()?>covertime">Overtime</a></li> -->
                <!-- <li class="nav-item"> <a class="nav-link" href="<?= site_url()?>cjamsostek">Jamsostek</a></li> -->
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(12,$active_menu); ?>" href="<?= site_url()?>cjamsostekkd">Komponen Jamsostek</a></li>
                <!-- <li class="nav-item"> <a class="nav-link" href="<?= site_url()?>cgaji">Gaji</a></li> -->
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(13,$active_menu); ?>" href="<?= site_url()?>cpphk">Komponen PPH 21</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(14,$active_menu); ?>" href="<?= site_url()?>cgshift">Shift</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(15,$active_menu); ?>" href="<?= site_url()?>cpphp">PTKP</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(16,$active_menu); ?>" href="<?= site_url()?>cppht">PPH 21 tarif</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item mt-3">
                <small class="nav-item font-weight-bold text-small pb-0 text-muted" style="font-size: small; padding-left: 1.25rem">Penggajian</small>
            </li>
          <li class="nav-item <?= $this->fungsi->active_menu("d",$active_accordion); ?> mt-0">
            <a class="nav-link" data-toggle="collapse" href="#konfigurasi" aria-expanded="<?= $this->fungsi->aria_expanded("d",$aria_expanded); ?>" aria-controls="konfigurasi">
              <i class="mdi mdi-arrow-decision-outline menu-icon"></i>

              <span class="menu-title">Konfigurasi</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= $this->fungsi->sub_menu_show("d",$sub_menu_show); ?>" id="konfigurasi">
              <ul class="nav flex-column sub-menu">
                <!-- <li class="nav-item">
                    <small class=" nav-item font-weight-bold text-uppercase text-muted text-small pb-1" style="font-size: small">Konfigurasi</small>
                </li> -->
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(17,$active_menu); ?>" href="<?= site_url()?>cgaji">Struktur Gaji</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(18,$active_menu); ?>" href="<?= site_url()?>ctunjangananak">Tunjangan Anak</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(19,$active_menu); ?>" href="<?= site_url()?>cjamsostek">Jamsostek</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(20,$active_menu); ?>" href="<?= site_url()?>covertime">Overtime</a></li>
                                
              </ul>
            </div>
            </li>
            <li class="nav-item <?= $this->fungsi->active_menu("d-1",$active_accordion); ?>">
                <a class="nav-link" data-toggle="collapse" href="#proses" aria-expanded="<?= $this->fungsi->aria_expanded("d-1",$aria_expanded); ?>" aria-controls="proses">
                  <i class="mdi mdi-camera-timer menu-icon"></i>
                  <span class="menu-title">Proses</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse <?= $this->fungsi->sub_menu_show("d-1",$sub_menu_show); ?>" id="proses">
                  <ul class="nav flex-column sub-menu">
                    
                    <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(21,$active_menu); ?>" href="<?= site_url()?>cgajikaryawanperiode">Bayar gaji</a></li>
                    <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(22,$active_menu); ?>" href="<?= site_url()?>cspl">Overtime</a></li>
                                    
                  </ul>
                </div>
            </li>
        <li class="nav-item <?= $this->fungsi->active_menu("e",$active_accordion); ?>">
            <a class="nav-link" data-toggle="collapse" href="#riwayat" aria-expanded="<?= $this->fungsi->aria_expanded("e",$aria_expanded); ?>" aria-controls="riwayat">
              <i class="mdi mdi-file-document-box-outline menu-icon"></i>
              <span class="menu-title">Riwayat</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= $this->fungsi->sub_menu_show("e",$sub_menu_show); ?>" id="riwayat">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(23,$active_menu); ?>" href="<?= site_url()?>ctpgajikaryawan">Penggajian</a></li>
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(24,$active_menu); ?>" href="<?= site_url()?>cperiodepph21">PPH21 Gaji</a></li>
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(25,$active_menu); ?>" href="<?= site_url()?>cbonus">PPH 21 - Bonus Tahunan</a></li>
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(26,$active_menu); ?>" href="<?= site_url()?>cperiodej">Jamsostek</a></li>
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(27,$active_menu); ?>" href="<?= site_url()?>cthr">PPH 21 - THR</a></li>

                <!-- <li class="nav-item"><a class="nav-link" href="<?= site_url()?>cspl">Overtime</a></li>                 -->
               <!--  <li class="nav-item"><a class="nav-link" href="<?= site_url()?>cgajikaryawanperiode">Gaji Karyawan Periode</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link" href="<?= site_url()?>cthr">PPH 21 - THR</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link" href="<?= site_url()?>cbonus">PPH 21 - Bonus Tahunan</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link" href="<?= site_url()?>ctpgajikaryawan">Periode Gaji Karyawan</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link" href="<?= site_url()?>cperiodej">Periode Jamsostek</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link" href="<?= site_url()?>cperiodepph21">Periode PPH21</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link" href="<?= site_url()?>ctsalary">Lap. Total Salary</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url()?>cperiodepph21clear">PPH 21 Clear</a></li> -->
              </ul>
            </div>
          </li>
          <li class="nav-item <?= $this->fungsi->active_menu("f",$active_accordion); ?>">
            <a class="nav-link" data-toggle="collapse" href="#lain" aria-expanded="<?= $this->fungsi->aria_expanded("f",$aria_expanded); ?>" aria-controls="lain">
              <i class="mdi mdi-more menu-icon"></i>
              <span class="menu-title">Lain</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= $this->fungsi->sub_menu_show("f",$sub_menu_show); ?>" id="lain">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(28,$active_menu); ?>" href="<?= site_url()?>ctsalary">Total Salary</a></li>
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(29,$active_menu); ?>" href="<?= site_url()?>cperiodepph21clear">Rekap PPH 21</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item mt-3">
                <small class="nav-item font-weight-bold text-small pb-0 text-muted" style="font-size: small; padding-left: 1.25rem">Menu Pendukung</small>
            </li>
          <li class="nav-item <?= $this->fungsi->active_menu("g",$active_accordion); ?> mt-0">
            <a class="nav-link" data-toggle="collapse" href="#absensi" aria-expanded="<?= $this->fungsi->aria_expanded("g",$aria_expanded); ?>" aria-controls="absensi">
              <i class="mdi mdi-pencil-box-outline menu-icon"></i>
              <span class="menu-title">Absensi</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= $this->fungsi->sub_menu_show("g",$sub_menu_show); ?>" id="absensi">
              <ul class="nav flex-column sub-menu">
                <!-- <li class="nav-item"><a class="nav-link" href="<?= site_url()?>cabsen">Absen</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url()?>ctransct">Absen - Commited</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url()?>cabsensakitcuti">Absen - Sakit/Cuti</a></li> -->
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(30,$active_menu); ?>" href="<?= site_url()?>cabsen">Data Finger Print</a></li>
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(31,$active_menu); ?>" href="<?= site_url()?>ctransct">Rekap Absen </a></li>
                <li class="nav-item"><a class="nav-link <?= $this->fungsi->active_menu(32,$active_menu); ?>" href="<?= site_url()?>cabsensakitcuti">Izin/Cuti</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item <?= $this->fungsi->active_menu("h",$active_accordion); ?>">
            <a class="nav-link" data-toggle="collapse" href="#administrator" aria-expanded="<?= $this->fungsi->aria_expanded("h",$aria_expanded); ?>" aria-controls="administrator">
              <i class="mdi mdi-account-multiple menu-icon"></i>
              <span class="menu-title">Administrator</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= $this->fungsi->sub_menu_show("h",$sub_menu_show); ?>" id="administrator">
              <ul class="nav flex-column sub-menu">
                <!-- <li class="nav-item"> <a class="nav-link" href="<?= site_url()?>cmuser">Manage User</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= site_url()?>cbackup">Backup</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('cperiode'); ?>">Periode</a></li> -->
                <!-- <li class="nav-item"> <a class="nav-link" href="<?= base_url('clogin/logout'); ?>">Logout</a></li> -->
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(33,$active_menu); ?>" href="<?= site_url()?>cmuser">Pengguna</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(34,$active_menu); ?>" href="<?= base_url('cperiode'); ?>">Periode</a></li>
                <li class="nav-item"> <a class="nav-link <?= $this->fungsi->active_menu(35,$active_menu); ?>" href="<?= site_url()?>cbackup">Backup</a></li>
              </ul>
            </div>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#template" aria-expanded="false" aria-controls="template">
              <i class="mdi mdi-table-large menu-icon"></i>
              <span class="menu-title">Template</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="template">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="<?= base_url('download/karyawan.csv'); ?>">Karyawan</a></li>
                <li class="nav-item"> <a class="nav-link" href="<?= site_url()?>download/absensi.csv">Absensi</a></li>
              </ul>
            </div>
          </li> -->
        </ul>
      </nav>
      <!-- partial -->
      
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        