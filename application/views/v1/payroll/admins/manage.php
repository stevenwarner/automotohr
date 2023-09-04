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
                                    Manage Admins
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading text-right">
                            <a href="<?= base_url('payrolls/admins/add'); ?>" class="btn csBG3 csBR5  csW  csF16" title="Add and admin" placement="top">
                                <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>&nbsp;
                                Add An Admin
                            </a>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Created At</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($admins) {
                                            foreach ($admins as $admin) {
                                        ?>
                                                <tr>
                                                    <td class="vam"><?= $admin['first_name']; ?></td>
                                                    <td class="vam"><?= $admin['last_name']; ?></td>
                                                    <td class="vam"><?= $admin['email_address']; ?></td>
                                                    <td class="vam">
                                                        <?= formatDateToDB(
                                                            $admin['created_at'],
                                                            DB_DATE_WITH_TIME,
                                                            DATE_WITH_TIME
                                                        ); ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <th scope="row">
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