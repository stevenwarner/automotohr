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
                            <?php echo validation_errors('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Error: </strong>', '</div>'); ?>
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="edit-template-from-main">
                                        <div class="add-new-company">
                                            <form action="<?php echo current_url();?>" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8" id="form_remarket_settings">
                                                <ul>
                                                    <li class="form-col-100">
                                                        <label for="page_content">Jobs<span class="hr-required">*</span></label>
                                                        <div class="hr-fields-wrap">
                                                            <input type="number" name="jobs" value="<?= $remarket_settings['jobs'] ?>" class="hr-form-fileds" required min="0" max="100">
                                                        </div>
                                                    </li>
                                                    <li class="form-col-100">
                                                        <label for="page_content">Duration to send related jobs to applicant(in days)<span class="hr-required">*</span></label>
                                                        <div class="hr-fields-wrap">
                                                            <input type="number" name="duration" value="<?= $remarket_settings['duration'] ?>" class="hr-form-fileds" required min="0" max="100">
                                                        </div>

                                                    </li>
                                                    <li class="form-col-100">
                                                        <label for="page_content">Email Template<span class="hr-required">*</span></label>
                                                        <div class="hr-fields-wrap">
                                                        <select class="invoice-fields" id="email_template_sid" name="email_template_sid" required>
                                                                <option value="">Please Select</option>
                                                                <?php if(!empty($email_templates)) { ?>
                                                                    <?php foreach ($email_templates as $email_template) { ?>
                                                                        <option value="<?php echo $email_template["sid"]; ?>" <?= $email_template["sid"] == $remarket_settings['email_template_sid'] ? "selected": "" ?> >
                                                                            <?php echo $email_template["name"]; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </li>

                                                    <li class="form-col-100">
                                                        <button onclick="func_save_customization();" type="button" class="search-btn" >Update</button>
                                                    </li>
                                                </ul>
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
</div>
<script type="text/javascript">
    //  This function validate footer logo form.
    function func_save_customization() {
        
        if ($('#form_remarket_settings').valid()) {
            
            
            var validation_flag = true;
            
                    
            if(validation_flag == true ){
                $('#form_remarket_settings').submit();
            }
            
        }
    }
</script> 