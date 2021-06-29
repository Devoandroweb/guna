
    <?php echo form_open('cabsena/update');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Transaksi Absen Aktual</strong></div>
                    <div class="col-xs-6 col-md-4" align="right"><input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsena" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        <?php foreach($query->result() as $row):?>
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-2 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input value="<?php echo $row->nik;?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm" id="nik" placeholder="NIK" />
                            <input name="nik" value="<?php echo $row->nik;?>" type="hidden" class="form-control col-md-4 input-smm" id="nik" placeholder="NIK" />
                        </div>
                       
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input maxlength="25" name="nama" disabled="disabled" value="<?php echo $row->nama;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="nama" placeholder="Nama" />
                        </div>
             
                        <div class="form-group">
                            <label for="departemen">Departemen</label>
                            <input maxlength="25" name="Departemen" disabled="disabled" value="<?php echo $row->departemen;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="departemen" placeholder="Departemen" />
                        </div>
         
                        <div class="form-group">
                            <label for="grup">Grup</label>
                            <input maxlength="25" name="grup" disabled="disabled" value="<?php echo $row->grup;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="grup" placeholder="Grup" />
                        </div>
    
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input maxlength="25" name="jabatan" disabled="disabled" value="<?php echo $row->jabatan;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="jabatan" placeholder="Jabatan" />
                        </div> 
                </div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input id="tgla" name="tanggal" value="<?php echo $row->tanggal;?>" required="required" type="text" class="form-control col-md-4 input-smm" placeholder="Tanggal" />
                        <input name="tanggal" value="<?php echo $row->tanggal;?>" type="hidden" class="form-control col-md-4 input-smm" placeholder="Tanggal" />
                    </div>
                    <?php
                    $qshift = $this->db->query("select nama_shift from master_absen_grup_shift where grup='$row->grup' and nama_shift not like '$row->shift'");
                    ?>
                    <div class="form-group">
                        <label for="masuk">Shift</label>
                        <?php
                        $sshift = $this->db->query("SELECT * FROM master_absen_grup_shift WHERE grup='$row->grup'")->result();
                        ?>
                        <select required="required" id="nama_shift" name="nama_shift" class="form-control col-md-4 input-smm">
                            <option selected="selected" value="<?php echo $row->shift;?>"><?php echo $row->shift;?></option>
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
                            <select required="required" id="status_aktual" name="status_aktual" class="form-control col-md-4 input-smm">
                                <option selected="selected" value="<?php echo $row->status_aktual;?>"><?php echo $row->status_aktual;?></option>
                                <?php foreach($ssa->result() as $ro):?>
                                <option value="<?php echo $ro->status_aktual?>"><?php echo $ro->status_aktual?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="masuk">Masuk</label>
                            <input id="datetimepicker4" name="masuk" value="<?php echo $row->masuk;?>" type="text" class="form-control col-md-4 input-smm" placeholder="Masuk" />
                        </div>
                        
                        <div class="form-group">
                            <label for="pulang">Pulang</label>
                            <input id="datetimepicker42" name="pulang" value="<?php echo $row->pulang;?>" type="text" class="form-control col-md-4 input-smm" placeholder="Pulang" />
                        </div>
                        <?php
                            $sketerangan = $this->db->query("SELECT keterangan FROM master_absen_aktual WHERE nik='$row->nik' and tanggal='$row->tanggal'")->row();
                        ?>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" class="form-control col-md-4 input-smm" rows="2" placeholder="Keterangan"><?php echo $sketerangan->keterangan;?></textarea>
                        </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
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