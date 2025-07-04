<?php 
    $states = [];
    $eOBJ = [];
?>
<div class="row">
    <div class="col-sm-12">
        <span class="pull-right">
            <a href="javascript:void(0)" class="btn btn-info js-add"><i class="fa fa-plus"
                    style="font-size: 12px;"></i> Add New</a>
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
                    <td class="col-lg-2">Relationship</td>
                    <td class="col-lg-2">Phone No.</td>
                    <td class="col-lg-4">Address</td>
                    <td class="col-lg-2">Priority</td>
                    <td class="text-center col-lg-2" colspan="2">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($emergencyContacts)) { ?>
                <?php   foreach($emergencyContacts as $v) {
                    $eOBJ[$v['sid']] = $v; ?>
                <tr data-id="<?=$v['sid'];?>">
                    <td><?php echo $v['first_name'] . ' ' . $v['last_name']; ?></td>
                    <td><?php echo $v['Relationship']; ?></td>
                    <td><?php echo $v['PhoneNumber']; ?></td>
                    <td><?php echo $v['Location_Address']; ?></td>
                    <td><?php echo $v['priority']; ?></td>
                    <td class="text-center">
                        <a href="javascript:void(0)" class="btn btn-info btn-block js-edit">Edit</a>
                    </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <td colspan="5" class="text-center">No emergency contacts information found!</td>
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
                <h5 class="modal-title">Add Emergency Contact</h5>
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
                                    <input type="text" class="form-control" id="jsLastName" placeholder="Doe" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>E-mail</label>
                                <div>
                                    <input type="text" class="form-control"  id="jsEmail" placeholder="john.doe@example.com" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Phone</label>
                                <div>
                                    <input type="text" class="form-control"  id="jsPhone"/>
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
                                            if($countries && count($countries)) {
                                                foreach($countries as $key => $value){ 
                                                    $states[$key] = $value['States'];?>
                                                <option value="<?=$key;?>"><?=$value['Name'];?></option>
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
                                <label>ZipCode</label>
                                <div>
                                    <input type="text" class="form-control" id="jsPostalCode" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label>Address</label>
                                <div>
                                    <input type="text" class="form-control" id="jsAddress" />
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
                                <label>Set Priority <span class="cs-required">*</span></label>
                                <div>
                                    <select class="jsSelect" id="jsPriority">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                   
                </form>
            </div>
            <div class="modal-footer">
                <div class="csModalFooterLoader jsEmergencyLoader">
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
                                    <input type="text" class="form-control" id="jsELastName" placeholder="Doe" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <label>E-mail</label>
                                <div>
                                    <input type="text" class="form-control"  id="jsEEmail" placeholder="john.doe@example.com" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label>Phone</label>
                                <div>
                                    <input type="text" class="form-control" id="jsEPhone"/>
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
                                            if($countries && count($countries)) {
                                                foreach($countries as $key => $value){ 
                                                    $states[$key] = $value['States'];?>
                                                <option value="<?=$key;?>"><?=$value['Name'];?></option>
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
                                <label>ZipCode</label>
                                <div>
                                    <input type="text" class="form-control" id="jsEPostalCode" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label>Address</label>
                                <div>
                                    <input type="text" class="form-control" id="jsEAddress" />
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
                                <label>Set Priority <span class="cs-required">*</span></label>
                                <div>
                                <select class="jsSelect" id="jsEPriority">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="csModalFooterLoader jsEmergencyLoader">
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
    $(function(){
        //
        let 
        userType = "<?=$users_type;?>",
        userSid = "<?=$users_sid;?>",
        companySid = "<?=$company_sid;?>",
        states = <?=json_encode($states);?>,
        eOBJ = <?=json_encode($eOBJ);?>,
        ae = {};
        //
        let activeSid = 0;
        //
        $('.jsSelect').select2();
        $('.jsDatePicker').datepicker({
            changeMonth: true,
            changeYear: true,
        });
        //
        $(document).on('change', '#jsCountry', function(){
            let l = states[$(this).val()];
            //
            if(l === undefined){
                $('#jsState').html('<option value="0">Select Country First</option>');
                return;
            }
            //
            let options = '';
            //
            $.each(l, (i, v) => { options += `<option value="${i}">${v.Name}</option>`; });
            $('#jsState').html(options);
        });
        //
        $(document).on('change', '#jsECountry', function(){
            let l = states[$(this).val()];
            //
            if(l === undefined){
                $('#jsEState').html('<option value="0">Select Country First</option>');
                return;
            }
            //
            let options = '';
            //
            $.each(l, (i, v) => { options += `<option value="${i}">${v.Name}</option>`; });
            $('#jsEState').html(options);
            //
            if(ae.hasOwnProperty('Location_State') ) $('#jsEState').select2('val', ae['Location_State']);
        });

        //
        $('.js-add').click((e) => {
            e.preventDefault();
            activeSid = 0;
            $('#modelId').modal('show');
        });

        //
        $('.js-edit').click(function(e){
            //
            e.preventDefault();
            //
            activeSid = $(this).closest('tr').data('id');
            //
            ae = eOBJ[activeSid];
            //
            $('#jsEFirstName').val(ae.first_name);
            $('#jsELastName').val(ae.last_name);
            $('#jsEEmail').val(ae.email);
            $('#jsEPhone').val(ae.PhoneNumber);
            $('#jsECountry').select2('val', ae.Location_Country);
            $('#jsECountry').trigger('change');
            $('#jsECity').val(ae.Lcoation_City);
            $('#jsEPostalCode').val(ae.Location_ZipCode);
            $('#jsEAddress').val(ae.Location_Address);
            $('#jsERelationship').val(ae.Relationship);
            $('#jsEPriority').val(ae.priority);
            //
            $('#modelEditId').modal('show');
        });

        //
        $('.jsSaveBtn').click(function(e){
            //
            e.preventDefault();
            //
            let obj = {};
            //
            obj.firstName = $('#jsFirstName').val().trim();
            obj.lastName = $('#jsLastName').val().trim();
            obj.email = $('#jsEmail').val().trim();
            obj.phone = $('#jsPhone').val();
            obj.country = $('#jsCountry').val();
            obj.state = $('#jsState').val();
            obj.city = $('#jsCity').val();
            obj.postalCode = $('#jsPostalCode').val();
            obj.address = $('#jsAddress').val().trim();
            obj.relationship = $('#jsRelationship').val();
            obj.priority = $('#jsPriority').val();
            //
            let nr =  new RegExp('^[a-zA-Z0-9\- ]+$', 'g');
            let pr =  new RegExp('^[0-9\-+ ]+$', 'g');
            let rr =  new RegExp("^[a-zA-Z\-#,':;. ]+$", 'g');
            let er =  /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            nr.lastIndex = 0;
            //
            if(obj.firstName == ''){
                alertify.alert('WARNING!', 'First name is required.', () => {});
                return;
            }
            //
            if(nr.test(obj.firstName) === false ){
                alertify.alert('WARNING!', 'First name is not valid.', () => {});
                return;
            }
            nr.lastIndex = 0;
            //
            if(obj.lastName == ''){
                alertify.alert('WARNING!', 'Last name is required.', () => {});
                return;
            }
            //
            if(nr.test(obj.lastName) === false ){
                alertify.alert('WARNING!', 'Last name is not valid.', () => {});
                return;
            }
            //
            // if(obj.email == ''){
            //     alertify.alert('WARNING!', 'Email is required.', () => {});
            //     return;
            // }
            //
            // if(er.test(obj.email) === false ){
            //     alertify.alert('WARNING!', 'Email is not valid.', () => {});
            //     return;
            // }
            //
            // if(obj.phone == ''){
            //     alertify.alert('WARNING!', 'Phone is required.', () => {});
            //     return;
            // }
            //
            // if(pr.test(obj.phone) === false){
            //     alertify.alert('WARNING!', 'Phone is not valid.', () => {});
            //     return;
            // }
            //
            if(obj.relationship == ''){
                alertify.alert('WARNING!', 'Relationship is required.', () => {});
                return;
            }
            //
            if(rr.test(obj.relationship) === false){
                alertify.alert('WARNING!', 'Relationship is not valid.', () => {});
                return;
            }
            //
            obj.userSid = userSid;
            obj.userType = userType;
            obj.companySid = companySid;
            //
            $('.jsEmergencyLoader').show();
            //
            $.post("<?=base_url('onboarding/emergency_contacts_add');?>", obj, (resp) => {
                $('#modelId').modal('hide');
                alertify.alert('SUCCESS!', 'You have succesfully added emergency contact.', () => {
                    window.location.reload();
                });
            });
        });
        
        //
        $('.jsUpdateBtn').click(function(e){
            //
            e.preventDefault();
            //
            let obj = {};
            //
            obj.firstName = $('#jsEFirstName').val().trim();
            obj.lastName = $('#jsELastName').val().trim();
            obj.email = $('#jsEEmail').val();
            obj.phone = $('#jsEPhone').val();
            obj.country = $('#jsECountry').val();
            obj.state = $('#jsEState').val();
            obj.city = $('#jsECity').val();
            obj.postalCode = $('#jsEPostalCode').val();
            obj.address = $('#jsEAddress').val().trim();
            obj.relationship = $('#jsERelationship').val();
            obj.priority = $('#jsEPriority').val();
            //
            let nr =  new RegExp('^[a-zA-Z0-9\- ]+$', 'g');
            let pr =  new RegExp('^[0-9\-+ ]+$', 'g');
            let rr =  new RegExp("^[a-zA-Z\-#,':;. ]+$", 'g');
            let er = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            nr.lastIndex = 0;
            er.lastIndex = 0;
            //
            if(obj.firstName == ''){
                alertify.alert('WARNING!', 'First name is required.', () => {});
                return;
            }
            //
            if(nr.test(obj.firstName) === false ){
                alertify.alert('WARNING!', 'First name is not valid.', () => {});
                return;
            }
            nr.lastIndex = 0;
            //
            if(obj.lastName == ''){
                alertify.alert('WARNING!', 'Last name is required.', () => {});
                return;
            }
            //
            if(nr.test(obj.lastName) === false ){
                alertify.alert('WARNING!', 'Last name is not valid.', () => {});
                return;
            }
            //
            // if(obj.email == ''){
            //     alertify.alert('WARNING!', 'Email is required.', () => {});
            //     return;
            // }
            //
            // if(er.test(obj.email) === false ){
            //     alertify.alert('WARNING!', 'Email is not valid.', () => {});
            //     return;
            // }
            //
            // if(obj.phone == ''){
            //     alertify.alert('WARNING!', 'Phone is required.', () => {});
            //     return;
            // }
            //
            // if(pr.test(obj.phone) === false){
            //     alertify.alert('WARNING!', 'Phone is not valid.', () => {});
            //     return;
            // }
            //
            if(obj.relationship == ''){
                alertify.alert('WARNING!', 'Relationship is required.', () => {});
                return;
            }
            //
            if(rr.test(obj.relationship) === false){
                alertify.alert('WARNING!', 'Relationship is not valid.', () => {});
                return;
            }
            //
            obj.userSid = userSid;
            obj.userType = userType;
            obj.companySid = companySid;
            obj.sid = activeSid;
            //
            $('.jsEmergencyLoader').show();
            //
            $.post("<?=base_url('onboarding/emergency_contacts_edit');?>", obj, (resp) => {
                $('#modelEditId').modal('hide');
                alertify.alert('SUCCESS!', 'You have succesfully edited emergency contact.', () => {
                    window.location.reload();
                });
            });
        });
    });
</script>