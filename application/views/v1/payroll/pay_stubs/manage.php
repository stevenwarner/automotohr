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

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1 class="csF16 csW" style="margin: 0">
                                <strong>Pay stubs</strong>
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
                                                <th scope="col" class="csW csBG4 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payStubs as $payStub) { ?>
                                                <tr data-key="<?= $payStub['sid']; ?>">
                                                    <td class="vam">
                                                        <?= formatDateToDB($payStub['check_date'], DB_DATE, DATE); ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <?= formatDateToDB($payStub['start_date'], DB_DATE, DATE); ?>
                                                        -
                                                        <?= formatDateToDB($payStub['end_date'], DB_DATE, DATE); ?>
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
                    <!-- loader -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>