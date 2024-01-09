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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit LogIn</h1>
                                    </div>
                                    <div class="hr-search-main" style="display: block;">
                                        <form enctype="multipart/form-data" method="post" id="header_form">

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
                                                    <h1 class="hr-registered pull-left">Login Here</h1>
                                                </div>
                                                <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Main Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_main_heading" id="section1_main_heading" value="<?php echo $pageContent['page']['sections']['section1']['mainheading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_heading" id="section1_heading" value="<?php echo $pageContent['page']['sections']['section1']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_btn_text" id="section1_btn_text" value="<?php echo $pageContent['page']['sections']['section1']['btnText']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">

                                                    <div class="field-row">
                                                        <label>Button slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_btn_slug" id="section1_btn_slug" value="<?php echo $pageContent['page']['sections']['section1']['btnSlug']; ?>" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">

                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Executive Admin Login Here</h1>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_heading" id="section2_heading" value="<?php echo $pageContent['page']['sections']['section2']['heading']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_btn_text" id="section2_btn_text" value="<?php echo $pageContent['page']['sections']['section2']['btnText']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_btn_slug" id="section2_btn_slug" value="<?php echo $pageContent['page']['sections']['section2']['btnSlug']; ?>" />
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="hr-box" style="margin: 15px 0 0;">

                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Having Trouble Loging In?</h1>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_heading" id="section3_heading" value="<?php echo $pageContent['page']['sections']['section3']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_btn_text" id="section3_btn_text" value="<?php echo $pageContent['page']['sections']['section3']['btnText']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">

                                                    <div class="field-row">
                                                        <label>Button slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_btn_slug" id="section3_btn_slug" value="<?php echo $pageContent['page']['sections']['section3']['btnSlug']; ?>" />
                                                    </div>
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
                section1_heading: {
                    required: true
                },section1_main_heading: {
                    required: true
                },
                section1_title: {
                    required: true
                },
                section1_slug: {
                    required: true
                },
                section2_heading: {
                    required: true
                },
                section2_title: {
                    required: true
                },
                section2_slug: {
                    required: true
                },
                section3_title: {
                    required: true
                },
                section3_title: {
                    required: true
                },
                section3_slug: {
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
                home_title: {
                    required: 'Hompe Title is required!'
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
        pageData.page['sections'] = {
            section1: {
                heading: $("#section1_heading").val(),
                mainheading: $("#section1_main_heading").val(),
                btnText: $("#section1_btn_text").val(),
                btnSlug: $("#section1_btn_slug").val()
            },
            section2: {
                heading: $("#section2_heading").val(),
                btnText: $("#section2_btn_text").val(),
                btnSlug: $("#section2_btn_slug").val()
            },
            section3: {
                heading: $("#section3_heading").val(),
                btnText: $("#section3_btn_text").val(),
                btnSlug: $("#section3_btn_slug").val()
            }
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