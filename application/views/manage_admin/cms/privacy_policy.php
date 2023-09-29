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

                                <form enctype="multipart/form-data" method="post" id="header_form">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Privacy Policy</h1>
                                        </div>

                                        <?php
                                        $pageContent = json_decode($page_data['content'], true);
                                        ?>

                                        <div class="hr-box" style="margin: 15px 0 0;">

                                            <div class="hr-box-header bg-header-green">
                                                <h1 class="hr-registered pull-left">Meta Details</h1>
                                            </div>

                                            <div class="col-xs-12 form-group">
                                                <br> <label>Meta Title:</label><b class="text-danger"> *</b>
                                                <input type="text" class="invoice-fields" name="meta_title" id="meta_title" value="<?php echo $pageContent['page']['meta']['title']; ?>" />
                                            </div>

                                            <div class="col-xs-12 form-group">
                                                <label>Meta Description:</label><b class="text-danger"> *</b>
                                                <textarea class="invoice-fields" name="meta_description" id="meta_description" rows="4" cols="60"><?php echo $pageContent['page']['meta']['description']; ?></textarea>
                                            </div>

                                            <div class="col-xs-12 form-group">
                                                <label>Meta Keywords:</label><b class="text-danger"> *</b>
                                                <textarea class="invoice-fields" name="meta_key_word" id="meta_key_word" rows="4" cols="60"><?php echo $pageContent['page']['meta']['keyword']; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="hr-box" style="margin: 15px 0 0;">
                                            <div class="hr-box-header bg-header-green">
                                                <h1 class="hr-registered pull-left">Privacy Policy</h1>
                                            </div> <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />

                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <label>Privacy Policy Slug</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="privacy_policy_slug" id="privacy_policy_slug" value="<?php echo $pageContent['page']['privacy_policy']['slug']; ?>" />
                                                </div>
                                            </div>

                                            <div class="col-xs-12"><br>
                                                <label>Privacy Policy Text</label><b class="text-danger"> *</b>
                                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                <textarea class="ckeditor" name="privacy_policy_text" id="privacy_policy_text" rows="8" cols="60"><?php echo $pageContent['page']['privacy_policy']['text']; ?></textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <hr />
                                    <div class="col-lg-12 text-right"><br>
                                        <input type="submit" name="submit_button" class="btn btn-success" value="Save">
                                        <a class="btn btn-default" href='<?php echo base_url('manage_admin/cms') ?>'>Cancel</a>
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
</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#header_form").validate({
            ignore: [],
            rules: {
                privacy_policy_slug: {
                    required: true
                },
                privacy_policy_text: {
                    required: true
                },
                meta_title: {
                    required: true
                },
                meta_description: {
                    required: true
                },
                meta_key_word: {
                    required: true
                }
            },
            messages: {
                privacy_policy_slug: {
                    required: 'Privacy Policy Slug is required!'
                }
            },
            submitHandler: function(form) {
                savepage();
                return;
            }
        });
    });


    function savepage() {

        const pageData = {
            page: {}
        };


        const pageId = $("#page_id").val();
        //
        pageData.page['privacy_policy'] = {
            text: CKEDITOR.instances['privacy_policy_text'].getData().trim(),
            slug: $("#privacy_policy_slug").val()
        };

        pageData.page['meta'] = {
            title: $("#meta_title").val(),
            keyword: $("#meta_key_word").val(),
            description: $("#meta_description").val()
        };

        //
        url_to = "<?= base_url() ?>manage_admin/cms/update_page";
        $.post(url_to, {
                pageId: pageId,
                content: pageData
            })
            .done(function() {
                alertify.success('Page Sucessfully Saved.');
            });
    }
</script>