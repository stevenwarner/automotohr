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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit People Operations Product</h1>
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
                                                        <label>Section 1</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section1_heading" id="section1_heading" value="<?php echo $pageContent['page']['sections']['section1']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section1_heading1" id="section1_heading1" value="<?php echo $pageContent['page']['sections']['section1']['heading1']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12"><br>
                                                        <label>Detail</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="section1_heading1_detail" id="section1_heading1_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section1']['heading1Detail']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="hr-search-main" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Section 2</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section2_heading" id="section2_heading" value="<?php echo $pageContent['page']['sections']['section2']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section2_heading1" id="section2_heading1" value="<?php echo $pageContent['page']['sections']['section2']['heading1']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12"><br>
                                                        <label>Detail</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="section2_heading1_detail" id="section2_heading1_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section2']['heading1Detail']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="hr-search-main" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Section 3</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section3_heading" id="section3_heading" value="<?php echo $pageContent['page']['sections']['section3']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12"><br>
                                                        <label>Detail</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="section3_heading_detail" id="section3_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section3']['headingDetail']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-search-main" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Section 4</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section4_heading" id="section4_heading" value="<?php echo $pageContent['page']['sections']['section4']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12"><br>
                                                        <label>Detail</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="section4_heading_detail" id="section4_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section4']['headingDetail']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-search-main" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Section 5</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section5_heading" id="section5_heading" value="<?php echo $pageContent['page']['sections']['section5']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12"><br>
                                                        <label>Detail</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="section5_heading_detail" id="section5_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section5']['headingDetail']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-search-main" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Section 6</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section6_heading" id="section6_heading" value="<?php echo $pageContent['page']['sections']['section6']['heading']; ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12"><br>
                                                        <label>Detail</label><b class="text-danger"> *</b>
                                                        <textarea class="invoice-fields" name="section6_heading_detail" id="section6_heading_detail" rows="4" cols="60"><?php echo $pageContent['page']['sections']['section6']['headingDetail']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-search-main" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Section 7</label>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="field-row">
                                                            <label>Heading</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="section7_heading" id="section7_heading" value="<?php echo $pageContent['page']['sections']['section7']['heading']; ?>" />
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
                section1_heading: {
                    required: true
                },
                section1_heading1: {
                    required: true
                },
                section1_heading1_detail: {
                    required: true
                },

                section2_heading: {
                    required: true
                },
                section2_heading1: {
                    required: true
                },
                section2_heading1_detail: {
                    required: true
                },


                section3_heading: {
                    required: true
                },
                section3_heading_detail: {
                    required: true
                },
                section4_heading: {
                    required: true
                },
                section4_heading_detail: {
                    required: true
                },
                section5_heading: {
                    required: true
                },
                section5_heading_detail: {
                    required: true
                },
                section6_heading: {
                    required: true
                },
                section6_heading_detail: {
                    required: true
                },
                section7_heading: {
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
                heading1: $("#section1_heading1").val(),
                heading1Detail: $("#section1_heading1_detail").val(),
            }, 
            section2: {
                heading: $("#section2_heading").val(),
                heading1: $("#section2_heading1").val(),
                heading1Detail: $("#section2_heading1_detail").val(),
            },
            section3: {
                heading: $("#section3_heading").val(),
                headingDetail: $("#section3_heading_detail").val(),
            },
            section4: {
                heading: $("#section4_heading").val(),
                headingDetail: $("#section4_heading_detail").val(),
            },
            section5: {
                heading: $("#section5_heading").val(),
                headingDetail: $("#section5_heading_detail").val(),
            },
            section6: {
                heading: $("#section6_heading").val(),
                headingDetail: $("#section6_heading_detail").val(),
            },
            section7: {
                heading: $("#section7_heading").val(),
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