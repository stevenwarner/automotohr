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
                                    </div>
                                    <div class="add-new-company">
                                        <form action="" method="POST" name='add_brand_company' id='add_brand_company'>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><?php echo $page_title; ?></h1>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label>Select Company<span class="hr-required"> * </span></label>
                                                        <br>
                                                        <div class="multiselect-wrp">
                                                            <?php if (sizeof($companies) > 0) { ?>
                                                                <select style="width:350px;" multiple class="chosen-select" tabindex="8" name='companies[]' id='companies'>
                                                                    <?php foreach ($companies as $company) { ?>
                                                                        <option value="<?php echo $company['sid']; ?>"><?php echo $company['CompanyName']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <?php foreach ($companies as $company) { ?>
                                                                    <input type="hidden" name="company_name[<?php echo $company['sid']; ?>]" value="<?php echo $company['CompanyName']; ?>"/>
                                                                    <input type="hidden" name="company_website[<?php echo $company['sid']; ?>]" value="<?php echo $company['WebSite']; ?>"/>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <p>No Company Found.</p>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <a href='<?php echo base_url().'manage_admin/exclude_companies'; ?>' class="search-btn black-btn">Cancel</a>
                                                    <input type="submit" class="search-btn" value="Add" name="submit" onclick="return validate_form();">                        
                                                </div>                                        
                                            </div>
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
<script type="text/javascript">
    // Multiselect
    var config = {
        '.chosen-select': {}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    // validate form for empty selects
    function validate_form() {
        var items_length = $('#companies :selected').length;
        if (items_length == 0) {
            alertify.alert('No company selected', "Please select some companies");
            return false;
        }
    }
</script>