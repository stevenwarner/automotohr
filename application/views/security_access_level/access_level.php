<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                                
                            </div>
                        </div>
                        <div class="btn-panel">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 pull-right">
                                <a class="btn btn-success btn-block" href="<?php echo base_url('security_access_level/details') ?>">Access Level Details</a>
                            </div>
                        </div>
                <div class="dashboard-conetnt-wrp">               
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="create-job-wrap">
                            <div class="universal-form-style-v2">
                                <ul>
                                    <?php foreach($company_employees as $company_employee){ ?>
                                        <?php 
                                            $txt = $company_employee['first_name'].' '.$company_employee['last_name'];
                                            $txtJ = 'N/A';
                                            //
                                            if(!empty($company_employee['username'])) $txt .= ' / '.$company_employee['username'];
                                            else $txt .= ' / N/A';
                                            if(!empty($company_employee['job_title'])) $txtJ = $company_employee['job_title'];
                                        ?>
                                        <?php //echo form_open('', array('name' => 'access_level')); ?>
                                            <li class="col-sm-4 col-xs-12">
                                                <label>Employee Name / Username</label>	
                                                <input type="text" class="invoice-fields" name="employee_details" value="<?= $txt;?>" disabled="">
                                            </li>
                                            <li class="col-sm-4 col-xs-12">
                                                <label>Job Title</label>	
                                                <input type="text" class="invoice-fields" name="employee_details" value="<?= $txtJ;?>" disabled="">
                                            </li>                                        
                                            <li class="col-sm-4 col-xs-12">
                                                <label>Access Level</label>								
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="access_level" name="access_level" onchange="update_reocrd(this.value, <?php echo $company_employee['sid']; ?>)">
    <!--                                                    <option value="">Select Access Level</option>-->
                                                        <?php foreach ($access_level as $al) { ?>
                                                            <option value="<?php echo $al; ?>" 
                                                            <?php if(isset($company_employee['access_level']) && $al == $company_employee['access_level']) { echo "selected"; } ?>> 
                                                                <?php echo $al; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>								
                                            </li> 
                                            <input type="hidden" name="sid" value="<?php echo $company_employee['sid']; ?>">
                                        <?php //echo form_close(); ?>
                                    <?php } ?> 
                                </ul>
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
<script language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
    function validate_form() {
        $("#access_level").validate({
            ignore: ":hidden:not(select)",
             rules: {
                access_level:   {
                                    required: true
                                }
                },
            messages: {
                access_level:   {
                                    required: 'Access Level is required',
                                }
                },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    
    function update_reocrd(level, id) {
        console.log(level+' - '+id);
        url = "<?= base_url() ?>security_access_level/access_level";
                    $.post(url, {id: id, level: level, action: 'update_record'})
                            .done(function (data) {
                               alertify.notify(data, 'success');
                            });
    }
</script>