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
                                                                Sliders
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
                                                                                        <tr id="<?php echo $sliderRow['sid'] ?>">
                                                                                            <td><?php echo $sliderRow['sid'] ?></td>
                                                                                            <td><?php echo $sliderRow['description_heading'] ?></td>
                                                                                            <td><?php echo $sliderRow['description'] ?></td>
                                                                                            <td> <img src="<?php echo AWS_S3_BUCKET_URL;
                                                                                                            if (isset($sliderRow['background_image']) && $sliderRow['background_image'] != "") {
                                                                                                                echo $sliderRow['background_image'];
                                                                                                            } elseif (isset($sliderRow['background_image']) && $sliderRow['background_image'] != "") {
                                                                                                                echo $sliderRow['background_image'];
                                                                                                            } else {
                                                                                                            ?>
                                                                                                            default_pic-ySWxT.jpg
                                                                                                            <?php } ?>" alt="Profile Picture" width="50px;" height="50px;">

                                                                                            </td>
                                                                                            <td>
                                                                                                <span><a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-warning btn-sm editslider" data-original-title="Edit Slider" data-sid="<?php echo $sliderRow['sid']; ?>"><i class="fa fa-pencil"></i> Edit</a></span><br><br>
                                                                                                <span> <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-sm deleteslider" data-original-title="Delete Slider" data-sid="<?php echo $sliderRow['sid']; ?>"><i class="fa fa-trash"></i> Delete</a></span>
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



                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_section">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                Sections
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_section" class="panel-collapse collapse">
                                                        <div class="table-responsive">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <div class="table-responsive">
                                                                            <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-success btn-sm  pull-right" data-original-title="Edit Item" id="addsection">Add New</a><br><br>
                                                                            <table class="table table-bordered table-hover table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="col-xs-1">Id</th>
                                                                                        <th class="col-xs-1">Heading 1</th>
                                                                                        <th class="col-xs-2">Heading 2</th>
                                                                                        <th class="col-xs-2 text-center">Description</th>
                                                                                        <th class="col-xs-2 text-center">Image Background</th>
                                                                                        <th class="col-xs-1">Display</th>
                                                                                        <th class="col-xs-1 text-center">Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                    <?php foreach ($sectionsList as $sectionRow) { ?>
                                                                                        <tr id="<?php echo $sectionRow['sid'] ?>">
                                                                                            <td><?php echo $sectionRow['sid'] ?></td>
                                                                                            <td><?php echo $sectionRow['heading_1'] ?></td>
                                                                                            <td><?php echo $sectionRow['heading_2'] ?></td>
                                                                                            <td><?php echo $sectionRow['description'] ?></td>
                                                                                            <td> <img src="<?php echo AWS_S3_BUCKET_URL;
                                                                                                            if (isset($sectionRow['background_image']) && $sectionRow['background_image'] != "") {
                                                                                                                echo $sectionRow['background_image'];
                                                                                                            } elseif (isset($sectionRow['background_image']) && $sectionRow['background_image'] != "") {
                                                                                                                echo $sectionRow['background_image'];
                                                                                                            } else {
                                                                                                            ?>
                                                                                                            default_pic-ySWxT.jpg
                                                                                                            <?php } ?>" alt="Profile Picture" width="50px;" height="50px;">

                                                                                            </td>
                                                                                            <td><?php echo $sectionRow['display_mode'] ?></td>
                                                                                            <td>
                                                                                                <span><a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-warning btn-sm editsection" data-original-title="Edit Section" data-sid="<?php echo $sectionRow['sid']; ?>"><i class="fa fa-pencil"></i> Edit</a></span><br><br>
                                                                                                <span> <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-sm deletesection" data-original-title="Delete Section" data-sid="<?php echo $sectionRow['sid']; ?>"><i class="fa fa-trash"></i> Delete</a></span>
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

                <form method="post" enctype="multipart/form-data" action="<?php echo base_url('cms/pages/add_slider'); ?>" id="addsliderform">

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
                                    <a href="javascript:;">Choose File</a> <br><br><br>
                                    <span id="picture_error" class="text-danger"></span>

                                </div>
                            </li>
                            <input type="hidden" name="sliderid" id="sliderid" value="0">
                            <input type="hidden" name="pageid" value="<?php echo $pageData['sid']; ?>">
                            <input type="hidden" name="pageaction" id="pageaction" value="addslider">
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







<div id="sectionModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Section</h4>
            </div>
            <div class="modal-body">

                <form method="post" enctype="multipart/form-data" action="<?php echo base_url('cms/pages/add_slider'); ?>" id="addsectionform">

                    <input type="hidden" id="edit_link_sid" name="edit_link_sid" value="" />
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100">
                                <label>Heading 1:<span class="staric">*</span></label>
                                <input type="text" name="heading_1" id="heading_1" class="invoice-fields">
                                <span id="heading_1_error" class="text-danger"></span>
                            </li>

                            <li class="form-col-100">
                                <label>Heading 2:</label>
                                <input type="text" name="heading_2" id="heading_2" class="invoice-fields">
                            </li>

                            <li class="form-col-100 ">
                                <label>Description:</label>
                                <textarea name="description" class="invoice-fields autoheight" cols="40" rows="4" id="section_description"></textarea>
                            </li>

                            <li class="form-col-100 ">
                                <label>Button Text:</label>
                                <input type="text" name="button_text" id="section_button_text" class="invoice-fields">
                            </li>
                            <li class="form-col-100 ">
                                <label>Button Link:</label>
                                <input type="text" name="button_link" id="section_button_link" class="invoice-fields">
                            </li>
                            <li class="form-col-100 ">
                                <label>Display Mode:</label>
                                <select name="display_mode" id="display_mode" class="invoice-fields">
                                    <option value="right">Right</option>
                                    <option value="left">Left</option>
                                </select>
                            </li>

                            <li class="form-col-100">
                                <label>Background Image:<span class="staric">*</span></label>
                                <div class="upload-file invoice-fields">
                                    <span class="selected-file" id="name_sectionpictures">No file
                                        selected</span>
                                    <input type="file" name="sectionpictures" id="sectionpictures" onchange="check_section_picture('sectionpictures')">
                                    <a href="javascript:;">Choose File</a> <br><br><br>
                                    <span id="sectionpicture_error" class="text-danger"></span>

                                </div>
                            </li>
                            <input type="hidden" name="sectionid" id="sectionid" value="0">
                            <input type="hidden" name="pageid" value="<?php echo $pageData['sid']; ?>">
                            <input type="hidden" name="pageaction" id="sectionpageaction" value="addsection">
                        </ul>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success float-right" id="add_section_button">Save</button>&nbsp;
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
        $('#add_slider_button').text('Save');
        $('#pageaction').val('addslider');
        $('#sliderModal').modal('show');
    });


    $("#add_slider_button").click(function() {
        var description_heading = $('#description_heading').val();
        var picture = $('#pictures').val();

        if (description_heading == '') {
            $('#description_heading_error').html('<strong>Please provide description heading</strong>');
        } else if (picture == '' && $('#pageaction').val() == 'addslider') {
            $('#picture_error').html('<strong>Please provide background image</strong>');
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



    $(".editslider").click(function() {
        var sliderId = $(this).data("sid");
        edit_slider(sliderId)
    });

    $(".deleteslider").click(function() {
        var sliderId = $(this).data("sid");
        delete_slider(sliderId);
    });



    function delete_slider(sliderId) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this slider?',
            function() {
                var my_request;
                my_request = $.ajax({
                    url: '<?php echo base_url('cms/pages/handler'); ?>',
                    type: 'POST',
                    data: {
                        'action': 'delete_slider',
                        'slider_sid': sliderId
                    }
                });

                my_request.success(function(response) {
                    alertify.success('Slider is Deleted');
                    $('#' + sliderId).remove();
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function edit_slider(sliderId) {

        var my_request;
        my_request = $.ajax({
            url: '<?php echo base_url('cms/pages/handler'); ?>',
            type: 'POST',
            data: {
                'action': 'edit_slider',
                'slider_sid': sliderId
            }
        });

        my_request.success(function(response) {
            //console.log(response.Data);
            $('#description_heading').val(response.Data.description_heading);
            $('#description').text(response.Data.description);
            $('#button_text').val(response.Data.button_text);
            $('#button_link').val(response.Data.button_link);
            $('#add_slider_button').text('Update');
            $('#pageaction').val('updateslider');
            $('#sliderid').val(response.Data.sid);
            $('#sliderModal').modal('show');
        });
    }


    $("#addsection").click(function() {
        $('#add_section_button').text('Save');
        $('#sectionpageaction').val('addsection');
        $('#heading_1').val('');
        $('#heading_2').val('');
        $('#section_description').text('');
        $('#section_button_text').val('');
        $('#section_button_link').val('');
        $('#sectionModal').modal('show');
    });

    $("#add_section_button").click(function() {

        var heading1 = $('#heading_1').val();
        var picture = $('#sectionpictures').val();

        if (heading1 == '') {
            $('#heading_1_error').html('<strong>Please provide heading 1</strong>');
        } else if (picture == '' && $('#pageaction').val() == 'addsection') {
            $('#sectionpicture_error').html('<strong>Please provide background image</strong>');
        } else {
            document.getElementById("addsectionform").submit();
        }

    });



    function check_section_picture(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName);
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'sectionpictures' || val == 'sectionpictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "gif") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png .jpe .gif) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }



    //
    $(".deletesection").click(function() {
        var sectionId = $(this).data("sid");
        delete_section(sectionId);
    });

    $(".editsection").click(function() {
        var sectionId = $(this).data("sid");
        edit_section(sectionId)
    });

    function delete_section(sectionId) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this section?',
            function() {
                var my_request;
                my_request = $.ajax({
                    url: '<?php echo base_url('cms/pages/handler'); ?>',
                    type: 'POST',
                    data: {
                        'action': 'delete_section',
                        'section_sid': sectionId
                    }
                });

                my_request.success(function(response) {
                    alertify.success('Section is Deleted');
                    $('#' + sectionId).remove();
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function edit_section(sectionId) {

        var my_request;
        my_request = $.ajax({
            url: '<?php echo base_url('cms/pages/handler'); ?>',
            type: 'POST',
            data: {
                'action': 'edit_section',
                'section_sid': sectionId
            }
        });

        my_request.success(function(response) {
            $('#heading_1').val(response.Data.heading_1);
            $('#heading_2').val(response.Data.heading_2);
            $('#section_description').text(response.Data.description);
            $('#section_button_text').val(response.Data.button_text);
            $('#section_button_link').val(response.Data.button_link);
            $('#display_mode').val(response.Data.display_mode);

            $('#add_section_button').text('Update');
            $('#sectionpageaction').val('updatesection');
            $('#sectionid').val(response.Data.sid);
            $('#sectionModal').modal('show');
        });
    }
</script>