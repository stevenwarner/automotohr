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
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Company</a>
                                    </div>
                                    <div class="edit-template-from-main">
                                        <div class="add-new-company">
                                            <div class="heading-title page-title">
                                                <h2 class="page-title">Company Name: <?php echo ucwords($company_name); ?></h2>
                                            </div>
                                            <form action="<?php echo current_url();?>" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8" id="form_remarket_company_settings">
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <ul>
                                                    <li class="form-col-100">
                                                        <label for="page_content">Status</label>
                                                        <label class="control control--radio admin-access-level radio_right_margin">
                                                            <input name="status" value="1" type="radio" <?php echo $remarket_company_settings['status'] == 1 ? 'checked="checked"' : ''; ?>/>
                                                            Enabled
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio admin-access-level radio_right_margin">
                                                            <input name="status" value="0" type="radio" <?php echo $remarket_company_settings['status'] != 1 ? 'checked="checked"' : ''; ?>/>
                                                            Disabled
                                                            <div class="control__indicator"></div>
                                                        </label>
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
        
        if ($('#form_remarket_company_settings').valid()) {
            
            
            var validation_flag = true;
            
                    
            if(validation_flag == true ){
                $('#form_remarket_company_settings').submit();
            }
            
        }
    }
</script>