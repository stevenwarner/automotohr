<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th class="col-xs-2">Title</th>
                        <th class="col-xs-4">Address</th>
                        <th class="col-xs-2">Phone</th>
                        <th class="col-xs-2">Fax</th>
                        <th class="col-xs-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($office_locations)) { ?>
                        <?php foreach ($office_locations  as $office_location) { ?>
                            <tr>
                                <td>
                                    <?php echo $office_location['location_title']; ?>
                                </td>
                                <td>
                                    <?php echo $office_location['location_address']; ?>
                                </td>
                                <td>
                                    <?php echo $office_location['location_telephone']; ?>
                                </td>
                                <td>
                                    <?php echo $office_location['location_fax']; ?>
                                </td>
                                <td>

                                    <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-info btn-sm pencil_useful_link" data-original-title="Update Office Location" onclick="func_edit_location(<?php echo $office_location['sid']; ?>, <?php echo $company_sid; ?>);">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <?php if ($office_location['is_primary'] != 1) { ?>

                                        <button class="btn btn-success jsMakeOLPrimary" data-id="<?= $office_location['sid']; ?>" title="Make this location primary" data-placement="top">
                                            <i class="fa fa-shield" aria-hidden="true"></i>
                                        </button>
                                        <div class="trash_useful_link">
                                            <form id="form_delete_office_location_<?php echo $office_location['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_office_location" />
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <input type="hidden" id="office_location_sid" name="office_location_sid" value="<?php echo $office_location['sid']; ?>" />
                                                <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-sm" data-original-title="Delete Location" onclick="func_delete_office_location(<?php echo $office_location['sid']; ?>);"><i class="fa fa-trash"></i></a>
                                            </form>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">
                                <span class="no-data">No Office Locations</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr />
<div class="row" id="add_new_location_form">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                Add New Office Location
            </div>
            <div class="hr-innerpadding">
                <form id="func_insert_new_office_location" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="insert_new_office_location" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100">
                                <?php $field_id = 'location_title'; ?>
                                <?php echo form_label('Title: <span class="required">*</span>', $field_id); ?>
                                <?php echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>
                            <li class="form-col-100 autoheight">
                                <?php $field_id = 'location_address'; ?>
                                <?php echo form_label('Address: <span class="required">*</span>', $field_id); ?>
                                <?php echo form_textarea($field_id, '', 'class="invoice-fields autoheight" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>
                            <li class="form-col-100">
                                <?php $field_id = 'location_telephone'; ?>
                                <?php echo form_label('Phone:', $field_id); ?>
                                <?php echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>
                            <li class="form-col-100">
                                <?php $field_id = 'location_fax'; ?>
                                <?php echo form_label('Fax:', $field_id); ?>
                                <?php echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>
                        </ul>
                        <button type="submit" class="btn btn-success">Add Office Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Office Location</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_location_sid" name="edit_location_sid" value="" />
                <div class="universal-form-style-v2">
                    <ul>
                        <li class="form-col-100">
                            <label>Title:<span class="staric">*</span></label>
                            <input type="text" name="edit_location_title" id="edit_location_title" class="invoice-fields">
                            <span id="edit_location_title_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100 autoheight">
                            <label>Address:<span class="staric">*</span></label>
                            <textarea name="edit_location_address" class="invoice-fields autoheight" cols="40" rows="10" id="edit_location_address"></textarea>
                            <span id="edit_location_address_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100">
                            <label>Phone:</label>
                            <input type="text" name="edit_location_telephone" id="edit_location_telephone" class="invoice-fields">
                            <span id="edit_location_telephone_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100">
                            <label>Fax:</label>
                            <input type="text" name="edit_location_fax" id="edit_location_fax" class="invoice-fields">
                            <span id="edit_location_fax_error" class="text-danger"></span>
                        </li>
                    </ul>
                    <button class="btn btn-success" id="edit_location_button">Update Office Location</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function func_delete_office_location(office_location_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Office Location?',
            function() {
                $('#form_delete_office_location_' + office_location_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    $(document).ready(function() {
        $('#edit_location_title').keyup(function() {
            $('#edit_location_titlee_error').html('');
        });

        $('#edit_location_address').keyup(function() {
            $('#edit_location_address_error').html('');
        });

        $('#edit_location_telephone').keyup(function() {
            $('#edit_location_telephone_error').html('');
        });

        $('#edit_location_fax').keyup(function() {
            $('#edit_location_faxerror').html('');
        });
        //$('#add_new_location_form').hide();
        $("#func_insert_new_office_location").validate({
            ignore: [],
            rules: {
                location_title: {
                    required: true
                },
                location_address: {
                    required: true
                },
                location_telephone: {
                    required: false
                },
                location_fax: {
                    required: false
                }
            },
            messages: {
                location_title: {
                    required: 'Title is required.'
                },
                location_address: {
                    required: 'Address is required.'
                },
                location_telephone: {
                    required: 'Phone is required'
                },
                location_fax: {
                    required: 'Fax is required'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });

    function func_edit_location(location_sid, company_sid) {
        var myurl = "<?= base_url() ?>onboarding/getOfficeLocation/" + location_sid + "/" + company_sid;

        $.ajax({
            type: "GET",
            url: myurl,
            async: false,
            success: function(data) {
                var obj = jQuery.parseJSON(data);
                var location_sid = obj.location_sid;
                var location_title = obj.location_title;
                var location_address = obj.location_address;
                var location_telephone = obj.location_telephone;
                var location_fax = obj.location_fax;
                $('#edit_location_sid').val(location_sid);
                $('#edit_location_title').val(location_title);
                $('#edit_location_address').val(location_address);
                $('#edit_location_telephone').val(location_telephone);
                $('#edit_location_fax').val(location_fax);
            },
            error: function(data) {

            }
        });
        $('#myModal').modal('show');
    }

    $("#edit_location_button").click(function() {
        var location_title = $('#edit_location_title').val();
        var location_address = $('#edit_location_address').val();
        var location_telephone = $('#edit_location_telephone').val();
        var location_fax = $('#edit_location_fax').val();

        if (location_title === "" && location_address === "") {
            $('#edit_location_title_error').html('<strong>Title is required.</strong>');
            $('#edit_location_address_error').html('<strong>Address is required.</strong>');
        } else if (location_title === "" || location_address === "") {
            if (location_title === "") {
                $('#edit_location_title_error').html('<strong>Title is required.</strong>');
            }
            if (location_address === "") {
                $('#edit_location_address_error').html('<strong>Address is required.</strong>');
            }
        } else {
            var id = $('#edit_location_sid').val();
            var title = location_title;
            var address = location_address;
            var phone = location_telephone;
            var fax = location_fax;
            var myurl = "<?= base_url() ?>onboarding/updateLocation";

            $.ajax({
                type: 'POST',
                data: {
                    sid: id,
                    location_title: title,
                    location_address: address,
                    location_telephone: phone,
                    location_fax: fax
                },
                url: myurl,
                success: function(data) {
                    location.reload();
                    alertify.success('Link Update  Successfully');
                },
                error: function() {

                }
            });
        }
    });

    //
    $(function() {
        //
        var xhr = null;
        //
        $('.jsMakeOLPrimary').click(function(e) {
            //
            e.preventDefault();
            //
            var obj = {
                rowId: $(this).data('id'),
                companyId: <?= $company_sid; ?>
            };
            //
            return alertify.confirm(
                'This action will make the office location primary. <br> Would you like to continue?',
                function() {
                    makeLocationPrimary(obj);
                }
            );
        });

        function makeLocationPrimary(obj) {
            //
            xhr = $
                .post("<?= base_url('onboarding/office_location'); ?>", obj)
                .done(function(xhr) {
                    xhr = null;
                    return alertify.alert(
                        'Success',
                        'You have successfully updated the primary location.',
                        function() {
                            window.location.reload();
                        }
                    );
                });
        }
    });
</script>