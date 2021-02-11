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
                                    <label>Policies <span class="cs-required">*</span> </label>
                                    <select id="js-policies"></select>
                                </div>
                                <div class="form-group">
                                    <label>Status <span class="cs-required">*</span> </label>
                                    <select id="js-status">
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Two -->
                        <div class="row">
                            <div class="col-sm-6 col-sm-12">
                                <div class="form-group">
                                    <label>Date <span class="cs-required">*</span></label>
                                    <input readonly="true" type="text" id="js-timeoff-date" class="form-control js-request-date" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row" id="js-time"></div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Three -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <label for="">Is this for a partial day? <span class="cs-required">*</span></label>
                                <br />
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-partial-check" name="partial" value="yes" type="radio" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-partial-check" name="partial" value="no" type="radio" checked="true" />
                                    <div class="control__indicator"></div>
                                </label>

                                <div class="js-note-box" style="display: none;">
                                    <br />
                                    <label>Note</label></label>
                                    <input type="text" class="form-control" id="js-note" />
                                </div>
                            </div>
                        </div>
                        <hr />
                        <hr>
                        <div class="row m15">
                                <div class="col-sm-12">
                                    <h4>Send Email to Employee / Supervisor ?</h4>
                                </div>
                                <div class="col-sm-12">
                                    <label class="control control--radio">
                                        <input type="radio" name="js-send-email" class="js-send-email" value="yes" checked="true" />Yes
                                        <div class="control__indicator"></div>
                                    </label>&nbsp;
                                    <label class="control control--radio">
                                        <input type="radio" name="js-send-email" class="js-send-email" value="no" />No
                                        <div class="control__indicator"></div>
                                    </label>
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
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-rounded" id="js-save-btn">SAVE</button>
                <button type="button" class="btn btn-default btn-rounded" data-dismiss="modal" id="js-cancel-btn">Cancel</button>
            </div>
        </div>
  </div>
</div>
<script type="text/javascript">

    $(function(){
        var
        xhr = null;
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

        $('.js-partial-check').click(function(){
            if($(this).val() == 'yes')
            $('.js-note-box').show();
            else
            $('.js-note-box').hide();
        })

        $('#js-timeoff-date').datepicker({
            format: 'mm-dd-yyyy',
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));

        //
        function setTimeView(target){
            var row = '';
            if(policies[0]['format'] == 'D:H:M'){
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Days <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required ">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }else if(policies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            //
            $(target).html(row);
        }

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
            megaOBJ.note = $('#js-note').val().trim();
            megaOBJ.action = 'create_employee_timeoff';
            megaOBJ.reason = CKEDITOR.instances['js-reason'].getData().trim();
            megaOBJ.comment = CKEDITOR.instances['js-comment'].getData().trim();
            megaOBJ.policyId = $('#js-policies').val();
            megaOBJ.policyName = $('#js-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            if(megaOBJ.policyId == 0){
                alertify.alert('WARNING!', 'Please select a Policy to continue.');
                return;
            }
            megaOBJ.isPartial = $('.js-partial-check:checked').val() == 'no' ? 0 : 1;
            megaOBJ.sendEmail = $('.js-send-email:checked').val() == 'no' ? 0 : 1;
            megaOBJ.companySid = <?=$companyData['sid'];?>;
            megaOBJ.employeeSid = <?=$sid;?>;
            megaOBJ.sendEmail = 0;
            megaOBJ.status = $('#js-status').val();
            megaOBJ.employeeFullName = "<?=$employerData['first_name'].' '.$employerData['last_name'];?>";
            megaOBJ.employeeEmail = "<?=$employerData['email'];?>";
            megaOBJ.requestDate = moment($('#js-timeoff-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');
            // Set hours minutes array
            megaOBJ.requestedTimeDetails = getTimeInMinutes();
            //
            if(megaOBJ.requestedTimeDetails.requestedMinutes <= 0){
                alertify.alert('WARNING!', 'Requested time off can not be less or equal to zero.');
                return;
            }
            var t = getPolicy( megaOBJ.policyId );
            if(t.is_unlimited == '0' && megaOBJ.requestedTimeDetails.requestedMinutes > (t.employee_timeslot*60)){
                alertify.alert('WARNING!', 'Requested time off can not be greater than shift time.');
                return;
            } 
            else if(t.is_unlimited == '0' && megaOBJ.requestedTimeDetails.requestedMinutes > t.allowed_timeoff){
                alertify.alert('WARNING!', 'Requested time off can not be greater than allowed time.');
                return;
            }
            //
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( getPolicy( megaOBJ.policyId ) );
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
                    // On success
                    alertify.alert('SUCCESS!', resp.Response, function(){
                        window.location.reload();
                    });
                }
            );
        });
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
</style>
