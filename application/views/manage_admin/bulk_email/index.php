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
                                        <h1 class="page-title"><i class="fa fa-group"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin'); ?>" class="black-btn pull-right">
                                            <i class="fa fa-long-arrow-left"></i> Dashboard
                                        </a>
                                    </div>
                                    <!-- drop down area -->
                                    <div class="hr-search-main search-collapse-area" style='display:block'>
                                        <form method="GET" action="<?php echo base_url('manage_admin/bulk_email'); ?>" name="search" id="search">
                                            <div class="row">
                                                <div id="company_div">                                                    
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <label class="text-left">Company : <span class="hr-required">*</span></label>
                                                            <div class="hr-select-dropdown">
                                                                <?php if (sizeof($companies) > 0) { ?>
                                                                    <select class="invoice-fields" name="company_sid" id="company_sid">
                                                                        <option value="">Please Select</option>
                                                                        <option value="all" <?php if (isset($company_sid) && $company_sid == 'all') { ?> selected="selected" <?php } ?>>All Companies</option>
                                                                        <?php foreach ($companies as $active_company) { ?>
                                                                            <option <?php if (isset($company_sid) && $company_sid == $active_company['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
                                                                                <?php echo $active_company['CompanyName']; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <p>No company found.</p>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row field-row-autoheight text-right">
                                                            <input type="submit" class="btn btn-success" value="Select Company" name="submit" id="submit">
                                                            <a class="btn btn-success" href="<?php echo base_url('manage_admin/bulk_email'); ?>">Reset Filters</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- drop down area -->
                                    <!-- all employees area -->
                                    <div class="field-row field-row-autoheight">
                                        <?php if (!empty($all_employers)) { ?>
                                            <div class="field-row field-row-autoheight text-right">
                                                <button class='btn btn-success' name='select_all' id='select_all'>Select All</button>
                                                <button class='btn btn-success' name='un_select_all' id='un_select_all'>Unselect All</button>
                                            </div>
                                        <?php } ?>
                                        <form method="post" action="<?php echo base_url('manage_admin/bulk_email/send_email'); ?>" name="employees_form" id="employees_form">
                                            <?php if (!empty($all_employers)) { ?>
                                                <div class="field-row field-row-autoheight text-right">
                                                    <input type="submit" class="btn btn-success" value="Send Bulk Email" name="bulk_email_submit" id="bulk_email_submit" onclick='return validate_emp_form();'>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($all_employers)) { ?>
                                                <?php foreach ($all_employers as $company => $employees) { ?>
                                                    <div class="hr-box">
                                                        <div class="hr-box-header bg-header-green">
                                                            <h4 class="hr-registered pull-left">
                                                                <?php echo ucwords($company); ?>
                                                            </h4>
                                                        </div>
                                                        <div class="table-responsive hr-innerpadding">
                                                            <table class="table table-bordered">
                                                                <tbody>
                                                                    <?php $employees = $employees['company_employees']; ?>
                                                                    <?php $row_count = 0; ?>
                                                                    <?php if (!empty($employees)) { ?>
                                                                        <?php foreach ($employees as $employee) { ?>
                                                                            <?php
                                                                            if ($row_count > 1) {
                                                                                echo '<tr>';
                                                                                $row_count = 0;
                                                                            }
                                                                            ?>  
                                                                        <td>
                                                                            <label class="control control--checkbox font-normal">
                                                                                <input type="checkbox" name="emp[]" value="<?php echo $employee['sid']; ?>" class='employee_checkboxes'/>
                                                                                <?php echo ucwords($employee['first_name'] . ' ' . $employee['last_name']) . '<span style="float:right;">' . $employee['email'] . '</span>'; ?>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </td>
                                                                        <?php $row_count ++; ?>
                                                                        <?php
                                                                        if ($row_count > 1) {
                                                                            echo '</tr>';
                                                                        }
                                                                        ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td>
                                                                            No employees found.
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <h4 class="hr-registered pull-left">Bulk Email</h4>
                                                    </div>
                                                    <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr class='text-center'>
                                                                    <td>Please Select Company</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($all_employers)) { ?>
                                                <div class="field-row field-row-autoheight text-right">
                                                    <input type="submit" class="btn btn-success" value="Send Bulk Email" name="bulk_email_submit" id="bulk_email_submit" onclick='return validate_emp_form();'>
                                                </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                    <!-- all employees area -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<button style='display:none;' type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#bulk_email_modal" id="bulk_email_button">Open Bulk Email Modal</button>
<div id="bulk_email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Bulk Email to Employees</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">                            
                        <ul>
                            <form method='post' id='employee-bulk-form' name='employee-bulk-form'>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-3"><b>Templates</b></div>
                                        <div class="col-md-9">
                                            <select class="form-control js-email-template">
                                                <option value="null">[Select a template]</option>
                                                <?php if(sizeof($admin_templates)) { ?>
                                                <?php   foreach ($admin_templates as $k0 => $v0) { ?>
                                                <option value="<?=$v0['id'];?>"><?=$v0['templateName'];?></option>
                                                <?php   } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-3"><b>Subject</b><span class="hr-required red"> * </span></div>
                                        <div class="col-md-9">
                                            <input type='text' class="hr-form-fileds invoice-fields" id="bulk_email_subject" name='subject' />
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-3"><b>Message</b><span class="hr-required red"> * </span></div>
                                        <div class="col-md-6">                      
                                            <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" id="bulk_email_message" name="bulk_email_message"></textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="offer-letter-help-widget pull-left" style="top: 0;">
                                                
                                                <div class="tags-area pull-left">
                                                    <br />
                                                    <strong>Tags :</strong>
                                                    <ul class="tags">
                                                        <li>{{first_name}}</li>
                                                        <li>{{last_name}}</li>
                                                        <li>{{phone}}</li>
                                                        <li>{{email}}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <div class="message-action-btn">
                                                <input type="submit" value="Send Message" class="btn btn-success" onclick="bulk_email_form_validate()"> 
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>

<script>
    $('#submit').click(function () {
        $("#search").validate({
            ignore: [],
            rules: {
                company_sid: {
                    required: true,
                }
            },
            messages: {
                company_sid: {
                    required: 'Company name is required'
                }
            }
        });
    });

    $('#select_all').click(function () {
        $("#employees_form input:checkbox").each(function () {
            this.checked = true;
        });
    });

    $('#un_select_all').click(function () {
        $("#employees_form input:checkbox").each(function () {
            this.checked = false;
        });
    });
    
    function validate_emp_form() {
        var emp_size = $(".employee_checkboxes:checked").size();
        
        if(emp_size > 0){
            $('#bulk_email_button').click();
        } else {
            alertify.alert('Send Bulk Email Error', 'Please select Applicant(s) to send bulk email.');
        }
        
        return false; // stop form propagation
    }
    
    function bulk_email_form_validate() {
        $("#employee-bulk-form").validate({
            ignore: [],
            rules: {
                subject: {
                    required: true
                },
                bulk_email_message: {
                    required: function () {
                        CKEDITOR.instances.bulk_email_message.updateElement();
                    },
                    minlength: 10
                }
            },
            messages: {
                subject: {
                    required: 'E-Mail Subject is required'
                },
                bulk_email_message: {
                    required: "E-Mail Message is required",
                    minlength: "Please enter few characters"
                }
            },
            submitHandler: function () {
                var ids = [{}];
                var counter = 0;
                $.each($(".employee_checkboxes:checked"), function () {
                    ids[counter++] = $(this).val();
                });
               
                var subject = ($('#bulk_email_subject').val()).trim();
                var message = ($('#bulk_email_message').val()).trim();
                
                url_to = "<?= base_url() ?>manage_admin/bulk_email/send_email";
                $.post(url_to, {action: "bulk_email", ids: ids, subject: subject, message: message})
                    .done(function (response) {
                        $("#bulk_email_modal .close").click();
                        alertify.success('Bulk email sent to selected employee(s).');
                    });
                return false;
            }
        });
    }
</script>

<style>
.modal-backdrop {
    z-index: 1;
}
.universal-form-style-v2 ul li label,
.universal-form-style-v2 form label{
    float: none !important;
}
</style>

<script>
    $(function(){
        var email_templates = <?=@json_encode($admin_templates);?>;

        $('.js-email-template').change(function(event) {
            var obj = indexFinder('id', $(this).val());
            console.log(obj);
            if(!obj) return false;
            //
            $('#bulk_email_subject').val(obj['subject']);
            // $('#bulk_email_message').val(obj['body']);
            CKEDITOR.instances['bulk_email_message'].setData(obj['body']);
        });

        function indexFinder(searchIndex, searchValue){
            var i = 0,
            arrLength = email_templates.length;
            for(i; i < arrLength; i++){
                if(email_templates[i][searchIndex] == searchValue) return email_templates[i];
            }
            return false;
        }
    })
</script>


<style>
    .tags-area strong{ padding: 0 10px;}
    .offer-letter-help-widget{ padding-bottom: 10px; }
    .tags-area ul.tags{ padding: 0 0; }
    .tags-area ul.tags li{ width: auto; background-color: #f8f8f8; border: 1px solid #d9d8d5;border-radius: 50px;display: inline-block;height: auto !important;margin: 10px 0 0 10px !important;overflow: hidden;padding: 7px;text-align: center; }
</style>