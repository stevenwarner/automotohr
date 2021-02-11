<script>
      var timeoffDateFormat = 'MMM DD YYYY, ddd';
    var
    policies = [],
    baseURI = "<?=base_url();?>timeoff/",
    handlerURI = "<?=base_url();?>timeoff/handler",
    format = 'H',
    xhr = null,
    defaultHours = null,
    defaultMinutes = null;
    defaultTimeslot = null;

    //
    $(function(){ fetchEmployeePolicies(); })
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
        return (days * defaultTimeslot * 60) + (hours * 60) + minutes;
    }
    //
    function getTimeInMinutes(){
        var
        days = 0,
        hours = 0,
        minutes = 0,
        format = '',
        inText = '';
        //
        if($('#js-request-days').length !== 0) { format +='D,'; days = isNaN(parseInt($('#js-request-days').val().trim())) ? 0 : parseInt($('#js-request-days').val().trim()); }
        if($('#js-request-hours').length !== 0) { format += 'H,'; hours = isNaN(parseInt($('#js-request-hours').val().trim())) ? 0 : parseInt($('#js-request-hours').val().trim()); }
        if($('#js-request-minutes').length !== 0) { format += 'M,'; minutes = isNaN(parseInt($('#js-request-minutes').val().trim())) ? 0 : parseInt($('#js-request-minutes').val().trim()); }
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
            defaultTimeslot: defaultTimeslot,
            format: format.replace(/,$/, ''),
            formated: inText,
            requestedMinutes: (days * defaultTimeslot * 60) + (hours * 60) + minutes
        };
    }
    //
    function setPolicyTime(days, hours, minutes){
        // if(format.match(/D/)) $('#js-request-days').val(days);
        // if(format.match(/H/)) $('#js-request-hours').val(hours);
        // if(format.match(/M/)) $('#js-request-minutes').val(minutes);
    }
    //
    function getPolicy( sid ){
        var i = 0,
        l = policies.length;
        //
        for(i; i < l; i++) if(policies[i]['sid'] == sid) return policies[i];
        return null;
    }
  
    //
    function fetchEmployeePolicies(){
        if(xhr != null) return;
        xhr = $.post(handlerURI, {
            action: 'get_employee_policies',
            employeeSid: <?=$employerData['sid'];?>,
            companySid: <?=$companyData['sid'];?>
        }, function(resp){
            if(resp.Status === false){
                console.log('No policies found.');
                return;
            }

            policies = resp.Data;
            defaultHours = policies[0]['user_shift_hours'],
            defaultMinutes = policies[0]['user_shift_minutes'];
            defaultTimeslot = policies[0]['employee_timeslot'];
            //
            setTimeView('#js-time');
            var rows = '';
            rows += '<option value="0">[Select a policy]</option>';
            policies.map(function(policy){
                rows += '<option value="'+( policy.sid )+'">'+( policy.title )+' ( '+( policy.timeoff_breakdown !== undefined ? policy.timeoff_breakdown.text : 'Unlimited' )+' )</option>';
            })
            $('#js-policies').html(rows);
            $('#js-policies').select2();
        });
    }
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
            row += '        <label>Minutes <span class="cs-required">*</span></label>';
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


    function getImageURL(img){
        if(img == '' || img == null){
            return "<?=base_url('assets/images/img-applicant.jpg');?>";
        } else return "<?=AWS_S3_BUCKET_URL;?>"+( img )+"";
    }
    //
    function getEmployeeId(i, n){
        return n == '' || n == null ? i : n;
    }
    //
    function getReasonHTML(reason){
        if(reason != '')
            return `<a href="javascript:void(0)" title="Reason" data-trigger="hover" data-toggle="popover" data-placement="left" data-content="${reason}" class="action-activate custom-tooltip"><i class="fa fa-list-alt fa-fw text-success"></i></a>`;
        else
            return '<a href="javascript:void(0)"><i class="fa fa-list-alt fa-fw"></i></a>';
    }
    //
     function getLevelHTML(level){
        let levelText = level == 1 ? 'TEAM LEAD' : level == 2 ? 'SUPERVISOR' : 'APPROVER';
        return `<span class="text-success cs-status-text">${levelText}</span>`;
    }
    //
    function getStatusHTML(status){
        return '<p><span class="text-'+( status.toLowerCase() == 'approved' ? 'success' : status.toLowerCase() == 'rejected' ? 'danger' : 'warning' )+' cs-status-text">'+( status.toUpperCase() )+'</span></p>';
    }
</script>
