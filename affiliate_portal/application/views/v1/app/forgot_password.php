 <main>
 	<div class="row">
 		<div class="col-xs-12 background-image-css" style="background-image: url('<?= image_url("forgotpassword.png"); ?>')">
 			<div class="top-div">
 				<div class="parent-div">
 					<form action="" class="form-horizontal" method="post">
 						<div class="first-div-password">
 							<div class="high-lighted-text-div">
 								<div class="highlighted-text-upper-div">
 									<p class="forgot-password-text text-center forgot-text">
 										Forgot Password
 									</p>
 								</div>

 								<div class="login-section forgot-section-padding column-flex-center">
 									<p class="text-center forgot-screen-text">
 										Please, enter your email in the field below and we'll
 										<br />
 										send you a link to a page where you can change <br />
 										your password:
 									</p>
 									<?php if ($this->session->flashdata('message')) { ?>
 										<div class="flash_error_message">
 											<div class="alert alert-info alert-dismissible" role="alert">
 												<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 												<?php echo $this->session->flashdata('message'); ?>
 											</div>
 										</div>
 									<?php } ?>

 									<div class="">
 										<input name="email" value="" class="-block password-screen-inputs" placeholder="Email" type="email">
 										<?php echo form_error('email'); ?>
 									</div>
 									<div class="">
 										<div class="btn-panel">
 											<input name="submit" value="Submit" class="btn btn-primary btn-block btn-flat" type="submit">
 										</div>
 									</div>

 								</div>
 							</div>
 						</div>
 						<div class="second-div-password">
 							<div class="first-child-password-screen position-relative column-flex-center">
 								<button class="d-flex justify-content-center align-items-center forgot-password-buttons btn-animate">
 									<p class="text">Submit</p>
 									<i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
 								</button>
 								<button type="button" onclick="<?php echo base_url('login'); ?>" class="d-flex justify-content-center align-items-center margin-top-20 cancel-button forgot-password-buttons btn-animate">
 									<p class="text">Cancel</p>
 									<i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
 								</button>
 							</div>
 						</div>
 					</form>
 				</div>
 			</div>
 		</div>
 	</div>
 </main>