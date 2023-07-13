<?php
$states = [];
$jDependents = [];
?>



<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <div class="form-group" style="margin-top: 20px;">
            <label class="control control--radio">
                <input type="radio" class="havedependents" name="havedependents" value="1" checked="true">
                <?php echo $dependents_yes_text; ?> &nbsp; <div class="control__indicator"></div> </label><br>
            <label class="control control--radio">
                <input type="radio" class="havedependents" name="havedependents" value="0">
                <?php echo $dependents_no_text ?> &nbsp; <div class="control__indicator"></div>
            </label>

        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12">
        <span class="pull-right">
            <a href="javascript:void(0)" class="btn btn-info js-add"><i class="fa fa-plus" style="font-size: 12px;"></i> Add New</a>
            <a href="javascript:void(0)" class="btn btn-info js-add-save">Save</a>

        </span>
    </div>
</div>

<br />

<div id="dependents">
    <div class="table-responsive table-outer">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td class="col-lg-2">Name</td>
                    <td class="col-lg-2">Phone No.</td>
                    <td class="col-lg-4">Address</td>
                    <td class="col-lg-2">Relationship</td>
                    <td class="text-center col-lg-2" colspan="2">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dependents)) { ?>
                    <?php foreach ($dependents as $dependent) {
                        $v = @unserialize($dependent['dependant_details']);
                        $jDependents[$dependent['sid']] = $v; ?>
                        <tr data-id="<?= $dependent['sid']; ?>">
                            <td><?php echo $v['first_name'] . ' ' . $v['last_name']; ?></td>
                            <td><?php echo $v['phone']; ?></td>
                            <td><?php echo $v['address']; ?></td>
                            <td><?php echo $v['relationship']; ?></td>
                            <td class="text-center">
                                <a href="javascript:void(0)" class="btn btn-info btn-block js-edit">Edit</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="text-center">No dependent information found!</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Dependent</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>First Name <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsFirstName" placeholder="John" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Last Name <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsLastName" placeholder="Smith" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Address <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsAddress" placeholder="P.O. BOX 123" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Address Line 2</label>
                                <div>
                                    <input type="text" class="form-control" id="jsAddress2" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Country</label>
                                <div>
                                    <select class="jsSelect" id="jsCountry">
                                        <option value="0">Select Country</option>
                                        <?php
                                        if ($countries && count($countries)) {
                                            foreach ($countries as $key => $value) {
                                                $states[$key] = $value['States']; ?>
                                                <option value="<?= $key; ?>"><?= $value['Name']; ?></option>
                                        <?php   }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>State</label>
                                <div>
                                    <select class="jsSelect" id="jsState">
                                        <option value="0">Select Country First</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>City</label>
                                <div>
                                    <input type="text" class="form-control" id="jsCity" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Postal Code2</label>
                                <div>
                                    <input type="text" class="form-control" id="jsPostalCode" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Phone <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsPhone" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Birth Date</label>
                                <div>
                                    <input type="text" class="form-control jsDatePicker" id="jsBirthDate" readonly="true" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Relationship <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsRelationship" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>SSN#</label>
                                <div>
                                    <input type="text" class="form-control" id="jsSSN" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Gender</label>
                                <div>
                                    <select class="jsSelect" id="jsGender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <br>
                                <label class="control control--checkbox">
                                    Add Family Members
                                    <input type="checkbox" name="" value="1" id="jsFamilyMember" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="csModalFooterLoader jsDependentLoader">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info jsSaveBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelEditId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Dependent</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>First Name <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsEFirstName" placeholder="John" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Last Name <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsELastName" placeholder="Smith" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Address <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsEAddress" placeholder="P.O. BOX 123" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Address Line 2</label>
                                <div>
                                    <input type="text" class="form-control" id="jsEAddress2" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Country</label>
                                <div>
                                    <select class="jsSelect" id="jsECountry">
                                        <option value="0">Select Country</option>
                                        <?php
                                        if ($countries && count($countries)) {
                                            foreach ($countries as $key => $value) {
                                                $states[$key] = $value['States']; ?>
                                                <option value="<?= $key; ?>"><?= $value['Name']; ?></option>
                                        <?php   }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>State</label>
                                <div>
                                    <select class="jsSelect" id="jsEState">
                                        <option value="0">Select Country First</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>City</label>
                                <div>
                                    <input type="text" class="form-control" id="jsECity" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Postal Code2</label>
                                <div>
                                    <input type="text" class="form-control" id="jsEPostalCode" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Phone <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsEPhone" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Birth Date</label>
                                <div>
                                    <input type="text" class="form-control jsDatePicker" id="jsEBirthDate" readonly="true" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Relationship <span class="cs-required">*</span></label>
                                <div>
                                    <input type="text" class="form-control" id="jsERelationship" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>SSN#</label>
                                <div>
                                    <input type="text" class="form-control" id="jsESSN" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>Gender</label>
                                <div>
                                    <select class="jsSelect" id="jsEGender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <br>
                                <label class="control control--checkbox">
                                    Add Family Members
                                    <input type="checkbox" name="" value="1" id="jsEFamilyMember" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="csModalFooterLoader jsDependentLoader">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info jsUpdateBtn">Update</button>
            </div>
        </div>
    </div>
</div>


<!--  -->
<script>
    $(function() {
        //
        let
            userType = "<?= $users_type; ?>",
            userSid = "<?= $users_sid; ?>",
            companySid = "<?= $company_sid; ?>",
            states = <?= json_encode($states); ?>,
            dependents = <?= json_encode($jDependents); ?>,
            activeDependent = {};
        //
        let activeSid = 0;
        //
        $('.jsSelect').select2();
        $('.jsDatePicker').datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });
        //
        $(document).on('change', '#jsCountry', function() {
            let l = states[$(this).val()];
            //
            if (l === undefined) {
                $('#jsState').html('<option value="0">Select Country First</option>');
                return;
            }
            //
            let options = '';
            //
            $.each(l, (i, v) => {
                options += `<option value="${i}">${v.Name}</option>`;
            });
            $('#jsState').html(options);
        });
        //
        $(document).on('change', '#jsECountry', function() {
            let l = states[$(this).val()];
            //
            if (l === undefined) {
                $('#jsEState').html('<option value="0">Select Country First</option>');
                return;
            }
            //
            let options = '';
            //
            $.each(l, (i, v) => {
                options += `<option value="${i}">${v.Name}</option>`;
            });
            $('#jsEState').html(options);
            //
            if (activeDependent.hasOwnProperty('Location_State')) $('#jsEState').select2('val', activeDependent['Location_State']);
        });

        //
        $('.js-add').click((e) => {
            e.preventDefault();
            activeSid = 0;
            $('#modelId').modal('show');
        });

        //
        $('.js-edit').click(function(e) {
            e.preventDefault();
            activeSid = $(this).closest('tr').data('id');
            //
            activeDependent = dependents[activeSid];
            //
            $('#jsEFirstName').val(activeDependent.first_name);
            $('#jsELastName').val(activeDependent.last_name);
            $('#jsEAddress').val(activeDependent.address);
            $('#jsEAddress2').val(activeDependent.address_line);
            $('#jsECountry').select2('val', activeDependent.Location_Country);
            $('#jsECountry').trigger('change');
            $('#jsECity').val(activeDependent.city);
            $('#jsEPostalCode').val(activeDependent.postal_code);
            $('#jsEPhone').val(activeDependent.phone);
            $('#jsEBirthDate').val(activeDependent.birth_date);
            $('#jsERelationship').val(activeDependent.relationship);
            $('#jsESSN').val(activeDependent.ssn);
            $('#jsEGender').val(activeDependent.gender);
            $('#jsEFamilyMember').prop('checked', (activeDependent.family_member == null || activeDependent.family_member == 'off' ? false : true));
            $('#modelEditId').modal('show');
        });

        //
        $('.jsSaveBtn').click(function(e) {
            //
            e.preventDefault();
            //
            let obj = {};
            //
            obj.firstName = $('#jsFirstName').val().trim();
            obj.lastName = $('#jsLastName').val().trim();
            obj.address = $('#jsAddress').val().trim();
            obj.address2 = $('#jsAddress2').val().trim();
            obj.country = $('#jsCountry').val();
            obj.state = $('#jsState').val();
            obj.city = $('#jsCity').val();
            obj.postalCode = $('#jsPostalCode').val();
            obj.phone = $('#jsPhone').val();
            obj.birthDate = $('#jsBirthDate').val();
            obj.relationship = $('#jsRelationship').val();
            obj.ssn = $('#jsSSN').val();
            obj.gender = $('#jsGender').val();
            obj.familyMember = $('#jsFamilyMember').prop('checked') == true ? 'on' : 'off';
            //
            let nr = new RegExp('^[a-zA-Z0-9\- ]+$', 'g');
            let pr = new RegExp('^[0-9\-+ ]+$', 'g');
            let rr = new RegExp("^[a-zA-Z\-#,':;. ]+$", 'g');
            nr.lastIndex = 0;
            //
            if (obj.firstName == '') {
                alertify.alert('WARNING!', 'First name is required.', () => {});
                return;
            }
            //
            if (nr.test(obj.firstName) === false) {
                alertify.alert('WARNING!', 'First name is not valid.', () => {});
                return;
            }
            nr.lastIndex = 0;
            //
            if (obj.lastName == '') {
                alertify.alert('WARNING!', 'Last name is required.', () => {});
                return;
            }
            //
            if (nr.test(obj.lastName) === false) {
                alertify.alert('WARNING!', 'Last name is not valid.', () => {});
                return;
            }
            nr.lastIndex = 0;
            //
            if (obj.address == '') {
                alertify.alert('WARNING!', 'Address is required.', () => {});
                return;
            }
            //
            if (nr.test(obj.address) === false) {
                alertify.alert('WARNING!', 'Address is not valid.', () => {});
                return;
            }
            //
            if (obj.phone == '') {
                alertify.alert('WARNING!', 'Phone is required.', () => {});
                return;
            }
            //
            if (pr.test(obj.phone) === false) {
                alertify.alert('WARNING!', 'Phone is not valid.', () => {});
                return;
            }
            //
            if (obj.relationship == '') {
                alertify.alert('WARNING!', 'Relationship is required.', () => {});
                return;
            }
            //
            if (rr.test(obj.relationship) === false) {
                alertify.alert('WARNING!', 'Relationship is not valid.', () => {});
                return;
            }
            //
            obj.userSid = userSid;
            obj.userType = userType;
            obj.companySid = companySid;
            //
            $('.jsDependentLoader').show();
            //
            $.post("<?= base_url('onboarding/dependent_add'); ?>", obj, (resp) => {
                $('#modelId').modal('hide');
                alertify.alert('SUCCESS!', 'You have succesfully added a dependent.', () => {
                    window.location.reload();
                });
            });
        });

        //
        $('.jsUpdateBtn').click(function(e) {
            //
            e.preventDefault();
            //
            let obj = {};
            //
            obj.firstName = $('#jsEFirstName').val().trim();
            obj.lastName = $('#jsELastName').val().trim();
            obj.address = $('#jsEAddress').val().trim();
            obj.address2 = $('#jsEAddress2').val().trim();
            obj.country = $('#jsECountry').val();
            obj.state = $('#jsEState').val();
            obj.city = $('#jsECity').val();
            obj.postalCode = $('#jsEPostalCode').val();
            obj.phone = $('#jsEPhone').val();
            obj.birthDate = $('#jsEBirthDate').val();
            obj.relationship = $('#jsERelationship').val();
            obj.ssn = $('#jsESSN').val();
            obj.gender = $('#jsEGender').val();
            obj.familyMember = $('#jsEFamilyMember').prop('checked') == true ? 'on' : 'off';
            //
            let nr = new RegExp('^[a-zA-Z0-9\- ]+$', 'g');
            let pr = new RegExp('^[0-9\-+ ]+$', 'g');
            let rr = new RegExp("^[a-zA-Z\-#,':;. ]+$", 'g');
            nr.lastIndex = 0;
            //
            if (obj.firstName == '') {
                alertify.alert('WARNING!', 'First name is required.', () => {});
                return;
            }
            //
            if (nr.test(obj.firstName) === false) {
                alertify.alert('WARNING!', 'First name is not valid.', () => {});
                return;
            }
            nr.lastIndex = 0;
            //
            if (obj.lastName == '') {
                alertify.alert('WARNING!', 'Last name is required.', () => {});
                return;
            }
            //
            if (nr.test(obj.lastName) === false) {
                alertify.alert('WARNING!', 'Last name is not valid.', () => {});
                return;
            }
            nr.lastIndex = 0;
            //
            if (obj.address == '') {
                alertify.alert('WARNING!', 'Address is required.', () => {});
                return;
            }
            //
            if (nr.test(obj.address) === false) {
                alertify.alert('WARNING!', 'Address is not valid.', () => {});
                return;
            }
            //
            if (obj.phone == '') {
                alertify.alert('WARNING!', 'Phone is required.', () => {});
                return;
            }
            //
            if (pr.test(obj.phone) === false) {
                alertify.alert('WARNING!', 'Phone is not valid.', () => {});
                return;
            }
            //
            if (obj.relationship == '') {
                alertify.alert('WARNING!', 'Relationship is required.', () => {});
                return;
            }
            //
            if (rr.test(obj.relationship) === false) {
                alertify.alert('WARNING!', 'Relationship is not valid.', () => {});
                return;
            }
            //
            obj.userSid = userSid;
            obj.userType = userType;
            obj.companySid = companySid;
            obj.sid = activeSid;
            //
            $('.jsDependentLoader').show();
            //
            $.post("<?= base_url('onboarding/dependent_edit'); ?>", obj, (resp) => {
                $('#modelEditId').modal('hide');
                alertify.alert('SUCCESS!', 'You have succesfully edited a dependent.', () => {
                    window.location.reload();
                });
            });
        });
    });


    //
    $('.js-add-save').click(function(e) {
        //
        e.preventDefault();
        //
        let obj = {};
        //
        obj.userSid = '<?php echo $users_sid; ?>';
        obj.userType = '<?php echo $users_type; ?>';
        obj.companySid = '<?php echo $company_sid; ?>';
        //
        $('.jsDependentLoader').show();
        //
        $.post("<?= base_url('onboarding/dependent_add_blanck'); ?>", obj, (resp) => {
            $('#modelId').modal('hide');
            alertify.alert('SUCCESS!', 'Saved succesfully.', () => {
                window.location.reload();
            });
        });
    });

    $(document).ready(function() {
        <?php if (isDontHaveDependens($company_sid, $users_sid, $users_type) > 0) { ?>
            $('.js-add').hide();
            $('.js-add-save').show();
            $("input[name=havedependents][value='0']").prop("checked", true);
            $("input[name=havedependents][value='1']").prop("checked", false);
        <?php  } else { ?>
            $('.js-add').show();
            $('.js-add-save').hide();
            $("input[name=havedependents][value='1']").prop("checked", true);
            $("input[name=havedependents][value='0']").prop("checked", false);
        <?php } ?>
    });

    $(".havedependents").click(function() {
        if ($(this).val() == '1') {
            $('.js-add').show();
            $('.js-add-save').hide();
        } else {
            $('.js-add').hide();
            $('.js-add-save').show();
        }
    });
</script>