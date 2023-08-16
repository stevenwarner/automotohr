<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" style="float: right;" href="<?= base_url('cms/pages') ?>"><i class="fa fa-long-arrow-left"></i> Back</a>
                                    </div>
                                </div>

                                <div class="hr-box">
                                    <div class="hr-box-header">
                                        <div class="hr-items-count">
                                            <strong class="employerCount"><?php echo $pageData['title'] ?></strong>
                                        </div>

                                    </div>
                                    <div class="hr-innerpadding">

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_meta">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                Meta
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_meta" class="panel-collapse collapse">
                                                        <div class="table-responsive">
                                                            <div class="panel-body">
                                                                <form method="post" action="<?php echo current_url(); ?>">

                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <label for="location_address">Title: </label>
                                                                            <input type="text" name="meta_title" value="<?php echo $pageData['meta_title']; ?>" class="invoice-fields" id="meta_title">
                                                                        </div>
                                                                    </div><br>

                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <label for="location_address">keywords: </label>
                                                                            <textarea name="meta_keyword" id="meta_keyword" class="invoice-fields autoheight"><?php echo $pageData['meta_keyword']; ?> </textarea>
                                                                        </div>
                                                                    </div><br>

                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <label for="location_address">Description: </label>
                                                                            <textarea name="meta_description" id="meta_description" class="invoice-fields autoheight"><?php echo $pageData['meta_description']; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <input type="hidden" name="pageid" value="<?php echo $pageData['sid']; ?>">
                                                                    <button type="submit" class="btn btn-success">Update</button>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_slider">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                Slider
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_slider" class="panel-collapse collapse">
                                                        <div class="table-responsive">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <div class="table-responsive">
                                                                            <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-success btn-sm  pull-right" data-original-title="Edit Item" id="addslider">Add New</a><br><br>
                                                                            <table class="table table-bordered table-hover table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="col-xs-4">Id</th>
                                                                                        <th class="col-xs-6">Description Heading</th>
                                                                                        <th class="col-xs-2 text-center">Description</th>
                                                                                        <th class="col-xs-2 text-center">Image Background</th>
                                                                                        <th class="col-xs-2 text-center">Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                    <?php foreach ($sliderList as $sliderRow) { ?>
                                                                                        <tr>
                                                                                            <td><?php echo $sliderRow['sid'] ?></td>
                                                                                            <td><?php echo $sliderRow['description_heading'] ?></td>
                                                                                            <td><?php echo $sliderRow['description'] ?></td>
                                                                                            <td><?php echo $sliderRow['background_image'] ?></td>
                                                                                            <td>
                                                                                                <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-warning btn-sm pencil_useful_link" data-original-title="Edit Item"><i class="fa fa-pencil"></i> Edit</a>
                                                                                                <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-sm" data-original-title="Delete Item"><i class="fa fa-trash"></i> Delete</a>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php } ?>

                                                                                </tbody>
                                                                            </table>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


<div id="sliderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Slider</h4>
            </div>
            <div class="modal-body">

                <form method="post" action="<?php echo base_url('cms/pages/add_slider'); ?>" id="addsliderform">

                    <input type="hidden" id="edit_link_sid" name="edit_link_sid" value="" />
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100">
                                <label>Description Heading:<span class="staric">*</span></label>
                                <input type="text" name="description_heading" id="description_heading" class="invoice-fields">
                                <span id="description_heading_error" class="text-danger"></span>
                            </li>
                            <li class="form-col-100 ">
                                <label>Description:</label>
                                <textarea name="description" class="invoice-fields autoheight" cols="40" rows="4" id="description"></textarea>
                            </li>

                            <li class="form-col-100 ">
                                <label>Button Text:</label>
                                <input type="text" name="button_text" id="button_text" class="invoice-fields">
                            </li>
                            <li class="form-col-100 ">
                                <label>Button Link:</label>
                                <input type="text" name="button_link" id="button_link" class="invoice-fields">
                            </li>

                            <li class="form-col-100">
                                <label>Background Image:<span class="staric">*</span></label>
                                <div class="upload-file invoice-fields">
                                    <span class="selected-file" id="name_pictures">No file
                                        selected</span>
                                    <input type="file" name="pictures" id="pictures" onchange="check_slider_picture('pictures')">
                                    <a href="javascript:;">Choose File</a>
                                </div>
                            </li>
                            <input type="hidden" name="sliderid" value="0">
                            <input type="hidden" name="pageid" value="<?php echo $pageData['sid']; ?>">
                            <input type="hidden" name="pageaction" value="addslider">
                        </ul>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success float-right" id="add_slider_button">Save</button>&nbsp;
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> &nbsp;
            </div>
        </div>
    </div>
</div>

<!--  -->
<link rel="stylesheet" href="<?= base_url("assets/css/SystemModel.css"); ?>">
<script src="<?= base_url("assets/js/SystemModal.js"); ?>"></script>

<script>
    $("#addslider").click(function() {
        $('#sliderModal').modal('show');
    });


    $("#add_slider_button").click(function() {
        var description_heading = $('#description_heading').val();
        if (description_heading == '') {
            $('#description_heading_error').html('<strong>Please provide title</strong>');
        } else {
            document.getElementById("addsliderform").submit();
        }

    });



    function check_slider_picture(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName);
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'pictures' || val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "gif") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png .jpe .gif) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

</script>