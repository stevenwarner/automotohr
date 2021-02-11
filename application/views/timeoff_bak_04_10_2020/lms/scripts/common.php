<script>
    var timeoffDateFormat = 'MMM DD YYYY, ddd';
    var attachmentMode = 'add';
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
            setTimeView('#js-time-draft', 'draft');
            setTimeView('#js-time-edit', 'edit');
            setTimeView('#js-time-view', 'view');
            var typeRows = '';
            var types = [];
            //
            typeRows += '<option value="0">[Select a type]</option>';
            //
            policies.map(function(policy){
                if($.inArray(policy.Category, types) === -1){
                    types.push(policy.Category);
                    typeRows += '<option value="'+( policy.Category )+'">'+( policy.Category )+'</option>';
                }
            });
            $('.js-policy-box').hide(0);
            $('#js-types').html(typeRows);
            $('#js-types').select2();
            $('#js-types-draft').html(typeRows);
            $('#js-types-draft').select2();
            $('#js-types-edit').html(typeRows);
            $('#js-types-edit').select2();
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
        // For FMLA
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
            $('.js-vacation-row-add').show(0);
            fmla = {};
        } else{
            $('.js-vacation-row-add').hide(0);
            $('.js-vacation-row-add input').val('');
        }
        // For Bereavement
        if(v.toLowerCase().match(/(bereavement)/g) !== null ){
            $('.js-bereavement-row-add').show(0);
            fmla = {};
        } else{
            $('.js-bereavement-row-add').hide(0);
            $('.js-bereavement-row-add input').val('');
        }
        // For Compensatory
        if(v.toLowerCase().match(/(compensatory)/g) !== null ){
            $('.js-compensatory-row-add').show(0);
            fmla = {};
        } else{
            $('.js-compensatory-row-add').hide(0);
            $('.js-compensatory-row-add input').val('');
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
    $(document).on('change', '#js-types-draft', function(){
        var rows = '';
        rows += '<option value="0">[Select a policy]</option>';
        var v = $(this).val();
        //
        if(v == 0 || v == null || v == undefined){
            $('.js-fmla-wrap-draft').hide(0);
            fmla = {};
            $('#js-policies-draft').html('');
            $('#js-policies-draft').select2('destroy');
            $('.js-policy-box-draft').hide(0);
            return;
        }

        if(v.toLowerCase().match(/(fmla)/g) !== null ){
            $('.js-fmla-wrap-draft').show(0);
            fmla = {};
        } else{
            $('.js-fmla-wrap-draft').hide(0);
            $('.js-fmla-check-draft[value="no"]').trigger('click');
            fmla = {};
        }
        // For Vacation
        if(v.toLowerCase().match(/(vacation)/g) !== null ){
            $('.js-vacation-row-draft').show(0);
            fmla = {};
        } else{
            $('.js-vacation-row-draft').hide(0);
            $('.js-vacation-row-draft input').val('');
        }
        // For Bereavement
        if(v.toLowerCase().match(/(bereavement)/g) !== null ){
            $('.js-bereavement-row-draft').show(0);
            fmla = {};
        } else{
            $('.js-bereavement-row-draft').hide(0);
            $('.js-bereavement-row-draft input').val('');
        }
        // For Compensatory
        if(v.toLowerCase().match(/(compensatory)/g) !== null ){
            $('.js-compensatory-row-draft').show(0);
            fmla = {};
        } else{
            $('.js-compensatory-row-draft').hide(0);
            $('.js-compensatory-row-draft input').val('');
        }
        policies.map(function(policy){
            if(policy.Category == v){
                rows += '<option value="'+( policy.sid )+'">'+( policy.title )+' ('+( policy.timeoff_breakdown !== undefined ? policy.timeoff_breakdown.text : 'Unlimited' )+')</option>';
            }
        });
        $('#js-policies-draft').html(rows);
        $('#js-policies-draft').select2();
        $('.js-policy-box-draft').show();
    })
    $(document).on('change', '#js-types-edit', function(){
        var rows = '';
        rows += '<option value="0">[Select a policy]</option>';
        var v = $(this).val();
        //
        if(v == 0 || v == null || v == undefined){
            $('.js-fmla-wrap-edit').hide(0);
            fmla = {};
            $('#js-policies-edit').html('');
            $('#js-policies-edit').select2('destroy');
            $('.js-policy-box-edit').hide(0);
            return;
        }

        if(v.toLowerCase().match(/(fmla)/g) !== null ){
            $('.js-fmla-wrap-edit').show(0);
            fmla = {};
        } else{
            $('.js-fmla-wrap-edit').hide(0);
            $('.js-fmla-check-edit[value="no"]').trigger('click');
            fmla = {};
        }
        // For Vacation
        if(v.toLowerCase().match(/(vacation)/g) !== null ){
            $('.js-vacation-row-edit').show(0);
            fmla = {};
        } else{
            $('.js-vacation-row-edit').hide(0);
            $('.js-vacation-row-edit input').val('');
        }
        // For Bereavement
        if(v.toLowerCase().match(/(bereavement)/g) !== null ){
            $('.js-bereavement-row-edit').show(0);
            fmla = {};
        } else{
            $('.js-bereavement-row-edit').hide(0);
            $('.js-bereavement-row-edit input').val('');
        }
        // For Compensatory
        if(v.toLowerCase().match(/(compensatory)/g) !== null ){
            $('.js-compensatory-row-edit').show(0);
            fmla = {};
        } else{
            $('.js-compensatory-row-edit').hide(0);
            $('.js-compensatory-row-edit input').val('');
        }
        policies.map(function(policy){
            if(policy.Category == v){
                rows += '<option value="'+( policy.sid )+'">'+( policy.title )+' ('+( policy.timeoff_breakdown !== undefined ? policy.timeoff_breakdown.text : 'Unlimited' )+')</option>';
            }
        });
        $('#js-policies-edit').html(rows);
        $('#js-policies-edit').select2();
        $('.js-policy-box-edit').show();
    })
    //
    function setTimeView(target, typo){
        typo = typo === undefined ? '' : '-'+typo;
        var row = '';
        if(policies[0]['format'] == 'D:H:M'){
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Days</label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days'+( typo )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Hours</label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( typo )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes</label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( typo )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'D:H'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Days</label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days'+( typo )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours</label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( typo )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'H:M'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours</label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( typo )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes</label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( typo )+'" />';
            row += '    </div>';
            row += '</div>';
        }else if(policies[0]['format'] == 'H'){
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Hours</label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( typo )+'" />';
            row += '    </div>';
            row += '</div>';
        }else{
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes</label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( typo )+'" />';
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

    String.prototype.ucfirst = function() {
        return this.charAt(0).toUpperCase() + this.slice(1)
    };

    function inLoader(a,b){
        b = b === undefined ? '' : '-'+b;
        a = a == undefined || a == true || a == 'show' ? true : a;
        if(!a){
            $('.js-btn'+( b )+'').hide();
            $('#js-load-btn'+( b )+'').show();
        }else{
            $('.js-btn'+( b )+'').show();
            $('#js-load-btn'+( b )+'').hide();
        }
    }

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
</script>
