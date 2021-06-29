
    <?php echo form_open('cabsensp/update');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Absen Grup Shift Pola Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right"><input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsensp" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        <?php foreach($query->result() as $row):?>
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-3 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="grup">Grup</label>
                        <input value="<?php echo $row->grup;?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm" id="grup" placeholder="Grup" />
                        <input name="grup" value="<?php echo $row->grup;?>" type="hidden" class="form-control col-md-4 input-smm" id="grup" placeholder="Grup" />
                    </div>
                    <div class="form-group">
                        <label for="urutan">Urutan</label>
                        <input value="<?php echo $row->urutan;?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm" id="urutan" placeholder="Urutan" />
                        <input name="urutan" value="<?php echo $row->urutan;?>" type="hidden" class="form-control col-md-4 input-smm" id="urutan" placeholder="Urutan" />
                    </div>
                    <div class="form-group">
                        <label for="shift">Shift</label>
                        <input maxlength="25" name="shift" value="<?php echo $row->shift;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="shift" placeholder="Shift" />
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