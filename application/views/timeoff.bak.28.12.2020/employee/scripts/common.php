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
    function getTimeInMinutes(typo){
        typo = typo === undefined ? '' : '-'+typo;
        var
        days = 0,
        hours = 0,
        minutes = 0,
        format = '',
        inText = '';
        //
        if($('#js-request-days'+( typo )+'').length !== 0) { format +='D,'; days = isNaN(parseInt($('#js-request-days'+( typo )+'').val().trim())) ? 0 : parseInt($('#js-request-days'+( typo )+'').val().trim()); }
        if($('#js-request-hours'+( typo )+'').length !== 0) { format += 'H,'; hours = isNaN(parseInt($('#js-request-hours'+( typo )+'').val().trim())) ? 0 : parseInt($('#js-request-hours'+( typo )+'').val().trim()); }
        if($('#js-request-minutes'+( typo )+'').length !== 0) { format += 'M,'; minutes = isNaN(parseInt($('#js-request-minutes'+( typo )+'').val().trim())) ? 0 : parseInt($('#js-request-minutes'+( typo )+'').val().trim()); }
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
            defaultTimeslotMinutes: (defaultTimeslot * 60),
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
            employeeSid: <?=$sid;?>,
            companySid: <?=$companyData['sid'];?>
        }, function(resp){
            if(resp.Status === false){
                console.log('No policies found.');
                return;
            }

            policies = resp.Data;
            defaultHours = policies[0]['user_shift_hours'];
            defaultMinutes = policies[0]['user_shift_minutes'];
            defaultTimeslot = policies[0]['employee_timeslot'];
            //
            setTimeView('#js-time');
            var 
            typeRows = '',
            types = [];
            //
            typeRows += '<option value="0">[Select a type]</option>';
            //
            policies.map(function(policy){
                if($.inArray(policy.Category, types) === -1){
                    types.push(policy.Category);
                    typeRows += '<option value="'+( policy.Category )+'">'+( policy.Category )+'</option>';
                }
            });
            //
            $('.js-policy-box').hide(0);
            $('#js-types').html(typeRows);
            $('#js-types').select2();
        });
    }

    $(document).on('change', '#js-types', function(){
        var rows = '';
        rows += '<option value="0">[Select a policy]</option>';
        var v = $(this).val();
        //
        if(v == 0){
            $('.js-fmla-wrap').hide(0);
            fmla = {};
            $('#js-policies').html('');
            $('#js-policies').select2('destroy');
            $('.js-policy-box').hide(0);
            return;
        }

        if(v.toLowerCase().match(/(fmla)/g) !== null ){
            $('.js-fmla-wrap').show(0);
            fmla = {};
        } else{
            $('.js-fmla-wrap').hide(0);
            $('.js-fmla-check[value="no"]').trigger('click');
            fmla = {};
        }
        // For Vacation
        if(v.toLowerCase().match(/(vacation)/g) !== null ){
            $('.js-vacation-row').show(0);
            fmla = {};
        } else{
            $('.js-vacation-row').hide(0);
            $('.js-vacation-row input').val('');
        }
        // For Bereavement
        if(v.toLowerCase().match(/(bereavement)/g) !== null ){
            $('.js-bereavement-row').show(0);
            fmla = {};
        } else{
            $('.js-bereavement-row').hide(0);
            $('.js-bereavement-row input').val('');
        }
        // For Compensatory
        if(v.toLowerCase().match(/(compensatory)/g) !== null ){
            $('.js-compensatory-row').show(0);
            fmla = {};
        } else{
            $('.js-compensatory-row').hide(0);
            $('.js-compensatory-row input').val('');
        }
        policies.map(function(policy){
            if(policy.Category == v){
                rows += '<option value="'+( policy.sid )+'">'+( policy.title )+' ('+( policy.timeoff_breakdown !== undefined ? policy.timeoff_breakdown.text : 'Unlimited' )+')</option>';
            }
        });
        $('#js-policies').html(rows);
        $('#js-policies').select2();
        $('.js-policy-box').show();
        })
    //
    function setTimeView(target){
        var row = '';
        if(policies[0]['format'] == 'D:H:M'){
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'D:H'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'H:M'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes" />';
            row += '    </div>';
            row += '</div>';
        }else if(policies[0]['format'] == 'H'){
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
            row += '    </div>';
            row += '</div>';
        }else{
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
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
        let levelText = level == 1 ? 'Team Lead' : level == 2 ? 'Supervisor' : 'approver';
        return `<a href="javascript:void(0)" class="text-success">${levelText}</a>`;
    }
    //
    function getStatusHTML(status){
        status = status.toLowerCase() == 'cancelled' ? 'canceled' : status;
        return '<p><span class="text-'+( status.toLowerCase() == 'approved' ? 'success' : status.toLowerCase() == 'rejected' ? 'danger' : 'warning' )+' cs-status-text">'+( status.toUpperCase() )+'</span></p>';
    }
      // Loader
    function loader(show_it, msg){
        msg = msg == undefined || msg == '' ? 'Please wait, while we are processing your request.' : msg;
        show_it = show_it === undefined || show_it == true || show_it == 'show' ? 'show' : 'hide';
        $('.js-ad-loader').find('.cs-loader-text').html(msg);
        if(show_it === 'show') $('.js-ad-loader').show();
        else $('.js-ad-loader').hide();
    }
    function getHolidayText(v){
        var 
        b = moment(v.from_date, 'YYYY-MM-DD'),
        a = moment(v.to_date, 'YYYY-MM-DD'),
        c = a.diff(b, 'days');
        //
        if( c >= 2 ){
            return moment(v.from_date, 'YYYY-MM-DD').format('MMMM, D')+' - '+moment(v.to_date, 'YYYY-MM-DD').format('MMMM, D');
        }
        return moment(v.from_date, 'YYYY-MM-DD').format('MMMM, D');
    }
      String.prototype.ucwords = function() {
        return this.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
            function(s){
                return s.toUpperCase();
            }
        );
    };

    function getNegativeBalance(o){
        if(o.accrual.allow_negative_balance == 0) return 0;
        //
        var r = o.accrual.negative_balance;
        //
        if(o.accrual.accrual_method == 'hours_per_month'){
            return r * 60;
        }
        //
        return r * (o.employee_timeslot * 60);
    }

    //
    function remakeAccessLevel(obj){
        if(typeof(obj['is_executive_admin']) !== undefined && obj['is_executive_admin'] != 0){
            obj['access_level'] = 'Executive '+obj['access_level'];
        }
        if(obj['access_level_plus'] == 1 && obj['pay_plan_flag'] == 1) return obj['access_level']+' Plus / Payroll';
        if(obj['access_level_plus'] == 1) return obj['access_level']+' Plus';
        if(obj['pay_plan_flag'] == 1) return obj['access_level']+' Payroll';
        return obj['access_level'];
    }

     function remakeEmployeeName(o, i){
        //
        var r = '';
        //
        if(i == undefined) r += o.first_name+' '+o.last_name;
        //
        if(o.job_title != '' && o.job_title != null) r+= ' ('+( o.job_title )+')';
        //
        r += ' [';
        //
        if(typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
        //
        if(o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1)  r += o['access_level']+' Plus / Payroll';
        else if(o['access_level_plus'] == 1) r += o['access_level']+' Plus';
        else if(o['pay_plan_flag'] == 1) r += o['access_level']+' Payroll';
        else r += o['access_level'];
        //
        r += ']';
        //
        return r;
    }
        function getArrayFromMinutes(
        minutes, 
        defaultTimeFrame, 
        slug
    ){
        var r = {};
        r['timeFrame'] = defaultTimeFrame;
        r['originalMinutes'] = minutes;
        r['D:H:M'] = {};
        //
        r['D:H:M']['days'] = parseInt(((minutes)/(defaultTimeFrame * 60)));
        r['D:H:M']['hours'] = parseInt((((minutes)%(defaultTimeFrame * 60))/60));
        r['D:H:M']['minutes'] = parseInt((((minutes)%(defaultTimeFrame * 60))%60));

        r['H:M'] = {};
        r['H:M']['hours'] = parseInt((minutes/60));
        r['H:M']['minutes'] = parseInt((minutes%60));

        r['D'] = {};
        r['D']['days'] = ( minutes/ (defaultTimeFrame * 60)).toFixed(2);

        r['M'] = {};
        r['M']['minutes'] = minutes;

        r['H'] = {};
        r['H']['hours'] = ( minutes/60).toFixed(2);

        r['active'] = r[slug];
        r['text'] = '';

        if(r[slug]['days'] !== undefined) r['text'] += r[slug]['days']+' day'+(r[slug]['days'] > 1 ? 's' : '')+', ';
        if(r[slug]['hours']  !== undefined ) r['text'] += r[slug]['hours']+' hour'+(r[slug]['hours'] > 1 ? 's' : '')+', ';
        if(r[slug]['minutes'] !== undefined) r['text'] += r[slug]['minutes']+' minute'+(r[slug]['minutes'] > 1 ? 's' : '')+', ';
        r['text'] = r['text'].substring(0, r['text'].length - 2);

        return r;
    }

    // 
    function getPendingTimeOff( o ){
        return new Promise((res, rej) => {
            // Lets create a AJAX request
            $.post(
                handlerURI,
                o,
                function( resp ){
                    //
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response);
                        rej();
                        return;
                    }
                    //
                    res(resp.Data);
                }
            );
        }) 
    }


    async function getAdditionalTime(
        megaOBJ, 
        t
    ){
        //
        var r = {
            additionTime: 0,
            isError: false
        };
        // Handle monthly time offs
        if( 
            t.accrual.accrual_frequency != 'none' &&
            t.is_unlimited == '0'
        ){
            //
            var tt = parseInt(t.actual_allowed_timeoff) + parseInt(getNegativeBalance(t));
            //
            var consumedTimeOffArray = await getPendingTimeOff({
                action: 'check_available_time',
                companySid: megaOBJ.companySid,
                employeeSid: megaOBJ.employeeSid,
                policyId: megaOBJ.policyId,
                startDate: megaOBJ.startDate,
                endDate: megaOBJ.endDate,
                employeeTimeSlot: t.employee_timeslot
            });


            // Time off was consumed at some point
            if( consumedTimeOffArray.length !== 0 ){
                //
                var 
                    i = 0,
                    j = 0,
                    il = consumedTimeOffArray.length,
                    jl = megaOBJ.requestedDays.days.length,
                    md,
                    sd,
                    ed,
                    cd,
                    c = {},
                    rs;
                //
                for( i; i < il; i++ ){
                    //
                    sd = moment( consumedTimeOffArray[i]['request_from_date'] ).format('MM');
                    ed = moment( consumedTimeOffArray[i]['request_to_date'] ).format('MM');
                    ct = consumedTimeOffArray[i]['requested_time'];
                    //
                    if(c[sd] === undefined) c[sd] = { month: sd, time: parseFloat(ct) };
                    else c[sd]['time'] = c[sd]['time'] + parseFloat(ct);
                }
                //
                for( j; j < jl; j++ ){
                    //
                    md = moment( megaOBJ.requestedDays.days[j].date ).format('MM');
                    //
                    if(c[md] !== undefined){
                        rs = getArrayFromMinutes(
                            tt - c[md]['time'],
                            t.employee_timeslot,
                            t.format
                        );

                        //
                        if(tt - c[md]['time'] <= 0){
                            alertify.alert('WARNING!', 'You have already consumed the allowed time on <b>'+( megaOBJ.requestedDays.days[j].date )+'</b>.');
                            r.isError = true;
                        } else if( tt - c[md]['time'] < megaOBJ.requestedDays.days[j].time ){
                            alertify.alert('WARNING!', 'You have only <b>'+( rs.text )+'</b> left on <b>'+( megaOBJ.requestedDays.days[j].date )+'</b>.');
                            r.isError = true;
                        } else{
                            r.additionTime += megaOBJ.requestedDays.days[j].time;
                        }
                    }  
                }
            }
        }

        //
        return r;
    }
</script>
