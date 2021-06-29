
    <?php echo form_open('cmpph21/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
        <div class="rows col-md-11 wells btn-info">
            <div class="rows">
                <div class="col-xs-12 col-sm-6 col-md-8"><strong>PPH 21 Tarif Information</strong></div>
                <div class="col-xs-6 col-md-4" align="right">
                    <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>/index.php/cppht" class="btn-lgs btn btn-default">Cancel</a></div>
            </div>
        </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-3 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="pph21_metode">PPH21 Metode</label>
                        <input required="required" name="pph21_metode" type="text" class="form-control col-md-4 input-smm" id="pph21_metode" placeholder="PPH21 Metode" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>