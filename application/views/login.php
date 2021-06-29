<!DOCTYPE html>
<html>
<head> 
    <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <title>Serein Admin</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/bootstrap/images/payroll icon.ico" />
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" /> -->

    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/floating-labels.css" /> -->

	
	<!-- plugins:css -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/serein/vendors/iconfonts/mdi/font/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/serein/vendors/css/vendor.bundle.base.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/serein/vendors/css/vendor.bundle.addons.css">
	<!-- endinject -->
	<!-- plugin css for this page -->
	<!-- End plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/serein/css/style.css">
	<style type="text/css">
		.custom-margin-top{
			margin-top: 6rem !important;
		}
	</style>
	<body>
		<!-- <div class="form-signin" id="login-box">
			<form action="" method="post">
		      	<div class="text-center mb-10">
		        	<h4>guna Payroll System Login</h4>
		      	</div>
		      	<p>&nbsp;</p>
					<?php 
					if (!empty($pesan)) {
						echo '<div style="color: red;">' . $pesan . '</div>';
					}
					?>
		      	<div class="form-group">
		      		<input type="text" name="u_name" id="u_name" class="form-control form-control-bg" placeholder="Username" required autofocus />
		        	<?php echo form_error('u_name', '<p style="color: red;">', '</p>');?>
		      	</div>

		      	<div class="form-group">
		      		<input id="pass_word" type="password" name="pass_word" class="form-control form-control-bg" placeholder="Password" required />
		        	<?php echo form_error('pass_word', '<p style="color: red;">', '</p>');?>
		      	</div>
		      	<?php 
				$q_periode = $this->db->query("SELECT * FROM trans_periode");
				?>	
		      	<div class="form-group">
		      		<select name="periode" required class="form-control form-control-bg">
	                	<option></option>
	                	<?php foreach($q_periode->result() as $row):?>
	                	<option value="<?php echo $row->periode; ?>"><?php echo $row->periode; ?></option>
	            		<?php endforeach;?>
	            	</select>
		      	</div>
		      	<button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
		      	<p class="mt-5 mb-3 text-muted text-center"><?php echo date('Y'); ?> by guna Software</p>
		    </form>
		</div>  -->

		<div class="container-scroller">
		    <div class="container-fluid page-body-wrapper full-page-wrapper">
		      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
		        <div class="row flex-grow">
		          <div class="col-lg-6 d-flex align-items-center justify-content-center">
		            <div class="auth-form-transparent text-left p-3 h-100 custom-margin-top">
		              <div class="brand-logo">
		                <!-- <img src="../../../../images/logo.svg" alt="logo"> -->
		              </div>
		              	<div class="row mb-4">
		              		<div class="col text-center">
		              			<img src="<?= base_url('assets/serein/images/brand-logo-example.png') ?>" width="60%">
		              		</div>
		              	</div>
			    		<?php 
						if (!empty($pesan)) {
							echo '<div style="color: red;">' . $pesan . '</div>';
						}
						?>	
		              <form class="pt-4 mt-4" action="" method="post">
		                <div class="form-group">
		                  <label for="exampleInputEmail">Username</label>
		                  <div class="input-group">
		                    <div class="input-group-prepend bg-transparent">
		                      <span class="input-group-text bg-transparent border-right-0">
		                        <i class="mdi mdi-account-outline text-primary"></i>
		                      </span>
		                    </div>
		                    <input type="text" name="u_name" id="u_name" class="form-control form-control-lg border-left-0" placeholder="Username" required autofocus >
		                    <?php echo form_error('u_name', '<p style="color: red;">', '</p>');?>
		                  </div>
		                </div>
		                <div class="form-group">
		                  <label for="exampleInputPassword">Password</label>
		                  <div class="input-group">
		                    <div class="input-group-prepend bg-transparent">
		                      <span class="input-group-text bg-transparent border-right-0">
		                        <i class="mdi mdi-lock-outline text-primary"></i>
		                      </span>
		                    </div>
		                    <input id="pass_word" type="password" name="pass_word" class="form-control form-control-lg border-left-0" placeholder="Password" required="">
		                    <?php echo form_error('pass_word', '<p style="color: red;">', '</p>');?>           
		                  </div>
		                  <?php 
							$q_periode = $this->db->query("SELECT * FROM trans_periode");
							?>
		                </div>
		              <!--   <div class="form-group">
				      		<select name="periode" required class="form-control form-control-lg border-left-0">
			                	<option></option>
			                	<?php foreach($q_periode->result() as $row):?>
			                	<option value="<?php echo $row->periode; ?>"><?php echo $row->periode; ?></option>
			            		<?php endforeach;?>
			            	</select>
				      	</div> -->
		                <div class="my-2 d-flex justify-content-between align-items-center">
		                  <div class="form-check">
		                    <label class="form-check-label text-muted">
		                      <input type="checkbox" class="form-check-input">
		                      Keep me signed in
		                    </label>
		                  </div>
		                  <a href="#" class="auth-link text-black">Forgot password?</a>
		                </div>
		                <div class="my-3">
		                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">LOGIN</button>
		                </div>
		                <!-- <div class="mb-2 d-flex">
		                  <button type="button" class="btn btn-facebook auth-form-btn flex-grow mr-1">
		                    <i class="mdi mdi-facebook mr-2"></i>Facebook
		                  </button>
		                  <button type="button" class="btn btn-google auth-form-btn flex-grow ml-1">
		                    <i class="mdi mdi-google mr-2"></i>Google
		                  </button>
		                </div>
		                <div class="text-center mt-4 font-weight-light">
		                  Don't have an account? <a href="register-2.html" class="text-primary">Create</a>
		                </div> -->
		              </form>
		              <div class="row" style="position: absolute; bottom: 15px; right: 0; left: 0;">	
	            		<div class="col text-center">
	            				<p class=" m-auto">	Powered By <a class="text-info" href="https://www.aptikma.co.id/" target="_blank">	Aptikma Tekhnologi Indonesia</a></p>	
	            		</div>
		            </div>
		            </div>

		          </div>
		          <div class="col-lg-6 login-half-bg d-flex flex-row">
		          	<img class="img w-100" src="<?php echo base_url(); ?>assets/serein/images/lightbox/thumb-v-v-2.jpg">
		           
		          </div>
		        </div>
		      </div>
		      <!-- content-wrapper ends -->
		    </div>
		    <!-- page-body-wrapper ends -->
		  </div>


		<script src="<?php echo base_url(); ?>assets/serein/vendors/js/vendor.bundle.base.js"></script>
		<script src="<?php echo base_url(); ?>assets/serein/vendors/js/vendor.bundle.addons.js"></script>
		<!-- endinject -->
		<!-- inject:js -->
		<script src="<?php echo base_url(); ?>assets/serein/js/off-canvas.js"></script>
		<script src="<?php echo base_url(); ?>assets/serein/js/hoverable-collapse.js"></script>
		<script src="<?php echo base_url(); ?>assets/serein/js/template.js"></script>
		<script src="<?php echo base_url(); ?>assets/serein/js/settings.js"></script>
		<script src="<?php echo base_url(); ?>assets/serein/js/todolist.js"></script>
</body>
</html>



