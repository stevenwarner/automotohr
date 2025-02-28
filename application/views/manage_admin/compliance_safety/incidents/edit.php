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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/dashboard') ?>" class="btn black-btn"><i class="fa fa-arrow-left"> </i> Back To Compliance Safety Overview</a>
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/report_types/add') ?>" class="btn btn-success"><i class="fa fa-plus-circle"> </i> Add An Report Type</a>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <div class="hr-registered">
                                                Edit A Compliance Report Incident Type
                                            </div>
                                        </div>

                                        <div class="hr-innerpadding">
                                            <form action="<?= current_url(); ?>" method="POST" id="edit_type">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="heading-title page-title">
                                                            <h1 class="page-title"><?= $form == 'add' ? 'New Incident Type' : $name; ?></h1>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label for="report_name">Report Incident Name <span class="hr-required">*</span></label>
                                                            <?php echo form_input('compliance_incident_type_name', set_value('compliance_incident_type_name', $report_type["compliance_incident_type_name"]), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('compliance_incident_type_name'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label for="status">Status <span class="hr-required">*</span></label>
                                                            <select name="status" class="hr-form-fileds">
                                                                <option <?= $report_type["status"] == "0" ? "selected" : ""; ?> value="0">In Active</option>
                                                                <option <?= $report_type["status"] == "1" ? "selected" : ""; ?> value="1">Active</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="description-editor">
                                                            <label>Add description <span class="hr-required">*</span></label>
                                                            <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                            <textarea class="ckeditor" name="description" rows="8" cols="60" required>
                                                                <?php echo set_value('description', $report_type["description"]); ?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label for="report_name">Code</label>
                                                            <?php echo form_input('code', set_value('code', $report_type["code"]), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('code'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label for="report_name">Priority</label>
                                                            <?php echo form_input('priority', set_value('priority', $report_type["priority"]), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('priority'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header bg-header-green">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <div class="hr-registered text-left">
                                                                        Listings
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <div class="hr-registered text-right">
                                                                        <a href="javascript:void(0)" class="btn btn-success jsViewDocument"><i class="fa fa-plus-circle"> </i> Add</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-hover table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Name</th>
                                                                                <th>Level</th>
                                                                                <th>BgColor</th>
                                                                                <th class="last-col" width="1%" colspan="2">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                            <tr>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td>
                                                                                    <button class="btn btn-sm btn-danger" id="8h" title="Delete" src="Disable" type="button"><i class="fa fa-trash"></i></button>
                                                                                </td>

                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                        <input type="submit" class="search-btn" value="Update" name="form-submit" />
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


<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>


<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>/assets/v1/plugins/ms_modal/main.css?v=1740748262">
<script type="text/javascript" src="<?= base_url(); ?>/assets/v1/plugins/ms_modal/main.js?v=1740748262"></script>



<script type="text/javascript">
    $(function() {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });

        $("#edit_type").validate({
            ignore: ":hidden:not(select)",
            debug: false,
            rules: {
                compliance_incident_type_name: {
                    required: true
                },
                description: {
                    required: function() {
                        CKEDITOR.instances.description.updateElement();
                    }
                },
            },
            messages: {
                report_name: {
                    required: 'Incident Type is required'
                },
                instructions: {
                    required: 'Please provide some description for this report'
                },
            },
            submitHandler: function(form) {

                var instances = $.trim(CKEDITOR.instances.description.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Description Missing', "Description cannot be Empty");
                    return false;
                }

                form.submit();
            }
        });
    });

    $(document).ready(function() {
        CKEDITOR.replace('description');
    });

    //
    $(".jsViewDocument").click(function(event) {
        //
        event.preventDefault();
        //
        //
        Modal({
                Title: "Add List Item",
                Id: "jsListingModal",
                Loader: "jsListingModalLoader",
                Body: '<div id="jsListingModalBody"></div>',
            },
            generateFileBody
        );
    });



    function generateFileBody() {


        var reportIncidentName = $("[name='compliance_incident_type_name']").val();
        let listForm = '';

        listForm += '<div class="hr-box">';

        listForm += '<div class="hr-innerpadding">';
        listForm += '<div class="row">';
        listForm += '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">';
        listForm += ' <div class="heading-title page-title">';
        listForm += '<h1 class="page-title">' + reportIncidentName + '</h1>';
        listForm += '</div>';
        listForm += '</div>';
        listForm += '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
        listForm += '<div class="field-row field-row-autoheight">';
        listForm += '<label for="report_name">Name <span class="hr-required">*</span></label>';
        listForm += '<input type="text" name="list_name" id="list_name" value="" class="hr-form-fileds valid" aria-required="true" aria-invalid="false">';
        listForm += '</div>';
        listForm += '</div>';

        listForm += '<div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">';
        listForm += '<div class="field-row field-row-autoheight">';
        listForm += '<label for="report_name">Level <span class="hr-required">*</span></label>';
        listForm += '<input type="text" name="list_level" id="list_level" value="" class="hr-form-fileds valid" aria-required="true" aria-invalid="false">';
        listForm += '</div>';
        listForm += '</div>';


        listForm += ' <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">';
        listForm += '<div class="field-row">';
        listForm += '<label for="status">BG Color <span class="hr-required">*</span></label>';
        listForm += '<input type="color" name="bg_color_code" id="bg_color_code" class="hr-form-fileds">';
        listForm += ' </div>';
        listForm += '</div>';
        listForm += '<div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
        listForm += '<div class="field-row">';
        listForm += '<label for="status">Color <span class="hr-required">*</span></label>';
        listForm += '<input type="color" name="color_code" id="color_code" class="hr-form-fileds">';
        listForm += '</div>';
        listForm += '</div>';

        listForm += '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">';
        listForm += '<div class="description-editor">';
        listForm += '<label>Add Instructions <span class="hr-required">*</span></label>';
        listForm += '<textarea class="ckeditor" name="instructions" rows="8" cols="60" required>';

        listForm += '</textarea>';
        listForm += '</div>';
        listForm += '</div>';

        listForm += '</div>';
        listForm += '</div>';
        listForm += '</div>';

        listForm += '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">';
        listForm += '<input type="button" class="search-btn" value="Add" name="form-submit" id="jsbtnAddList" />';
        listForm += '</div>';


        $("#jsListingModalBody").html(listForm);
        ml(false, "jsListingModalLoader");

        CKEDITOR.replace('instructions');

    }

    //
    let tempTable = [{
        incidentTypeId: '',
        listName: "",
        listLevel: '',
        bgColorCode: '',
        ColorCode: ''
    }];

    $(document).on('click', '#jsbtnAddList', function() {
        let listName = $("#list_name").val();
        let listLevel = $("#list_level").val();
        let bgColorCode = $("#bg_color_code").val();
        let ColorCode = $("#color_code").val();

        // Adding a new row to the table
        addRowToTable(4, listName, listLevel, bgColorCode, ColorCode);

        // Display the updated table
        // displayTable();

        function addRowToTable(incidentTypeId, listName, listLevel, bgColorCode, ColorCode) {
            const newRow = {
                incidentTypeId,
                listLevel,
                listName,
                bgColorCode,
                ColorCode
            };

            tempTable.push(newRow);
            alertify.alert('Success! ', "List Added Successfully");

            $("#list_name").val('');
            $("#list_level").val('');
            $("#bg_color_code").val('');
            $("#color_code").val('');

            // console.log(`Added new row: ${JSON.stringify(newRow)}`);
        }

        
        function displayTable() {
            console.log("-------------------");
            tempTable.forEach(row => {
                console.log(`${row.incidentTypeId}  | ${row.listName} | ${row.ColorCode}`);
            });
        }
            

    });
</script>