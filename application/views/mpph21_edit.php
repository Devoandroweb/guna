
    <?php echo form_open('cppht/update');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
        <div class="rows col-md-11 wells btn-info">
            <div class="rows">
                <div class="col-xs-12 col-sm-6 col-md-8"><strong>PPH 21 Tarif Information</strong></div>
                <div class="col-xs-6 col-md-4" align="right">
                    <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cppht" class="btn-lgs btn btn-default">Cancel</a></div>
            </div>
        </div>
        <?php foreach($query->result() as $row):?>
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-3 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="kode_gaji">Kode Tarif</label>
                        <input value="<?php echo $row->kode_tarif;?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm" id="kode_tarif" />
                        <input name="kode_tarif" value="<?php echo $row->kode_tarif;?>" type="hidden" class="form-control col-md-4 input-smm" id="kode_tarif" />
                    </div>
                    <div class="form-group">
                        <label for="batas_atas">Batas Atas</label>
                        <input value="<?php echo $row->batas_atas;?>" required="required"  name="batas_atas" type="text" class="form-control col-md-4 input-smm" id="batas_atas" placeholder="Batas Atas" />
                    </div>
                    <div class="form-group">
                        <label for="batas_atas">Batas Bawah</label>
                        <input value="<?php echo $row->batas_bawah;?>" required="required" name="batas_bawah" type="text" class="form-control col-md-4 input-smm" id="batas_bawah" placeholder="Batas Bawah" />
                    </div>
                    <div class="form-group">
                        <label for="tarif">Tarif</label>
                        <input value="<?php echo $row->tarif;?>" required="required" name="tarif" type="text" class="form-control col-md-4 input-smm" id="tarif" placeholder="Tarif" />
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