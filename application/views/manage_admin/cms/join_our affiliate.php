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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Join Our Affiliate</h1>
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
                                                    <h1 class="hr-registered pull-left">Section 1</h1>
                                                </div>
                                                <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />

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

                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">

                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 2</h1>
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

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_btn_text" id="section2_btn_text" value="<?php echo $pageContent['page']['sections']['section2']['btnText']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Button Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section2_btn_slug" id="section2_btn_slug" value="<?php echo $pageContent['page']['sections']['section2']['btnSlug']; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">

                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 3</h1>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_heading" id="section3_heading" value="<?php echo $pageContent['page']['sections']['section3']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_heading1" id="section3_heading1" value="<?php echo $pageContent['page']['sections']['section3']['heading1']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section3_heading2" id="section3_heading2" value="<?php echo $pageContent['page']['sections']['section3']['heading2']; ?>" />
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


                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 4</h1>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section4_heading" id="section4_heading" value="<?php echo $pageContent['page']['sections']['section4']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading 1</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section4_heading1" id="section4_heading1" value="<?php echo $pageContent['page']['sections']['section4']['heading1']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 1</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section4_tick1" id="section4_tick1" value="<?php echo $pageContent['page']['sections']['section4']['tick1']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 2</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section4_tick2" id="section4_tick2" value="<?php echo $pageContent['page']['sections']['section4']['tick2']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 3</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section4_tick3" id="section4_tick3" value="<?php echo $pageContent['page']['sections']['section4']['tick3']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 4</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section4_tick4" id="section4_tick4" value="<?php echo $pageContent['page']['sections']['section4']['tick4']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 5</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section4_tick5" id="section4_tick5" value="<?php echo $pageContent['page']['sections']['section4']['tick5']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 6</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section4_tick6" id="section4_tick6" value="<?php echo $pageContent['page']['sections']['section4']['tick6']; ?>" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 5</h1>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section5_heading" id="section5_heading" value="<?php echo $pageContent['page']['sections']['section5']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading 1</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section5_heading1" id="section5_heading1" value="<?php echo $pageContent['page']['sections']['section5']['heading1']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 1</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section5_tick1" id="section5_tick1" value="<?php echo $pageContent['page']['sections']['section5']['tick1']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 2</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section5_tick2" id="section5_tick2" value="<?php echo $pageContent['page']['sections']['section5']['tick2']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 3</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section5_tick3" id="section5_tick3" value="<?php echo $pageContent['page']['sections']['section5']['tick3']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 4</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section5_tick4" id="section5_tick4" value="<?php echo $pageContent['page']['sections']['section5']['tick4']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 5</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section5_tick5" id="section5_tick5" value="<?php echo $pageContent['page']['sections']['section5']['tick5']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 6</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section5_tick6" id="section5_tick6" value="<?php echo $pageContent['page']['sections']['section5']['tick6']; ?>" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 6</h1>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section6_heading" id="section6_heading" value="<?php echo $pageContent['page']['sections']['section6']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12"><br>
                                                    <label>Heading 1</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="section6_heading1" id="section6_heading1" value="<?php echo $pageContent['page']['sections']['section6']['heading1']; ?>" />
                                                </div>
                                                <div class="col-xs-12"><br>
                                                    <label>Heading 1 Detail</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section6_heading1_detail" id="section6_heading1_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section6']['heading1Detail']; ?></textarea>
                                                </div>


                                                <div class="col-xs-12"><br>
                                                    <label>Heading 2</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="section6_heading2" id="section6_heading2" value="<?php echo $pageContent['page']['sections']['section6']['heading2']; ?>" />
                                                </div>
                                                <div class="col-xs-12"><br>
                                                    <label>Heading 2 Detail</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section6_heading2_detail" id="section6_heading2_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section6']['heading2Detail']; ?></textarea>
                                                </div>

                                            </div>


                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 7</h1>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section7_heading" id="section7_heading" value="<?php echo $pageContent['page']['sections']['section7']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12"><br>
                                                    <label>Detail</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section7_heading_detail" id="section7_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section7']['headingDetail']; ?></textarea>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading 1</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section7_heading1" id="section7_heading1" value="<?php echo $pageContent['page']['sections']['section7']['heading1']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 1</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section7_tick1" id="section7_tick1" value="<?php echo $pageContent['page']['sections']['section7']['tick1']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 2</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section7_tick2" id="section7_tick2" value="<?php echo $pageContent['page']['sections']['section7']['tick2']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 3</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section7_tick3" id="section7_tick3" value="<?php echo $pageContent['page']['sections']['section7']['tick3']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 4</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section7_tick4" id="section7_tick4" value="<?php echo $pageContent['page']['sections']['section7']['tick4']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 5</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section7_tick5" id="section7_tick5" value="<?php echo $pageContent['page']['sections']['section7']['tick5']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Tick 6</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section7_tick6" id="section7_tick6" value="<?php echo $pageContent['page']['sections']['section7']['tick6']; ?>" />
                                                    </div>
                                                </div>


                                                <div class="col-xs-12"><br>
                                                    <label>Detail</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section7_heading_detail1" id="section7_heading_detail1" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section7']['headingDetail1']; ?></textarea>
                                                </div>


                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 8</h1>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section8_heading" id="section8_heading" value="<?php echo $pageContent['page']['sections']['section8']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading 1</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section8_heading1" id="section8_heading1" value="<?php echo $pageContent['page']['sections']['section8']['heading1']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12"><br>
                                                    <label>Detail</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section8_heading1_detail" id="section8_heading1_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section8']['heading1Detail']; ?></textarea>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading 2</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section8_heading2" id="section8_heading2" value="<?php echo $pageContent['page']['sections']['section8']['heading2']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12"><br>
                                                    <label>Detail</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section8_heading2_detail" id="section8_heading2_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section8']['heading2Detail']; ?></textarea>
                                                </div>

                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 9</h1>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section9_heading" id="section9_heading" value="<?php echo $pageContent['page']['sections']['section9']['heading']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12"><br>
                                                    <label>Detail</label><b class="text-danger"> *</b>
                                                    <textarea class="invoice-fields" name="section9_heading_detail" id="section9_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section9']['headingDetail']; ?></textarea>
                                                </div>

                                                <div class="col-xs-4">
                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_heading1" id="section9_heading1" value="<?php echo $pageContent['page']['sections']['section9']['heading1']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12"><br>
                                                        <label>Detail</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="section9_heading1_detail" id="section9_heading1_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section9']['heading1Detail']; ?></textarea>
                                                    </div>

                                                </div>
                                                <div class="col-xs-4">
                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_heading2" id="section9_heading2" value="<?php echo $pageContent['page']['sections']['section9']['heading2']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12"><br>
                                                        <label>Detail</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="section9_heading2_detail" id="section9_heading2_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section9']['heading2Detail']; ?></textarea>
                                                    </div>

                                                </div>
                                                <div class="col-xs-4">
                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_heading3" id="section9_heading3" value="<?php echo $pageContent['page']['sections']['section9']['heading3']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12"><br>
                                                        <label>Detail</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="section9_heading3_detail" id="section9_heading3_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section9']['heading3Detail']; ?></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Section 10</h1>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <label>Heading</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="section10_heading" id="section10_heading" value="<?php echo $pageContent['page']['sections']['section10']['heading']; ?>" />
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
                section1_heading: {
                    required: true
                },
                section1_heading_detail: {
                    required: true
                },
                section2_heading: {
                    required: true
                },
                section2_heading_detail: {
                    required: true
                },
                section2_btn_text: {
                    required: true
                },
                section2_btn_slug: {
                    required: true
                },
                section3_heading: {
                    required: true
                },
                section3_heading1: {
                    required: true
                },
                section3_heading2: {
                    required: true
                },
                section3_btn_text: {
                    required: true
                },
                section3_btn_slug: {
                    required: true
                },
                section4_heading: {
                    required: true
                },
                section4_heading1: {
                    required: true
                },
                section4_tick1: {
                    required: true
                },
                section4_tick2: {
                    required: true
                },
                section4_tick3: {
                    required: true
                },
                section4_tick4: {
                    required: true
                },
                section4_tick5: {
                    required: true
                },
                section4_tick6: {
                    required: true
                },
                section5_heading: {
                    required: true
                },
                section5_heading1: {
                    required: true
                },
                section5_tick1: {
                    required: true
                },
                section5_tick2: {
                    required: true
                },
                section5_tick3: {
                    required: true
                },
                section5_tick4: {
                    required: true
                },
                section5_tick5: {
                    required: true
                },
                section5_tick6: {
                    required: true
                },
                section6_heading: {
                    required: true
                },
                section6_heading1: {
                    required: true
                },
                section6_heading1_detail: {
                    required: true
                },
                section6_heading2: {
                    required: true
                },
                section6_heading2_detail: {
                    required: true
                },
                section7_heading: {
                    required: true
                },
                section7_heading_detail: {
                    required: true
                },
                section7_heading1: {
                    required: true
                },
                section7_heading_detail1: {
                    required: true
                },
                section7_tick1: {
                    required: true
                },
                section7_tick2: {
                    required: true
                },
                section7_tick3: {
                    required: true
                },
                section7_tick4: {
                    required: true
                },
                section7_tick5: {
                    required: true
                },
                section7_tick6: {
                    required: true
                },
                section8_heading: {
                    required: true
                },
                section8_heading1: {
                    required: true
                },
                section8_heading1_detail: {
                    required: true
                },
                section8_heading2: {
                    required: true
                },
                section8_heading2_detail: {
                    required: true
                },
                section9_heading: {
                    required: true
                },
                section9_heading_detail: {
                    required: true
                },
                section9_heading1: {
                    required: true
                },
                section9_heading1_detail: {
                    required: true
                },
                section9_heading2: {
                    required: true
                },
                section9_heading2_detail: {
                    required: true
                },
                section9_heading3: {
                    required: true
                },
                section9_heading3_detail: {
                    required: true
                },
                section10_heading: {
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
                heading: $("#section1_heading").val(),
                headingDetail: $("#section1_heading_detail").val(),
            },
            section2: {
                heading: $("#section2_heading").val(),
                headingDetail: $("#section2_heading_detail").val(),
                btnText: $("#section2_btn_text").val(),
                btnSlug: $("#section2_btn_slug").val(),
            },
            section3: {
                heading: $("#section3_heading").val(),
                heading1: $("#section3_heading1").val(),
                heading2: $("#section3_heading2").val(),
                btnText: $("#section3_btn_text").val(),
                btnSlug: $("#section3_btn_slug").val(),
            },
            section4: {
                heading: $("#section4_heading").val(),
                heading1: $("#section4_heading1").val(),
                tick1: $("#section4_tick1").val(),
                tick2: $("#section4_tick2").val(),
                tick3: $("#section4_tick3").val(),
                tick4: $("#section4_tick4").val(),
                tick5: $("#section4_tick5").val(),
                tick6: $("#section4_tick6").val(),
            },
            section5: {
                heading: $("#section5_heading").val(),
                heading1: $("#section5_heading1").val(),
                tick1: $("#section5_tick1").val(),
                tick2: $("#section5_tick2").val(),
                tick3: $("#section5_tick3").val(),
                tick4: $("#section5_tick4").val(),
                tick5: $("#section5_tick5").val(),
                tick6: $("#section5_tick6").val(),
            },
            section6: {
                heading: $("#section6_heading").val(),
                heading1: $("#section6_heading1").val(),
                heading1Detail: $("#section6_heading1_detail").val(),
                heading2: $("#section6_heading2").val(),
                heading2Detail: $("#section6_heading2_detail").val(),
            },
            section7: {
                heading: $("#section7_heading").val(),
                headingDetail: $("#section7_heading_detail").val(),
                heading1: $("#section7_heading1").val(),
                headingDetail1: $("#section7_heading_detail1").val(),
                tick1: $("#section7_tick1").val(),
                tick2: $("#section7_tick2").val(),
                tick3: $("#section7_tick3").val(),
                tick4: $("#section7_tick4").val(),
                tick5: $("#section7_tick5").val(),
                tick6: $("#section7_tick6").val(),
            },
            section8: {
                heading: $("#section8_heading").val(),
                heading1: $("#section8_heading1").val(),
                heading1Detail: $("#section8_heading1_detail").val(),
                heading2: $("#section8_heading2").val(),
                heading2Detail: $("#section8_heading2_detail").val(),
            },
            section9: {
                heading: $("#section9_heading").val(),
                headingDetail: $("#section9_heading_detail").val(),
                heading1: $("#section9_heading1").val(),
                heading1Detail: $("#section9_heading1_detail").val(),
                heading2: $("#section9_heading2").val(),
                heading2Detail: $("#section9_heading2_detail").val(),
                heading3: $("#section9_heading3").val(),
                heading3Detail: $("#section9_heading3_detail").val(),
            },
            section10: {
                heading: $("#section10_heading").val(),
            }

        };

        pageData.page['meta'] = {
            title: $("#meta_title").val(),
            keyword: $("#meta_key_word").val(),
            description: $("#meta_description").val()
        };


        //                headingDetail: CKEDITOR.instances['section2_heading_detail'].getData().trim(),


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