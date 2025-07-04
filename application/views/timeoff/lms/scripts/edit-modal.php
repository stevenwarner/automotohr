<script src="<?=base_url("assets/js/moment.min.js")?>"></script>
<link rel="stylesheet" href="<?=base_url('assets');?>/css/timeoffstyle.css">
<link rel="stylesheet" href="<?=base_url('assets');?>/css/fmla.css">
<style>
    .employee-info figure img{ border-radius: 3px !important; }
</style>
<!-- Modal -->
<div class="modal fade" id="js-timeoff-edit-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content model-content-custom">
            <!-- loader -->
            <div 
                class="loader js-iler"
                style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; width: 100%; background: rgba(255,255,255,.8); z-index: 9999 !important; display: none;"
            >
                <i 
                    class="fa fa-spinner fa-spin"
                    style="text-align: center; top: 50%; left: 50%; font-size: 40px; position: relative;"
                ></i>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modify a Time off Request </h4>
            </div>
            <div class="modal-body full-width modal-body-custom">
                <div class="tab-content">
                    <div id="" class="">
                        <br />
                        <!-- Section One -->
                        <div class="row">
                            <div class="col-sm-6 col-sm-12">
                                <div class="employee-info">
                                    <figure>
                                        <img src="<?php 
                                            if($employerData['profile_picture'] == ''){
                                                    echo base_url('assets/images/img-applicant.jpg');
                                                }else{
                                                    echo AWS_S3_BUCKET_URL.$employerData['profile_picture'];      
                                                }
                                         ?>" class="img-sqaure emp-image" />
                                    </figure>
                                    <div class="text cs-info-text">
                                        <h4><?=$employerData['first_name'].' '.$employerData['last_name'];?></h4>
                                        <p><a href="<?=base_url('employee_profile');?>/<?=$employerData['sid'];?>" target="_blank">Id: <?=$employerData['employee_number'] ? $employerData['employee_number'] : $employerData['sid'];?></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Types <span class="cs-required">*</span> </label>
                                    <select id="js-types-edit"></select>
                                </div>
                                <div class="form-group js-policy-box-edit" style="display: none;">
                                    <label>Policies <span class="cs-required">*</span> </label>
                                    <select id="js-policies-edit"></select>
                                </div>
                            </div>
                        </div>
                        <!-- Section Two -->
                        <div class="row">
                            <hr />
                            <div class="col-sm-12">
                                <!-- <div class="form-group">
                                    <label>Date <span class="cs-required">*</span></label>
                                </div> -->
                                <div class="row">
                                    <div class="col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="">Start Date <span class="cs-required">*</span></label>
                                            <input readonly="true" type="text" id="js-timeoff-start-date-edit" class="form-control js-request-start-date-edit" />
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="">End Date <span class="cs-required">*</span></label>
                                            <input readonly="true" type="text" id="js-timeoff-end-date-edit" class="form-control js-request-end-date-edit" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="js-timeoff-date-box-edit" style="display: none;">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Duration Type</th>
                                                        <th>Duration</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section Three -->
                        
                        <!-- Section Three -->
                        <div class="row js-fmla-wrap-edit" style="display: none;">
                            <hr />
                            <div class="col-sm-12 col-sm-12">
                                <label for="">Is this time off under FMLA? <span class="cs-required">*</span></label>
                                <br />
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-fmla-check-edit" name="fmla" value="no" type="radio" checked="true" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-fmla-check-edit" name="fmla" value="yes" type="radio" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;

                                <div class="js-fmla-box-edit" style="display: none;">
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Designation Notice
                                        <input class="js-fmla-type-check-edit" name="fmla_check" value="designation" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label> 
                                    &nbsp;<i title="Designation Notice" data-content="<?=FMLA_DESIGNATION;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Certification of Health Care Provider for Employee’s Serious Health Condition 
                                        <input class="js-fmla-type-check-edit" name="fmla_check" value="health" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Certification of Health Care" data-content="<?=FMLA_CERTIFICATION_OF_HEALTH;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Notice of Eligibility and Rights & Responsibilities 
                                        <input class="js-fmla-type-check-edit" name="fmla_check" value="medical" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Notice of Eligibility and Rights & Responsibilities" data-content="<?=FMLA_RIGHTS;?>" class="fa fa-question-circle js-popover"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-vacation-row-edit" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Emergency Contact Number</label>
                                    <input type="text" class="form-control" id="js-vacation-contact-number-edit" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="text" class="form-control" id="js-vacation-return-date-edit" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Alternate Temporary Address</label>
                                    <input type="text" class="form-control" id="js-vacation-address-edit" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-bereavement-row-edit" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Relationship</label>
                                    <input type="text" class="form-control" id="js-bereavement-relationship-edit" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="text" class="form-control" id="js-bereavement-return-date-edit" readonly="true" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-compensatory-row-edit" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation Date</label>
                                    <input type="text" class="form-control" id="js-compensatory-return-date-edit" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation Start Time</label>
                                    <input type="text" class="form-control" id="js-compensatory-start-time-edit" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation End Time</label>
                                    <input type="text" class="form-control" id="js-compensatory-end-time-edit" readonly="true" />
                                </div>
                            </div>
                        </div>
                        <!-- Section Four -->
                        <div class="row">
                            <hr />
                            <div class="col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea id="js-reason-edit"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="row m15">
                        <hr />
                        <div class="col-sm-12">
                            <h4>
                                Supporting documents
                                <span class="pull-right">
                                    <button class="btn btn-success js-timeoff-attachment" data-type="edit" style="background-color: #3554dc"><i class="fa fa-plus"></i>&nbsp; Add Document</button>
                                </span>
                            </h4>
                        </div>
                        <div class="col-sm-12">
                            <br />
                            <div class="reponsive">
                                <table class="table table-striped table-bordered" id="js-attachment-listing-edit">
                                    <thead>
                                        <tr>
                                            <th>Document Title</th>
                                            <th class="col-sm-3">Document Type</th>
                                            <th class="col-sm-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="js-no-records-edit">
                                            <td colspan="3">
                                                <p class="alert alert-info text-center">You haven't attached any documents yet.</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info btn-5" id="js-load-btn-edit" style="display: none;">Updating...</button>
                <button type="submit" class="btn btn-info btn-5 js-btn-edit" id="js-update-edit-btn">Update Request</button>
                <input type="hidden" id="js-update-edit-sid" />
                <button type="button" class="btn btn-info btn-5 js-btn-edit" data-dismiss="modal" id="js-cancel-btn-edit">Cancel</button>
            </div>
        </div>
  </div>
