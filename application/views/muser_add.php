<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Grup Information</h4>
                <hr>

                <?php echo form_open('cmuser/create');?>
                <div class="container">
                        
                    
                    <div class="rows col-md-12 wells m-0 p-0">
                        <div class="row">
                            <div class="rows col-md-12 wellsy btn-sm">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input maxlength="25" name="username" required="required" type="text" class="form-control input-smm" id="username" placeholder="Username" />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input maxlength="25" name="password" required="required" type="text" class="form-control input-smm" id="password" placeholder="Password" />
                                    </div>
                                    <div class="form-group">
                                        <label for="aktif">Aktif</label>
                                        <select name="aktif" required="required" class="form-control input-smm">
                                            <option></option>
                                            <option value="Y">Ya</option>
                                            <option value="N">Tidak</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="level">Level</label>
                                        <select name="level" required="required" class="form-control input-smm">
                                            <option></option>
                                            <option value="SYS">SYS</option>
                                        </select>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cmuser" class="btn btn-default text-muted">Cancel</a>
                            </div>
                        </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>