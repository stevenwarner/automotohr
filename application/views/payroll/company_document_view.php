<style>
    table td {
        vertical-align: middle;
    }
</style>
<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <h3><strong>Company Document - <?= $payrollDocument['document_title']; ?></strong></h3>
            </div>
        </div>
        <br>
        <?php if ($payrollDocument['ip_address']) {
        ?>
            <div class="alert alert-success">
                <p class="csF18">
                    <i class="fa fa-check-circle-o" aria-hidden="true"></i> Company Document Signed
                </p>
                <hr>
                <p class="csF14">
                    <strong>IP Address:</strong> <?= $payrollDocument['ip_address']; ?>
                </p>
                <p class="csF14">
                    <strong>Email Address:</strong> <?= $payrollDocument['signed_by']; ?>
                </p>
                <p class="csF14">
                    <strong>Accepted Date:</strong> <?= formatDateToDB($payrollDocument['signed_at'], DB_DATE, DATE); ?>
                </p>
            </div>
            <br>
        <?php
        } ?>
        <!-- Main area -->
        <iframe src="<?= $fileName; ?>" style="height: 200px; width: 100%;" frameborder="0"></iframe>
    </div>
</div>