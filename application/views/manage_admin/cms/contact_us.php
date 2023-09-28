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
                                            <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Contact Us</h1>
                                        </div>
                                        <?php
                                        $pageContent = json_decode($page_data['content'], true);
                                        ?>

                                        <div class="hr-search-main" style="display: block;">
                                            <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Contact Us Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="contactus_slug" id="contactus_slug" value="<?php echo $pageContent['page']['contactUs']['slug']; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-6">

                                                    <div class="hr-search-main" style="display: block;">
                                                        <label> Sales Support</label>
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Heading</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="sales_heading" id="sales_heading" value="<?php echo $pageContent['page']['salesHeading']['text']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Contact Number</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="sales_number" id="sales_number" value="<?php echo $pageContent['page']['salesNumber']['text']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Email</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="sales_email" id="sales_email" value="<?php echo $pageContent['page']['salesEmail']['text']; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="hr-search-main" style="display: block;">
                                                        <label> Technical Support</label>
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Heading</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="technical_heading" id="technical_heading" value="<?php echo $pageContent['page']['technicalHeading']['text']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Contact Number</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="technical_number" id="technical_number" value="<?php echo $pageContent['page']['technicalNumber']['text']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Email</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="technical_email" id="technical_email" value="<?php echo $pageContent['page']['technicalEmail']['text']; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12">

                                                    <div class="hr-search-main" style="display: block;">
                                                        <label> Form Headings</label>
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Heading 1</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="form_heading1" id="form_heading1" value="<?php echo $pageContent['page']['formHeading1']['text']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Heading 2</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="form_heading2" id="form_heading2" value="<?php echo $pageContent['page']['formHeading2']['text']; ?>" />
                                                            </div>
                                                        </div>
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
                contactus_slug: {
                    required: true
                },
                sales_heading: {
                    required: true
                },
                sales_number: {
                    required: true
                },
                sales_email: {
                    required: true
                },
                technical_heading: {
                    required: true
                },
                technical_number: {
                    required: true
                },
                technical_email: {
                    required: true
                },
                form_heading1: {
                    required: true
                },
                form_heading2: {
                    required: true
                }




            },
            messages: {
                aboutus_slug: {
                    required: 'Slug is required!'
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
        pageData.page['contactUs'] = {
            slug: $("#contactus_slug").val(),
        };
        pageData.page['salesHeading'] = {
            text: $("#sales_heading").val(),
        };
        pageData.page['salesNumber'] = {
            text: $("#sales_number").val(),
        };
        pageData.page['salesEmail'] = {
            text: $('#sales_email').val(),
        };
        pageData.page['technicalHeading'] = {
            text: $("#technical_heading").val(),
        };
        pageData.page['technicalNumber'] = {
            text: $("#technical_number").val(),
        };
        pageData.page['technicalEmail'] = {
            text: $("#technical_email").val(),
        };
        pageData.page['formHeading1'] = {
            text: $("#form_heading1").val(),
        };
        pageData.page['formHeading2'] = {
            text: $("#form_heading2").val(),
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