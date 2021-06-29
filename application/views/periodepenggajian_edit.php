
    <?php echo form_open('cperiodepenggajian/update');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Periode Penggajian Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right"><input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cperiodepenggajian" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        <?php foreach($query->result() as $row):?>
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="rows col-md-3 wellsy btn-sm">
                    <input name="id" value="<?php echo $row->id;?>" type="hidden" class="form-control col-md-4 input-smm" id="id" placeholder="ID" />
                    <div class="form-group">
                        <label for="periodepenggajian">Jabatan</label>
                        <input maxlength="20" name="periodepenggajian" value="<?php echo $row->periode_penggajian;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="periodepenggajian" placeholder="Periode Penggajian" />
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