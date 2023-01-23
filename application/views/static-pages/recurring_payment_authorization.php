<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recurring Payment Authorization</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/alertify.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/themes/default.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/metallic.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/responsive.css">

    <script src="<?php echo base_url('assets') ?>/js/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <script src="<?php echo base_url('assets') ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets') ?>/js/zebra_datepicker.js"></script>
    <script src="<?php echo base_url('assets') ?>/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){

    		$('.checkdate').mask("99-99-9999", {placeholder: 'mm/dd/yyyy' });
    		
    		$('input.datepicker').Zebra_DatePicker();

    		// Upload Image file
    		$(".fileto-upload").change(function () {
		        if (this.files && this.files[0]) {
		            var reader = new FileReader();
		            reader.onload = imageIsLoaded;
		            reader.readAsDataURL(this.files[0]);
		        }
		    });
			function imageIsLoaded(e) {
			    $('#myImg').attr('src', e.target.result);
			};

    	});
    </script>
</head>
<body>
	<div class="main-content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					<div class="credit-card-authorization">
						<div class="top-logo text-center">
							<img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
						</div>
						<span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>RECURRING PAYMENT AUTHORIZATION FORM</span>
						<div class="end-user-agreement-wrp recurring-payment-authorization">
							<div class="recurring-payment-text-area">
								<p>Schedule your payment to be automatically deducted from your bank account, or charged to your Visa, MasterCard, American Express or Discover Card. Just complete and sign this form to get started! </p>
								<h2 class="credit-card-form-heading">Recurring Payments Will Make Your Life Easier: </h2>
								<ul>
									<li>It's convenient (saving you time and postage) </li>
									<li>Your payment is always on time (even if you're out of town), eliminating late charges </li>
								</ul>
								<h2 class="credit-card-form-heading">Here's How Recurring Payments Work:</h2>
								<p>You authorize regularly scheduled charges to your checking/savings account or credit card. You will be charged the amount indicated below each billing period. A receipt for each payment will be emailed to you and the charge will appear on your bank statement as an "ACH Debit." You agree that no prior-notification will be provided unless the date or amount changes, in which case you will receive notice from us at least 10 days prior to the payment being collected. </p>
							</div>
							<form action="" method="post" enctype="multipart/form-data">
								<div class="card-fields-row">
									<div class="row">
										<div class="col-lg-12">
											<label>I,</label>
											<div class="form-outer">
												<input type="text" name="" class="invoice-fields">
											</div>
											authorize < Insert Business Name> to change my credit card indicated below for < Inser $ > on the <div class="form-outer"><input class="invoice-fields datepicker" type="text" name="expiry_date">
											</div> of each < Insert frequency > for payment of my
										</div>
										
									</div>
								</div>
								<h2 class="credit-card-form-heading">Credit Card Billing Address:</h2>
								<div class="col-lg-6">
									<div class="card-fields-row">
										<div class="row">
											<div class="col-lg-3">
												<label>Billing Address</label>
											</div>
											<div class="col-lg-9">
												<input type="text" name="" class="invoice-fields"> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="card-fields-row">
										<div class="row">
											<div class="col-lg-3">
												<label>Phone#</label>
											</div>
											<div class="col-lg-9">
												<input type="text" name="" class="invoice-fields"> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="card-fields-row">
										<div class="row">
											<div class="col-lg-3">
												<label>City, State, Zip</label>
											</div>
											<div class="col-lg-9">
												<input type="text" name="" class="invoice-fields"> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="card-fields-row">
										<div class="row">
											<div class="col-lg-3">
												<label>Email</label>
											</div>
											<div class="col-lg-9">
												<input type="text" name="" class="invoice-fields"> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="card-boxes">
										<h2 class="credit-card-form-heading text-center">Checking/Savings Account</h2>
										<div class="card-box-inner">
											<div class="col-lg-6">
												<strong class="text-center">Checking</strong>
											</div>
											<div class="col-lg-6">
												<strong class="text-center">Savings</strong>
											</div>
											<div class="card-fields-row">
												<div class="col-lg-4">
													<label>Name of Acct</label>
												</div>
												<div class="col-lg-8">
													<input type="text" class="invoice-fields" name=""> 
												</div>
											</div>
											<div class="card-fields-row">
												<div class="col-lg-4">
													<label>Bank Name</label>
												</div>
												<div class="col-lg-8">
													<input type="text" class="invoice-fields" name=""> 
												</div>
											</div>
											<div class="card-fields-row">
												<div class="col-lg-4">
													<label>Account Number</label>
												</div>
												<div class="col-lg-8">
													<input type="text" class="invoice-fields" name=""> 
												</div>
											</div>
											<div class="card-fields-row">
												<div class="col-lg-4">
													<label>Bank Routing #</label>
												</div>
												<div class="col-lg-8">
													<input type="text" class="invoice-fields" name=""> 
												</div>
											</div>
											<div class="card-fields-row">
												<div class="col-lg-4">
													<label>Bank City/State</label>
												</div>
												<div class="col-lg-8">
													<input type="text" class="invoice-fields" name=""> 
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="card-boxes">
										<h2 class="credit-card-form-heading text-center">Credit Card</h2>
										<div class="card-box-inner">
											<div class="col-lg-6">
												<strong class="text-center">Visa</strong>
											</div>
											<div class="col-lg-6">
												<strong class="text-center">Mastercard</strong>
											</div>
											<div class="col-lg-6">
												<strong class="text-center">Amex</strong>
											</div>
											<div class="col-lg-6">
												<strong class="text-center">Discover</strong>
											</div>
											<div class="card-fields-row">
												<div class="col-lg-4">
													<label>Cardholder Name</label>
												</div>
												<div class="col-lg-8">
													<input type="text" class="invoice-fields" name=""> 
												</div>
											</div>
											<div class="card-fields-row">
												<div class="col-lg-4">
													<label>Account Number</label>
												</div>
												<div class="col-lg-8">
													<input type="text" class="invoice-fields" name=""> 
												</div>
											</div>
											<div class="card-fields-row">
												<div class="col-lg-4">
													<label>Exp. Date</label>
												</div>
												<div class="col-lg-8">
													<input type="text" class="invoice-fields" name=""> 
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-fields-row">
									<div class="row">
										<div class="col-lg-8">
											<div class="card-fields-row">
												<div class="col-lg-2">
													<label>SIGNATURE</label>
												</div>
												<div class="col-lg-10">
													<input type="text" class="invoice-fields" name="">
												</div>
											</div>
										</div>
										<div class="col-lg-4">
											<div class="card-fields-row">
												<div class="col-lg-2">
													<label>DATE</label>
												</div>
												<div class="col-lg-10">
													<input type="text" class="invoice-fields" name="">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-fields-row">
									<p>I understand that this authorization will remain in effect until I cancel it in writing, and I agree to notify <business name> in writing of any changes in my account information or termination of this authorization at least 15 days prior to the next billing date. If the above noted payment dates fall on a weekend or holiday, I understand that the payments may be executed on the next business day. For ACH debits to my checking/savings account, I understand that because these are electronic transactions, these funds may be withdrawn from my account as soon as the above noted periodic transaction dates. In the case of an ACH Transaction being rejected for Non Sufficient Funds (NSF) I understand that </p>
								</div>
								<div style="border-top:1px solid #ddd; padding-top:8px;" class="card-fields-row">
									<p>must comply with the previous U.S law. I certify that I am an authorized user of this credit card/bank account and will not dispute these scheduled transaction with my bank or credit card company; so long as the transactions coresspond to the terms indicated in this authorization form. </p>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>