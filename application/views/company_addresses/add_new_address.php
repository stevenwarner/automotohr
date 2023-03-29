<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('company_addresses'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp form-wrp">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form method="post" id="new-company-address-form">
                                            <div class="row">
                                                <div class="<?php echo $form == 'add' ? 'col-lg-12 col-md-12 col-xs-12 col-sm-12' : 'col-lg-9 col-md-9 col-xs-12 col-sm-9'?>">
                                                    <div class="form-group">
                                                        <label>Address:<span class="staric">*</span></label>
                                                        <input class="form-control" type="text" id="address" value="<?php echo $address?>" name="address"/>
                                                        <input type="hidden" id="form" name="form" value="<?php echo $form?>"/>
                                                    </div>
                                                </div>
                                                <?php if($form != 'add'){?>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <div class="form-group">
                                                            <label>Status:<span class="staric">*</span></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="form-control" type="text" id="status" name="status">
                                                                    <option value="0" <?php echo $status ? '' : 'selected="selected"'?>>In Active</option>
                                                                    <option value="1" <?php echo $status ? 'selected="selected"' : ''?>>Active</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <input type="submit" name="submit" id="submit" class="btn btn-success btn-equalizer" value="<?php echo $form == 'add' ? 'Save Address' : 'Update Address'?>">
                                                    </div>
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#new-company-address-form').validate({
            ignore: ":hidden:not(select)",
            rules: {
                address: {
                    required: true
                }
            },
            messages: {
                address: {
                    required: 'Address is required'
                }
            }
        });
    });
</script>