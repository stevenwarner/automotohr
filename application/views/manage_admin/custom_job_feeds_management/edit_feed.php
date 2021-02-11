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
                                        <h1 class="page-title"><i class="fa fa-list"></i><?= $page_title?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/custom_job_feeds_management'); ?>"><i class="fa fa-long-arrow-left"></i> Back</a>
                                    </div>
                                    <br />
                                    <br />
                                    <br />
                                    <div style="min-height: 790px;">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="add-new-company" id="menu-form">
                                                    <form action="" method="POST" id="edit_feed" autocomplete="off">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <div class="field-row">
                                                                    <label for="anchor-title">Title <span class="hr-required">*</span></label>
                                                                    <?php echo form_input('title', set_value('title', $edit_data['title']), 'class="hr-form-fileds" id="title"'); ?>
                                                                    <?php echo form_error('title'); ?>
                                                                </div>
                                                            </div>

<!--                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
<!--                                                                <div class="field-row">-->
<!--                                                                    <label for="anchor-title">URL <span class="hr-required">*</span></label>-->
<!--                                                                    --><?php //echo form_input('url', set_value('url', $edit_data['url']), 'class="hr-form-fileds" id="url"'); ?>
<!--                                                                    --><?php //echo form_error('url'); ?>
<!--                                                                </div>-->
<!--                                                            </div>-->

                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <div class="field-row">
                                                                    <label for="type">Status</label>
                                                                    <select name="status" id="status" class="hr-form-fileds">
                                                                        <option value="1" <?php $edit_data['status'] == '1' ? 'selected="selected"':''?>>Active</option>
                                                                        <option value="0" <?php $edit_data['status'] == '0' ? 'selected="selected"':''?>>In Active</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <div class="field-row">
                                                                    <label for="type">Type</label>
                                                                    <select name="type" id="type" class="hr-form-fileds">
                                                                        <option value="Paid" <?php $edit_data['type'] == 'Paid' ? 'selected="selected"':''?>>Paid</option>
                                                                        <option value="Organic" <?php $edit_data['type'] == 'Organic' ? 'selected="selected"':''?>>Organic</option>
                                                                        <option value="Paid/Organic" <?php $edit_data['type'] == 'Paid/Organic' ? 'selected="selected"':''?>>Paid/Organic</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <div class="field-row field-row-autoheight">
                                                                    <label for="desc">Description</label>
                                                                    <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                                    <textarea class="ckeditor textarea" name="desc" id="desc" rows="8" cols="60" required>
                                                                        <?php echo set_value('desc',$edit_data['description']); ?>
                                                                    </textarea>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <h4 class="hr-registered">Is It Accept URL?</h4>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <div class="field-row">
                                                                        <label class="control control--radio">No
                                                                            <input type="radio" name="accept_url" class="video_source" value="0" <?php echo $edit_data['accept_url_flag'] == '0' ? 'checked="checked"':''?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <div class="field-row">
                                                                        <label class="control control--radio">Yes
                                                                            <input type="radio" name="accept_url" class="video_source" value="1" <?php echo $edit_data['accept_url_flag'] == '1' ? 'checked="checked"':''?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" value="edit" name="perform_action">

                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                                <input type="submit" class="btn btn-success" value="Update">
                                                                <a href="<?= base_url('manage_admin/custom_job_feeds_management')?>" class="btn btn-default">Cancel</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">

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
</div>
<script type="text/javascript">
    $(function () {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        $("#edit_feed").validate({
            ignore: ":hidden:not(select)",
            rules: {
//                url: {
//                    required: true,
//                    pattern: /(?:(?:ftp|https?):\/\/)?(www\.)?[a-z0-9\-\.]{3,}\.[a-z]{3}$/
//                },
                title: {
                    required: true
                }
            },
            messages: {
//                url: {
//                    required: 'Url is required',
//                    pattern: 'Not a valid URL'
//                },
                title: {
                    required: 'Title is required'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });
</script>