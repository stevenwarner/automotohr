<div class="wrapper-outer full-width">
<?php $this->load->view('main/header_logo_public_pages'); ?>
    <div class="main">
        <div class="message-box mt-5 text-center">
        	<div class="row justify-content-center">
        		<div class="col-xl-6">
        			<h1 class="text-white mt-5 text-shadow-white"><?php echo $page_title; ?></h1>
        			<h2 class="text-white mt-3"><?php echo $message; ?></h2>
        		</div>
        	</div>
        </div>
    </div>
    <div style="padding: 15px;" class="login-copyright text-center full-width mt-1">Copyright &copy; <?php echo date('Y') . ' ' . STORE_NAME; ?>. All Rights Reserved.</div>
</div>
