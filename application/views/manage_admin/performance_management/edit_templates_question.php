
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
                                        <h1 class="page-title"><i class="fa fa-envelope-square" aria-hidden="true"></i>Edit Questions Of <?php echo $template[0]['name'];?>
                                    </h1>
                                    <span class="pull-right">
                                        <a href="<?=base_url("manage_admin/performance_management");?>" class="btn btn-default">Back To Templates</a>
                                    </span>
                                    </div>
                                    <div class="edit-email-template">
                                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                        <div class="edit-template-from-main" >
                                            <?php echo form_open_multipart(''); ?>
                                            <?php foreach ($questions as $key => $question) { ?>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>Question <?php echo $key+1; ?> <span class="hr-required">*</span></label>
                                                        <input class="form-control" type="text" name="Question<?php echo $key+1; ?>" value="<?php echo $question['title'] ?>">
                                                        <br>
                                                        <textarea rows="5" class="form-control" placeholder="Description goes here" name="Description<?php echo $key+1; ?>"><?php echo strip_tags($question['description']); ?></textarea>
                                                    </div>
                                                </div>
                                                <hr />
                                                <?php } ?>
                                                <div class="col-sm-12">
                                                    <input type="submit" name="action" value="Save" class="search-btn">
                                                    <a href="<?php echo base_url('manage_admin/performance_management'); ?>" class="btn search-btn btn-default">Cancel</a>
                                                </div>
                                            <?php echo form_close(); ?>
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