<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content csPageWrap">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-envelope-o" aria-hidden="true"></i><?php echo $company_info['CompanyName']; ?> - Payroll</h1>
                                        <span class="pull-right">
                                            <a class="btn btn-black" href="<?php echo base_url('manage_admin/companies/manage_company') . '/' . $company_info['sid']; ?>"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>Back</a>
                                        </span>
                                    </div>
                                    <!-- EIN message -->
                                    <?php if (!$company_info['ssn']) : ?>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <br>
                                                <p class="alert alert-danger csF16">
                                                    <strong>
                                                        Note: The company's EIN is required to use the payroll module. Please use the "Update EIN" button to set the EIN. <br>
                                                        Warning: The EIN can not be changed once set.
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- EIN message -->
                                    <?php if ($company_info['ssn'] && !$company_info['gusto_company_uid']) : ?>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <br>
                                                <p class="alert alert-danger csF16">
                                                    <strong>
                                                        Note: Please use the "Start Onboard Process" button to start the payroll onboard.
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="row">
                                        <div class="col-xs-12 source-breadcrumb">
                                            <!-- EIN -->
                                            <?php if (!$company_info['gusto_company_uid']) : ?>
                                                <button class="btn btn-success jsSetSSN">
                                                    Update EIN
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($company_info['gusto_company_uid']) : ?>
                                                <button data-status="<?php echo $companyPayrollStatus; ?>" company_sid="<?php echo $company_info['sid'] ?>" class="btn js-dynamic-module-btn btn-<?php echo $companyPayrollStatus == 0 ? "success" : "danger" ?>">
                                                    <?php echo $companyPayrollStatus == 0 ? "Activate" : "Deactivate" ?>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($company_info['ssn'] && $company_info['access_token']) { ?>
                                                <button class="btn btn-success jsDetails">
                                                    Details
                                                </button>
                                                <a class="btn btn-success" href="<?php echo $onboarding_link; ?>" target="_blank">
                                                    Onboard Process
                                                </a>
                                                <?php if ($company_status['onboarding_completed']): ?>
                                                <button class="btn btn-success jsPayrollSyncData">
                                                    Sync
                                                </button>
                                                <?php endif; ?>
                                            <?php } else if ($company_info['ssn']) { ?>
                                                <button class="btn btn-orange jsAddCompanyToGusto" data-company_sid="<?php echo $company_info['sid'] ?>">
                                                    Start Onboard Process
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- Email Logs Start -->
                                    <?php if ($company_info['ssn'] && !empty($onboarding_link)) { ?>
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <span class="pull-left">
                                                    <h1 class="hr-registered">Onboarding</h1>
                                                </span>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="csIPLoader jsIPLoader" data-page="main"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></div>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="embed-responsive embed-responsive-16by9">
                                                            <iframe src="<?php echo $onboarding_link; ?>" allowfullscreen title=""></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- Email Logs End -->
                                    <!-- Company Status info Start -->
                                    <?php if (!empty($company_status)) { ?>
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <span class="pull-left">
                                                    <h1 class="csF22 m0 csW">Company Onboarding Status</h1>
                                                </span>
                                                <span class="pull-right">
                                                    <?php if (!$company_status['onboarding_completed']) : ?>
                                                        <span class="btn btn-warning">In-progress</span>
                                                    <?php else : ?>
                                                        <span class="btn btn-danger">Onboard</span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="csFC4" scope="col">Module Name</th>
                                                                        <th class="csFC4" scope="col">Required</th>
                                                                        <th class="csFC4" scope="col">Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!--All records-->
                                                                    <?php foreach ($company_status["onboarding_steps"] as $status) { ?>
                                                                        <tr>
                                                                            <td><?php echo $status["title"]; ?></td>
                                                                            <td><?php echo $status["required"] ? "Yes" : "No"; ?></td>
                                                                            <td>
                                                                                <?php
                                                                                if (!empty($status["completed"]) && $status["completed"] == "true") {
                                                                                    echo '<strong class="text-success">Competed</strong>';
                                                                                } else {
                                                                                    echo '<strong class="text-danger">Not Competed</strong>';
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- Compant Status info End -->
                                    <?php if ($company_info['ssn'] && $company_info['gusto_company_uid']) : ?>
                                        <!--  -->
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <span class="pull-left">
                                                    <h1 class="csF22 m0 csW">Payroll Employee(s)</h1>
                                                </span>
                                                <span class="pull-right">
                                                    <span id="jsPayrollEmployeesListingCount" class="csF22 m0 csW">Total: 0</span>
                                                </span>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <?php if(!$company_status['onboarding_completed']): ?>
                                                <!--  -->
                                                <div class="row">
                                                    <div class="col-xs-12 text-right">
                                                        <button class="btn btn-success" id="jsPayrollEmployeeAddBtn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Onboard Employee To Payroll</button>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <!--  -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover">
                                                                <caption></caption>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="csFC4" scope="col">Employee</th>
                                                                        <th class="csFC4" scope="col" class="text-right">Payroll Id</th>
                                                                        <th class="csFC4" scope="col" class="text-right">Onboard Status</th>
                                                                        <th class="csFC4" scope="col" class="text-right">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="jsPayrollEmployeesListingBox"></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--  -->
                                        <?php endif; ?>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        /**
         * Set default company Id
         * @type {number}
         */
        var companyId = <?php echo $this->uri->segment(4); ?>;

        /**
         * Set base url
         * @type {string}
         */
        var baseURI = "<?php echo rtrim('/', base_url()); ?>/";
    </script>
    <!-- Include Modal -->
    <link rel="stylesheet" href="<?= base_url("assets/css/SystemModel.css"); ?>" />
    <script src="<?= base_url("assets/js/SystemModal.js"); ?>"></script>
    <!-- Include moment -->
    <script src="<?= base_url("assets/js/moment.min.js"); ?>"></script>

    <script src="<?= base_url('assets/js/common.js?v1.0.1'); ?>"></script>
    <script src="<?= base_url('assets/portal/app.js'); ?>?v=1.0.2"></script>
    <!-- Include payroll -->
    <link rel="stylesheet" href="<?= base_url("assets/css/theme-2021.css"); ?>" />
    <script src="<?= base_url("assets/payroll/js/payroll_company_onboard.js"); ?>"></script>
    <script src="<?= base_url("assets/payroll/js/employee_onboard.js"); ?>"></script>


    <script>
        $(function PayrollSuperAdmin() {
            //
            var obj = {};
            //
            obj.companyId = <?= $company_info["sid"]; ?>;
            obj.moduleId = 0;
            obj.action = '';
            //
            var companyData = <?= json_encode($company_info); ?>;
            //
            $('.js-dynamic-module-btn').click(function(e) {
                //
                e.preventDefault();
                //
                if (!companyData['ssn']) {
                    alertify.alert('Error!', 'To activate payroll for this company, the EIN number is required.');
                    return;
                }
                //
                obj.status = $(this).attr('data-status');
                obj.action = 'update_status';
                //
                alertify.confirm(
                    'Do you really want to ' + (obj.status == 1 ? 'disable' : 'enable') + ' the module against this company?',
                    function() {
                        ml(true, 'main');
                        UpdateAction();
                    });
            });
            //
            $('.jsSetSSN').click(function(event) {
                //
                event.preventDefault();
                //
                var html = '';
                html += '<div class="container">';
                html += '   <div class="row">';
                html += '       <div class="col-sm-12">';
                html += '           <label>EIN (9 digits) <strong class="text-danger">*</strong></label>';
                html += '           <input type="text" class="form-control" id="jsEinNumberText" value="' + (companyData['ssn'] != null ? companyData['ssn'] : '') + '" />';
                html += '       </div>';
                html += '   </div>';
                html += '   <br>';
                html += '   <div class="row">';
                html += '       <div class="col-sm-12">';
                html += '           <label></label>';
                html += '           <button class="btn btn-success" id="jsUpdateEINNumber">Update</button>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';
                //
                Modal({
                    Id: 'jsEINModal',
                    Title: 'Update EIN Number for ' + companyData['CompanyName'],
                    Loader: 'jsEINModalLoader',
                    Body: html
                }, function() {
                    //
                    ml(false, 'jsEINModalLoader');
                });
            });
            //
            $(document).on('click', '.jsRefreshToken', function(event) {
                //
                event.preventDefault();
                //
                ml(true, 'jsCompanyDetailModalLoader');
                //
                obj.action = 'refresh_token';
                //
                UpdateAction('jsCompanyDetailModalLoader');
            });
            //
            $(document).on('click', '#jsUpdateEINNumber', function(event) {
                //
                event.preventDefault();
                //
                var newEIN = $('#jsEinNumberText').val().trim().replace(/[^0-9]/g, '').substr(0, 9);
                //
                if (newEIN.length != 9) {
                    alertify.alert(
                        "Warning!",
                        "EIN must be of 9 digits"
                    );
                    return;
                }
                //
                ml(true, 'jsEINModalLoader');
                //
                obj.ein = newEIN;
                obj.action = 'update_ein';
                //
                UpdateAction('jsEINModalLoader');
            });
            //
            $(document).on('keyup', '#jsEinNumberText', function(event) {
                //
                $(this).val(
                    $(this).val().trim().replace(/[^0-9]/g, '').substr(0, 9)
                );
            });
            //
            $('.jsDetails').click(function(event) {
                //
                event.preventDefault();
                //
                var html = '';
                html += '<div class="container">';
                html += '   <div class="row">';
                html += '       <div class="col-sm-12">';
                html += '           <label>Company UUID</label>';
                html += '           <input type="text" class="form-control" disabled value="' + (companyData['gusto_company_uid'] != null ? companyData['gusto_company_uid'] : '') + '" />';
                html += '       </div>';
                html += '   </div>';
                html += '   <br>';
                html += '   <div class="row">';
                html += '       <div class="col-sm-12">';
                html += '           <label>Access Token</label>';
                html += '           <input type="text" class="form-control" disabled value="' + (companyData['access_token'] != null ? companyData['access_token'] : '') + '" />';
                html += '       </div>';
                html += '   </div>';
                html += '   <br>';
                html += '   <div class="row">';
                html += '       <div class="col-sm-12">';
                html += '           <label>Refresh Token</label>';
                html += '           <input type="text" class="form-control" disabled value="' + (companyData['refresh_token'] != null ? companyData['refresh_token'] : '') + '" />';
                html += '       </div>';
                html += '   </div>';
                html += '   <br>';
                html += '   <div class="row">';
                html += '       <div class="col-sm-12">';
                html += '           <label>Old Access Token</label>';
                html += '           <input type="text" class="form-control" disabled value="' + (companyData['old_access_token'] != null ? companyData['old_access_token'] : '') + '" />';
                html += '       </div>';
                html += '   </div>';
                html += '   <br>';
                html += '   <div class="row">';
                html += '       <div class="col-sm-12">';
                html += '           <label>Old Refresh Token</label>';
                html += '           <input type="text" class="form-control" disabled value="' + (companyData['old_refresh_token'] != null ? companyData['old_refresh_token'] : '') + '" />';
                html += '       </div>';
                html += '   </div>';
                html += '   <br>';
                html += '   <div class="row">';
                html += '       <div class="col-sm-6">';
                html += '           <label>Created At</label>';
                html += '           <input type="text" class="form-control" disabled value="' + (companyData['created_at'] != null ? moment(companyData['created_at']).format('MMM DD YYYY, ddd hh:mm a') : '') + '" />';
                html += '       </div>';
                html += '       <div class="col-sm-6">';
                html += '           <label>Updated At</label>';
                html += '           <input type="text" class="form-control" disabled value="' + (companyData['updated_at'] != null ? moment(companyData['updated_at']).format('MMM DD YYYY, ddd hh:mm a') : '') + '" />';
                html += '       </div>';
                html += '   </div>';
                html += '   <br>';
                html += '</div>';
                //
                Modal({
                    Id: 'jsCompanyDetailModal',
                    Title: 'Details for ' + companyData['CompanyName'],
                    Loader: 'jsCompanyDetailModalLoader',
                    Buttons: [
                        '<button class="btn btn-success jsRefreshToken">Refresh Token</button>'
                    ],
                    Body: html
                }, function() {
                    //
                    ml(false, 'jsCompanyDetailModalLoader');
                });
            });

            //
            $('.jsPayrollSyncData').click(function(event){
                //
                event.preventDefault();
                //
                ml(true, 'main');
                //
                $.ajax({
                    url: baseURI+'payroll_sync',
                    method: "post",
                    data: {
                        id: companyId
                    }
                })
                .success(function(){
                    return alertify.alert(
                        'Success!',
                        'Hurray! process completed.',
                        function(){
                            ml(false, 'main');
                        }
                    );
                })
                .fail(function(){
                    return alertify.alert(
                        'Error!',
                        'Something went wrong while syncing.',
                        function(){
                            ml(false, 'main');
                        }
                    );
                });
            });
            //
            function UpdateAction(loader) {
                //
                $.post(
                    "<?= base_url("update_payroll_module") ?>",
                    obj
                ).done(function(resp) {
                    //
                    if (loader !== undefined) {
                        ml(false, loader);
                    }
                    //
                    if (resp.Status === false) {
                        alertify.alert('Error!', resp.Response);
                        return;
                    }
                    //
                    obj = {};
                    //
                    $('.jsModalCancel').click();
                    //
                    alertify.alert('Success!', resp.Response, function() {
                        //
                        window.location.reload();
                    });
                });
            }
            //
            ml(false, 'main');
        })
    </script>