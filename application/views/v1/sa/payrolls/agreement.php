<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="heading-title page-title">
                                <h1 class="page-title">
                                    <i class="fa fa-cogs"></i>
                                    <?php echo $page_title; ?>
                                </h1>
                            </div>

                            <br>
                            <br>
                            <br>
                            <br>

                            <!--  -->
                            <div class="alert alert-info text-center">
                                <p class="text-center">
                                    Please use the following button to sign the agreement.
                                </p>
                                <br />
                                <button class="btn btn-success jsServiceAgreement" data-cid="<?= $loggedInCompanyId; ?>">
                                    Payroll agreement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>