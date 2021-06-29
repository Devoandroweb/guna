<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">User Information</h4>
                <hr>

                <?php echo form_open('cmuser/update');?>
                <div class="container">
                        
                    <?php foreach($query->result() as $row):?>
                    <div class="rows col-md-12 wells m-0 p-0">
                        <div class="row">
                            <div class="rows col-md-12 wellsy btn-sm">
                                    <div class="form-group">
                                        <label for="id">ID</label>
                                        <input value="<?php echo $row->id;?>" disabled="disabled" type="text" class="form-control input-smm" id="nik" placeholder="ID" />
                                        <input name="id" value="<?php echo $row->id;?>" type="hidden" class="form-control input-smm" id="nik" placeholder="ID" />
                                    </div>
                                   
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input maxlength="25" name="username" value="<?php echo $row->u_name;?>" required="required" type="text" class="form-control input-smm" id="username" placeholder="Username" />
                                    </div>
                                    <div class="form-group">
                                        <label for="aktif">Aktif</label>
                                        <select name="aktif" required="required" class="form-control input-smm">
                                            <?php
                                            if ($row->aktif == "Y"){
                                            ?>
                                            <option selected="selected" value="Y">Ya</option>
                                            <option value="N">Tidak</option>
                                            <?php
                                            } else {
                                            ?>
                                            <option value="Y">Ya</option>
                                            <option selected="selected" value="N">Tidak</option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">
                                    <a href="<?=base_URL()?>cmuser" class="btn btn-default text-dark">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

<?php include "footer.php" ?>
</body>
</html>