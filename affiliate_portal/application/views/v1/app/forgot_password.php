 <main>
 	<div class="row">
 		<div class="col-xs-12 background-image-css" style="background-image: url('<?= getImageURL($pageContent['page']["sections"]["section_0"]['sourceFile']); ?>')">
 			<div class="top-div">
 				<div class="parent-div">
 					<form action="" class="form-horizontal" method="post">
 						<div class="first-div-password">
 							<div class="high-lighted-text-div">
 								<div class="highlighted-text-upper-div">
 									<p class="forgot-password-text text-center forgot-text">
 										<?= convertToStrip($pageContent['page']["sections"]["section_0"]['mainHeading']); ?>
 									</p>
 								</div>

 								<div class="login-section forgot-section-padding column-flex-center">
 									<p class="text-center forgot-screen-text">
 										<?= convertToStrip($pageContent['page']["sections"]["section_0"]['details']); ?>
 									</p>
 									<?php if ($this->session->flashdata('message')) { ?>
 										<div class="flash_error_message">
 											<div class="alert alert-info alert-dismissible" role="alert">
 												<?php echo $this->session->flashdata('message'); ?>
 											</div>
 										</div>
 									<?php } ?>

 									<div class="w-100">
 										<input name="email" class="d-block password-screen-inputs w-100" placeholder="Email" type="email" />
 										<?php echo form_error('email'); ?>
 									</div>

 								</div>
 							</div>
 						</div>
 						<div class="second-div-password">
 							<div class="first-child-password-screen position-relative column-flex-center w-100">
 								<button class="d-flex justify-content-center align-items-center forgot-password-buttons btn-animate w-100">
 									<p class="text">
 										<?= convertToStrip($pageContent['page']["sections"]["section_0"]['buttonText']); ?>
 									</p>
 									<i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
 								</button>
 								<a href="<?php echo main_url($pageContent['page']["sections"]["section_0"]['buttonLinkCancel']); ?>" class="button d-flex justify-content-center align-items-center margin-top-20 cancel-button forgot-password-buttons btn-animate w-100">
 									<p class="text">
 										<?= convertToStrip($pageContent['page']["sections"]["section_0"]['buttonTextCancel']); ?>
 									</p>
 									<i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
 								</a>
 							</div>
 						</div>
 					</form>
 				</div>
 			</div>
 		</div>
 	</div>
 </main>