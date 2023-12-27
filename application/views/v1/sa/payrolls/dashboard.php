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
                                    <?php echo $page_title.' ('.$companyOnboardingStatus.')'; ?>
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
                                    <!--  -->
                                    <a href="<?= base_url("sa/payrolls/company/" . $loggedInCompanyId . "/setup_payroll"); ?>" class="btn btn-success csF16">
                                        <i class="fa fa-cog csF16"></i>
                                        &nbsp;Setup Company Payroll
                                    </a>

                                    <?php if ($companyOnboardingStatus != 'Not Connected') { ?>
                                        <!-- admins -->
                                        <a href="<?= base_url("sa/payrolls/company/" . $loggedInCompanyId . "/admins/manage"); ?>" class="btn btn-success csF16">
                                            <i class="fa fa-users csF16"></i>
                                            &nbsp;Manage Admins
                                        </a>

                                        <?php if ($payrollBlockers && $mode == 'Demo') { ?>
                                            <button class="btn btn-success jsVerifyCompany csF16" title="Verify Company" placement="top">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;
                                                <span>Verify Company</span>
                                            </button>
                                            <button class="btn btn-success jsVerifyBankAccount csF16" title="Verify bank account" placement="top">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;
                                                <span>Verify Bank Account</span>
                                            </button>
                                        <?php } ?>
                                        <button class="btn btn-success jsSyncCompanyData csF16" title="Sync data" placement="top">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;
                                            <span>Sync</span>
                                        </button>
                                    <?php } ?>
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
                                                    <select <?php echo $companyOnboardingStatus != 'Not Connected' ? 'disabled' : '';?> name="company_mode" class="form-control" id="jsCompanyMode">
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

                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong class="csF16 csW">Company Primary Admin</strong>
                                        </div>
                                        <div class="panel-body">
                                            <!--  -->
                                            <p class="text-danger csF16">
                                                <em>
                                                    <strong>
                                                        Primary Admin for Gusto Company Onboarding
                                                    </strong>
                                                </em>
                                            </p>
                                            <form action="javascript:void(0)" id="jsPaymentConfigurationForm">
                                                <?php
                                                $adminStatus = "";
                                                if ($primaryAdmin['is_sync'] == 1) {
                                                    $adminStatus = "disabled";
                                                }
                                                ?>
                                                <div class="form-group">
                                                    <label>First Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $primaryAdmin['first_name'] ?>" <?= $adminStatus ?> id="jsFirstName" />
                                                </div>

                                                <div class="form-group">
                                                    <label>Last Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $primaryAdmin['last_name'] ?>" <?= $adminStatus ?> id="jsLastName" />
                                                </div>

                                                <div class="form-group">
                                                    <label>Email <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $primaryAdmin['email_address'] ?>" <?= $adminStatus ?> id="jsEmail" />
                                                </div>

                                                <?php if ($primaryAdmin['is_sync'] == 0) { ?>
                                                    <div class="form-group text-right">
                                                        <button class="btn btn-success jsSaveDefaultAdmin csF16">
                                                            <i class="fa fa-save csF16" aria-hidden="true"></i>
                                                            <span>Save Primary Admin</span>
                                                        </button>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <?php if ($companyOnboardingStatus != 'Not Connected') { ?>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong class="csF16 csW">Company Gusto Agreement</strong>
                                            </div>
                                            <div class="panel-body">
                                                <!--  -->
                                                <?php if ($companyTermsCondition['is_ts_accepted'] != 1) { ?>
                                                    <p class="text-danger csF16">
                                                        <em>
                                                            <strong>
                                                                Gusto agreement not signed yet.
                                                            </strong>
                                                        </em>
                                                    </p>
                                                <?php } ?>
                                                <form action="javascript:void(0)">
                                                    <div class="form-group">
                                                        <label>Accepted By Name</label>
                                                        <input type="text" class="form-control" value="<?= getUserNameBySID($companyTermsCondition['ts_user_sid']) ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Accepted By Email</label>
                                                        <input type="text" class="form-control" value="<?= $companyTermsCondition['ts_email'] ?>" disabled  />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Accepted By IP Address</label>
                                                        <input type="text" class="form-control" value="<?= $companyTermsCondition['ts_ip'] ?>" disabled />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong class="csF16 csW">Company Payment Configs</strong>
                                            </div>
                                            <div class="panel-body">
                                                <!--  -->
                                                <p class="text-danger csF16">
                                                    <em>
                                                        <strong>
                                                            Configure 2-day & 4-day ACH, create company specific earnings, run off-cycle payroll, create historical payroll, and create pay schedules.
                                                        </strong>
                                                    </em>
                                                </p>
                                                <form action="javascript:void(0)" id="jsPaymentConfigurationForm">
                                                    <div class="form-group">
                                                        <label>Payment Speed <span class="text-danger">*</span></label>
                                                        <?php
                                                        $speed = '1-day';
                                                        //
                                                        if (!empty($companyPaymentConfiguration['payment_speed'])) {
                                                            $speed = $companyPaymentConfiguration['payment_speed'];
                                                        }
                                                        ?>
                                                        <select name="payment_speed" class="form-control" id="jsPaymentSpeed">
                                                            <option value="1-day" <?= $speed == '1-day' ? 'selected' : ''; ?>>1 Day</option>
                                                            <option value="2-day" <?= $speed == '2-day' ? 'selected' : ''; ?>>2 Day</option>
                                                            <option value="4-day" <?= $speed == '4-day' ? 'selected' : ''; ?>>4 Day</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Fast Payment Limit</label>
                                                        <input type="text" class="form-control" value="<?= !empty($companyPaymentConfiguration['fast_payment_limit']) ? $companyPaymentConfiguration['fast_payment_limit'] : 0; ?>" id="jsFastPaymentLimit" />
                                                    </div>

                                                    <div class="form-group text-right">
                                                        <button class="btn btn-success jsSaveConfiguration csF16">
                                                            <i class="fa fa-save csF16" aria-hidden="true"></i>
                                                            <span>Save Payment Configuration</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong class="csF16 csW">Company Signatory</strong>
                                            </div>
                                            <div class="panel-body">
                                                <!--  -->
                                                <?php if (empty($companySignatories)) { ?>   
                                                    <p class="text-danger csF16">
                                                        <em>
                                                            <strong>
                                                                A signatory has not been determined yet.
                                                            </strong>
                                                        </em>
                                                    </p>
                                                <?php } ?>    
                                                <form action="javascript:void(0)">
                                                    <div class="form-group">
                                                        <label>First Name</label>
                                                        <input type="text" class="form-control" value="<?= $companySignatories['first_name'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Last Name</label>
                                                        <input type="text" class="form-control" value="<?= $companySignatories['last_name'] ?>" disabled  />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="text" class="form-control" value="<?= $companySignatories['email'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Title</label>
                                                        <input type="text" class="form-control" value="<?= $companySignatories['title'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" class="form-control" value="<?= $companySignatories['phone'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>SSN</label>
                                                        <input type="text" class="form-control" value="<?= $companySignatories['ssn'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <input type="text" class="form-control" value="<?= $companySignatories['street_1'] ?>, <?= $companySignatories['city'] ?>, <?= $companySignatories['zip'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Date of birth</label>
                                                        <input type="text" class="form-control" value="<?= $companySignatories['birthday'] ?>" disabled />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong class="csF16 csW">Company Bank Account</strong>
                                            </div>
                                            <div class="panel-body">
                                                <!--  -->
                                                <?php if (empty($companyBankInfo)) { ?>   
                                                    <p class="text-danger csF16">
                                                        <em>
                                                            <strong>
                                                                A bank account has not been determined yet.
                                                            </strong>
                                                        </em>
                                                    </p>
                                                <?php } ?>    
                                                <form action="javascript:void(0)">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" value="<?= $companyBankInfo['name'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Account Type</label>
                                                        <input type="text" class="form-control" value="<?= $companyBankInfo['account_type'] ?>" disabled  />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Routing Number</label>
                                                        <input type="text" class="form-control" value="<?= $companyBankInfo['routing_number'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Account Number</label>
                                                        <input type="text" class="form-control" value="<?= $companyBankInfo['hidden_account_number'] ?>" disabled />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong class="csF16 csW">Company Federal Tax</strong>
                                            </div>
                                            <div class="panel-body">
                                                <!--  -->
                                                <?php if (empty($companyFederalTaxInfo)) { ?>   
                                                    <p class="text-danger csF16">
                                                        <em>
                                                            <strong>
                                                                A federal tax has not been determined yet.
                                                            </strong>
                                                        </em>
                                                    </p>
                                                <?php } ?>    
                                                <form action="javascript:void(0)">
                                                    <div class="form-group">
                                                        <label>Type</label>
                                                        <input type="text" class="form-control" value="<?= $companyFederalTaxInfo['tax_payer_type'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Filing Form</label>
                                                        <input type="text" class="form-control" value="<?= $companyFederalTaxInfo['filing_form'] ?>" disabled  />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Legal Name</label>
                                                        <input type="text" class="form-control" value="<?= $companyFederalTaxInfo['legal_name'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>EIN Verified</label>
                                                        <input type="text" class="form-control" value="<?= $companyFederalTaxInfo['ein_verified'] == 0 ? 'No' : 'Yes';?>" disabled />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>    

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong class="csF16 csW">Company Industry / Earning Types</strong>
                                            </div>
                                            <div class="panel-body">
                                                <!--  -->
                                                <?php if (empty($companyIndustry)) { ?>   
                                                    <p class="text-danger csF16">
                                                        <em>
                                                            <strong>
                                                                The industry has not been determined yet.
                                                            </strong>
                                                        </em>
                                                    </p>
                                                <?php } ?>    
                                                <?php if (empty($companyEarningTypes)) { ?>   
                                                    <p class="text-danger csF16">
                                                        <em>
                                                            <strong>
                                                                Earning types not been determined yet.
                                                            </strong>
                                                        </em>
                                                    </p>
                                                <?php } ?> 
                                                <form action="javascript:void(0)">
                                                    <div class="form-group">
                                                        <label>Industry</label>
                                                        <input type="text" class="form-control" value="<?= $companyIndustry['title'] ?>" disabled />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Earning Types:</label>
                                                        <br>
                                                        <?php if ($companyEarningTypes) { ?>
                                                            <?php foreach ($companyEarningTypes as $earningType) { ?>
                                                                <a href="#" style="padding: 8px; margin-bottom: 5px;" class="badge badge-secondary"><?php echo $earningType['name']; ?></a>
                                                            <?php } ?>
                                                        <?php } ?>    
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>  

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong class="csF16 csW">Company Pay Schedules</strong>
                                            </div>
                                            <div class="panel-body">
                                                <!--  -->
                                                <?php if (empty($companyPaySchedules)) { ?>   
                                                    <p class="text-danger csF16">
                                                        <em>
                                                            <strong>
                                                                Company pay schedule not found.
                                                            </strong>
                                                        </em>
                                                    </p>
                                                <?php } ?>    

                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="csW csBG4">
                                                                    Name
                                                                </th>
                                                                <th scope="col" class="csW csBG4">
                                                                    frequency
                                                                </th>
                                                                <th scope="col" class="csW csBG4">
                                                                    Pay Period Start<br />date
                                                                </th>
                                                                <th scope="col" class="csW csBG4">
                                                                    Pay Period End<br />date
                                                                </th>
                                                                <th scope="col" class="csW csBG4">
                                                                    Active Pay Schedule
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($companyPaySchedules as $schedule) { ?>
                                                                <tr data-id="<?= $value['sid'] ?>">
                                                                    <td class="vam">
                                                                        <?= $schedule['custom_name']; ?>
                                                                    </td>
                                                                    <td class="vam">
                                                                        <?= $schedule['frequency']; ?>
                                                                    </td>
                                                                    <td class="vam">
                                                                        <?= $schedule['anchor_pay_date']; ?>
                                                                    </td>
                                                                    <td class="vam">
                                                                        <?= $schedule['anchor_end_of_pay_period']; ?>
                                                                    </td>
                                                                    <td class="vam text-<?= $schedule['active'] == 1 ? 'success' : 'danger'; ?>"">
                                                                        <?= $schedule['active'] == 1 ? "Yes" : "No"; ?>
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
                                
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <strong class="csF16 csW">Company Employees</strong>
                                            </div>
                                            <div class="panel-body">
                                                <!--  -->
                                                <?php if (empty($companyEmployees)) { ?>   
                                                    <p class="text-danger csF16">
                                                        <em>
                                                            <strong>
                                                                No employee sync with gusto.
                                                            </strong>
                                                        </em>
                                                    </p>
                                                <?php } ?>    

                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="csW csBG4">
                                                                    Name
                                                                </th>
                                                                <th scope="col" class="csW csBG4 text-right">
                                                                    Personal<br />detail
                                                                </th>
                                                                <th scope="col" class="csW csBG4 text-right">
                                                                    Compensation<br />detail
                                                                </th>
                                                                <th scope="col" class="csW csBG4 text-right">
                                                                    Work<br />address
                                                                </th>
                                                                <th scope="col" class="csW csBG4 text-right">
                                                                    Home<br />address
                                                                </th>
                                                                <th scope="col" class="csW csBG4 text-right">
                                                                    Federal Tax
                                                                </th>
                                                                <th scope="col" class="csW csBG4 text-right">
                                                                    State Tax
                                                                </th>
                                                                <th scope="col" class="csW csBG4 text-right">
                                                                    Is Onboard
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($companyEmployees as $employee) { ?>
                                                                <tr data-id="<?= $value['sid'] ?>">
                                                                    <td class="vam">
                                                                        <?= $employee['name']; ?>
                                                                    </td>
                                                                    <td class="vam text-right text-<?= $employee['personal_details'] == 1 ? 'success' : 'danger'; ?>"">
                                                                        <?= $employee['personal_details'] == 1 ? "Yes" : "No"; ?>
                                                                    </td>
                                                                    <td class="vam text-right text-<?= $employee['compensation_details'] == 1 ? 'success' : 'danger'; ?>"">
                                                                        <?= $employee['compensation_details'] == 1 ? "Yes" : "No"; ?>
                                                                    </td>
                                                                    <td class="vam text-right text-<?= $employee['work_address'] == 1 ? 'success' : 'danger'; ?>"">
                                                                        <?= $employee['work_address'] == 1 ? "Yes" : "No"; ?>
                                                                    </td>
                                                                    <td class="vam text-right text-<?= $employee['home_address'] == 1 ? 'success' : 'danger'; ?>"">
                                                                        <?= $employee['home_address'] == 1 ? "Yes" : "No"; ?>
                                                                    </td>
                                                                    <td class="vam text-right text-<?= $employee['federal_tax'] == 1 ? 'success' : 'danger'; ?>"">
                                                                        <?= $employee['federal_tax'] == 1 ? "Yes" : "No"; ?>
                                                                    </td>
                                                                    <td class="vam text-right text-<?= $employee['state_tax'] == 1 ? 'success' : 'danger'; ?>"">
                                                                        <?= $employee['state_tax'] == 1 ? "Yes" : "No"; ?>
                                                                    </td>
                                                                    <td class="vam text-right text-<?= $employee['is_onboarded'] == 1 ? 'success' : 'danger'; ?>"">
                                                                        <?= $employee['is_onboarded'] == 1 ? "Yes" : "No"; ?>
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

                            <!-- Main body ends -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>