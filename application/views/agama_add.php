
    
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Agama Information</h4>
                    <hr>
                    <?php echo form_open('cagama/create');?>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="agama">Agama</label>
                                <input maxlength="20" name="agama" required="required" type="text" class="form-control col-md-4 input-smm" id="agama" placeholder="Agama" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input class="btn btn-primary" type="submit" value="Save">
                            <a href="<?=base_URL()?>cagama" class="btn btn-default text-muted">Cancel</a>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>