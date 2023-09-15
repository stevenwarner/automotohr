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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Forgot Password</h1>
                                    </div>
                                    <div class="hr-search-main" style="display: block;">
                                        <form enctype="multipart/form-data" method="post" id="header_form">

                                            <?php
                                            $pageContent = json_decode($page_data['content'], true);
                                            ?>

                                            <div class="hr-search-main" style="display: block;">
                                                <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="heading" id="heading" value="<?php echo $pageContent['page']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Sub Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="sub_heading" id="sub_heading" value="<?php echo $pageContent['page']['subHeading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button 1 title</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="btn1_text" id="btn1_text" value="<?php echo $pageContent['page']['btn1Text']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button 1 slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="btn1_slug" id="btn1_slug" value="<?php echo $pageContent['page']['btn1Slug']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button 2 title</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="btn2_text" id="btn2_text" value="<?php echo $pageContent['page']['btn2Text']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button 1 slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="btn2_slug" id="btn2_slug" value="<?php echo $pageContent['page']['btn2Slug']; ?>" />
                                                        </div>
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
                heading: {
                    required: true
                },
                sub_heading: {
                    required: true
                },
                btn1_text: {
                    required: true
                },
                btn1_slug: {
                    required: true
                },
                btn2_text: {
                    required: true
                },
                btn2_slug: {
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

        pageData.page['heading'] = $("#heading").val();
        pageData.page['subHeading'] = $("#sub_heading").val();
        pageData.page['btn1Slug'] = $("#btn1_slug").val();
        pageData.page['btn1Text'] = $("#btn1_text").val();
        pageData.page['btn2Slug'] = $("#btn2_slug").val();
        pageData.page['btn2Text'] = $("#btn2_text").val();

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