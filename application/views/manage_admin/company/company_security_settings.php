<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies/manage_company/'.$company_sid); ?>"><i class="fa fa-long-arrow-left"></i>Company Dashboard</a>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="" method="POST">
                                            <?php if(sizeof($company_employees)>0){
                                                foreach($company_employees as $company_employee){ ?>
                                                    <div class="row">
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <div class="field-row">
                                                                        <label for="CompanyName">Employee Name / Username <span class="hr-required">*</span></label>
                                                                        <input type="text" class="invoice-fields" name="employee_details[]" value="<?php echo $company_employee['first_name'].' '.$company_employee['last_name'].' / '.$company_employee['username'];?>" disabled="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <div class="field-row">
                                                                        <label for="country">Access Level</label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields access_level_dropdown" name="access_level[]" data-employee_sid="<?php echo $company_employee['sid']; ?>">
                                                                                <option value="">Access Level Not Found</option>
                                                                                <?php foreach ($access_level as $al) { ?>
                                                                                    <option value="<?php echo $al; ?>"
                                                                                        <?php if(isset($company_employee['access_level']) && $al == $company_employee['access_level']) { echo "selected"; } ?>>
                                                                                        <?php echo $al; ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <div class="field-row">
                                                                <br />
                                                                <label class="control control--radio admin-access-level">
                                                                    <input type="radio" name="radio_group" id="checkbox_<?php echo $company_employee['sid']; ?>" class="is_primary_admin_checkbox" data-employee_sid="<?php echo $company_employee['sid']; ?>" <?php echo ($company_employee['is_primary_admin'] == 1 ? 'checked="checked"' : ''); ?> />
                                                                    <input type="hidden" class="is_primary_admin_hidden" name="is_primary_admin[]" id="is_primary_admin_hidden_<?php echo $company_employee['sid']; ?>" value="<?php echo $company_employee['is_primary_admin']; ?>" />
                                                                    Primary Admin
                                                                    <div class="control__indicator"></div>
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="sid[]" value="<?php echo $company_employee['sid']; ?>">
                                                <?php } ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <input type="submit" class="search-btn" onclick="return confirm('Are you sure you want to update security?');" value="Update Security Access" name="submit">
                                                </div>
                                            <?php } else { ?>
                                                <h2>No employee found!</h2>
                                            <?php } ?>
                                        </form>
                                    </div>
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
    $(document).ready(function () {
        $('.is_primary_admin_checkbox').each(function () {
            $(this).on('click', function () {
                var employee_sid = $(this).attr('data-employee_sid');

                var status = $(this).prop('checked');

                if(status == true){
                    $('.is_primary_admin_hidden').each(function () {
                        $(this).val(0);
                    });

                    $('#is_primary_admin_hidden_' + employee_sid).val(1);
                }else{
                    $('.is_primary_admin_hidden').each(function () {
                        $(this).val(0);
                    });
                }

                //console.log(status);
            });
        });
    });
</script>