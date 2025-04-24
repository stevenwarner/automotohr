<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    = <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?>
                                        </h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/dashboard') ?>"
                                                class="btn black-btn"><i class="fa fa-arrow-left"> </i> Back To
                                                Compliance Safety Overview</a>
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/report_types/add') ?>"
                                                class="btn btn-success"><i class="fa fa-plus-circle"> </i> Add An Report
                                                Type</a>
                                        </div>
                                    </div>
                                    <form action="<?= current_url(); ?>" method="POST" id="edit_type">

                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <div class="hr-registered">
                                                    Edit A Compliance Report Incident Type
                                                </div>
                                            </div>

                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="heading-title page-title">
                                                            <h1 class="page-title">
                                                                <?= $form == 'add' ? 'New Incident Type' : $name; ?>
                                                            </h1>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label for="report_name">Report Incident Name <span
                                                                    class="hr-required">*</span></label>
                                                            <?php echo form_input('compliance_incident_type_name', set_value('compliance_incident_type_name', $report_type["compliance_incident_type_name"]), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('compliance_incident_type_name'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label for="status">Status <span
                                                                    class="hr-required">*</span></label>
                                                            <select name="status" class="hr-form-fileds">
                                                                <option <?= $report_type["status"] == "0" ? "selected" : ""; ?> value="0">In Active</option>
                                                                <option <?= $report_type["status"] == "1" ? "selected" : ""; ?> value="1">Active</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="description-editor">
                                                            <label>Add description <span
                                                                    class="hr-required">*</span></label>
                                                            <script type="text/javascript"
                                                                src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                            <textarea class="ckeditor" name="description" rows="8"
                                                                cols="60" required>
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


                                                </div>
                                            </div>
                                        </div>

                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <div class="hr-registered">
                                                    Manage Incident Items
                                                </div>
                                            </div>

                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-sm-12 text-right">
                                                        <button type="button" class="btn btn-success jsAddItemBtn">
                                                            <i class="fa fa-plus-circle"></i>
                                                            &nbsp;Add Item
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr>

                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-sm-1" scope="col">Item #</th>
                                                                    <th class="col-sm-2" scope="col">Severity</th>
                                                                    <th class="col-sm-2" scope="col">Title</th>
                                                                    <th class="col-sm-5" scope="col">Description</th>
                                                                    <th class="col-sm-2" scope="col">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="jsMainArea">
                                                                <?php if ($items) { ?>
                                                                    <?php $itemNumber = count($items); ?>
                                                                    <?php foreach ($items as $k0 => $item) { ?>
                                                                        <tr class="jsItemRow" data-key="<?= $item["sid"]; ?>">
                                                                            <td style="vertical-align: middle;">
                                                                                <?= $itemNumber; ?>
                                                                            </td>
                                                                            <td style="vertical-align: middle;">
                                                                                <button type="button" class="btn"
                                                                                    style="background-color: <?= $severity_status[$item["severity_level_sid"]]["bg_color"]; ?>; color: <?= $severity_status[$item["severity_level_sid"]]["txt_color"]; ?>;">

                                                                                    Severity Level
                                                                                    <?= $item["severity_level_sid"]; ?>
                                                                                </button>
                                                                            </td>
                                                                            <td style="vertical-align: middle;">
                                                                                <?= $item["title"]; ?>
                                                                            </td>
                                                                            <td style="vertical-align: middle;">
                                                                                <?= $item["description"]; ?>
                                                                            </td>
                                                                            <td style="vertical-align: middle"
                                                                                class="text-center">
                                                                                <button type="button"
                                                                                    class="btn btn-warning jsEditItemBtn">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="btn btn-danger jsDeleteItemBtn">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                        <?php $itemNumber--; ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="4">
                                                                            <p class="alert alert-info text-center">
                                                                                No items found.
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div
                                                class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                <input type="submit" class="search-btn" value="Update"
                                                    name="form-submit" />
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


<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript"
    src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    $(function () {
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
                    required: function () {
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
            submitHandler: function (form) {

                var instances = $.trim(CKEDITOR.instances.description.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Description Missing', "Description cannot be Empty");
                    return false;
                }

                form.submit();
            }
        });



        $(document).on("click", ".jsDeleteItemBtn", function (event) {
            //
            event.preventDefault();
            //
            const recordId = $(this).closest("tr").data("key");
            let _this = $(this)

            alertify.confirm(
                "Are you sure you want to delete it?",
                function () {
                    _this.remove()
                    deleteTheItem(recordId)
                }
            );
        });

        //
        $(document).on("click", "#jsItemUpdateBtn", function (event) {
            //
            event.preventDefault();
            const recordId = $("#jsItemId").val();
            const title = $("#jsItemTitle").val();
            var itemDescription = CKEDITOR.instances["jsItemDescription"].getData();
            if (title.trim() === "") {
                alertify.alert("Item title cannot be empty");
                return false;
            }
            if (itemDescription.trim() === "") {
                alertify.alert("Item description cannot be empty");
                return false;
            }
            const severityLevel = $("#jsItemSeverityLevel").val();

            toggleButtonLoading($("#jsItemUpdateBtn"), true);

            $.ajax({
                url: '<?= base_url('manage_admin/compliance_safety/incident_types_item'); ?>/' + recordId + '/update',
                type: 'POST',
                data: {
                    title: title,
                    description: itemDescription,
                    severityLevel: severityLevel
                },
                success: function (response) {
                    // Hide loader
                    //
                    alertify.success(response.message);
                    // Append item to table
                    $(`.jsItemRow[data-key='${recordId}']`).find("td:eq(1)")
                        .find(".jsSeverityLevelText").html(`Severity Level ${severityLevel}`);
                    $(`.jsItemRow[data-key='${recordId}']`).find("td:eq(1)")
                        .find("button").css({
                            "background-color": severity_status[severityLevel]["bg_color"],
                            "color": severity_status[severityLevel]["txt_color"]
                        });
                    $(`.jsItemRow[data-key='${recordId}']`).find("td:eq(2)").html(title);
                    $(`.jsItemRow[data-key='${recordId}']`).find("td:eq(3)").html(itemDescription);
                    // Hide modal
                    $("#jsIssueModal").modal('hide');
                },
                error: function () {
                    // Hide loader
                    alertify.error('Error occurred while updating item');
                }
            });

        });

        //
        function deleteTheItem(recordId) {
            $.ajax({
                url: '<?= base_url('manage_admin/compliance_safety/incident_types_item'); ?>/' + recordId,
                type: 'delete',
                success: function (response) {
                    // Hide loader
                    $(".loader").remove();
                    //
                    alertify.success(response.message);
                    // Append item to table
                    $(".jsItemRow[data-key='" + recordId + "']").remove();
                    // Reset item numbers
                    $(".jsMainArea tr").each(function (index) {
                        $(this).find("td:first").text($(".jsMainArea tr").length - index);
                    });
                    // Hide modal
                    $("#addItemModal").modal('hide');
                },
                error: function () {
                    // Hide loader
                    alertify.error('Error occurred while deleting item');
                }
            });
        }

        // new logic
        CKEDITOR.replace('description');


        //
        $(".jsAddItemBtn").click(function (event) {
            event.preventDefault();
            loadModal();
            //
            $("#jsIssueModalLabel").html("Add Item");
        });

        $(document).on("click", "#jsItemSubmitBtn", function (event) {
            event.preventDefault();
            const title = $("#jsItemTitle").val();
            if (title.trim() === "") {
                alertify.alert("Item title cannot be empty");
                return false;
            }
            var itemDescription = CKEDITOR.instances["jsItemDescription"].getData();
            if (itemDescription.trim() === "") {
                alertify.alert("Item description cannot be empty");
                return false;
            }
            const severityLevel = $("#jsItemSeverityLevel").val();
            toggleButtonLoading($("#jsItemSubmitBtn"), true);
            //
            $.ajax({
                url: '<?= base_url('manage_admin/compliance_safety/incident_types/' . $incidentId . '/save_item'); ?>',
                type: 'POST',
                data: {
                    title: title,
                    description: itemDescription,
                    severityLevel: severityLevel
                },
                always: function () {
                    toggleButtonLoading($("#jsItemSubmitBtn"), false);
                },
                success: function (response) {
                    //
                    alertify.success(response.message);
                    // Append item to table
                    generateRow(response.id, title, itemDescription, severityLevel);
                    // Hide modal
                    $("#jsIssueModal").modal('hide');
                },
                error: function () {
                    // Hide loader
                    alertify.error('Error occurred while saving item');
                }
            });
        });

        // Edit
        //
        $(document).on("click", ".jsEditItemBtn", function (event) {
            event.preventDefault();
            //
            const recordId = $(this).closest("tr").data("key");
            loadModal();
            $("#jsIssueModalLabel").html("Edit Item");
            //
            loadItemDetails(recordId)
        });

        //
        function loadItemDetails(recordId) {
            $.ajax({
                url: '<?= base_url('manage_admin/compliance_safety/incident_types_item'); ?>/' + recordId,
                type: 'get',
                success: function (response) {
                    //
                    populateModalValues(response.title, response.description, recordId, "edit", response.severity_level_sid);
                },
                error: function () {
                    // Hide loader
                    alertify.error('Error occurred while fetching the item');
                }
            });
        }

        function toggleButtonLoading(_html, doLoad) {
            _html.html(doLoad ? "Saving..." : "Save changes");
        }


        const severity_status = <?= json_encode($severity_status); ?>;


        // Load the modal when the page is ready
        function loadModal() {
            // Check if the modal already exists
            if (!$("#jsIssueModal").length) {
                addModalToBody();
            }
            //
            populateModalValues("", "", 0, "add");
            // Show the modal
            $("#jsIssueModal").modal({
                backdrop: 'static',
                keyboard: false
            });
            // Set the modal to be static
            $("#jsIssueModal").modal("show");
        }

        function addModalToBody() {
            //
            let severityLevelList = "";
            if (severity_status) {
                $.each(severity_status, function (index, value) {
                    severityLevelList += `
                        <div class="col-xs-12">
                            <div data-id="${value["sid"]}"
                                class="csLabelPill applicant jsSelectedLabelPill text-center"
                                style="background-color: ${value["bg_color"]}; color: ${value["txt_color"]};">
                                Severity Level ${value["level"]}
                            </div>
                        </div>
                    `;
                });
            }
            var modalHtml = `
               <!-- Bootstrap Modal -->
                <div class="modal fade" id="jsIssueModal" tabindex="-1" role="dialog" aria-labelledby="jsIssueModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="jsIssueModalLabel">Modal Title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Modal content goes here -->
                                <form action="" id="jsItemForm"> 
                                    <div class="form-group">
                                        <label>Title <strong class="text-danger">*</strong></label>
                                        <input type="text" name="title" class="form-control jsItemTitle" id="jsItemTitle" required />
                                        <input type="hidden" id="jsItemId" />
                                        <input type="hidden" id="jsItemSeverityLevel" />
                                    </div>
                                    <div class="form-group">
                                        <label>Description <strong class="text-danger">*</strong></label>
                                        <textarea name="description" class="form-control jsItemDescription" rows="4"
                                            required id="jsItemDescription"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Severity Level <strong class="text-danger">*</strong></label>
                                        <div class="label-wrapper-outer">
                                            <div class="row">
                                                <div class="col-xs-10 jsSelectedPill">
                                                    <div id="jsItemSelectedSeverityLevel" data-id="${severity_status[1]["sid"]}"
                                                        class="csLabelPill jsSelectedLabelPill text-center"
                                                        style="background-color: ${severity_status[1]["bg_color"]}; color: ${severity_status[1]["txt_color"]};">
                                                        Severity Level ${severity_status[1]["level"]}
                                                    </div>
                                                </div>
                                                <div class="col-xs-2 text-left">
                                                    <div class="btn btn-primary show-status-box">
                                                        <i class="fa fa-pencil"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Selected one -->
                                            <div class="lable-wrapper">
                                                <div style="height:20px;">
                                                    <i class="fa fa-times cross"></i>
                                                </div>
                                                ${severityLevelList}       
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <div style="background-color: lightyellow; padding: 10px; margin-top: 10px;">
                                            <p>
                                                <strong>{{input}}</strong>
                                            </p>
                                            <p>
                                                <strong>{{checkbox}}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary hidden" id="jsItemSubmitBtn">Save changes</button>
                                <button type="button" class="btn btn-warning hidden" id="jsItemUpdateBtn">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $("body").append(modalHtml);
            //
            if (typeof CKEDITOR.instances["jsItemDescription"] === "undefined") {
                CKEDITOR.replace("jsItemDescription");
            }
        }

        //
        function generateRow(itemId, itemTitle, itemDescription, itemSeverityLevel) {
            var newItemHtml = `
            <tr class="jsItemRow" data-key="{{itemId}}">
                <td style="vertical-align: middle;">
                    ${$(".jsMainArea tr").length + 1}
                </td>
                <td style="vertical-align: middle;">
                    <button type="button" class="btn"
                        style="background-color: ${severity_status[itemSeverityLevel]["bg_color"]}; color: ${severity_status[itemSeverityLevel]["txt_color"]};">
                    <span class="jsSeverityLevelText">
                        Severity Level ${itemSeverityLevel}
                    </span>
                    </button>
                </td>
                <td style="vertical-align: middle;">
                    ${itemTitle}
                </td>
                <td style="vertical-align: middle;">
                    ${itemDescription}
                </td>
                <td style="vertical-align: middle;" class="text-center">
                    <button type="button" class="btn btn-warning jsEditItemBtn">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger jsDeleteItemBtn">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            `;
            $(".jsMainArea").prepend(newItemHtml.replace('{{itemId}}', itemId));

        }

        //
        function populateModalValues(title, description, itemId = 0, type = "add", severityLevel = 1) {
            // Populate the modal with values
            $("#jsItemSubmitBtn").html("Save changes");
            $("#jsItemUpdateBtn").html("Save changes");
            //
            $("#jsItemTitle").val(title);
            CKEDITOR.instances["jsItemDescription"].setData(description);
            $("#jsItemId").val(itemId);
            $("#jsItemSeverityLevel").val(severityLevel);
            $("#jsItemSelectedSeverityLevel").data("id", severityLevel);
            $("#jsItemSelectedSeverityLevel").html(`
                Severity Level ${severity_status[severityLevel]["level"]}
            `);
            $("#jsItemSelectedSeverityLevel").css({
                "background-color": severity_status[severityLevel]["bg_color"],
                "color": severity_status[severityLevel]["txt_color"]
            });
            //
            if (type === "edit") {
                $("#jsItemSubmitBtn").addClass("hidden");
                $("#jsItemUpdateBtn").removeClass("hidden");
            } else {
                $("#jsItemSubmitBtn").removeClass("hidden");
                $("#jsItemUpdateBtn").addClass("hidden");
            }
        }

        $(document).on("click", ".jsSelectedLabelPill", function () {
            $(this).closest(".row").next().show();
        });



        $(document).on("click", ".show-status-box", function () {
            $(this).closest(".row").next().show();
        });

        $(document).on("hover", ".applicant",
            function () {
                $(this).find(".jsSeverityLevelText").animate(
                    {
                        "padding-top": 0,
                        "padding-right": 0,
                        "padding-bottom": 0,
                        "padding-left": 15,
                    },
                    "fast"
                );
            },
            function () {
                $(this).find(".jsSeverityLevelText").animate(
                    {
                        "padding-top": 0,
                        "padding-right": 0,
                        "padding-bottom": 0,
                        "padding-left": 5,
                    },
                    "fast"
                );
            }
        );

        $(document).on("click", ".applicant", function () {
            //
            const id = $(this).data("id");

            $("#jsItemSelectedSeverityLevel").data("id", id);
            $("#jsItemSelectedSeverityLevel").css({
                "background-color": $(this).css("background-color"),
                "color": $(this).css("color"),
            });
            $("#jsItemSelectedSeverityLevel").html(`
                Severity Level ${$(this).find(".jsSeverityLevelText").text()}
            `);


            $("#jsItemSeverityLevel").val(id);
        });

        $(document).on("click", ".cross", function () {
            $(this).parent().parent().css("display", "none");
        });


    });
</script>


<style>
    .candidate-status {
        width: 100%;
        height: 50px;
    }

    .label-wrapper-outer {
        width: 100%;
        position: relative;
    }

    .candidate-status .lable-wrapper {
        top: 40px;
        border-radius: 5px;
        padding: 20px;
        width: 100%;
    }

    .lable-wrapper {
        width: 100%;
        display: none;
        background-color: white;
        padding: 20px;
        padding-top: 0;
        box-shadow: 0px 0px 6px #888888;
        right: 0;
        position: absolute;
        top: 30px;
        z-index: 999;
    }

    .label.csLabelPill {
        display: block !important;
    }

    .csLabelPill {
        font-family: arial;
        font-weight: bold;
        padding: 6px;
        font-size: 13px;
        margin-bottom: 3px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

    .candidate-status .label {
        height: 30px;
        line-height: 24px;
        font-style: italic;
        font-size: 13px;
        font-weight: 600;
    }

    .candidate-status .fa.fa-times.cross {
        background-color: #000;
        border-radius: 100%;
        color: #fff;
        font-size: 9px;
        height: 20px;
        line-height: 19px;
        padding: 0;
        position: absolute;
        right: 20px;
        text-align: center;
        top: 10px;
        width: 20px;
    }
</style>