<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/pagination.min.css"/>
<script src="<?=base_url('assets/js/pagination.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>
<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/",
    intervalCatcher = null;
    $(function(){
        var
        employeePolicies = [],
         overwrite = {
            'teamlead' : 'Team Lead',
            'supervisor' : 'Supervisor',
            'approver' : 'Approver'
        },
        request = <?=json_encode($Request);?>;

        console.log(<?=json_encode($Params);?>);

        loadHistoryData(request.History);
        //
        $('#js-status').select2();
        //
        if(request['Info']['is_partial_leave'] == 0){
            $('#js-partial-box').hide(0);
        }
        //
        $(document).on('click', '.js-partial-leave', function(){
            if($(this).val() == 'yes') $('#js-partial-box').show();
            else $('#js-partial-box').hide();
        });
        //
        $('#js-date').datepicker({
            format: 'mm-dd-yy',
            minDate: 1
        }).val(moment(request.Info.requested_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
        //
        loader('hide');


        // Save TO from modal
        $(document).on('click', '#js-save-btn', function(){
            var megaOBJ = {};
            megaOBJ.note = $('#js-partial-note').val().trim();
            megaOBJ.action = 'update_employee_timeoff';
            megaOBJ.status = $('#js-status').val();
            megaOBJ.comment = CKEDITOR.instances['message'].getData().trim();
            megaOBJ.policyId = $('#js-policies').val();
            megaOBJ.policyName = $('#js-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            if(megaOBJ.policyId == 0){
                alertify.alert('WARNING!', 'Please select a Policy to continue.');
                return;
            }
            megaOBJ.isPartial = $('.js-partial-leave:checked').val() == 'no' ? 0 : 1;
            megaOBJ.companySid = <?=$Params['companyId'];?>;
            megaOBJ.employeeSid = <?=$Params['employeeId'];?>;
            megaOBJ.requestDate = moment($('#js-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');
            // Set hours minutes array
            megaOBJ.requestedTimeDetails = getTimeInMinutes();
            //
            if(megaOBJ.requestedTimeDetails.requestedMinutes <= 0){
                alertify.alert('WARNING!', 'Requested time off can not be less or equal to zero.');
                return;
            }
            //
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( getPolicy( megaOBJ.policyId ) );
            megaOBJ.requestId = <?=$Params['id'];?>;
            megaOBJ.request = request.Info;
            megaOBJ.tls = request.Assigned;

            // Disable all fields in modal
            $('#js-save-btn').val('Saving....');
            // Let's save the TO
            $.post(
                baseURI+'handler',
                megaOBJ,
                function(resp){
                    //
                    $('#js-save-btn').val('SAVE');
                    // When an error occured
                    if(resp.Status === false){
                        loader('hide');
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

        // CKEDITOR.replace(emt.comment);

        /* FILTER START */
        fetchEmployeeAllPolicies(
            <?=$Request['Info']['employee_sid'];?>,
            function(resp){
                // Set policies
                var rows = '';
                resp.Data.map(function(policy){
                    rows += '<option value="'+( policy.sid )+'" '+( policy.sid == request.Info.policyId ? 'selected=true' : '' )+'>'+( policy.title )+' ('+(  policy.timeoff_breakdown !== undefined  ? policy.timeoff_breakdown.text : 'Unlimited' )+')</option>';
                });
                $('#js-policies').html(rows);
                $('#js-policies').select2();
                //
                setTimeView(
                    '#js-time', 
                    request.Info.timeoff_breakdown.active
                );
                // Set hours and time
                if($('#js-request-days').length != 0) $('#js-request-days').val(request.Info.timeoff_breakdown.active.days);
                if($('#js-request-hours').length != 0) $('#js-request-hours').val(request.Info.timeoff_breakdown.active.hours);
                if($('#js-request-minutes').length != 0) $('#js-request-minutes').val(request.Info.timeoff_breakdown.active.minutes);
                //
                // loader('hide');             
        });

        //
        function getTimeInMinutes(){
            var
            days = 0,
            hours = 0,
            minutes = 0,
            format = '',
            inText = '';
            //
            if($('#js-request-days').length !== 0) { format +='D,'; days = parseInt($('#js-request-days').val().trim()); }
            if($('#js-request-hours').length !== 0) { format += 'H,'; hours = parseInt($('#js-request-hours').val().trim()); }
            if($('#js-request-minutes').length !== 0) { format += 'M,'; minutes = parseInt($('#js-request-minutes').val().trim()); }
            //
            if(format == 'D:H:M') inText = days+' day'+( days > 1 ? 's' : '' )+', '+hours+' hour'+( hours > 1 ? 's' : '' )+', and '+minutes+' minute'+( minutes > 1 ? 's' : '' );
            else if(format == 'D') inText = days+' day'+( days > 1 ? 's' : '' );
            else if(format == 'H') inText = hours+' day'+( hours > 1 ? 's' : '' );
            else if(format == 'M') inText = minutes+' minute'+( minutes > 1 ? 's' : '' );
            else inText = hours+' hour'+( hours > 1 ? 's' : '' )+', and '+minutes+' minute'+( minutes > 1 ? 's' : '' );
            return  {
                days: days,
                hours: hours,
                minutes: minutes,
                defaultTimeslot: request.Info.timeoff_breakdown.timeFrame,
                format: format.replace(/,$/, ''),
                formated: inText,
                requestedMinutes: (days * request.Info.timeoff_breakdown.timeFrame * 60) + (hours * 60) + minutes
            };
        }
        //
        function getAllowedTimeInMinutes(policy){
            if(policy.is_unlimited == 1) return 0;
            var
            days = 0,
            hours = 0,
            minutes = 0;
            if(policy.format.match(/D/)) days = policy.timeoff_breakdown.active.days;
            if(policy.format.match(/H/)) days = policy.timeoff_breakdown.active.hours;
            if(policy.format.match(/M/)) days = policy.timeoff_breakdown.active.minutes;
            //
            return (days * policy.timeoff_breakdown.timeFrame * 60) + (hours * 60) + minutes;
        }
        
        // Fetch employee all policies
        function fetchEmployeeAllPolicies(employeeId, cb){
            $.post(baseURI+'handler', {
                action: 'get_employee_all_policies',
                companySid: <?=$Params['companyId'];?>,
                employeeSid: employeeId
            }, function(resp){
                if(resp.Status === false){
                    console.log('Failed to load policies.');
                    return;
                }
                //
                employeePolicies = resp.Data;
                cb(resp);
            });
        }
        //
        function setTimeView(target, data){
            var row = '';
            if(employeePolicies[0]['format'] == 'D:H:M'){
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Days <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }else if(employeePolicies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            //
            $(target).html(row);
        }
        //
        function loadHistoryData(data){
            if(data.length == 0){
                loader('hide');
                return;
            }
            $('#js-history-data-area').pagination({
                dataSource: data,
                pageSize: 5,
                showPrevious: true,
                showNext: true,
                callback: function(hs, pagination) {
                    var rows = '';
                    hs.map(function(h){
                        var 
                        employeeData = getEmployeeRecord(h.timeoff_request_assignment_sid),
                        policyData = getPolicyRecord(h.timeoff_request_assignment_sid);
                        rows += '<div class="row cs-timeoff-history-row">';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Employee</b></h5>';
                        rows += '       <span>'+( employeeData['full_name'] )+' ('+(overwrite[employeeData['role']])+')</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Requested Date</b></h5>';
                        rows += '       <span>'+( h.requested_date != '0000-00-00' && h.requested_date != '' && h.requested_date != null ? moment(h.requested_date, 'YYYY-MM-DD').format('MM-DD-YYYY') : 'N/A' )+'</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Hours</b></h5>';
                        rows += '       <span>'+( h.timeoff_breakdown.text )+'</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Requested Status</b></h5>';
                        rows += '       <span>'+( h.status )+'</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-12 cs-row">';
                        rows += '       <hr />';
                        rows += '       <p>';
                        rows += '           <strong>Policy Name:</strong> '+( policyData == 'N/A' ? 'N/A' : policyData['title'] )+'';
                        rows += '       </p>';
                        rows += '       <p>';
                        rows += '           <strong>Reason:</strong> '+( h.reason )+'';
                        rows += '       </p>';
                        rows += '    </div>';
                        rows += '</div>';
                    });
                    $('#js-history-data-append').html(rows);
                }
            })
        }
        //
        function getEmployeeRecord(timeoff_request_assignment_sid){
            var 
            i = 0,
            l = request.Assigned.length;
            //
            for(i; i < l; i++){
                if(request.Assigned[i]['sid'] == timeoff_request_assignment_sid) return request.Assigned[i];
            }
            return 'N/A';
        }
        //
        function getPolicyRecord(policyId){
            var 
            i = 0,
            l = employeePolicies.length;
            //
            for(i; i < l; i++){
                if(employeePolicies[i]['sid'] == policyId) return employeePolicies[i];
            }
            return 'N/A';
        }
        //
        function getPolicy( sid ){
            var i = 0,
            l = employeePolicies.length;
            //
            for(i; i < l; i++) if(employeePolicies[i]['sid'] == sid) return employeePolicies[i];
            return null;
        }

        // Loader
        function loader(show_it){
            show_it = show_it === undefined || show_it == true || show_it == 'show' ? 'show' : 'hide';
            if(show_it === 'show') $('.js-ad-loader').show();
            else $('.js-ad-loader').hide();
        }

        String.prototype.ucwords = function() {
            return this.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                function(s){
                    return s.toUpperCase();
                }
            );
        };
    })

</script>

<!-- Loader -->
<div class="text-center cs-loader js-ad-loader">
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text">Please wait, while we process your request.</div>
    </div>
</div><!-- Loader -->


