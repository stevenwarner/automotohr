<script src="<?=base_url("assets/js/moment.min.js")?>"></script>
<link rel="stylesheet" href="<?=base_url('assets');?>/css/timeoffstyle.css">
<style>
    .employee-info figure img{ border-radius: 3px !important; }
</style>
<!-- Modal -->
<div class="modal fade" id="js-timeoff-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content model-content-custom">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create a Time off Request</h4>
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
                                        <?php if($employer['profile_picture'] != ''){ ?>
                                        <img src="<?=AWS_S3_BUCKET_URL;?><?=$employer['profile_picture'];?>" class="img-square emp-image" />
                                        <?php } else { ?>
                                        <img src="<?=base_url('assets/images/img-applicant.jpg');?>" class="img-square emp-image" />
                                        <?php }?>
                                    </figure>
                                    <div class="text cs-info-text">
                                        <h4><?=$employer['first_name'].' '.$employer['last_name'];?></h4>
                                        <p><a href="<?=base_url('employee_profile');?>/<?=$sid;?>" target="_blank">Id: <?=$employer['employee_number'] ? $employer['employee_number'] : $sid;?></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Types <span class="cs-required">*</span> </label>
                                    <select id="js-types"></select>
                                </div>
                                <div class="form-group js-policy-box">
                                    <label>Policies <span class="cs-required">*</span> </label>
                                    <select id="js-policies"></select>
                                </div>
                            </div>
                        </div>
                        <!-- Section Two -->
                        <div class="row">
                            <hr />
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="">Start Date <span class="cs-required">*</span></label>
                                            <input readonly="true" type="text" id="js-timeoff-start-date" class="form-control js-request-start-date" />
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="">End Date <span class="cs-required">*</span></label>
                                            <input readonly="true" type="text" id="js-timeoff-end-date" class="form-control js-request-end-date" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Status <span class="cs-required">*</span> </label>
                                            <select id="js-status">
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="js-timeoff-date-box" style="display: none;">
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
                        <!--  -->
                        <div class="row m15">
                            <hr>
                            <div class="col-sm-12">
                                <h4>Send Email to Employee / Supervisor ?</h4>
                            </div>
                            <div class="col-sm-12">
                                <label class="control control--radio">
                                    <input type="radio" name="js-send-email" class="js-send-email" value="no" />No
                                    <div class="control__indicator"></div>
                                </label>&nbsp;
                                <label class="control control--radio">
                                    <input type="radio" name="js-send-email" class="js-send-email" value="yes" checked="true" />Yes
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <!-- Section Three -->
                        <div class="row js-fmla-wrap" style="display: none;">
                            <hr />
                            <div class="col-sm-12 col-sm-12">
                                <label for="">Is this time off under FMLA? <span class="cs-required">*</span></label>
                                <br />
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-fmla-check" name="fmla" value="no" type="radio" checked="true" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-fmla-check" name="fmla" value="yes" type="radio" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;

                                <div class="js-fmla-box" style="display: none;">
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Designation Notice
                                        <input class="js-fmla-type-check" name="fmla_check" value="designation" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Designation Notice" data-content="<?=FMLA_DESIGNATION;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Certification of Health Care Provider for Employee’s Serious Health Condition 
                                        <input class="js-fmla-type-check" name="fmla_check" value="health" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Certification of Health Care" data-content="<?=FMLA_CERTIFICATION_OF_HEALTH;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Notice of Eligibility and Rights & Responsibilities 
                                        <input class="js-fmla-type-check" name="fmla_check" value="medical" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Notice of Eligibility and Rights & Responsibilities" data-content="<?=FMLA_RIGHTS;?>" class="fa fa-question-circle js-popover"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-vacation-row" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Emergency Contact Number</label>
                                    <input type="text" class="form-control" id="js-vacation-contact-number" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="text" class="form-control" id="js-vacation-return-date" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Alternate Temporary Address</label>
                                    <input type="text" class="form-control" id="js-vacation-address" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-bereavement-row" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Relationship</label>
                                    <input type="text" class="form-control" id="js-bereavement-relationship" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="text" class="form-control" id="js-bereavement-return-date" readonly="true" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-compensatory-row" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation Date</label>
                                    <input type="text" class="form-control" id="js-compensatory-return-date" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation Start Time</label>
                                    <input type="text" class="form-control" id="js-compensatory-start-time" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation End Time</label>
                                    <input type="text" class="form-control" id="js-compensatory-end-time" readonly="true" />
                                </div>
                            </div>
                        </div>

                        <hr />
                        <!-- Section Four -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea id="js-reason"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Five -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label>Comment</label>
                                    <textarea id="js-comment"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!--  -->
                        <div class="row m15">
                            <div class="col-sm-12">
                                <h4>
                                    Supporting documents
                                    <span class="pull-right">
                                        <button class="btn btn-success js-timeoff-attachment"><i class="fa fa-plus"></i>&nbsp; Add Document</button>
                                    </span>
                                </h4>
                            </div>
                            <div class="col-sm-12">
                                <br />
                                <div class="reponsive">
                                    <table class="table table-striped table-bordered" id="js-attachment-listing">
                                        <thead>
                                            <tr>
                                                <th>Document Title</th>
                                                <th class="col-sm-3">Document Type</th>
                                                <th class="col-sm-2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="js-no-records">
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
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-rounded" id="js-save-btn">Create</button>
                <button type="button" class="btn btn-default btn-rounded" data-dismiss="modal" id="js-cancel-btn">Cancel</button>
            </div>
        </div>
  </div>
