<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Absen Information</h4>
                <hr>
                <?php echo form_open('cabsen/create');?>
                <div class="container">
                        
                    
                    <div class="rows col-md-12 wells">
                        <div class="row">
                            <div class="rows col-md-6 wellsy">
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <!-- <input type="search" name="nik" required="required" class="autocomplete form-control input-smm" id="autocomplete" placeholder="NIK" /> -->

                                    <select class="form-control input-smm" name="nik">
                                            <option selected="" disabled>- Pilih NIK -</option>
                                            <?php foreach ($pegawai as $key) :?>
                                                <option><?= $key->nik ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input readonly type="text" name="nama" required="required" class="autocomplete form-control input-smm" id="v_nama" placeholder="Nama" />
                                </div>
                                <div class="form-group">
                                    <label for="enroll">Enroll</label>
                                    <input readonly type="text" name="enroll" required="required" class="autocomplete form-control input-smm" id="v_enroll" placeholder="Enroll" />
                                </div>    
                                <!-- <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input id="tgla" name="tanggal" required="required" type="text" class="form-control input-smm" placeholder="Tanggal" />
                                </div> -->
                                <div class="form-group">
                                  <label for="tanggal_lahir">Tanggal</label>
                                        <div id="" class="input-group date datepicker datepicker-popup">
                                        <input id="tgla" name="tanggal" required="required" type="text" class="form-control input-smm" placeholder="Tanggal" />
                                        <span class="input-group-addon input-group-append border-left">
                                                <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                   </div>
                             </div>
                                <div class="form-group">
                                    <?php
                                            $s = $this->db->query("SELECT distinct nama_shift FROM master_absen_grup_shift");
                                            ?>
                                    <label for="shift">Shift</label>
                                    <select required="required" id="shift" name="shift" class="form-control input-smm">
                                        <option></option>
                                        <?php foreach($s->result() as $o):?>
                                        <option value="<?php echo $o->nama_shift?>"><?php echo $o->nama_shift?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <?php
                                            $ssa = $this->db->query("SELECT status_aktual FROM content_status_aktual");
                                            ?>
                                    <label for="status_aktual">Status Aktual</label>
                                    <select required="required" id="status_aktual" name="status_aktual" class="form-control input-smm">
                                        <option></option>
                                        <?php foreach($ssa->result() as $ro):?>
                                        <option value="<?php echo $ro->status_aktual?>"><?php echo $ro->status_aktual?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="waktu">Waktu</label>
                                    <input required="required" id="datetimepicker4" name="waktu" type="text" class="form-control input-smm" placeholder="Masuk" />
                                </div> -->
                                <div class="form-group">
                                    
                                        <label for="tanggal_lahir">Waktu</label>
                                            <div id="" class="input-group date datepicker datepicker-popup">
                                                <input required="required" id="datetimepicker4" name="waktu" type="text" class="form-control input-smm" placeholder="Masuk" />
                                                <span class="input-group-addon input-group-append border-left">
                                                        <span class="mdi mdi-calendar input-group-text"></span>
                                                </span>
                                           </div>
                                    
                                    
                             </div>
                                <div class="form-group">
                                        <label for="kondisi">CIn/COut</label>
                                        <select required="required" id="kondisi" name="kondisi" class="form-control input-smm">
                                            <option></option>
                                            <option value="COut">COut</option>
                                            <option value="CIn">CIn</option>
                                        </select>
                                </div>
                                
                            </div>
                            
                            <div class="rows col-md-6 wellsy">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea name="keterangan" class="form-control input-smm" rows="2" placeholder="Keterangan"></textarea>
                                </div>
                                <br /><br /><br />
                            </div>
                        </div>
                    </div>
                    <div class="rows col-md-11 wells">
                            <div class="rows">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsen" class="btn btn-default text-muted">Cancel</a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
<script>

// $.datetimepicker.setLocale('en');

// $('#datetimepicker_format').datetimepicker({value:'2015/04/15 05:03', format: $("#datetimepicker_format_value").val()});
// console.log($('#datetimepicker_format').datetimepicker('getValue'));

// $("#datetimepicker_format_change").on("click", function(e){
//     $("#datetimepicker_format").data('xdsoft_datetimepicker').setOptions({format: $("#datetimepicker_format_value").val()});
// });
// $("#datetimepicker_format_locale").on("change", function(e){
//     $.datetimepicker.setLocale($(e.currentTarget).val());
// });

// $('#datetimepicker').datetimepicker({
// dayOfWeekStart : 1,
// lang:'en',
// disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
// startDate:  '2016/01/01'
// });
// $('#datetimepicker').datetimepicker({value:'2015/04/15 05:03',step:10});

// $('.some_class').datetimepicker();

// $('#default_datetimepicker').datetimepicker({
//     formatTime:'H:i',
//     formatDate:'d.m.Y',
//     //defaultDate:'8.12.1986', // it's my birthday
//     defaultDate:'+03.01.1970', // it's my birthday
//     defaultTime:'10:00',
//     timepickerScrollbar:false
// });


// $('#datetimepicker_mask').datetimepicker({
//     mask:'9999/19/39 29:59'
// });



// $('#datetimepicker42').datetimepicker();
// $('#open').click(function(){
//     $('#datetimepicker42').datetimepicker('show');
// });
// $('#close').click(function(){
//     $('#datetimepicker42').datetimepicker('hide');
// });
// $('#reset').click(function(){
//     $('#datetimepicker42').datetimepicker('reset');
// });


// $('#datetimepicker4').datetimepicker();
// $('#open').click(function(){
//     $('#datetimepicker4').datetimepicker('show');
// });
// $('#close').click(function(){
//     $('#datetimepicker4').datetimepicker('hide');
// });
// $('#reset').click(function(){
//     $('#datetimepicker4').datetimepicker('reset');
// });

$.fn.datepicker.defaults.format = "yyyy-mm-dd";
$(".datepicker-popup").datepicker();

</script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('change', 'select[name="nik"]', function(event) {
            var value = $(this).val();
            $.ajax({
                url: '<?= base_url() ?>/Cgajikaryawan/search/'+value,
                type: 'POST',
                dataType: 'JSON',
            })
            .done(function(data) {
                // console.log(data['suggestions'][0].nama);
                $("input[name='nama']").val(data['suggestions'][0].nama);
                $("input[name='enroll']").val(data['suggestions'][0].enroll);
            })
            
            
        });   
    });
</script>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>