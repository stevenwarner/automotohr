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
                                    Manage Earning Types
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- loader -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading text-right">
                            <button class="btn csBG3 csBR5 csW csF16 jsAddEarningType" title="Add an Earning Type" placement="top">
                                <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>&nbsp;
                                Add an Earning Type
                            </button>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <th scope="col">Name</th>
                                        <th scope="col" class="text-center">Is Default?</th>
                                        <th scope="col" class="text-right">Created At</th>
                                        <th scope="col" class="text-right">Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($earnings) {
                                            foreach ($earnings as $earning) {
                                        ?>
                                                <tr data-id="<?= $earning['sid']; ?>">
                                                    <td class="vam"><?= $earning['name']; ?></td>
                                                    <td class="vam text-center"><?= $earning['is_default'] ? "Yes" : "No"; ?></td>
                                                    <td class="vam text-right">
                                                        <?= formatDateToDB(
                                                            $earning['created_at'],
                                                            DB_DATE_WITH_TIME,
                                                            DATE_WITH_TIME
                                                        ); ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <?php if (!$earning['is_default']) { ?>
                                                            <button class="btn btn-danger csF16 jsDeactivateEarningType" title="Deactivate the earning type" placement="top">
                                                                <i class="fa fa-ban csF16" aria-hidden="true"></i>
                                                                &nbsp;Deactivate
                                                            </button>
                                                            <button class="btn btn-success csF16 jsUpdateEarningType" title="Update the earning type" placement="top">
                                                                <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                                                &nbsp;Update
                                                            </button>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <th scope="row" colspan="4">
                                                    <div class="alert alert-info text-center">
                                                        <p>
                                                            No records found.
                                                        </p>
                                                    </div>
                                                </th>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>