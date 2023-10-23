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
                                            <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit About Us</h1>
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
                                            </div>

                                            <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />

                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <label>About Us Slug</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="aboutus_slug" id="aboutus_slug" value="<?php echo $pageContent['page']['aboutUs']['slug']; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <label>Main Heading</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="main_heading_text" id="main_heading_text" value="<?php echo $pageContent['page']['mainHeading']['text']; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="field-row">
                                                    <label>Text Under Main Heading</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="main_heading_detail_text" id="main_heading_detail_text" value="<?php echo $pageContent['page']['mainHeadingDetail']['text']; ?>" />
                                                </div>
                                            </div>

                                            <div class="col-xs-12"><br>
                                                <label>Text Under Main Heading Picture</label><b class="text-danger"> *</b>
                                                <textarea class="invoice-fields" name="main_heading_picture_text" id="main_heading_picture_text" rows="8" cols="60"><?php echo $pageContent['page']['mainHeadingPicture']['text']; ?></textarea>
                                            </div>


                                        </div>


                                        <div class="hr-box" style="margin: 15px 0 0;">

                                            <div class="hr-box-header bg-header-green">
                                            </div>

                                            <div class="col-xs-12"><br>
                                                <label>An Award-Winning Solution</label><b class="text-danger"> *</b>
                                                <textarea class="invoice-fields" name="award_winning_text" id="award_winning_text" rows="8" cols="60"><?php echo $pageContent['page']['awardWinning']['text']; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="hr-box" style="margin: 15px 0 0;">

                                            <div class="hr-box-header bg-header-green">
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <label>Our Leadership Team</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="leadership_team" id="leadership_team" value="<?php echo $pageContent['page']['leaderShip']['text']; ?>" />
                                                </div>
                                            </div>

                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                </div>

                                            </div>

                                            <div class="col-xs-6">
                                                <label>Robert Hollenshead </label><b class="text-danger"> *</b>
                                                <textarea class="invoice-fields" name="leadership_left1_text" id="leadership_left1_text" rows="8" cols="60"><?php echo $pageContent['page']['leaderShipLeft1']['text']; ?></textarea>

                                            </div>

                                            <div class="col-xs-6">
                                                <div class="col-xs-12">
                                                    <label>Steven Warner</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="leadership_right1_text" id="leadership_right1_text" rows="8" cols="60"><?php echo $pageContent['page']['leaderShipRight1']['text']; ?></textarea>

                                                </div>

                                                <div class="col-xs-12"><br>
                                                    <label>E J Shelby</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="leadership_right2_text" id="leadership_right2_text" rows="8" cols="60"><?php echo $pageContent['page']['leaderShipRight2']['text']; ?></textarea>

                                                </div>

                                            </div>

                                        </div>


                                    </div>

                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-12 text-right"><br>
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
                aboutus_slug: {
                    required: true
                },
                main_heading_text: {
                    required: true
                },
                main_heading_detail_text: {
                    required: true
                },
                main_heading_picture_text: {
                    required: true
                },
                award_winning_text: {
                    required: true
                },
                leadership_team: {
                    required: true
                },
                leadership_left1_text: {
                    required: true
                },
                leadership_right1_text: {
                    required: true
                },
                leadership_right2_text: {
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
        pageData.page['aboutUs'] = {
            slug: $("#aboutus_slug").val(),

        };
        pageData.page['mainHeading'] = {
            text: $("#main_heading_text").val(),
        };
        pageData.page['mainHeadingDetail'] = {
            text: $("#main_heading_detail_text").val(),
        };
        pageData.page['mainHeadingPicture'] = {
            text: $("#main_heading_picture_text").val(),
        };
        pageData.page['awardWinning'] = {
            text: $("#main_heading_picture_text").val(),
        };
        pageData.page['leaderShip'] = {
            text: $("#leadership_team").val(),
        };
        pageData.page['leaderShipLeft1'] = {
            text: $("#leadership_left1_text").val(),
        };
        pageData.page['leaderShipRight1'] = {
            text: $("#leadership_right1_text").val(),        };
        pageData.page['leaderShipRight2'] = {
            text: $("#leadership_right2_text").val(),
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