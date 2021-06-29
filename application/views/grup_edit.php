    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Grup Information</h4>
                    <hr>
                    <?php echo form_open('cgrup/save_update');?>
                    <div class="">                            
                        <?php foreach($query->result() as $row):?>
                        <div class="row">
                            <div class="col">
                                <input name="id_grup" value="<?php echo $row->id_grup;?>" type="hidden" class="form-control col-md-4 input-smm" id="id_grup" placeholder="ID" />
                                <div class="form-group">
                                    <label for="grup">Grup</label>
                                    <input maxlength="20" name="grup" value="<?php echo $row->grup;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="grup" placeholder="Grup" />
                                </div>
                                <div class="form-group">
                                    <label for="jenis">Jenis</label>
                                    <select name="jenis" class="form-control col-md-4 input-smm" required="required">
                                        <?php 
                                        $selectShift = "";
                                        $selectNonSift = "";
                                        $selectWeekSift = "";
                                        $query = "SELECT * FROM content_grup WHERE jenis='".$row->jenis."'";
                                        $result = $this->db->query($query);
                                        foreach ($result->result() as $row) {
                                           if ($row->jenis == "SHIFT") {
                                               $selectShift = 'selected=""';
                                           }
                                           if ($row->jenis == "NONSHIFT") {
                                               $selectNonSift = 'selected=""';
                                           }
                                           if ($row->jenis == "WEEKSHIFT") {
                                               $selectWeekSift = 'selected=""';
                                           }
                                        }
                                        ?>
                                        <option value="SHIFT" <?= $selectShift; ?>>SHIFT</option>
                                        <option value="NONSHIFT" <?= $selectNonSift; ?>>NONSHIFT</option>
                                        <option value="WEEKSHIFT" <?= $selectWeekSift; ?>>WEEKSHIFT</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="row">
                                <div class="col">
                                    <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cgrup" class="btn btn-light text-muted">Cancel</a>
                                </div>
                            </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
<?php include "footer.php" ?>
</body>
</html>