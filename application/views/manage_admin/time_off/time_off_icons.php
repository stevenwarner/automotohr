<?php
    $slugs = [];
    $slugs['type_info'] = 'Policy Type';
    $slugs['policy_title_info'] = 'Policy Title';
    $slugs['sort_order_info'] = 'Sort Order';
    $slugs['entitled_employee_info'] = 'Non-entitled Employee(s)';
    $slugs['approvers_only'] = 'Only Approver(s) can see';
    $slugs['deactivate_policy'] = 'Deactivate';
    $slugs['include_in_balance'] = 'Include in Balance';
    $slugs['accrual_method_info'] = 'Accrual Method';
    $slugs['accrual_time_info'] = 'Accrual Time';
    $slugs['accrual_frequency_info'] = 'Accrual Frequency';
    $slugs['accrual_rate_info'] = 'Accrual Rate';
    $slugs['minimum_applicable_hours_info'] = 'Minimum Applicable Time for permanent employee(s)';
    $slugs['allow_carryover_cap_info'] = 'Allow Carryover Cap? (use it or lose it)';
    $slugs['carryover_cap_info'] = 'Carryover Cap';
    $slugs['allow_negative_balance_info'] = 'Allow Negative Balance';
    $slugs['new_hire_maximum_days_off_HD_info'] = 'Allowed negative balance';
    $slugs['applicable_date_for_policy_info'] = 'Applicable Date for Policy';
    $slugs['reset_date_info'] = 'Reset Date';
    $slugs['new_hire_tag'] = 'Probation Message';
    $slugs['waiting_period_info'] = 'Waiting period';
    $slugs['new_hire_maximum_days_off_HD'] = 'New hire maximum time off';
    $slugs['accrual_plans'] = 'Accrual Plans';
    $slugs['accruals_plans_label'] = 'Accrual Plans';
    // 
    $slugs['select_a_template_label'] = 'Select a Template (Label)';
    $slugs['policy_type_label'] = 'Policy Type (Label)';
    $slugs['policy_title_label'] = 'Policy Title (Label)';
    $slugs['sort_order_label'] = 'Sort Order (Label)';
    $slugs['non_employees_label'] = 'Non entiled employees (Label)';
    $slugs['approvers_can_see_label'] = 'Only approvers can see (Label)';
    $slugs['deactivate_label'] = 'Deactivate this policy (Label)';
    $slugs['employees_type_label'] = 'Employee Type (Label)';
    $slugs['employees_type_info'] = 'Employee Type';
    $slugs['include_in_balance_label'] = 'Include this policy in balance (Label)';
    $slugs['accrual_method_label'] = 'Accrual Method (Label)';
    $slugs['accrual_time_label'] = 'Accrual Time (Label)';
    $slugs['accrual_frequency_label'] = 'Accrual Frequency (Label)';
    $slugs['accrual_rate_label'] = 'Accrual Rate (Label)';
    $slugs['minimum_applicable_time_label'] = 'Minimum applicable time for this policy to take affect (Label)';
    $slugs['carry_over_label'] = 'Allow this policy to annually carryover (Label)';
    $slugs['carryover_cap_label'] = 'Allowed carryover time (Label)';
    $slugs['negative_balance_label'] = 'Allow Negative Balance (Label)';
    $slugs['negative_balance_limit_label'] = 'Allowed negative balance (Label)';
    
    $slugs['applicable_date_label'] = 'Applicable date for policy to take affect (Label)';
    $slugs['applicable_date_msg_label'] = 'Applicable date for policy to take affect (Label)';
    $slugs['employee_joining_date_label'] = 'Employee Joining Date (Label)';
    $slugs['pick_a_date_label'] = 'Pick a Date (Label)';
    
    $slugs['reset_date_label'] = 'Reset Date (Label)';
    $slugs['reset_date_1_label'] = 'Policy Applicable Date (Label)';
    $slugs['reset_date_2_label'] = 'Pick a Date (Label)';
    
    $slugs['probation_period_label'] = 'Probation period (Label)';
    $slugs['probation_period_rate_label'] = 'Probation employees accrual rate (Label)';
    $slugs['probation_msg_label'] = 'Any employee that doesn\'t meet the following criteria will be considered as on "probation"';
?>

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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Time Off</h1>
                                    </div>
                                    <!-- Search Table Start -->
                                    <!-- Search Table End -->
                                    <!-- Email Logs Start -->
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Time Off Help icons & labels</h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                               <div class="col-xs-12">
                                                    <span class="pull-right">
                                                       <button class="btn btn-black" onclick="window.location.href='<?php echo base_url('manage_admin/modules') ?>' ">Back</button>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-right">
                                                        <button class="btn btn-success save_icons">
                                                            Save Icons
                                                        </button>
                                                    </span>
                                                </div>
                                            </div> 
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12 icon_list"> 
                                                    <ul> 
                                                        <?php if(!empty($timeoff_icons))  {  ?>
                                                            <?php foreach($timeoff_icons as $timeoff_icon)  {  ?>
                                                                <li>
                                                                    <label><?php echo $slugs[trim($timeoff_icon['slug'])]; ?>:</label>
                                                                    <?php if(preg_match('/label/', $timeoff_icon['slug'])) { ?>
                                                                    <input id="icon_id_<?php echo $timeoff_icon['sid']; ?>" class="hr-form-fileds icon_information" value=" <?php echo strip_tags($timeoff_icon['info_content']); ?>" name="header_text" spellcheck="false"/>
                                                                    <div class="clearfix"></div>
                                                                    <?php } else { ?>
                                                                    <textarea id="icon_id_<?php echo $timeoff_icon['sid']; ?>" class="hr-form-fileds ckeditor icon_information" required="" name="header_text" spellcheck="false">
                                                                        <?php echo $timeoff_icon['info_content']; ?>
                                                                    </textarea>
                                                                    <?php }?>
                                                                </li>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </ul>     
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-right">
                                                        <button class="btn btn-success save_icons">
                                                            Save Icons
                                                        </button>
                                                    </span>
                                                </div>
                                            </div> 
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-right">
                                                       <button class="btn btn-black" onclick="window.location.href='<?php echo base_url('manage_admin/company_module/1') ?>' ">Back</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Email Logs End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    li {
        width:100%; 
        list-style: none; 
        padding: 12px;
    }

    textarea {
        padding:10px; 
        height:150px;
    }

    li:nth-child(odd) {
        background-color: #F5F5F5;
    }

    .icon_list{
        padding: 6px;
    }

    .btn-black {
        color: #fff;
        background: #000;
    }
</style>
<script>
    CKEDITOR.config.toolbar = [
       ['Bold', 'Italic', 'Underline', 'FontSize']
       // ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print']
    ] ;

    $(".save_icons").on('click', function () {
        $('.icon_information').each(function (key) {
            var icon_id = $(this).attr('id');
            var info_content = CKEDITOR.instances[icon_id] === undefined ? $(`#${icon_id}`).val().trim() : $.trim(CKEDITOR.instances[icon_id].getData());

            icon_id = icon_id.replace("icon_id_", '');
            
            var baseURI = "<?=base_url();?>manage_admin/time_off/handler";

            var formData = new FormData();
            formData.append('icon_id', icon_id);
            formData.append('info_content', info_content);
            formData.append('action', 'change_timeoff_icon_info');

            $.ajax({
                url: baseURI,
                data: formData,
                method: 'POST',
                processData: false,
                contentType: false
            });
        });

        var successMSG = 'Time off icons info updated successsfully.';

        alertify.alert('SUCCESS!', successMSG, function(){
                
        });
    });
</script>
