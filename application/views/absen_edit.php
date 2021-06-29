<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Absen Information</h4>
                <hr>
                <?php echo form_open('cabsen/update');?>
                <div class="container">
                        
                    <?php foreach($query->result() as $row):?>
                    <div class="rows col-md-12 wells">
                        <div class="row">
                            <div class="rows col-md-6 wellsy">
                                    <div class="form-group">
                                        <label for="enroll">Enroll</label>
                                        <input name="enroll" value="<?php echo $row->enroll;?>" disabled="disabled" type="text" class="form-control input-smm" id="enroll" placeholder="Enroll" />
                                        <input name="no" value="<?php echo $row->no;?>" type="hidden" class="form-control input-smm" id="no" />
                                    </div>
                                    <?php
                                    $snama = $this->db->query("SELECT nama,departemen,grup,jabatan FROM master_karyawan where enroll='$row->enroll'")->row();
                                    if (!empty($snama)){
                                    ?>
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input maxlength="25" name="nama" disabled="disabled" value="<?php echo $snama->nama;?>" required="required" type="text" class="form-control input-smm" id="nama" placeholder="Nama" />
                                    </div>
                         
                                    <div class="form-group">
                                        <label for="departemen">Departemen</label>
                                        <input maxlength="25" name="Departemen" disabled="disabled" value="<?php echo $snama->departemen;?>" required="required" type="text" class="form-control input-smm" id="departemen" placeholder="Departemen" />
                                    </div>
                     
                                    <div class="form-group">
                                        <label for="grup">Grup</label>
                                        <input maxlength="25" name="grup" disabled="disabled" value="<?php echo $snama->grup;?>" required="required" type="text" class="form-control input-smm" id="grup" placeholder="Grup" />
                                    </div>
                
                                    <div class="form-group">
                                        <label for="jabatan">Jabatan</label>
                                        <input maxlength="25" name="jabatan" disabled="disabled" value="<?php echo $snama->jabatan;?>" required="required" type="text" class="form-control input-smm" id="jabatan" placeholder="Jabatan" />
                                    </div> 
                                    <?
                                    } 
                                    }
                                    ?>
                            </div>
                            <div class="rows col-md-6 wellsy">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input id="tgla" name="tanggal" value="<?php echo $row->tanggal;?>" required="required" type="text" class="form-control input-smm" placeholder="Tanggal" />
                                    <input name="tanggal" value="<?php echo $row->tanggal;?>" type="hidden" class="form-control input-smm" placeholder="Tanggal" />
                                </div>
                                <?php
                                $qshift = $this->db->query("select distinct nama_shift from master_absen_grup_shift where nama_shift not like '$row->shift'");
                                ?>
                                <div class="form-group">
                                    <label for="masuk">Shift</label>
                                    <?php
                                    $sshift = $this->db->query("SELECT * FROM master_absen_grup_shift")->result();
                                    ?>
                                    <select id="nama_shift" name="nama_shift" class="form-control input-smm h45">
                                        <option selected="selected" value="<?php echo $row->shift;?>"><?php echo $row->shift;?></option>
                                        <option></option>
                                        <?php foreach($qshift->result() as $rs):?>
                                        <option><?php echo $rs->nama_shift?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                    <div class="form-group">
                                        <label for="status_aktual">Status Aktual</label>
                                            <?php
                                            $ssa = $this->db->query("SELECT status_aktual FROM content_status_aktual");
                                            ?>
                                        <select required="required" id="status_aktual" name="status_aktual" class="form-control input-smm h45">
                                            <option selected="selected" value="<?php echo $row->status_aktual;?>"><?php echo $row->status_aktual;?></option>
                                            <option></option>
                                            <?php foreach($ssa->result() as $ro):?>
                                            <option value="<?php echo $ro->status_aktual?>"><?php echo $ro->status_aktual?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="waktu">Waktu</label>
                                        <input id="datetimepicker4" name="waktu" value="<?php echo $row->waktu;?>" type="text" class="form-control input-smm" placeholder="Masuk" />
                                    </div>
                                            <?php
                                            $sb = $this->db->query("SELECT kondisi FROM master_mesin where enroll='$row->enroll' and tanggal='$row->tanggal' and kondisi!='$row->kondisi'");
                                            ?>
                                    <div class="form-group">
                                        <label for="kondisi">CIn/COut</label>
                                        <select required="required" id="kondisi" name="kondisi" class="form-control input-smm h45">
                                            <?php 
                                            if($row->kondisi == "COut"){
                                                echo'
                                                <option selected="selected" value="COut">COut</option>
                                                <option value="CIn">CIn</option>
                                                ';
                                            } else if($row->kondisi == "CIn"){
                                                echo'
                                                <option value="COut">COut</option>
                                                <option selected="selected" value="CIn">CIn</option>
                                                ';
                                            } else{
                                                echo'
                                                <option value="COut">COut</option>
                                                <option value="CIn">CIn</option>
                                                ';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea name="keterangan" class="form-control input-smm" rows="2" placeholder="Keterangan"><?php echo $row->keterangan;?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php 
                    } else {
                        echo'<div id="alert" class="alert alert-danger" role="alert">Unable load data karyawan, harap periksa kembali enroll.</div>';
                    } 
                    ?>
                    <?php endforeach;?>
                    <div class="rows col-md-12 wells">
                        <div class="rows">
                            <input class="btn btn-primary" type="submit" value="Save">
                            <a href="<?=base_URL()?>cabsen" class="btn btn-default text-muted">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script>

$.datetimepicker.setLocale('en');

$('#datetimepicker_format').datetimepicker({value:'2015/04/15 05:03', format: $("#datetimepicker_format_value").val()});
console.log($('#datetimepicker_format').datetimepicker('getValue'));

$("#datetimepicker_format_change").on("click", function(e){
    $("#datetimepicker_format").data('xdsoft_datetimepicker').setOptions({format: $("#datetimepicker_format_value").val()});
});
$("#datetimepicker_format_locale").on("change", function(e){
    $.datetimepicker.setLocale($(e.currentTarget).val());
});

$('#datetimepicker').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
startDate:  '2016/01/01'
});
$('#datetimepicker').datetimepicker({value:'2015/04/15 05:03',step:10});

$('.some_class').datetimepicker();

$('#default_datetimepicker').datetimepicker({
    formatTime:'H:i',
    formatDate:'d.m.Y',
    //defaultDate:'8.12.1986', // it's my birthday
    defaultDate:'+03.01.1970', // it's my birthday
    defaultTime:'10:00',
    timepickerScrollbar:false
});


$('#datetimepicker_mask').datetimepicker({
    mask:'9999/19/39 29:59'
});



$('#datetimepicker42').datetimepicker();
$('#open').click(function(){
    $('#datetimepicker42').datetimepicker('show');
});
$('#close').click(function(){
    $('#datetimepicker42').datetimepicker('hide');
});
$('#reset').click(function(){
    $('#datetimepicker42').datetimepicker('reset');
});


$('#datetimepicker4').datetimepicker();
$('#open').click(function(){
    $('#datetimepicker4').datetimepicker('show');
});
$('#close').click(function(){
    $('#datetimepicker4').datetimepicker('hide');
});
$('#reset').click(function(){
    $('#datetimepicker4').datetimepicker('reset');
});

</script>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>