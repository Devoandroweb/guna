
    <?php echo form_open('cabsensi/update');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Insentive Kehadiran Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right"><input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsensi" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        <?php foreach($query->result() as $row):?>
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-1 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="nilai_insentive_kehadiran">Nilai Intensive</label>
                        <input name="id" value="<?php echo $row->id;?>" type="hidden" class="form-control col-md-4 input-smm" id="id" placeholder="ID" />
                        <input maxlength="25" name="nilai_insentive_kehadiran" value="<?php echo $row->nilai_insentive_kehadiran;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="nilai_insentive_kehadiran" placeholder="Nilai Insentive Kehadiran" />
                    </div>
                </div>
                
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>