</div>

<!-- FMLA model -->
<div class="modal fade" id="js-fmla-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="float: none;">
               <!-- FMLA forms -->
                <div class="js-page" id="js-fmla">
                    <span class="pull-right" style="margin: 5px 10px 20px;"><button class="btn btn-info btn-5 js-shift-page">Back</button></span>
                    
                    <div class="js-form-area" data-type="health" style="display: none;">
                        <?php $this->load->view('timeoff/fmla/employee/health'); ?>
                    </div>
                    <div class="js-form-area" data-type="medical" style="display: none;">
                        <?php $this->load->view('timeoff/fmla/employee/medical'); ?>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function(){
        var
        xhr = null,
        holidayDates = <?=json_encode($holidayDates);?>,
        fmla = {};
        //
        $('.select2').select2();
        $('#js-status').select2();

        //
        CKEDITOR.replace('js-reason');
        CKEDITOR.replace('js-comment');
        //
        $('#js-create-pto').click(function(e){
            e.preventDefault();
            $('#js-timeoff-modal').modal({
                keyboard: false,
                backdrop: 'static'
            });
        })

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
        $('.js-partial-check').click(function(){
            if($(this).val() == 'yes')
            $('.js-note-box').show();
            else
            $('.js-note-box').hide();
        })

        $('#js-timeoff-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));
        
        //
        $(document).on('keyup', '.js-number', function(){
            $(this).val(
                $(this).val().replace(/[^0-9]/, '')
            );
        });

        // Add process
        //
        $(document).on('change', '#js-policies', function(e){
            //
            if( $(this).val() == 0) return;
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
            $('.js-number').val(0);
            $('#js-policies').select2('val', 0);
            $('#js-note').val('');
            CKEDITOR.instances['js-reason'].setData('');
            CKEDITOR.instances['js-comment'].setData('');
            $('.js-partial-check[value="no"]').trigger('click');
            $('#js-timeoff-date').val(moment().add('1', 'day').format('MM-DD-YYYY'));

        });

        // Save TO from modal
        $(document).on('click', '#js-save-btn', function(){
            var megaOBJ = {};
            megaOBJ.action = 'create_employee_timeoff';
            megaOBJ.reason = CKEDITOR.instances['js-reason'].getData().trim();
            megaOBJ.comment = CKEDITOR.instances['js-comment'].getData().trim();
            megaOBJ.policyId = $('#js-policies').val();
            megaOBJ.policyName = $('#js-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            if(megaOBJ.policyId == 0 || megaOBJ.policyId == null){
                alertify.alert('WARNING!', 'Please select a Policy to continue.');
                return;
            }
            megaOBJ.sendEmail = $('.js-send-email:checked').val() == 'no' ? 0 : 1;
            megaOBJ.companySid = <?=$companyData['sid'];?>;
            megaOBJ.employeeSid = <?=$sid;?>;
            megaOBJ.sendEmail = 0;
            megaOBJ.status = $('#js-status').val();
            megaOBJ.employeeFullName = "<?=$employerData['first_name'].' '.$employerData['last_name'];?>";
            megaOBJ.employeeEmail = "<?=$employerData['email'];?>";
            //
            megaOBJ.startDate = $('#js-timeoff-start-date').val();
            megaOBJ.endDate = $('#js-timeoff-end-date').val();
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
            var requestedDays = getRequestedDays();
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

            // FMLA 
            megaOBJ.isFMLA = $('.js-fmla-check:checked').val();
            if(megaOBJ.isFMLA == 'yes' && Object.keys(fmla).length == 0){
                alertify.alert('WARNING!', 'Please fill the selected FMLA form.');
                return;   
            }
            megaOBJ.isFMLA = fmla.type;
            megaOBJ.fmla = fmla;
            //
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( t );
            

            // Phase 3 code 
            megaOBJ.returnDate = '';
            megaOBJ.relationship = '';
            megaOBJ.temporaryAddress = '';
            megaOBJ.compensationEndTime = '';
            megaOBJ.compensationStartTime = '';
            megaOBJ.emergencyContactNumber = '';
            //
            switch ($('#js-types').val().toLowerCase()) {
                case 'vacation':
                    megaOBJ.returnDate = $('#js-vacation-return-date').val().trim();
                    megaOBJ.temporaryAddress = $('#js-vacation-address').val().trim();
                    megaOBJ.emergencyContactNumber = $('#js-vacation-contact-number').val().trim();
                break;

                case 'bereavement':
                    megaOBJ.returnDate = $('#js-bereavement-return-date').val().trim();
                    megaOBJ.relationship = $('#js-bereavement-relationship').val().trim();
                break;

                case 'compensatory/ in lieu time':
                    megaOBJ.returnDate = $('#js-compensatory-return-date').val().trim();
                    megaOBJ.compensationStartTime = $('#js-compensatory-start-time').val().trim();
                    megaOBJ.compensationEndTime = $('#js-compensatory-end-time').val().trim();
                break;
            }
            //
            //
            // Disable all fields in modal
            $('#js-timeoff-modal').find('input, textarea').prop('disabled', true);
            $('#js-save-btn').val('Saving....');
            // Let's save the TO
            $.post(
                handlerURI,
                megaOBJ,
                function(resp){
                    //
                    $('#js-timeoff-modal').find('input, textarea').prop('disabled', false);
                    $('#js-save-btn').val('SAVE');
                    // When an error occured
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response.replace(/{{TIMEOFFDDATE}}/, moment(megaOBJ.requestDate, 'YYYY-MM-DD').format('MM-DD-YYYY')));
                        return;
                    }

                    successMSG = resp.Response;
                    startDocumentUploadProcess(resp.InsertId);                     
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
            var formData = new FormData();
            //
            formData.append('action', 'add_attachment_to_request');
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
        $('.js-fmla-check').click(function(e){
            if($(this).val() == 'yes') $('.js-fmla-box').show();
            else{ $('.js-fmla-type-check').prop('checked', false);  fmla = {}; $('.js-fmla-box').hide(); }
        });
        //
        $('.js-fmla-type-check').click(function(e){
            //
            fmla = {};
            if($(this).val() !== 'designation'){
                //
                $('#js-timeoff-modal').modal('toggle');
                $('body').css('overflow', 'hidden');
                $('.js-fmla-employee-firstname').val("<?=$employer['first_name'];?>");
                $('.js-fmla-employee-lastname').val("<?=$employer['last_name'];?>");
                $('#js-fmla-modal').find('.modal-title').text('FMLA - '+( $(this).closest('label').text() )+'');
                $('#js-fmla-modal').find('div[data-type="'+( $(this).val() )+'"]').show();
                $('#js-fmla-modal').modal('toggle');
            }else{
                fmla = {
                    type: 'designation'
                };
            }
            //
        });
        //
        $('#js-fmla-modal').on('hide.bs.modal', function(){ $('#js-timeoff-modal').modal('show'); });
        $('#js-timeoff-modal').on('hide.bs.modal', function(){ $('body').css('overflow-y', 'auto'); });
        //
        $('.js-shift-page').click(function(){
            $('#js-fmla-modal').modal('hide');
            $('#js-timeoff-modal').modal('show');
        });
        //
        $('.js-fmla-save-button').click(function(){
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
                        lastname: $('#js-fmla-health-employee-lastname').val().trim()
                    }
                };
                //
                $('#js-fmla-modal').modal('hide');
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
                $('#js-fmla-modal').modal('hide');
            }
        });

        //
        $('.js-popover').popover({
            html: true,
            trigger: 'hover',
            placement: 'right'
        })

        $('#js-start-partial-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });

        $('#js-end-partial-time').datetimepicker({
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


        $('#js-vacation-return-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));
        //
        $('#js-bereavement-return-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));
        //
        $('#js-compensatory-return-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));

        //
        $('#js-compensatory-start-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });

        $('#js-compensatory-end-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15,
            onShow: function(d){
                this.setOptions({
                    minTime: $('#js-compensatory-start-time').val() ? $('#js-compensatory-start-time').val() : false
                });
            }
        });

        $('#js-timeoff-start-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            onSelect: function(d){
                $('#js-timeoff-end-date').datepicker('option', 'minDate', d);
                $('#js-timeoff-end-date').val(d);

                remakeRangeRows();
            }
        });

        $('#js-timeoff-end-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            onSelect: remakeRangeRows
        })

        //
        function remakeRangeRows(){
            var startDate = $('#js-timeoff-start-date').val(),
            endDate = $('#js-timeoff-end-date').val();
            //
            $('#js-timeoff-date-box').hide();
            $('#js-timeoff-date-box tbody tr').remove();
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
                rows += '<tr data-id="'+( i )+'" data-date="'+( sd.format('MM-DD-YYYY') )+'">';
                rows += '    <th style="vertical-align: middle">'+( sd.format('MMMM Do, YYYY') )+'</th>';
                rows += '    <th style="vertical-align: middle">';
                rows += '        <div>';
                rows += '            <label class="control control--radio">';
                rows += '                Full Day';
                rows += '                <input type="radio" name="'+( i )+'_day_type" checked="true" value="fullday" />';
                rows += '                <span class="control__indicator"></span>';
                rows += '            </label>';
                rows += '            <label class="control control--radio">';
                rows += '                Partial Day';
                rows += '                <input type="radio" name="'+( i )+'_day_type" value="partialday" />';
                rows += '                <span class="control__indicator"></span>';
                rows += '            </label>';
                rows += '        </div>';
                rows += '    </th>';
                rows += '    <th>';
                rows += '        <div class="rowd" id="row_'+( i )+'">';
                rows +=          setTimeView('#row_'+( i )+'', '-el'+i);
                rows += '        </div>';
                rows += '    </th>';
                rows += '</tr>';
                //
            }
            //
            $('#js-timeoff-date-box tbody').html(rows);
            $('#js-timeoff-date-box').show();
        }

        //
        function setTimeView(target, prefix){
            var row = '';
            if(policies[0]['format'] == 'D:H:M'){
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
            }else if(policies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
            }
            //
            if(prefix !== undefined) return row;
            $(target).html(row);
        }
 //
        function getRequestedDays(){
            //
            var 
            totalTime = 0,
            err = false,
            arr = [];
            //
            $('#js-timeoff-date-box tbody tr').map(function(i, v){
                if(err) return;
                var time = getTimeInMinutes('el'+i);
                console.log(time);
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
                    partial: $(this).find('input[name="'+( i )+'_day_type"]:checked').val(),
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


        <?php $this->load->view('timeoff/employee/scripts/attachments'); ?>
    })

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
.cs-required{ font-weight: bold; color: #cc0000; }
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
