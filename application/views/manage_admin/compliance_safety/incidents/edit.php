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
                                                                    <th class="col-sm-2" scope="col">Title</th>
                                                                    <th class="col-sm-7" scope="col">Description</th>
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

        //
        $(".jsAddItemBtn").click(function (event) {
            event.preventDefault();
            $("#addItemModal").remove();

            var modalHtml = `
                <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" data-backdrop="static">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body jsModalBody">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveItemBtn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $("body").append(modalHtml);
            let ht = generateHtml();
            $(".jsModalBody").html(ht.html);

            $("#addItemModal").modal('show');

            removeAllCKEditor(`textarea_${ht.index}`);
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
        $(document).on("click", "#saveItemBtn", function (event) {
            //
            event.preventDefault();
            const title = $(`#text_${$(".jsModalBody").find(".jsItemRow").data("key")}`).val();
            var itemDescription = CKEDITOR.instances[`textarea_${$(".jsModalBody").find(".jsItemRow").data("key")}`].getData();
            if (title.trim() === "") {
                alertify.alert("Item title cannot be empty");
                return false;
            }
            if (itemDescription.trim() === "") {
                alertify.alert("Item description cannot be empty");
                return false;
            }
            var newItemHtml = `
            <tr class="jsItemRow" data-key="{{itemId}}">
                <td style="vertical-align: middle;">
                    ${$(".jsMainArea tr").length + 1}
                </td>
                <td style="vertical-align: middle;">
                    ${title}
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

            // Show loader
            $(".jsModalBody").append('<div class="loader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; justify-content: center; align-items: center;"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');

            // Save item to database
            $.ajax({
                url: '<?= base_url('manage_admin/compliance_safety/incident_types/' . $incidentId . '/save_item'); ?>',
                type: 'POST',
                data: {
                    title: title,
                    description: itemDescription,
                },
                success: function (response) {
                    // Hide loader
                    $(".loader").remove();
                    //
                    alertify.success(response.message);
                    // Append item to table
                    $(".jsMainArea").prepend(newItemHtml.replace('{{itemId}}', response.id));
                    // Hide modal
                    $("#addItemModal").modal('hide');
                },
                error: function () {
                    // Hide loader
                    $(".loader").remove();
                    alertify.error('Error occurred while saving item');
                }
            });
        });

        //
        $(document).on("click", ".jsEditItemBtn", function (event) {
            event.preventDefault();
            $("#editItemModal").remove();

            //
            const recordId = $(this).closest("tr").data("key")

            var modalHtml = `
                <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" data-backdrop="static">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addItemModalLabel">Edit Item</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body jsModalBody">
                                <p class="alert text-center">Please wait, while we are generating view.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary hidden" id="updateItemBtn">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $("body").append(modalHtml);
            $("#editItemModal").modal('show');
            loadItemDetails(recordId)
        });

        //
        $(document).on("click", "#updateItemBtn", function (event) {
            //
            event.preventDefault();
            const title = $(`#text_title`).val();
            if (title.trim() === "") {
                alertify.alert("Item title cannot be empty");
                return false;
            }
            var itemDescription = CKEDITOR.instances[`textarea_edit`].getData();
            if (itemDescription.trim() === "") {
                alertify.alert("Item description cannot be empty");
                return false;
            }
            //
            const recordId = $("#itemId").val();
            //
            $(".jsModalBody").append('<div class="loader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; justify-content: center; align-items: center;"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');

            // Save item to database
            $.ajax({
                url: '<?= base_url('manage_admin/compliance_safety/incident_types_item'); ?>/' + recordId + '/update',
                type: 'POST',
                data: {
                    title: title,
                    description: itemDescription,
                },
                success: function (response) {
                    // Hide loader
                    $(".loader").remove();
                    //
                    alertify.success(response.message);
                    // Append item to table
                    $(`.jsItemRow[data-key='${recordId}']`).find("td:eq(1)").html(title);
                    $(`.jsItemRow[data-key='${recordId}']`).find("td:eq(2)").html(itemDescription);
                    // Hide modal
                    $("#editItemModal").modal('hide');
                },
                error: function () {
                    // Hide loader
                    $(".loader").remove();
                    alertify.error('Error occurred while updating item');
                }
            });
        });

        //
        function generateHtml() {
            var randomNumber = Math.floor(Math.random() * 1000000);
            var newItem = `
                    <div class="row jsItemRow" data-key="${randomNumber}">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="">
                                <label for="item_title">Item Title</label>
                                <input name="item_title[]" id="text_${randomNumber}" class="hr-form-fileds" />
                            </div>
                        </div>
                    </div>
                `;
            newItem += `
                    <div class="row jsItemRow" data-key="${randomNumber}">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="">
                                <label for="item_description">Item Description</label>
                                <textarea name="item_description[]" id="textarea_${randomNumber}" class="hr-form-fileds" rows="4" cols="50"></textarea>
                            </div>
                        </div>
                    </div>
                `;
            newItem += `
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div style="background-color: lightyellow; padding: 10px; margin-top: 10px;">
                            <strong>{{input}}</strong>
                            <strong>{{checkbox}}</strong>
                        </div>
                    </div>
                </div>
            `;

            return {
                html: newItem,
                index: randomNumber
            };
        }

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
        //
        function loadItemDetails(recordId) {
            $.ajax({
                url: '<?= base_url('manage_admin/compliance_safety/incident_types_item'); ?>/' + recordId,
                type: 'get',
                success: function (response) {
                    // Hide loader
                    $(".jsModalBody").html(response.view);
                    $("#updateItemBtn").removeClass("hidden")
                    removeAllCKEditor("textarea_edit");
                },
                error: function () {
                    // Hide loader
                    alertify.error('Error occurred while fetching the item');
                }
            });
        }
    });

    function removeAllCKEditor(incomingId) {

        if (CKEDITOR.instances[incomingId]) {
            console.log("destroying instance: " + incomingId);
            CKEDITOR.instances[incomingId].destroy(true);
        }
        setTimeout(function () {
            console.log("creating instance: " + incomingId);
            CKEDITOR.replace(incomingId);
        }, 1000);
    }

    $(document).ready(function () {
        CKEDITOR.replace('description');
    });
</script>