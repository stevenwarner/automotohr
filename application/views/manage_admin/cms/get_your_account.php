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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Get Your Account</h1>
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
                                                    <h1 class="hr-registered pull-left">Banner</h1>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="banner_heading" id="banner_heading" value="<?php echo $pageContent['page']['sections']['banner']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="banner_btn_text" id="banner_btn_text" value="<?php echo $pageContent['page']['sections']['banner']['btnText']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="banner_btn_slug" id="banner_btn_slug" value="<?php echo $pageContent['page']['sections']['banner']['btnSlug']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Call Us Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="banner_call_text" id="banner_call_text" value="<?php echo $pageContent['page']['sections']['banner']['calltext']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Call Number</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="banner_call_number" id="banner_call_number" value="<?php echo $pageContent['page']['sections']['banner']['callnumber']; ?>" />
                                                    </div>
                                                </div>

                                            </div>



                                            <div class="hr-box" style="margin: 15px 0 0;">

                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 1</h1>
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

                                                <div class="col-xs-12"><br>
                                                    <label>Detail</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section1_heading_detail" id="section1_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section1']['headingDetail']; ?></textarea>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 1</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_bullet1" id="section1_bullet1" value="<?php echo $pageContent['page']['sections']['section1']['bullet1']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 2</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_bullet2" id="section1_bullet2" value="<?php echo $pageContent['page']['sections']['section1']['bullet1']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 3</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_bullet3" id="section1_bullet3" value="<?php echo $pageContent['page']['sections']['section1']['bullet3']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 4</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_bullet4" id="section1_bullet4" value="<?php echo $pageContent['page']['sections']['section1']['bullet4']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 5</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_bullet5" id="section1_bullet5" value="<?php echo $pageContent['page']['sections']['section1']['bullet5']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 6</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_bullet6" id="section1_bullet6" value="<?php echo $pageContent['page']['sections']['section1']['bullet6']; ?>" />
                                                    </div>
                                                </div>


                                                <div class="col-xs-12"><br>
                                                    <label>Banner Text</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section1_banner_text" id="section1_banner_text" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section1']['bannertext']; ?></textarea>
                                                </div>


                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_banner_btn_text" id="section1_banner_btn_text" value="<?php echo $pageContent['page']['sections']['section1']['bannerbtnText']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section1_banner_btn_slug" id="section1_banner_btn_slug" value="<?php echo $pageContent['page']['sections']['section1']['bannerbtnSlug']; ?>" />
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 2</h1>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Main Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_main_heading" id="section2_main_heading" value="<?php echo $pageContent['page']['sections']['section2']['mainheading']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_heading" id="section2_heading" value="<?php echo $pageContent['page']['sections']['section2']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12"><br>
                                                    <label>Detail</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section2_heading_detail" id="section2_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section2']['headingDetail']; ?></textarea>
                                                </div>


                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 1</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_bullet1" id="section2_bullet1" value="<?php echo $pageContent['page']['sections']['section2']['bullet1']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 2</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_bullet2" id="section2_bullet2" value="<?php echo $pageContent['page']['sections']['section2']['bullet1']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 3</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_bullet3" id="section2_bullet3" value="<?php echo $pageContent['page']['sections']['section2']['bullet3']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 4</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_bullet4" id="section2_bullet4" value="<?php echo $pageContent['page']['sections']['section2']['bullet4']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Bullet 5</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_bullet5" id="section2_bullet5" value="<?php echo $pageContent['page']['sections']['section2']['bullet5']; ?>" />
                                                    </div>
                                                </div>
                                             

                                                <div class="col-xs-12"><br>
                                                    <label>Banner Text</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section2_banner_text" id="section2_banner_text" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section2']['bannertext']; ?></textarea>
                                                </div>


                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_banner_btn_text" id="section2_banner_btn_text" value="<?php echo $pageContent['page']['sections']['section2']['bannerbtnText']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_banner_btn_slug" id="section2_banner_btn_slug" value="<?php echo $pageContent['page']['sections']['section2']['bannerbtnSlug']; ?>" />
                                                    </div>
                                                </div>



                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 3</h1>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Youtube Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_youtube_heading" id="section3_youtube_heading" value="<?php echo $pageContent['page']['sections']['section3']['youtubeheading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_heading" id="section3_heading" value="<?php echo $pageContent['page']['sections']['section3']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_btn_text" id="section3_btn_text" value="<?php echo $pageContent['page']['sections']['section3']['btnText']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_btn_slug" id="section3_btn_slug" value="<?php echo $pageContent['page']['sections']['section3']['btnSlug']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Phone Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_heading_phone" id="section3_heading_phone" value="<?php echo $pageContent['page']['sections']['section3']['phoneHeading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Phone Number</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_phone_number" id="section3_phone_number" value="<?php echo $pageContent['page']['sections']['section3']['phoneNumber']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Email</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_email" id="section3_email" value="<?php echo $pageContent['page']['sections']['section3']['email']; ?>" />
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
                },
                banner_heading: {
                    required: true
                },
                banner_btn_text: {
                    required: true
                },
                banner_btn_slug: {
                    required: true
                },
                banner_call_text: {
                    required: true
                },
                banner_call_number: {
                    required: true
                },
                section1_main_heading: {
                    required: true
                },
                section1_heading_detail: {
                    required: true
                },
                section1_bullet1: {
                    required: true
                },
                section1_bullet2: {
                    required: true
                },
                section1_bullet3: {
                    required: true
                },
                section1_bullet4: {
                    required: true
                },
                section1_bullet5: {
                    required: true
                },
                section1_bullet6: {
                    required: true
                },
                section1_banner_text: {
                    required: true
                },
                section1_banner_btn_text: {
                    required: true
                },
                section1_banner_btn_slug: {
                    required: true
                },
                section2_main_heading: {
                    required: true
                },
                section2_heading: {
                    required: true
                },
                section2_heading_detail: {
                    required: true
                },
                section2_bullet1: {
                    required: true
                },
                section2_bullet2: {
                    required: true
                },
                section2_bullet3: {
                    required: true
                },
                section2_bullet4: {
                    required: true
                },
                section2_bullet5: {
                    required: true
                },
                section2_banner_text: {
                    required: true
                },
                section2_banner_btn_text: {
                    required: true
                },
                section2_banner_btn_slug: {
                    required: true
                },
                section3_youtube_heading: {
                    required: true
                },
                section3_heading: {
                    required: true
                },
                section3_btn_text: {
                    required: true
                },
                section3_btn_slug: {
                    required: true
                },
                section3_heading_phone: {
                    required: true
                },
                section3_phone_number: {
                    required: true
                },
                section3_email: {
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
                mainheading: $("#section1_main_heading").val(),
                heading: $("#section1_heading").val(),
                headingDetail: $("#section1_heading_detail").val(),
                bullet1: $("#section1_bullet1").val(),
                bullet2: $("#section1_bullet2").val(),
                bullet3: $("#section1_bullet3").val(),
                bullet4: $("#section1_bullet4").val(),
                bullet5: $("#section1_bullet5").val(),
                bullet6: $("#section1_bullet6").val(),
                bannertext: $("#section1_banner_text").val(),
                bannerbtnText: $("#section1_banner_btn_text").val(),
                bannerbtnSlug: $("#section1_banner_btn_slug").val(),

            },
            banner: {
                heading: $("#banner_heading").val(),
                btnText: $("#banner_btn_text").val(),
                btnSlug: $("#banner_btn_slug").val(),
                calltext: $("#banner_call_text").val(),
                callnumber: $("#banner_call_number").val()
            },
            section2: {
                mainheading: $("#section2_main_heading").val(),
                heading: $("#section2_heading").val(),
                headingDetail: $("#section2_heading_detail").val(),
                bullet1: $("#section2_bullet1").val(),
                bullet2: $("#section2_bullet2").val(),
                bullet3: $("#section2_bullet3").val(),
                bullet4: $("#section2_bullet4").val(),
                bullet5: $("#section2_bullet5").val(),
                bullet6: $("#section2_bullet6").val(),
                bannertext: $("#section2_banner_text").val(),
                bannerbtnText: $("#section2_banner_btn_text").val(),
                bannerbtnSlug: $("#section2_banner_btn_slug").val(),

            },
            section3: {
                youtubeheading: $("#section3_youtube_heading").val(),
                heading: $("#section3_heading").val(),
                btnText: $("#section3_btn_text").val(),
                btnSlug: $("#section3_btn_slug").val(),
                phoneHeading: $("#section3_heading_phone").val(),
                phoneNumber: $("#section3_phone_number").val(),
                email: $("#section3_email").val()
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