
    <?php echo form_open('cabsena/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Absensi Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right">
                        <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsena" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-2 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="search" name="nik" required="required" class="autocomplete form-control col-md-4 input-smm" id="autocomplete" placeholder="NIK" />
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input readonly type="text" name="nama" required="required" class="autocomplete form-control col-md-4 input-smm" id="v_nama" placeholder="Nama" />
                    </div>
                        
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input id="tgla" name="tanggal" required="required" type="text" class="form-control col-md-4 input-smm" placeholder="Tanggal" />
                    </div>
                    <div class="form-group">
                        <label for="status_aktual">Status Aktual</label>
                        <select required="required" id="status_aktual" name="status_aktual" class="form-control col-md-4 input-smm">
                            <option></option>
                            <?php foreach($query->result() as $row):?>
                            <option><?php echo $row->status_aktual?></option>
                            <?php endforeach;?>
                        </select>
                    </div> 
                    <div class="form-group">
                            <label for="mulai">Masuk</label>
                            <input id="datetimepicker4" name="mulai" type="text" class="form-control col-md-4 input-smm" placeholder="Masuk" />
                        </div>
                        
                        <div class="form-group">
                            <label for="selesai">Pulang</label>
                            <input id="datetimepicker42" name="selesai" type="text" class="form-control col-md-4 input-smm" placeholder="Pulang" />
                        </div>
                    <div class="form-group">
                        <label for="shift">Shift</label>
                        <select required="required" id="shift" name="shift" class="form-control col-md-4 input-smm">
                            <option></option>
                            <option value="shift-1">Shift-1</option>
                            <option value="shift-2">Shift-2</option>
                            <option value="shift-3">Shift-3</option>
                            <option value="non-shift">Non Shift</option>
                        </select>
                    </div>
                </div>
                
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control col-md-4 input-smm" rows="2" placeholder="Keterangan"></textarea>
                    </div>
                    <br /><br /><br />
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