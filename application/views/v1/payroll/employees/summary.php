<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong>Summary</strong>
        </h3>
    </div>
    <div class="panel-body">
        <h4 style="margin: 0;">
            <strong>Missing Requirements</strong>
        </h4>
        <p class="csF16">
            Please complete the following steps in order to continue.
        </p>
        <br>
        <?php foreach ($summary as $point) { ?>
            <p class="csF16">
                <i class="fa <?= $point[1] ? 'fa-check-circle text-success' : 'fa-circle-o'; ?> csF16"></i>
                &nbsp;<?= $point[0]; ?>
                <hr>
            </p>
        <?php } ?>
    </div>
    <div class="panel-footer text-right">
        <button class="btn csBG3 csF16 jsEmployeeFlowSavePaymentMethodBtn">
            <i class="fa fa-save csF16"></i>
            <span>Save & continue</span>
        </button>
    </div>
</div>