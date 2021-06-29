
    <?php echo form_open('cabsensp/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
        <div class="rows col-md-11 wells btn-info">
            <div class="rows">
                <div class="col-xs-12 col-sm-6 col-md-8"><strong>Absen Grup Shift Pola Information</strong></div>
                <div class="col-xs-6 col-md-4" align="right">
                    <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsensp" class="btn-lgs btn btn-default">Cancel</a></div>
            </div>
        </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-3 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="grup">Group</label>
                        <input maxlength="20" name="grup" required="required" type="text" class="form-control col-md-4 input-smm" id="grup" placeholder="Group" />
                    </div>
                    <div class="form-group">
                        <label for="urutan">Urutan</label>
                        <input maxlength="20" name="urutan" required="required" type="text" class="form-control col-md-4 input-smm" id="urutan" placeholder="Urutan" />
                    </div>
                    <div class="form-group">
                        <label for="shift">Shift</label>
                        <input maxlength="20" name="shift" required="required" type="text" class="form-control col-md-4 input-smm" id="shift" placeholder="Shift" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>