</div>

<script type="text/javascript">

    $(function(){
        var
        xhr = null,
        holidayDates = <?=json_encode($holidayDates);?>,
        timeOffDays = <?=json_encode($timeOffDays);?>,
        fmla = {};
        //
        $('#js-policies-edit').select2();
        //
        CKEDITOR.replace('js-reason-edit');

        var offdates = [];


        //
        if(holidayDates.length != 0){
            for(obj in holidayDates){
                if(holidayDates[obj]['work_on_holiday'] == 0) offdates.push(holidayDates[obj]['from_date']);
            }
        }
        
        var inObject = function(val, searchIn){
            for(obj in searchIn){
                if( searchIn[obj].diff >= 2 ){
                    if(searchIn[obj]['from_date'] <= val  && searchIn[obj]['to_date'] >= val) return searchIn[obj];
                }else{
                    if(searchIn[obj]['from_date'] == val) return searchIn[obj];
                }
            }
            return -1;
        };
        

        function unavailable(date) {
            var dmy = moment(date).format('MM-DD-YYYY');
            var t = inObject(dmy, holidayDates);
            if (t == -1) {
                return [true, ""];
            } else {
                if (t.work_on_holiday == 1) {
                    return [true, "set_disable_date_color", t.holiday_title];
                } else {
                    return [false, "", t.holiday_title];
                }
            }
        }

        $('.js-partial-check-edit').click(function(){
            if($(this).val() == 'yes')
            $('.js-note-box-edit').show();
            else
            $('.js-note-box-edit').hide();
        })

        $('#js-timeoff-date-edit').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));

        //
        $(document).on('keyup', '.js-number', function(){
            $(this).val(
                $(this).val().replace(/[^0-9]/, '')
            );
        });

        // Add process
        //
        $(document).on('change', '#js-policies-edit', function(e){
            //
            if( $(this).val() == 0 || $(this).val() == null) return;
            // Get single policy
            var policy = getPolicy( $(this).val() );
            if(policy['is_unlimited'] == 1) setPolicyTime(0, defaultHours, defaultMinutes);
            else{
                if(format == 'D:H:M') setPolicyTime(
                    policy['timeoff_breakdown']['active']['days'],
                    policy['timeoff_breakdown']['active']['hours'],
                    policy['timeoff_breakdown']['active']['minutes']
                );
                else if(format == 'D:H') setPolicyTime(
                    policy['timeoff_breakdown']['active']['days'],
                    0,
                    policy['timeoff_breakdown']['active']['minutes']
                );
                else if(format == 'H:M') setPolicyTime(
                    0,
                    policy['timeoff_breakdown']['active']['hours'],
                    policy['timeoff_breakdown']['active']['minutes']
                );
                else if(format == 'D') setPolicyTime(
                    policy['timeoff_breakdown']['active']['days'],
                    0,
                    0
                );
                else if(format == 'H') setPolicyTime(
                    0,
                    policy['timeoff_breakdown']['active']['hours'],
                    0
                );
                else setPolicyTime(
                    0,
                    0,
                    policy['timeoff_breakdown']['active']['minutes']
                );
            }
        });

        // Cancel TO from modal
        $(document).on('click', '#js-cancel-btn', function(){
           // resetModal
        });

        // Save TO from modal
        $(document).on('click', '#js-update-edit-btn', function(){
            var megaOBJ = {};
            megaOBJ.action = 'update_employee_timeoff_from_employee';
            megaOBJ.reason = CKEDITOR.instances['js-reason-edit'].getData().trim();
            megaOBJ.policyId = $('#js-policies-edit').val();
            megaOBJ.policyName = $('#js-policies-edit').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            megaOBJ.requestId = $('#js-update-edit-sid').val().trim();
            megaOBJ.companySid = <?=$companyData['sid'];?>;
            megaOBJ.employeeSid = <?=$employerData['sid'];?>;
            megaOBJ.employeeFullName = "<?=$employerData['first_name'].' '.$employerData['last_name'];?>";
            megaOBJ.employeeEmail = "<?=$employerData['email'];?>";
             //
            megaOBJ.startDate = $('#js-timeoff-start-date-edit').val();
            megaOBJ.endDate = $('#js-timeoff-end-date-edit').val();
            //
            if(megaOBJ.policyId == 0 || megaOBJ.policyId == null){
                alertify.alert('WARNING!', 'Please select a Policy to continue.');
                return;
            }
            //
            if(megaOBJ.startDate == ''){
                alertify.alert('WARNING!', 'Please select the start date.');
                return;
            }
            if(megaOBJ.endDate == ''){
                alertify.alert('WARNING!', 'Please select the end date.');
                return;
            }
            // Get days off
            var requestedDays = getRequestedDaysEdit();
            //
            if(requestedDays.error) return;
            //
            megaOBJ.requestedDays = requestedDays;
            //
            var t = getPolicy( megaOBJ.policyId );
            var checkBalance = (parseInt(t.actual_allowed_timeoff) + parseInt(getNegativeBalance(t))) - parseInt(t.consumed_timeoff);
            //
            if(t.is_unlimited == '0' && requestedDays.totalTime > checkBalance){
                alertify.alert('WARNING!', 'Requested time off can not be greater than allowed time.');
                return;
            }
            
            megaOBJ.slug = t['format'];
            megaOBJ.timeslot = defaultTimeslot;
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( t );
            // FMLA 
            megaOBJ.isFMLA = $('.js-fmla-check:checked').val();
            if(megaOBJ.isFMLA == 'yes' && Object.keys(fmla).length == 0){
                alertify.alert('WARNING!', 'Please fill the selected FMLA form.');
                return;   
            }
            // FMLA 
            megaOBJ.isFMLA = $('.js-fmla-check-edit:checked').val();
            if(megaOBJ.isFMLA == 'yes' && Object.keys(fmla).length == 0){
                alertify.alert('WARNING!', 'Please fill the selected FMLA form.');
                return;   
            }
            //
            if(megaOBJ.isFMLA == 'yes'){
                megaOBJ.isFMLA = fmla.type;
                megaOBJ.fmla = fmla;
            } else{
                 megaOBJ.isFMLA = null;   
                 megaOBJ.fmla = fmla;   
            }
           
            // Phase 3 code 
            megaOBJ.returnDate = '';
            megaOBJ.relationship = '';
            megaOBJ.temporaryAddress = '';
            megaOBJ.compensationEndTime = '';
            megaOBJ.compensationStartTime = '';
            megaOBJ.emergencyContactNumber = '';
            //
            switch ($('#js-types-edit').val().toLowerCase()) {
                case 'vacation':
                    megaOBJ.returnDate = $('#js-vacation-return-date-edit').val().trim();
                    megaOBJ.temporaryAddress = $('#js-vacation-address-edit').val().trim();
                    megaOBJ.emergencyContactNumber = $('#js-vacation-contact-number-edit').val().trim();
                break;

                case 'bereavement':
                    megaOBJ.returnDate = $('#js-bereavement-return-date-edit').val().trim();
                    megaOBJ.relationship = $('#js-bereavement-relationship-edit').val().trim();
                break;

                case 'compensatory/ in lieu time':
                    megaOBJ.returnDate = $('#js-compensatory-return-date-edit').val().trim();
                    megaOBJ.compensationStartTime = $('#js-compensatory-start-time-edit').val().trim();
                    megaOBJ.compensationEndTime = $('#js-compensatory-end-time-edit').val().trim();
                break;
            }
            //
            // Disable all fields in modal
            inLoader('show', 'edit');
            // Let's save the TO
            $.post(
                handlerURI,
                megaOBJ,
                function(resp){
                    //
                    inLoader('hide', 'edit');
                    $('#js-save-btn').val('SAVE');
                    // When an error occured
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response.replace(/{{TIMEOFFDDATE}}/, moment(megaOBJ.requestDate, 'YYYY-MM-DD').format('MM-DD-YYYY')));
                        return;
                    }
                    successMSG = resp.Response;
                    startDocumentUploadProcess(megaOBJ.requestId); 
                }
            );
        });

        //
        function startDocumentUploadProcess(
            requestId,
            attachmentId
        ){
            //
            if(attachedDocuments.length === 0 && attachmentId === undefined) {
                // On success
                alertify.alert('SUCCESS!', successMSG, function(){
                    window.location.reload();
                });
            }
            //
            var currentIndex = attachmentId === undefined ? 0 : attachmentId;
            //
            attachmentId = Object.keys(attachedDocuments)[currentIndex];
            //
            if(attachmentId === undefined){
                // On success
                alertify.alert('SUCCESS!', successMSG, function(){
                    window.location.reload();
                });
                return;
            }
            //
            currentIndex++;
            //
            if(attachedDocuments[attachmentId].type === 'generated'){
                //
                setTimeout(function(){
                    startDocumentUploadProcess(requestId, currentIndex);
                }, 1000);
                return;
            }
            //
            var formData = new FormData();
            //
            //
            var act = 'add_attachment_to_request';
            if(attachedDocuments[attachmentId].file.size === undefined){
                act = 'attach_document_to_request';
                formData.append('attachmentSid', attachmentId);
            }
            formData.append('action', act);
            formData.append('requestSid', requestId);
            formData.append('companySid', <?=$companyData['sid'];?>);
            formData.append('title', attachedDocuments[attachmentId]['title']);
            //
            formData.append(
                attachmentId,
                attachedDocuments[attachmentId]['file']
            );

            $.ajax({
                url: baseURI+'handler',
                data: formData,
                method: 'POST',
                processData: false,
                contentType: false
            }).done(function(resp){
                if(resp.Status === false){
                    // On success
                    alertify.alert('SUCCESS!', successMSG, function(){
                        window.location.reload();
                    });
                    return;
                }
                //
                setTimeout(function(){
                    startDocumentUploadProcess(requestId, currentIndex);
                }, 1000);
            });
        }

        // FMLAs
        $('.js-fmla-check-edit').click(function(e){
            if($(this).val() == 'yes') $('.js-fmla-box-edit').show();
            else{ $('.js-fmla-type-check-edit').prop('checked', false);  fmla = {}; $('.js-fmla-box').hide(); }
        });
        //
        $('.js-fmla-type-check-edit').click(function(e){
            $('.js-form-area').hide(0);
            //
            fmla = {};
            if($(this).val() !== 'designation'){
                //
                $('#js-timeoff-edit-modal').modal('toggle');
                $('body').addClass('modal-open');
                $('.js-fmla-employee-firstname').val("<?=$employerData['first_name'];?>");
                $('.js-fmla-employee-lastname').val("<?=$employerData['last_name'];?>");
                $('#js-fmla-modal-edit').find('.modal-title').text('FMLA - '+( $(this).closest('label').text() )+'');
                $('#js-fmla-modal-edit').find('div[data-type="'+( $(this).val() )+'"]').show();
                $('#js-fmla-modal-edit').modal('toggle');
            }else{
                fmla = {
                    type: 'designation'
                };
            }
            //
        });
        //
        $(document).on('hide.bs.modal', '#js-fmla-modal-edit',  function(){ 
            $('#js-timeoff-edit-modal').modal('show'); 
        });
        $('#js-timeoff-edit-modal').on('hide.bs.modal', function(){ $('body').removeClass('modal-open'); });
        //
        $(document).on('click', '#js-fmla-modal-edit .js-shift-page',function(){
            $('#js-fmla-modal-edit').modal('hide');
            $('#js-timeoff-edit-modal').modal('show');
        });
        //
        $(document).on('click','#js-fmla-modal-edit .js-fmla-save-button' ,function(){
            var type = $(this).data('type').toLowerCase().trim();

            if(type == 'health'){
                if($('#js-fmla-health-employee-firstname').val().trim() == ''){
                    alertify.alert('ERROR!', 'First name is required.');
                    return false;
                }
                if($('#js-fmla-health-employee-lastname').val().trim() == ''){
                    alertify.alert('ERROR!', 'Last name is required.');
                    return false;
                }
                //
                fmla = {
                    type: 'health',
                    employee:{
                        firstname: $('#js-fmla-health-employee-firstname').val().trim(),
                        middlename: $('#js-fmla-health-employee-middlename').val().trim(),
                        lastname: $('#js-fmla-health-employee-lastname').val().trim()
                    }
                };
                //
                $('#js-fmla-modal-edit').modal('hide');
            } else if(type == 'medical'){
                if(
                    $('#js-fmla-medical-childcare').prop('checked') === false &&
                    $('#js-fmla-medical-healthcondition').prop('checked') === false &&
                    $('#js-fmla-medical-care').prop('checked') === false &&
                    $('#js-fmla-medical-care-spouse').prop('checked') === false &&
                    $('#js-fmla-medical-care-child').prop('checked') === false &&
                    $('#js-fmla-medical-care-parent').prop('checked') === false &&
                    $('#js-fmla-medical-qualify').prop('checked') === false &&
                    $('#js-fmla-medical-qualify-spouse').prop('checked') === false &&
                    $('#js-fmla-medical-qualify-child').prop('checked') === false &&
                    $('#js-fmla-medical-qualify-parent').prop('checked') === false &&
                    $('#js-fmla-medical-other').prop('checked') === false &&
                    $('#js-fmla-medical-other-spouse').prop('checked') === false &&
                    $('#js-fmla-medical-other-child').prop('checked') === false &&
                    $('#js-fmla-medical-other-parent').prop('checked') === false &&
                    $('#js-fmla-medical-other-kin').prop('checked') === false
                ){
                    alertify.alert('ERROR!', 'Please select atleast one reason.');
                    return false;
                }
                //
                fmla = {
                    type: 'medical',
                    employee:{
                        childcare: $('#js-fmla-medical-childcare').prop('checked') == true ? 1 : 0,
                        healthcondition: $('#js-fmla-medical-healthcondition').prop('checked') == true ? 1 : 0,
                        care: $('#js-fmla-medical-care').prop('checked') == true ? 1 : 0,
                        carespouse: $('#js-fmla-medical-care-spouse').prop('checked') == true ? 1 : 0,
                        carechild: $('#js-fmla-medical-care-child').prop('checked') == true ? 1 : 0,
                        careparent: $('#js-fmla-medical-care-parent').prop('checked') == true ? 1 : 0,
                        qualify: $('#js-fmla-medical-qualify').prop('checked') == true ? 1 : 0,
                        qualifyspouse: $('#js-fmla-medical-qualify-spouse').prop('checked') == true ? 1 : 0,
                        qualifychild: $('#js-fmla-medical-qualify-child').prop('checked') == true ? 1 : 0,
                        qualifyparent: $('#js-fmla-medical-qualify-parent').prop('checked') == true ? 1 : 0,
                        other: $('#js-fmla-medical-other').prop('checked') == true ? 1 : 0,
                        otherspouse: $('#js-fmla-medical-other-spouse').prop('checked') == true ? 1 : 0,
                        otherchild: $('#js-fmla-medical-other-child').prop('checked') == true ? 1 : 0,
                        otherparent: $('#js-fmla-medical-other-parent').prop('checked') == true ? 1 : 0,
                        otherkin: $('#js-fmla-medical-other-kin').prop('checked') == true ? 1 : 0
                    }
                };
                //
                $('#js-fmla-modal-edit').modal('hide');
            }
        });
        //
        $('.js-popover').popover({
            html: true,
            trigger: 'hover',
            placement: 'right'
        });
        //
        $('#js-start-partial-time-edit').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });
        //
        $('#js-end-partial-time-edit').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15,
            onShow: function(d){
                this.setOptions({
                    minTime: $('#js-start-partial-time').val() ? $('#js-start-partial-time').val() : false
                });
            }
        });

        //
        $('#js-vacation-return-date-edit').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));
        //
        $('#js-bereavement-return-date-edit').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));
        //
        $('#js-compensatory-return-date-edit').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));

        //
        $('#js-compensatory-start-time-edit').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });

        $('#js-compensatory-end-time-edit').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15,
            onShow: function(d){
                this.setOptions({
                    minTime: $('#js-compensatory-start-time-edit').val() ? $('#js-compensatory-start-time-edit').val() : false
                });
            }
        });

        //
        $('#js-timeoff-start-date-edit').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1,
            onSelect: function(d){
                $('#js-timeoff-end-date-edit').datepicker('option', 'minDate', d);
                $('#js-timeoff-end-date-edit').val(d);

                remakeRangeRowsEdit();
            }
        });

        $('#js-timeoff-end-date-edit').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1,
            onShow: function(){
                $('#js-timeoff-end-date-edit').datepicker('option', 'minDate', d);
                $('#js-timeoff-end-date-edit').val(d);
            },
            onSelect: remakeRangeRowsEdit
        });

    })
    //
    function remakeRangeRowsEdit(data){
        var startDate = $('#js-timeoff-start-date-edit').val(),
        endDate = $('#js-timeoff-end-date-edit').val();
        //
        var d = {};
        //
        if(typeof(data) === 'object'){
            startDate = data.startDate;
            endDate = data.endDate;
            data.days.map(function(v,i){ d[v.date] = v; });
        }
        //
        $('#js-timeoff-date-box-edit').hide();
        $('#js-timeoff-date-box-edit tbody tr').remove();
        //
        if(startDate == '' || endDate == '') { return; }
        //
        startDate =  moment(startDate);
        endDate = moment(endDate);
        var diff = endDate.diff(startDate, 'days');
        //
        var rows = '';
        var i = 0,
        il = diff;
        for(i;i<=il;i++){
            var sd = moment(startDate).add(i, 'days');
            var ld = d[sd.format('MM-DD-YYYY')];
            if($.inArray(sd.format('MM-DD-YYYY'), offdates) === -1 && $.inArray( sd.format('dddd').toLowerCase(), timeOffDays) === -1){
                rows += '<tr data-id="'+( i )+'" data-date="'+( sd.format('MM-DD-YYYY') )+'">';
                rows += '    <th style="vertical-align: middle">'+( sd.format('MMMM Do, YYYY') )+'</th>';
                rows += '    <th style="vertical-align: middle">';
                rows += '        <div>';
                rows += '            <label class="control control--radio">';
                rows += '                Full Day';
                rows += '                <input type="radio" name="'+( i )+'_day_type_edit" value="fullday" '+( ld !== undefined && ld.partial == 'fullday' ? 'checked="true"' : ld == undefined ? 'checked="true"' : '' )+' />';
                rows += '                <span class="control__indicator"></span>';
                rows += '            </label>';
                rows += '            <label class="control control--radio">';
                rows += '                Partial Day';
                rows += '                <input type="radio" name="'+( i )+'_day_type_edit" value="partialday" '+( ld !== undefined && ld.partial != 'fullday' ? 'checked="true"' : '' )+' />';
                rows += '                <span class="control__indicator"></span>';
                rows += '            </label>';
                rows += '        </div>';
                rows += '    </th>';
                rows += '    <th>';
                rows += '        <div class="rowd" id="row_'+( i )+'_edit">';
                rows +=          setTimeViewEdit('#row_'+( i )+'', '-el-edit'+i, ld);
                rows += '        </div>';
                rows += '    </th>';
                rows += '</tr>';
                //
            }
        }
        //
        if(rows == '') return;
        //
        $('#js-timeoff-date-box-edit tbody').html(rows);
        $('#js-timeoff-date-box-edit').show();
    }

    //
    function setTimeViewEdit(target, prefix, data){
        var row = '';
        if(policies[0]['format'] == 'D:H:M'){
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.days : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.minutes : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'D:H'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.days : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'H:M'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.minutes : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }else if(policies[0]['format'] == 'H'){
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }else{
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.minutes : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        //
        if(prefix !== undefined) return row;
        $(target).html(row);
    }

     //
    function getRequestedDaysEdit(){
        //
        var 
        totalTime = 0,
        err = false,
        arr = [];
        //
        $('#js-timeoff-date-box-edit tbody tr').map(function(i, v){
            if(err) return;
            var time = getTimeInMinutes('el-edit'+( $(this).data('id') ));
            //
            if( time.requestedMinutes <= 0 ){
                err = true;
                alertify.alert('WARNING!', 'Please, add request time for date <b>'+( $(this).data('date') )+'</b>.', function(){ return; });
            } else if( time.requestedMinutes > time.defaultTimeslotMinutes ){
                err = true;
                alertify.alert('WARNING!', 'Requested time off can not be greater than shift time.', function(){ return; });
            }
            //
            arr.push({
                date: $(this).data('date'),
                partial: $(this).find('input[name="'+( i )+'_day_type_edit"]:checked').val(),
                time: time.requestedMinutes,
            });
            //
            totalTime = parseInt(totalTime) + parseInt(time.requestedMinutes);
        });

        return {
            totalTime: totalTime,
            days: arr,
            error: err
        }
    }

</script>

<style>
.cs-required{
    color:#cc0000;
    font-weight:bold;
}
.employee-info .cs-info-text {
    padding-left: 30px;
}
.employee-info figure {
    width: 60px;
    height: 60px;
}
.employee-info .text h4{
    font-weight: 600;
    font-size: 20px;
    margin: 0;
}
.employee-info .text a{ color: #aaa;}
.employee-info .text p{
    color: #818181;
    font-weight: normal;
    font-size: 18px;
    margin: 0;
}
.invoice-fields {
    float: left;
    width: 100%;
    height: 40px;
    border: 1px solid #ccc;
    border-radius: 5px;
    color: #000;
    padding: 0 5px;
    background-color: #eee;
}
.js-attachment-edit,
.js-attachment-view,
.js-attachment-delete,
.js-timeoff-attachment,
.btn-5{ border-radius: 5px !important; }

.modal{ overflow-y: auto !important; }
.ui-datepicker-unselectable .ui-state-default{ background-color: #555555; border-color: #555555; }
</style>
