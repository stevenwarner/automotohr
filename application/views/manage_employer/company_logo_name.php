<?php if ($this->session->userdata("logged_in")["company_detail"]["Logo"]) { ?>
	<img src="<?php echo AWS_S3_BUCKET_URL . $this->session->userdata("logged_in")["company_detail"]["Logo"]; ?>" style="width: 75px; height: 75px;" class="img-rounded" alt="Company logo" />
	<br />
<?php } ?>

<?php if ($this->session->userdata("logged_in")["company_detail"]["CompanyName"]) { ?>
	<br />
	<?php echo $this->session->userdata("logged_in")["company_detail"]["CompanyName"]; ?>
	<?php if (isPayrollOrPlus(true) && isCompanyClosed()) { ?>
		<label class="label label-danger" title="The store is closed." placement="top">
			Closed
		</label>
	<?php } ?>
<?php } ?>