<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br>
        <!--  -->
        <?php if($acceptedData['terms_accepted']): ?>
        <div class="alert alert-success">
            <p class="csF18">
                <i class="fa fa-check-circle-o" aria-hidden="true"></i> Payroll Service Agreement Accepted
            </p>
            <hr>
            <p class="csF14">
                <strong>IP Address:</strong> <?=$acceptedData['ip_address'];?>
            </p>
            <p class="csF14">
                <strong>Email Address:</strong> <?=$acceptedData['email_address'];?>
            </p>
            <p class="csF14">
                <strong>Accepted Date:</strong> <?=formatDateToDB($acceptedData['accepted_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?>
            </p>
        </div>
        <?php endif; ?>
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <iframe src="https://flows.gusto.com/terms" style="width: 100%; height: 600px;  border: 0" title="Terms"></iframe>
            </div>
        </div>
        <?php if(!$acceptedData['terms_accepted'] && $canSign): ?>
        <!--  -->
        <div class="container">
            <hr />
            <div class="row">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-success jsPayrollAcceptTerms">
                        <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Accept Service Terms
                    </button>
                </div>
            </div>
        </div>
        <?php endif;?>
        <br>
        <br>
    </div>
</div>