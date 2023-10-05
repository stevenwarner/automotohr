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

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url('payrolls/pay-stubs/report'); ?>" class="btn csW csBG4 csF16">
                                <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
                                &nbsp;Back to pay stubs
                            </a>
                        </div>
                    </div>
                    <br />

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1 class="csF16 csW" style="margin: 0">
                                <strong>Filter</strong>
                            </h1>
                        </div>
                        <form action="<?= base_url('payrolls/pay-stubs/report/' . $periodId); ?>" method="get">
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
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <a href="<?= base_url('payrolls/pay-stubs/report/' . $periodId); ?>" class="btn csW csBG4 csF16">
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
                            <?php if (!$payStub) { ?>
                                <?php $this->load->view('v1/no_data', [
                                    'message' => 'No pay stub found.'
                                ]); ?>
                            <?php } else { ?>
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h1 class="csF16">
                                            <strong>Pay period:</strong>
                                            <?= formatDateToDB($payStub['start_date'], DB_DATE, DATE); ?> -
                                            <?= formatDateToDB($payStub['end_date'], DB_DATE, DATE); ?>
                                        </h1>
                                        <h1 class="csF16">
                                            <strong>Check date:</strong>
                                            <?= formatDateToDB($payStub['check_date'], DB_DATE, DATE); ?>
                                        </h1>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col" class="csW csBG4">Employee</th>
                                                <th scope="col" class="csW csBG4 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payStub['employees'] as $value) { ?>
                                                <tr data-key="<?= $value['sid']; ?>">
                                                    <td class="vam">
                                                        <?= remakeEmployeeName($value); ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <button class="btn csW csBG3 csF16 jsViewPayStub">
                                                            <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                            &nbsp;View
                                                        </button>
                                                        <button class="btn csW csBG4 csF16 jsDownloadPayStub" data-key="<?= $payStub['sid']; ?>">
                                                            <i class="fa fa-download csF16" aria-hidden="true"></i>
                                                            &nbsp;Download
                                                        </button>
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