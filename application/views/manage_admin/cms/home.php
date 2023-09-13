<script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Home</h1>
                                    </div>
                                    <div class="hr-search-main" style="display: block;">
                                        <form enctype="multipart/form-data" method="post" id="header_form">

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label> Slider</label>
                                                    <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />

                                                </div>
                                            </div>

                                            <div class="hr-search-main" style="display: block;">
                                                <?php
                                                $pageContent = json_decode($page_data['content'], true);
                                                //  _e($pageContent ,true);
                                                ?>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Slider1</label>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Main Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="slider1_heading" id="slider1_heading" value="<?php echo $pageContent['page']['slider']['slider1']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Detail</label><b class="text-danger"> *</b>
                                                            <textarea class="invoice-fields" name="slider1_heading_detail" id="slider1_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['slider']['slider1']['headingDetail']; ?></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="slider1_btn_text" id="slider1_btn_text" value="<?php echo $pageContent['page']['slider']['slider1']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="slider1_btn_slug" id="slider1_btn_slug" value="<?php echo $pageContent['page']['slider']['slider1']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>

                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Slider2</label>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Main Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="slider2_heading" id="slider2_heading" value="<?php echo $pageContent['page']['slider']['slider2']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Detail</label><b class="text-danger"> *</b>
                                                            <textarea class="invoice-fields" name="slider2_heading_detail" id="slider2_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['slider']['slider2']['headingDetail']; ?></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="slider2_btn_text" id="slider2_btn_text" value="<?php echo $pageContent['page']['slider']['slider2']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="slider2_btn_slug" id="slider2_btn_slug" value="<?php echo $pageContent['page']['slider']['slider2']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>

                                                </div>
                                                <br>

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Slider3</label>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Main Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="slider3_heading" id="slider3_heading" value="<?php echo $pageContent['page']['slider']['slider3']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Detail</label><b class="text-danger"> *</b>
                                                            <textarea class="invoice-fields" name="slider3_heading_detail" id="slider3_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['slider']['slider3']['headingDetail']; ?></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="slider3_btn_text" id="slider3_btn_text" value="<?php echo $pageContent['page']['slider']['slider3']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="slider3_btn_slug" id="slider3_btn_slug" value="<?php echo $pageContent['page']['slider']['slider3']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                            <label>WHAT WE OFFER?</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section1_heading" id="section1_heading" value="<?php echo $pageContent['page']['sections']['section1']['heading']; ?>" />
                                                        </div>
                                                    </div>
                                                  
                                                    <div class="col-xs-12">
                                                            <label>Details</label><b class="text-danger"> *</b>
                                                            <textarea class="ckeditor" name="section1_details" id="section1_details" rows="8" cols="60"><?php echo $pageContent['page']['mainHeadingPicture']['text']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section1_button_text" id="section1_button_text" value="<?php echo $pageContent['page']['aboutUs']['slug']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section1_button_slug" id="section1_button_slug" value="<?php echo $pageContent['page']['aboutUs']['slug']; ?>" />
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
                slider1_heading: {
                    required: true
                },
                slider1_heading_detail: {
                    required: true
                },
                slider1_btn_text: {
                    required: true
                },
                slider1_btn_slug: {
                    required: true
                },
                slider2_heading: {
                    required: true
                },
                slider2_heading_detail: {
                    required: true
                },
                slider2_btn_text: {
                    required: true
                },
                slider2_btn_slug: {
                    required: true
                },
                slider3_heading: {
                    required: true
                },
                slider3_heading_detail: {
                    required: true
                },
                slider3_btn_text: {
                    required: true
                },
                slider3_btn_slug: {
                    required: true
                },
                section1_heading: {
                    required: true
                },
                section1_details: {
                    required: true
                },
                section1_button_text: {
                    required: true
                },
                section1_button_slug: {
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

        pageData.page['slider'] = {
            slider1: {
                heading: $("#slider1_heading").val(),
                headingDetail: $("#slider1_heading_detail").val(),
                btnText: $("#section1_button_text").val(),
                btnSlug: $("#slider1_btn_slug").val()
            },
            slider2: {
                heading: $("#slider2_heading").val(),
                headingDetail: $("#slider2_heading_detail").val(),
                btnText: $("#slider2_btn_text").val(),
                btnSlug: $("#slider2_btn_slug").val()
            },
            slider3: {
                heading: $("#slider3_heading").val(),
                headingDetail: $("#slider3_heading_detail").val(),
                btnText: $("#slider3_btn_text").val(),
                btnSlug: $("#slider3_btn_slug").val()
            }
        };

        pageData.page['sections'] = {
            section1: {
                heading: $("#section1_heading").val(),
                headingDetail: $("#section1_details").val(),
                btnText: $("#section1_button_text").val(),
                btnSlug: $("#section1_button_slug").val()
            }
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