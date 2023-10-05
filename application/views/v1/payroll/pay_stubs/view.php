<div class="container">
    <br />
    <!--  -->
    <div class="row">
        <div class="col-sm-2 col-xs-12">
            <p class="csF16">
                <strong>Pay date</strong>
            </p>
        </div>
        <div class="col-sm-10 col-xs-12">
            <?= formatDateToDB($payStub['check_date'], DB_DATE, DATE); ?>
        </div>
    </div>
    <!--  -->
    <div class="row">
        <div class="col-sm-2 col-xs-12">
            <p class="csF16">
                <strong>Description</strong>
            </p>
        </div>
        <div class="col-sm-10 col-xs-12">
            <?= formatDateToDB($payStub['start_date'], DB_DATE, DATE); ?>
            -
            <?= formatDateToDB($payStub['end_date'], DB_DATE, DATE); ?>
        </div>
    </div>
    <!--  -->
    <div class="row">
        <div class="col-sm-12 col-xs-12 text-right">
            <button class="btn csW csF16 csBG4 jsDownloadPayStub" data-key="<?= $payStub['sid']; ?>">
                <i class="fa fa-download csF16" aria-hidden="true"></i>
                &nbsp;Download
            </button>
        </div>
    </div>
    <!--  -->
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <iframe src="<?= $payStub['paystub_json']['s3_file_url']; ?>" style="border: 0; width: 100%; height: 700px;" title="My pay stub"></iframe>
        </div>
    </div>
</div>