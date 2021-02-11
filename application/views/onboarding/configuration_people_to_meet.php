<?php //foreach ($employees as $key => $value) { echo $value;} die(); ?>
<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th class="col-xs-3">Employee Name</th>
                        <th class="col-xs-7">Notes</th>
                        <th class="col-xs-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($people)){ ?>
                        <?php foreach($people  as $person) { ?>
                            <tr>
                                <td>
                                    <?php echo ucwords($person['first_name'] . ' ' . $person['last_name']); ?>
                                </td>
                                <td>
                                    <?php echo $person['notes']; ?>
                                </td>

                                <td>
                                    <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-info btn-sm pencil_useful_link" data-original-title="Edit Person" onclick="func_edit_person_to_meet(<?php echo $person['sid']; ?>, <?php echo $company_sid; ?>);">
                                        <i class="fa fa-pencil"></i></a>
                                    <div class="trash_useful_link">
                                        <form id="form_delete_person_to_meet_<?php echo $person['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_person_to_meet_record" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="person_to_meet_sid" name="person_to_meet_sid" value="<?php echo $person['sid']; ?>" />
                                            <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-sm" data-original-title="Delete Person" onclick="func_delete_person_to_meet(<?php echo $person['sid']; ?>);"><i class="fa fa-trash"></i></a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">
                                <span class="no-data">No Links</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr />
<div class="row" id="add_new_person_to_meet_form">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                Add New Link
            </div>
            <div class="hr-innerpadding">
                <form id="func_insert_new_person_to_meet" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="insert_people_to_meet_record" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100">
                                <?php $field_id = 'employee_sid'; ?>
                                <?php echo form_label('Employee:', $field_id); ?>
                                <div class="hr-select-dropdown">
                                    <?php echo form_dropdown($field_id, $employees, array() , 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                    <span id="add_person_error" class="text-danger person_error"></span>
                                </div>
                            </li>
                            <li class="form-col-100 autoheight">
                                <?php $field_id = 'notes'; ?>
                                <?php echo form_label('Notes:', $field_id); ?>
                                <?php echo form_textarea($field_id, '', 'class="invoice-fields autoheight" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>

                        </ul>
                        <button type="button" class="btn btn-success" id='add_new_person_to_meet_dtn'>Add New Person</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div id="personModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Person</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_peopleToMeet_sid" name="edit_peopleToMeet_sid" value="" />
                <input type="hidden" id="edit_company_sid" name="edit_company_sid" value="" />
                <input type="hidden" id="edit_employee_arr" name="edit_employee_arr" value="<?php print_r($employees); ?>" />
                <div class="universal-form-style-v2">
                    <ul>
                        <li class="form-col-100">
                            <?php $field_id = 'edit_peopleToMeet_name'; ?>
                            <?php echo form_label('Employee:', $field_id); ?>
                            <div class="hr-select-dropdown">
                                <select name="edit_peopleToMeet_name" class="invoice-fields" id="edit_peopleToMeet_name">
                                    <?php //foreach ($employees as $key => $value) { ?>
                                        <!-- <option value="<?php //echo $key; ?>" id="option_<?php echo $key; ?>" ><?php //echo $value; ?></option> -->
                                    <?php //} ?>
                                </select>
                                <span id="edit_person_error" class="text-danger person_error"></span>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <label>Notes:</label>
                            <textarea name="edit_peopleToMeet_notes" class="invoice-fields autoheight" cols="40" rows="10" id="edit_peopleToMeet_notes"></textarea>
                        </li>
                    </ul>    
                    <button class="btn btn-success" id="edit_peopleToMeet_button">Edit Person</button>
                </div>       
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function func_delete_person_to_meet(person_to_meet_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Person?',
            function () {
                $('#form_delete_person_to_meet_' + person_to_meet_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }


    $(document).ready(function () {
        $('#edit_location_title').keyup(function ()  {
               $('#edit_location_titlee_error').html('');   
        });
    });

    function func_edit_person_to_meet(person_sid, company_sid){
        var myurl = "<?= base_url() ?>onboarding/getPerson/"+person_sid+"/"+company_sid;
        
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                var person_sid = obj.person_sid;
                var employer = obj.employer_sid;
                var notes = obj.notes;
                var company_sid = obj.company_sid;
                var selectValues = <?php echo json_encode($employees); ?>;
                $("#edit_peopleToMeet_name").prop("selected", false)
                $('#edit_peopleToMeet_sid').val(person_sid);

                $.each(selectValues, function(key, value) {   
                    $('#edit_peopleToMeet_name').append($("<option></option>").attr("value",key).text(value)); 
                });
                $('#edit_peopleToMeet_name option[value='+employer+']').attr("selected", "selected");
                $('#edit_peopleToMeet_notes').val(notes);
                $('#edit_company_sid').val(company_sid);
            },
            error: function (data) {

            }
        });

        $('#personModel').modal('show');
    }

    $( "#edit_peopleToMeet_button" ).click(function() {
        var record_sid = $('#edit_peopleToMeet_sid').val();
        var employer_sid = $('#edit_peopleToMeet_name').val();
        var notes = $('#edit_peopleToMeet_notes').val();
        var company_sid = $('#edit_company_sid').val();

        var myurl = "<?= base_url() ?>onboarding/checkPersonBeforeEdit/"+company_sid+"/"+employer_sid+"/"+record_sid;
        
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                if (data == true) {
                    $('#edit_person_error').html('<strong>Employer Already Selected</strong>');
                } else {
                    var myurl = "<?= base_url() ?>onboarding/updatePerson";
                    $.ajax({
                        type: 'POST',
                        data:{
                            sid:record_sid,
                            person_sid: employer_sid,
                            person_notes: notes,
                        },
                        url: myurl,
                        success: function(data){
                            location.reload();
                            alertify.success('Link Update  Successfully');
                        },
                        error: function(){

                        }
                    });
                }
            }
        }); 
    });

    $("#add_new_person_to_meet_dtn").click(function() {
        $('#add_person_error').html('');
        var employee_sid = $("#employee_sid").val();
        var company_sid = $("#company_sid").val();
        var myurl = "<?= base_url() ?>onboarding/checkPerson/"+company_sid+"/"+employee_sid;
        
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                if (data == true) {
                    $('#add_person_error').html('<strong>Employer Already Selected</strong>');
                } else {
                    $('#func_insert_new_person_to_meet').submit();
                }
            }
        });
        
    });
</script>