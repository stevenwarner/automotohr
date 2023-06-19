<div class="csPageWrap">
    <!-- Nav bar -->
    <div class="container-fluid">
        <?php $this->load->view('payroll/navbar'); ?>
    </div>
    <br>
    <!--  -->
    <div class="row">
        <div class="container-fluid">
            <!-- Main Content Area -->
            <div class="col-md-12">
                <!-- Main Content Area -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="m0 p0 csB7">
                            Run Payroll
                            <span class="pull-right">
                                <a href="<?= base_url('payroll/history'); ?>" class="btn btn-orange">
                                    <i class="fa fa-history" aria-hidden="true"></i>&nbsp;Payroll History
                                </a>
                            </span>
                        </h1>
                        <hr />
                    </div>
                    <div class="clearfix"></div>
                </div>

                <?php if (count($payRollBlockersResponse['Response']) > 0) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="csF26 csB7">Reasons that prevent the company from running payrolls</p>
                            <div class="alert alert-danger ">
                                <?php foreach ($payRollBlockersResponse['Response'] as $message) { ?>
                                    <p class="csF18"><span class="glyphicon glyphicon-chevron-right"></span> <?php echo $message['message'] ?><hr></p>
                                <?php   }
                                ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php } ?>

                <?php if (!empty($period)) : ?>
                    <?php if (count($period) > 1) { ?>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <label class="csF16 csB7">
                                    Regular Payroll <span class="csRequired"></span>
                                </label>
                                <select class="form-control" id="jsRunSelectedPayroll">
                                    <?php foreach ($period as $payroll) { ?>
                                        <option value="<?php echo $payroll['payroll_id']; ?>">
                                            <?= formatDateToDB($payroll['start_date'], DB_DATE, DATE); ?> - <?= formatDateToDB($payroll['end_date'], DB_DATE, DATE); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button class="btn btn-orange" id="jsRunPayroll">
                                    Run Payroll
                                </button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <p style="font-size: 70px;" class="mb0"><i class="fa fa-money" aria-hidden="true"></i></p>
                                <p class="csF26 csB7">Regular Payroll (<?= formatDateToDB($period[0]['start_date'], DB_DATE, DATE); ?> - <?= formatDateToDB($period[0]['end_date'], DB_DATE, DATE); ?>)</p>
                                <p class="csF18">Please run payroll by <strong><?= GUSTO_PAYROLL_TIME; ?></strong> on <strong><?= formatDateToDB($period[0]['payroll_deadline'], DB_DATE, DATE); ?></strong> to pay your employees for their hard work. They’ll receive their funds on <strong><?= formatDateToDB($period[0]['check_date'], DB_DATE, DATE); ?></strong>. If you miss this deadline, your employees’ direct deposit will be delayed.</p>
                                <?php if (!empty($period[0]['payroll_id'])) { ?>
                                    <a href="<?= base_url('payroll/run/' . ($period[0]['payroll_id']) . ''); ?>" class="btn btn-orange">Run Regular Payroll</a>
                                <?php } else { ?>
                                    <a href="javascript:void(0)" disabled class="btn btn-black disabled">Run Regular Payroll</a>
                                    <br>
                                    <?= ShowInfo('Payroll UUID is missing.', ['icon' => 'fa-times-circle']); ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php else : ?>
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <p style="font-size: 70px;" class="mb0"><i class="fa fa-trophy csFC3" aria-hidden="true"></i></p>
                            <p class="csF26 csB7">Your Employees Have Been Paid on Time!!!</p>
                            <p class="csF18">You do not have any pending payrolls to run. An automatic email will notify you when it is time to run another regular payroll. In the meantime, carry on with a smile.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add System Model -->
<link rel="stylesheet" href="<?= base_url(_m("assets/css/SystemModel", 'css')); ?>">
<script src="<?= base_url(_m("assets/js/SystemModal")); ?>"></script>

<script>
    $(function() {
        //
        $('#jsPayrollSelect').select2({
            minimumResultsForSearch: -1
        });
        //
        $('.jsPayrollSubmit').click(function(event) {
            //
            event.preventDefault();
            //
            if ($('#jsPayrollSelect').val() == 0) {
                alertify.alert('WARNING!', 'Please, select a payroll to proceed.');
                return;
            }
            //
            window.location = window.location.origin + '/payroll/create/' + $('#jsPayrollSelect').val() + '/' + $('#jsPayrollSelect option[value="' + ($('#jsPayrollSelect').val()) + '"]').data('version') + '?step=1';
        });
        //
        $('#jsRunPayroll').click(function(event) {
            if ($('#jsRunSelectedPayroll').val() == 0) {
                alertify.alert('WARNING!', 'Please, select a payroll to proceed.');
                return;
            }
            //
            window.location = window.location.origin + '/payroll/run//' + $('#jsRunSelectedPayroll').val();
        });
    });
</script>