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
                                    <?php echo $page_title . ' (' . $companyOnboardingStatus . ')'; ?>
                                </h1>
                            </div>
                            <!-- Main body -->
                            <br />
                            <br />
                            <br />
                            <!-- Content area -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12 text-right">
                                    <!--  -->
                                    <a href="<?= base_url("manage_admin/companies/manage_company/" . $loggedInCompanyId); ?>" class="btn csW csBG4 csF16">
                                        <i class="fa fa-arrow-left csF16"></i>
                                        &nbsp;Back to Company Management
                                    </a>
                                </div>
                            </div>
                            <hr />

                            <!--  -->
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong class="csF16 csW">Company Mode</strong>
                                        </div>
                                        <div class="panel-body">
                                            <form action="javascript:void(0)" id="jsCompanyModeForm">
                                                <div class="form-group">
                                                    <label>Mode <span class="text-danger">*</span></label>
                                                    <select <?php echo $companyOnboardingStatus != 'Not Connected' ? 'disabled' : ''; ?> name="company_mode" class="form-control" id="jsCompanyMode">
                                                        <option value="demo" <?= $mode == 'Demo' ? 'selected' : ''; ?>>Demo</option>
                                                        <option value="production" <?= $mode == 'Production' ? 'selected' : ''; ?>>Production</option>
                                                    </select>
                                                </div>


                                                <?php if ($companyOnboardingStatus == 'Not Connected') { ?>
                                                    <div class="form-group text-right">
                                                        <button class="btn btn-success jsCompanyModeBtn csF16" type="submit">
                                                            <i class="fa fa-save csF16" aria-hidden="true"></i>
                                                            <span>Update Company Mode</span>
                                                        </button>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main body ends -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>