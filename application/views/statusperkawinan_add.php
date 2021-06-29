<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Status Perkawinan Information</h4>
                <hr>
                <?php echo form_open('cstatusperkawinan/create');?>
                <!-- <p>&nbsp;</p><br /><br />
                <div class="container">
                        <div class="rows col-md-11 wells btn-info">
                            <div class="rows">
                                <div class="col-xs-12 col-sm-6 col-md-8"><strong>Status Perkawinan Information</strong></div>
                                <div class="col-xs-6 col-md-4" align="right">
                                    <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cstatusperkawinan" class="btn-lgs btn btn-default">Cancel</a></div>
                            </div>
                        </div>
                    
                    <div class="rows col-md-11 wells">
                        <div class="row">
                            <div class="rows col-md-3 wellsy btn-sm">
                                <div class="form-group">
                                    <label for="statusperkawinan">Status Perkawinan</label>
                                    <input maxlength="20" name="statusperkawinan" required="required" type="text" class="form-control col-md-4 input-smm" id="statusperkawinan" placeholder="Status Perkawinan" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="statusperkawinan">Status Perkawinan</label>
                            <input maxlength="20" name="statusperkawinan" required="required" type="text" class="form-control col-md-4 input-smm" id="statusperkawinan" placeholder="Status Perkawinan" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input class="btn btn-primary" type="submit" value="Save">
                        <a href="<?=base_URL()?>cstatusperkawinan" class="btn btn-default text-muted">Cancel</a></div>
                    </div>
                </div>
                <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>