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
                                                        <textarea class="ckeditor" name="section1_details" id="section1_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section1']['headingDetail']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section1_button_text" id="section1_button_text" value="<?php echo $pageContent['page']['sections']['section1']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section1_button_slug" id="section1_button_slug" value="<?php echo $pageContent['page']['sections']['section1']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>


                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>WHY SHOULD YOU SWITCH TO AUTOMOTOHR?</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section2_heading" id="section2_heading" value="<?php echo $pageContent['page']['sections']['section2']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>ONBOARDING AND HIRING</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section3_heading" id="section3_heading" value="<?php echo $pageContent['page']['sections']['section3']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <label>Details</label><b class="text-danger"> *</b>
                                                        <textarea class="ckeditor" name="section3_details" id="section3_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section3']['headingDetail']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section3_button_text" id="section3_button_text" value="<?php echo $pageContent['page']['sections']['section3']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section3_button_slug" id="section3_button_slug" value="<?php echo $pageContent['page']['sections']['section3']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>TIME, PAYROLL, AND BENEFITS</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section4_heading" id="section4_heading" value="<?php echo $pageContent['page']['sections']['section4']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <label>Details</label><b class="text-danger"> *</b>
                                                        <textarea class="ckeditor" name="section4_details" id="section4_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section4']['headingDetail']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section4_button_text" id="section4_button_text" value="<?php echo $pageContent['page']['sections']['section4']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section4_button_slug" id="section4_button_slug" value="<?php echo $pageContent['page']['sections']['section4']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>EMPLOYEE EXPERIENCE & PERFORMANCE</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section5_heading" id="section5_heading" value="<?php echo $pageContent['page']['sections']['section5']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <label>Details</label><b class="text-danger"> *</b>
                                                        <textarea class="ckeditor" name="section5_details" id="section5_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section5']['headingDetail']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section5_button_text" id="section5_button_text" value="<?php echo $pageContent['page']['sections']['section5']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section5_button_slug" id="section5_button_slug" value="<?php echo $pageContent['page']['sections']['section5']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>VACATION & TIME OFF MONITORING</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section6_heading" id="section6_heading" value="<?php echo $pageContent['page']['sections']['section6']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <label>Details</label><b class="text-danger"> *</b>
                                                        <textarea class="ckeditor" name="section6_details" id="section6_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section6']['headingDetail']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section6_button_text" id="section6_button_text" value="<?php echo $pageContent['page']['sections']['section6']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section6_button_slug" id="section6_button_slug" value="<?php echo $pageContent['page']['sections']['section6']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>TRANSFORM, EMPOWER & SUCCEED</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section7_heading" id="section7_heading" value="<?php echo $pageContent['page']['sections']['section7']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <label>Details</label><b class="text-danger"> *</b>
                                                        <textarea class="ckeditor" name="section7_details" id="section7_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section7']['headingDetail']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section7_button_text" id="section7_button_text" value="<?php echo $pageContent['page']['sections']['section7']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section7_button_slug" id="section7_button_slug" value="<?php echo $pageContent['page']['sections']['section7']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>FULL COMPANY CAREER WEBSITE</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section8_heading" id="section8_heading" value="<?php echo $pageContent['page']['sections']['section8']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <label>Details</label><b class="text-danger"> *</b>
                                                        <textarea class="ckeditor" name="section8_details" id="section8_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section8']['headingDetail']; ?></textarea>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section8_button_text" id="section8_button_text" value="<?php echo $pageContent['page']['sections']['section8']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section8_button_slug" id="section8_button_slug" value="<?php echo $pageContent['page']['sections']['section8']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>OUR PROCESS</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_heading" id="section9_heading" value="<?php echo $pageContent['page']['sections']['section9']['heading']; ?>" />
                                                        </div>
                                                    </div>


                                                    <div class="col-xs-3">
                                                        <div class="field-row">
                                                            <label>Heading 1</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_heading_sub1" id="section9_heading_sub1" value="<?php echo $pageContent['page']['sections']['section9']['headingSub1']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="field-row">
                                                            <label>Heading 2</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_heading_sub2" id="section9_heading_sub2" value="<?php echo $pageContent['page']['sections']['section9']['headingSub2']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="field-row">
                                                            <label>Heading 3</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_heading_sub3" id="section9_heading_sub3" value="<?php echo $pageContent['page']['sections']['section9']['headingSub3']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="field-row">
                                                            <label>Heading 4</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_heading_sub4" id="section9_heading_sub4" value="<?php echo $pageContent['page']['sections']['section9']['headingSub4']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Text</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_btn_text" id="section9_btn_text" value="<?php echo $pageContent['page']['sections']['section9']['btnText']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="field-row">
                                                            <label>Button Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section9_btn_slug" id="section9_btn_slug" value="<?php echo $pageContent['page']['sections']['section9']['btnSlug']; ?>" />
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="hr-search-main" style="display: block;">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>INNOVATING HR</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section10_heading" id="section10_heading" value="<?php echo $pageContent['page']['sections']['section10']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="hr-search-main" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Our Mission</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section11_heading" id="section11_heading" value="<?php echo $pageContent['page']['sections']['section11']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <label>Details</label><b class="text-danger"> *</b>
                                                        <textarea class="ckeditor" name="section11_details" id="section11_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section11']['headingDetail']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-search-main" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Our Vision</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section12_heading" id="section12_heading" value="<?php echo $pageContent['page']['sections']['section12']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <label>Details</label><b class="text-danger"> *</b>
                                                        <textarea class="ckeditor" name="section12_details" id="section12_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section12']['headingDetail']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="hr-search-main" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Notable Benefits</label>
                                                    </div>

                                                </div>


                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="hr-search-main" style="display: block;">
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="field-row">
                                                                        <label>01 Heading</label><b class="text-danger"> *</b>
                                                                        <input type="text" class="invoice-fields" name="section13_heading" id="section13_heading" value="<?php echo $pageContent['page']['sections']['section13']['heading']; ?>" />
                                                                    </div>
                                                                </div>

                                                                <div class="col-xs-12">
                                                                    <label>Details</label><b class="text-danger"> *</b>
                                                                    <textarea class="invoice-fields" name="section13_details" id="section13_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section13']['headingDetail']; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="hr-search-main" style="display: block;">
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="field-row">
                                                                        <label>02 Heading</label><b class="text-danger"> *</b>
                                                                        <input type="text" class="invoice-fields" name="section14_heading" id="section14_heading" value="<?php echo $pageContent['page']['sections']['section14']['heading']; ?>" />
                                                                    </div>
                                                                </div>

                                                                <div class="col-xs-12">
                                                                    <label>Details</label><b class="text-danger"> *</b>
                                                                    <textarea class="invoice-fields" name="section14_details" id="section14_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section14']['headingDetail']; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="hr-search-main" style="display: block;">
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="field-row">
                                                                        <label>03 Heading</label><b class="text-danger"> *</b>
                                                                        <input type="text" class="invoice-fields" name="section15_heading" id="section15_heading" value="<?php echo $pageContent['page']['sections']['section15']['heading']; ?>" />
                                                                    </div>
                                                                </div>

                                                                <div class="col-xs-12">
                                                                    <label>Details</label><b class="text-danger"> *</b>
                                                                    <textarea class="invoice-fields" name="section15_details" id="section15_details" rows="8" cols="60"><?php echo $pageContent['page']['sections']['section15']['headingDetail']; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>



                                                <div class="hr-search-main" style="display: block;">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <label>Form</label>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Heading 1</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="section16_heading" id="section16_heading" value="<?php echo $pageContent['page']['sections']['section16']['heading']; ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Heading 2</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="section16_heading2" id="section16_heading2" value="<?php echo $pageContent['page']['sections']['section16']['heading2']; ?>" />
                                                            </div>
                                                        </div>


                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>Button Text</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="section16_btn_text" id="section16_btn_text" value="<?php echo $pageContent['page']['sections']['section16']['btnText']; ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>Button Slug</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="section16_btn_slug" id="section16_btn_slug" value="<?php echo $pageContent['page']['sections']['section16']['btnSlug']; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="hr-search-main" style="display: block;">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <label>Form Bottom</label>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Heading</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="section17_heading" id="section17_heading" value="<?php echo $pageContent['page']['sections']['section17']['heading']; ?>" />
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>Button Text</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="section17_btn_text" id="section17_btn_text" value="<?php echo $pageContent['page']['sections']['section17']['btnText']; ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>Button Slug</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="section17_btn_slug" id="section17_btn_slug" value="<?php echo $pageContent['page']['sections']['section17']['btnSlug']; ?>" />
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
                },
                section2_heading: {
                    required: true
                },
                section3_heading: {
                    required: true
                },
                section3_details: {
                    required: true
                },
                section3_button_text: {
                    required: true
                },
                section3_button_slug: {
                    required: true
                },
                section4_heading: {
                    required: true
                },
                section4_details: {
                    required: true
                },
                section4_button_text: {
                    required: true
                },
                section4_button_slug: {
                    required: true
                },
                section5_heading: {
                    required: true
                },
                section5_details: {
                    required: true
                },
                section5_button_text: {
                    required: true
                },
                section5_button_slug: {
                    required: true
                },
                section6_heading: {
                    required: true
                },
                section6_details: {
                    required: true
                },
                section6_button_text: {
                    required: true
                },
                section6_button_slug: {
                    required: true
                },
                section7_heading: {
                    required: true
                },
                section7_details: {
                    required: true
                },
                section7_button_text: {
                    required: true
                },
                section7_button_slug: {
                    required: true
                },
                section8_heading: {
                    required: true
                },
                section8_details: {
                    required: true
                },
                section8_button_text: {
                    required: true
                },
                section8_button_slug: {
                    required: true
                },
                section9_heading: {
                    required: true
                },
                section9_heading_sub1: {
                    required: true
                },
                section9_heading_sub2: {
                    required: true
                },
                section9_heading_sub3: {
                    required: true
                },
                section9_heading_sub4: {
                    required: true
                },
                section9_btn_text: {
                    required: true
                },
                section9_btn_slug: {
                    required: true
                },
                section10_heading: {
                    required: true
                },
                section11_heading: {
                    required: true
                },
                section11_details: {
                    required: true
                },
                section12_heading: {
                    required: true
                },
                section12_details: {
                    required: true
                },
                section13_heading: {
                    required: true
                },
                section13_details: {
                    required: true
                },
                section14_heading: {
                    required: true
                },
                section14_details: {
                    required: true
                },
                section15_heading: {
                    required: true
                },
                section15_details: {
                    required: true
                },
                section16_heading: {
                    required: true
                },
                section16_heading2: {
                    required: true
                },
                section16_btn_text: {
                    required: true
                },
                section16_btn_slug: {
                    required: true
                },
                section17_heading: {
                    required: true
                },
                section17_btn_text: {
                    required: true
                },
                section17_btn_slug: {
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

        //
        pageData.page['sections'] = {
            section1: {
                heading: $("#section1_heading").val(),
                headingDetail: CKEDITOR.instances['section1_details'].getData().trim(),
                btnText: $("#section1_button_text").val(),
                btnSlug: $("#section1_button_slug").val()
            },
            section2: {
                heading: $("#section2_heading").val(),
            },
            section3: {
                heading: $("#section3_heading").val(),
                headingDetail: CKEDITOR.instances['section3_details'].getData().trim(),
                btnText: $("#section3_button_text").val(),
                btnSlug: $("#section3_button_slug").val()
            },
            section4: {
                heading: $("#section4_heading").val(),
                headingDetail: CKEDITOR.instances['section4_details'].getData().trim(),
                btnText: $("#section4_button_text").val(),
                btnSlug: $("#section4_button_slug").val()
            },
            section5: {
                heading: $("#section5_heading").val(),
                headingDetail: CKEDITOR.instances['section5_details'].getData().trim(),
                btnText: $("#section5_button_text").val(),
                btnSlug: $("#section5_button_slug").val()
            },
            section6: {
                heading: $("#section6_heading").val(),
                headingDetail: CKEDITOR.instances['section6_details'].getData().trim(),
                btnText: $("#section6_button_text").val(),
                btnSlug: $("#section6_button_slug").val()
            },
            section7: {
                heading: $("#section7_heading").val(),
                headingDetail: CKEDITOR.instances['section7_details'].getData().trim(),
                btnText: $("#section7_button_text").val(),
                btnSlug: $("#section7_button_slug").val()
            },
            section8: {
                heading: $("#section8_heading").val(),
                headingDetail: CKEDITOR.instances['section8_details'].getData().trim(),
                btnText: $("#section8_button_text").val(),
                btnSlug: $("#section8_button_slug").val()
            },
            section9: {
                heading: $("#section9_heading").val(),
                headingSub1: $("#section9_heading_sub1").val(),
                headingSub2: $("#section9_heading_sub2").val(),
                headingSub3: $("#section9_heading_sub3").val(),
                headingSub4: $("#section9_heading_sub4").val(),
                btnText: $("#section9_btn_text").val(),
                btnSlug: $("#section9_btn_slug").val()
            },
            section10: {
                heading: $("#section10_heading").val(),
            },
            section11: {
                heading: $("#section11_heading").val(),
                headingDetail: CKEDITOR.instances['section11_details'].getData().trim(),
            },
            section12: {
                heading: $("#section12_heading").val(),
                headingDetail: CKEDITOR.instances['section12_details'].getData().trim(),
            },
            section13: {
                heading: $("#section13_heading").val(),
                headingDetail: $("#section13_details").val(),
            },
            section14: {
                heading: $("#section14_heading").val(),
                headingDetail: $('#section14_details').val(),
            },
            section15: {
                heading: $("#section15_heading").val(),
                headingDetail: $('#section15_details').val(),
            },
            section16: {
                heading: $("#section16_heading").val(),
                heading2: $("#section16_heading2").val(),
                btnText: $("#section16_btn_text").val(),
                btnSlug: $("#section16_btn_slug").val()
            },
            section17: {
                heading: $("#section17_heading").val(),
                btnText: $("#section17_btn_text").val(),
                btnSlug: $("#section17_btn_slug").val()
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