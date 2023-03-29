<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('organizational_hierarchy/vacancies'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                    </span>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                </div>

                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <h1 class="hr-registered pull-left">
                                        <span class=""><?php echo $subtitle; ?></span>
                                    </h1>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="universal-form-style-v2">
                                        <form enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid?>" />
                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid?>" />
                                            <input type="hidden" id="department_sid" name="department_sid" value="<?php echo $vacancy['department_sid']?>" />
                                            <input type="hidden" id="position_sid" name="position_sid" value="<?php echo $vacancy['position_sid']?>" />


                                            <?php if(!empty($vacancy)) { ?>
                                                <?php for($count = 0; $count < ($vacancy['vacancies_count'] - $vacancy['hired_count']); $count++) { ?>

                                                    <ul class="row">
                                                        <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label>Position:</label>
                                                            <span style="padding: 12px;" class="invoice-fields">
                                                                <?php echo $vacancy['position_name']; ?>&nbsp;(<?php echo $vacancy['department_name'] != '' ? $vacancy['department_name'] : 'General'; ?>)
                                                            </span>
                                                        </li>

                                                        <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label>Hire Under:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select data-rule-required="true" class="invoice-fields" name="hire_under[]" id="hire_under[]">
                                                                    <option value="">Please Select</option>
                                                                    <?php if(!empty($parent_employees)) { ?>
                                                                        <?php foreach($parent_employees as $employee) { ?>
                                                                            <option value="<?php echo $employee['employee_sid']; ?>"><?php echo $employee['first_name'] . ' ' . $employee['last_name']?></option>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </li>
                                                        <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label>Employee:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="employees_sid[]" id="employees_sid[]">
                                                                    <option value="">Please Select</option>
                                                                    <?php if(!empty($employees)) { ?>
                                                                        <?php foreach($employees as $employee) { ?>
                                                                            <option value="<?php echo $employee['sid']; ?>"><?php echo $employee['first_name'] . ' ' . $employee['last_name']?> <?php echo $employee['is_executive_admin'] == 1 ? '(Executive Admin)' : ''; ?></option>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </ul>

                                                <?php } ?>
                                            <?php } ?>
                                            <input class="btn btn-success" type="submit" value="Save" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main table view end -->
            </div>
        </div>
    </div>
</div>

