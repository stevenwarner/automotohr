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
                                            <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Termes of Service</h1>
                                        </div>

                                        <?php
                                        $pageContent = json_decode($page_data['content'], true);
                                        ?>

                                        <div class="hr-search-main" style="display: block;">
                                            <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />

                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Terms of Service Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="terms_slug" id="terms_slug" value="<?php echo $pageContent['page']['terms']['slug']; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12"><br>
                                                    <label>Terms of Service Text</label><b class="text-danger"> *</b>
                                                    <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                    <textarea class="ckeditor" name="terms_text" id="terms_text" rows="8" cols="60"><?php echo $pageContent['page']['terms']['text']; ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-12 text-right">
                                            <input type="submit" name="submit_button" class="btn btn-success" value="Save">
                                            <a class="btn btn-default" href='<?php echo base_url('manage_admin/cms') ?>'>Cancel</a>
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
</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#header_form").validate({
            ignore: [],
            rules: {
                terms_slug: {
                    required: true
                },
                terms_text: {
                    required: true
                }
            },
            messages: {
                terms_slug: {
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
        pageData.page['terms'] = {
            text: CKEDITOR.instances['terms_text'].getData().trim(),
            slug: $("#terms_slug").val()
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