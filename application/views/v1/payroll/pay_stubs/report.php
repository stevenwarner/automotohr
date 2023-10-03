<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('v1/payroll/sidebar'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- Top bar -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <!-- Company details header -->
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <!--  -->
                                    <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Dashboard
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1 class="csF16 csW" style="margin: 0">
                                <strong>Filter</strong>
                            </h1>
                        </div>
                        <form action="<?= base_url('payrolls/pay-stubs/report'); ?>" method="get">
                            <div class="panel-body">
                                <div class="row">
                                    <!-- Employees -->
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="csF16">
                                                Employees
                                            </label>
                                            <select name="employees[]" class="form-control" multiple>
                                                <option value="all" <?= in_array('all', $filter['employees']) ? 'selected' : ''; ?>>All</option>
                                                <?php foreach ($employees as $value) { ?>
                                                    <option value="<?= $value['id']; ?>" <?= in_array($value['id'], $filter['employees']) ? 'selected' : ''; ?>><?= $value['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="csF16">
                                                Pay periods
                                            </label>
                                            <select name="pay_periods[]" class="form-control" multiple>
                                                <option value="all" <?= in_array('all', $filter['pay_periods']) ? 'selected' : ''; ?>>All</option>
                                                <?php if ($payStubsFilter['payPeriods']) { ?>
                                                    <?php foreach ($payStubsFilter['payPeriods'] as $value) { ?>
                                                        <option value="<?= $value['sid']; ?>" <?= in_array($value['sid'], $filter['pay_periods']) ? 'selected' : ''; ?>><?= formatDateToDB($value['start_date'], DB_DATE, DATE); ?> - <?= formatDateToDB($value['end_date'], DB_DATE, DATE); ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- check date -->
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="csF16">
                                                Check date
                                            </label>
                                            <select name="check_dates[]" class="form-control" multiple>
                                                <option value="all" <?= in_array('all', $filter['check_dates']) ? 'selected' : ''; ?>>All</option>
                                                <?php if ($payStubsFilter['checkDates']) { ?>
                                                    <?php foreach ($payStubsFilter['checkDates'] as $value) { ?>
                                                        <option value="<?= $value['sid']; ?>" <?= in_array($value['sid'], $filter['check_dates']) ? 'selected' : ''; ?>><?= formatDateToDB($value['check_date'], DB_DATE, DATE); ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <a href="<?= base_url('payrolls/pay-stubs/report'); ?>" class="btn csW csBG4 csF16">
                                    <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                                    &nbsp;Clear filter
                                </a>
                                <button type="submit" class="btn csW csBG3 csF16">
                                    <i class="fa fa-filter csF16" aria-hidden="true"></i>
                                    &nbsp;Apply filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1 class="csF16 csW" style="margin: 0">
                                <strong>Pay stubs report</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <?php if (!$payStubs) { ?>
                                <?php $this->load->view('v1/no_data', [
                                    'message' => 'No pay stubs found.'
                                ]); ?>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col" class="csW csBG4">Pay<br />date</th>
                                                <th scope="col" class="csW csBG4 text-right">Description</th>
                                                <th scope="col" class="csW csBG4 text-right">Employees</th>
                                                <th scope="col" class="csW csBG4 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payStubs as $value) { ?>
                                                <tr>
                                                    <td class="vam">
                                                        <?= formatDateToDB($value['check_date'], DB_DATE, DATE); ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <?= formatDateToDB($value['start_date'], DB_DATE, DATE); ?> -
                                                        <?= formatDateToDB($value['end_date'], DB_DATE, DATE); ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <?= $value['count']; ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <a href="<?= base_url('payrolls/pay-stubs/report/' . $value['sid']); ?>" class="btn csW csBG3 csF16">
                                                            <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                            &nbsp;View details
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $('select').select2({
        closeOnSelect: false
    });
</script>