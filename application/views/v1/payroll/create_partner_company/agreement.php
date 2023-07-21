<!--  -->
<div class="container">
    <div class="csPageWrap">
        <?php if ($agreement['is_ts_accepted'] == 1) { ?>
            <div class="alert alert-success">
                <strong>Email: </strong> <?= $agreement['ts_email']; ?> <br>
                <strong>IP Address: </strong> <?= $agreement['ts_ip']; ?> <br>
                <strong>System User Reference : </strong> <?= $agreement['ts_user_sid']; ?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-sm-12">
                <iframe src="https://flows.gusto.com/terms" frameborder="0" style="width: 100%; height: 500px;" title="Gusto service agreement">
                </iframe>
            </div>
        </div>
        <hr>
        <?php if ($agreement['is_ts_accepted'] == 0) { ?>
            <form>
                <div class="form-group">
                    <label>Email <strong class="text-danger">*</strong></label>
                    <input type="email" id="jsTermsOfServiceEmail" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>System User Reference <strong class="text-danger">*</strong></label>
                    <input type="text" id="jsTermsOfServiceReference" class="form-control" required />
                </div>
            </form>

            <br>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-orange csF16 csB7 jsPayrollAgreeServiceTerms">
                        <i class="fa fa-pencil-square" aria-hidden="true"></i>&nbsp;
                        I consent to the "Payroll Service Agreement"
                    </button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>