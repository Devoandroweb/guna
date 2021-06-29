    <?php echo form_open('ccpaswod/gpaswod');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Change Password Form</strong></div>
                    <div class="col-xs-6 col-md-4" align="right">
                        <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>ckaryawan" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-2 btn-sm">&nbsp;</div>
                <div class="rows col-md-7 wellp btn-sm">
                        <div class="form-group">
	                        <label for="p1">Password Lama</label>
	                        <input maxlength="25" name="p1" required="required" type="password" class="form-control col-md-4 input-smm" id="p1" placeholder="Password Lama" />
	                    </div>
                        <div class="form-group">
	                        <label for="p2">Password Baru</label>
	                        <input maxlength="25" name="p2" required="required" type="password" class="form-control col-md-4 input-smm" id="p2" placeholder="Password Baru" />
	                    </div>
                        
                        <div class="form-group">
	                        <label for="p3">Password Baru (Lagi)</label>
	                        <input maxlength="25" name="p3" required="required" type="password" class="form-control col-md-4 input-smm" id="p3" placeholder="Password Baru (Lagi)" />
	                        <p>&nbsp;</p>
	                        <?=$this->session->flashdata("k")?>
	                    </div>
	                    <div class="form-group">
		                    <script type="text/javascript">
								$(document).ready(function(){
									$(" #alert" ).fadeOut(4000);
								});
							</script>
						</div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>