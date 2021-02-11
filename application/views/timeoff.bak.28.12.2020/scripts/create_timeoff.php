
<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        var
        currentEmployeeOBJ = {},
        employees = [],
        holidayDates = <?=json_encode($holidayDates);?>,
        timeOffDays = <?=json_encode($timeOffDays);?>,
        fmla = {},
        employeePolicies = [],
        intervalCatcher = null,
        offdates = [];
        //
        var members = <?=json_encode($TeamMembers);?>;
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

        var sortObjectByKey = function(obj){
            var keys = [];
            var sorted_obj = {};

            for(var key in obj){
                if(obj.hasOwnProperty(key)){
                    keys.push(key);
                }
            }

            // sort keys
            keys.sort();

            // create new array based on Sorted Keys
            jQuery.each(keys, function(i, key){
                sorted_obj[key] = obj[key];
            });

            return sorted_obj;
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

        fetchEmployees();
        /* VIEW PAGE START */

        $('#js-status').select2();

        $('#js-date').datepicker({
            format: 'mm-dd-yy',
            beforeShowDay: unavailable
        });

        $('.js-partial-leave').click(function(){
            if($(this).val() == 'yes') $('#js-partial-leave-box').show();
            else $('#js-partial-leave-box').hide();
        });

        CKEDITOR.replace('js-comment');
        CKEDITOR.replace('js-reason');

        /* VIEW PAGE END */

        /* ADD PAGE START*/
        // Fetch employee policies

        var successMSG = '';

        // Save TO from modal
        $(document).on('click', '#js-save-btn', async function(){
            successMSG = '';
            var megaOBJ = currentEmployeeOBJ;
            megaOBJ.sendEmail = $('.js-send-email:checked').val() == 'no' ? 0 : 1;
            megaOBJ.action = 'create_employee_timeoff';
            megaOBJ.reason = CKEDITOR.instances['js-reason'].getData().trim();
            megaOBJ.comment = CKEDITOR.instances['js-comment'].getData().trim();
            megaOBJ.policyId = $('#js-policies').val();
            megaOBJ.status = $('#js-status').val();
            megaOBJ.policyName = $('#js-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            if(megaOBJ.policyId == 0 || megaOBJ.policyId == null){
                alertify.alert('WARNING!', 'Please select a Policy to continue.');
                return;
            }
            megaOBJ.companySid = <?=$companyData['sid'];?>;
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
            var r = await getAdditionalTime(megaOBJ, t);
            //
            if(r.isError) return;
            var checkBalance = (parseInt(t.actual_allowed_timeoff) + parseInt(getNegativeBalance(t)) + r.additionalTime) - parseInt(t.consumed_timeoff);
            //
            if(t.is_unlimited == '0' && requestedDays.totalTime > checkBalance){
                alertify.alert('WARNING!', 'Requested time off can not be greater than allowed time.');
                return;
            }
            
            megaOBJ.slug = t['format'];
            megaOBJ.timeslot = employeePolicies[0].employee_timeslot;

            // FMLA 
            megaOBJ.isFMLA = $('.js-fmla-check:checked').val();
            if(megaOBJ.isFMLA == 'yes' && Object.keys(fmla).length == 0){
                alertify.alert('WARNING!', 'Please fill the selected FMLA form.');
                return;   
            }

            megaOBJ.isFMLA = fmla.type;
            megaOBJ.fmla = fmla;
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( t );

            // Phase 3 code 
            megaOBJ.returnDate = '';
            megaOBJ.relationship = '';
            megaOBJ.temporaryAddress = '';
            megaOBJ.compensationEndTime = '';
            megaOBJ.compensationStartTime = '';
            megaOBJ.emergencyContactNumber = '';
            //
            switch ($('#js-policies[value="'+( $('#js-policies').val() )+'"]').text().toLowerCase()) {
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

            // Disable all fields in modal
            $('#js-save-btn').val('Saving....');
            loader('show');
            // Let's save the TO
            $.post(
                baseURI+'handler',
                megaOBJ,
                function(resp){
                    //
                    $('#js-save-btn').val('SAVE');
                    // When an error occured
                    if(resp.Status === false){
                        loader('false');
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
                loader('false');
                alertify.alert('SUCCESS!', successMSG, function(){
                    window.location.reload();
                });
                $('#js-cancel-btn').trigger('click');
            }
            //
            var currentIndex = attachmentId === undefined ? 0 : attachmentId;
            //
            attachmentId = Object.keys(attachedDocuments)[currentIndex];
            //
            if(attachmentId === undefined){
                loader('false');
                alertify.alert('SUCCESS!', successMSG, function(){
                    window.location.reload();
                });
                $('#js-cancel-btn').trigger('click');
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
                    loader('false');
                    alertify.alert('SUCCESS!', successMSG, function(){
                        window.location.reload();
                    });
                    $('#js-cancel-btn').trigger('click');
                    return;
                }
                //
                setTimeout(function(){
                    startDocumentUploadProcess(requestId, currentIndex);
                }, 1000);
            });

        }
        /* ADD PAGE END*/

        loader('hide');


        // Employees
        function fetchEmployees(){
            //
            if(typeof members === 'object'){
                setTimeout(() => {
                    employees = members;
                    var rows = '';
                    employees.map(function(v){
                        rows += '<option data-id="'+( v.user_id )+'"  data-firstname="'+( v.first_name )+'" data-jobtitle="'+( v.job_title )+'" data-image="'+( v.image )+'" data-lastname="'+( v.last_name )+'" data-email="'+( v.email )+'" data-role="'+( remakeAccessLevel(v) )+'">';
                        rows += ''+( v.full_name )+'';
                        if(v.job_title != null && v.job_title != 'null') rows +=  ' ('+( v.job_title )+')';
                        rows += ' ['+( remakeAccessLevel(v) )+']';
                        rows += '</option>';
                    });
                    $('#js-employee-ul').html(rows);
                    $('#js-employee-ul').select2({
                        emplateResult: formatState,
                        templateSelection: formatState
                    });
                }, 0);
            } else{
                //
                $.post(baseURI+'handler', {
                    action: 'get_company_employees',
                    companySid: <?=$company_sid;?>
                }, function(resp){
                    if(resp.Status === false){
                        console.log('Failed to load employees.');
                        return;
                    }
                    employees = resp.Data;
                    var rows = '';
                    employees.map(function(v){
                        rows += '<option data-id="'+( v.user_id )+'"  data-firstname="'+( v.first_name )+'" data-jobtitle="'+( v.job_title )+'" data-image="'+( v.image )+'" data-lastname="'+( v.last_name )+'" data-email="'+( v.email )+'" data-role="'+( remakeAccessLevel(v) )+'">';
                        rows += ''+( v.full_name )+'';
                        if(v.job_title != null && v.job_title != 'null') rows +=  ' ('+( v.job_title )+')';
                        rows += ' ['+( remakeAccessLevel(v) )+']';
                        rows += '</option>';
                    });
                    $('#js-employee-ul').html(rows);
                    $('#js-employee-ul').select2({
                        emplateResult: formatState,
                        templateSelection: formatState
                    });
                });
            }
        }
        //
        function formatState (opt) {
            if (!opt.id) {
                return opt.text.toUpperCase();
            } 

            var optimage = $(opt.element).attr('data-image'); 
            var jobtitle = $(opt.element).attr('data-jobtitle'); 
            var title = $(opt.element).attr('data-firstname') +' '+ $(opt.element).attr('data-lastname');
  
            if(jobtitle != null && jobtitle != '') title += ' ('+( jobtitle )+')';
            title += ' ['+( $(opt.element).attr('data-role') )+']';
            if(!optimage || optimage == null || optimage == 'null') optimage = "<?=base_url('assets/images/img-applicant.jpg');?>";
            else optimage = "<?=AWS_S3_BUCKET_URL;?>"+optimage;
            var $opt = $(
               '<span><img  style="padding: 5px; margin-left: 5px;" src="' + optimage + '" width="60px" /> <span>' + title  + '</span></span>'
            );
            resetBox();
            //
            currentEmployeeOBJ.employeeFullName = $(opt.element).text;
            currentEmployeeOBJ.employeeFirstName = $(opt.element).attr('data-firstname');
            currentEmployeeOBJ.employeeLastName = $(opt.element).attr('data-lastname');
            currentEmployeeOBJ.employeeEmail = $(opt.element).attr('data-email');
            currentEmployeeOBJ.employeeSid = $(opt.element).attr('data-id');
            //
            loadPage($(opt.element).attr('data-id'));
            return $opt;
        };
        //
        $(".radio.radio-custom-partial input[type='radio']").click(function() {
            var current = $(this).val();

            if(current == 'yes'){
                $(".partial-leave-options").show();
            } else {
                $(".partial-leave-options").hide();
            }
        });

        // // Select an employee
        // $(document).on('change', "#js-employee-ul", function (e) {
        //     e.preventDefault();
        //     var selText = $(this).text();
        //     //
        //     var trg = $('#js-employee-ul').find('option[value="'+( $(this).val() )+'"]');
        //     console.log(trg.text());
        //     console.log(trg.data());
        //     console.log($(this).val());
        //     console.log($(this).attr('data-firstname'));
        //     return;
        //     // var imgSource = $(this).find('img').attr('src');
        //     // var img = '<img src="' + imgSource + '"/>';
        //     // $(this).parents('.img-dropdown').find('.dropdown-toggle').html(img + ' ' + selText + ' <span class="hr-select-dropdown"></span>');
        //     resetBox();
        //     //
        //     currentEmployeeOBJ.employeeFullName = selText;
        //     currentEmployeeOBJ.employeeFirstName = $(this).data('firstname');
        //     currentEmployeeOBJ.employeeLastName = $(this).data('lastname');
        //     currentEmployeeOBJ.employeeEmail = $(this).data('email');
        //     currentEmployeeOBJ.employeeSid = $(this).data('id');
        //     //
        //     loadPage($(this).data('id'));
        // });
        //
        function loadPage(employeeSid){
            //
            loader(true);
            
            // Fetch policies
            fetchEmployeeAllPolicies(employeeSid, function(resp){
                // Set policies
                var rows = '';
                rows += '<option value="0">Select Policy</option>';
                // Lets reset the policy
                let policyList = {};
                employeePolicies = resp.Data;
                resp.Data.map(function(policy){
                    if(!policyList.hasOwnProperty(policy.Category)) policyList[policy.Category] = [];
                    policyList[policy.Category].push(policy);
                });

                policyList  = sortObjectByKey(policyList);

                Object.keys(policyList).map((p) => {
                    let policy = policyList[p];
                    rows += '<optgroup label="'+(p)+'">';
                        policy.map((pi) => {
                            rows += '<option value="'+(pi.sid)+'">'+(pi.title)+' ('+(  pi.timeoff_breakdown !== undefined ? pi.timeoff_breakdown.text : 'Unlimited' )+')</option>';
                        });
                    rows += '</optgroup>';
                });

                $('#js-policies').html(rows);
                $('#js-policies').select2();
                // Set view
                setTimeView(
                    $('#js-time'),
                    resp.Data[0]
                );
                $('#js-main-box').show();
                $('#js-btn-box').show();
                loader('hide');
                
            });
        }
        //
        $('#js-cancel-btn').click(function(e){
            e.preventDefault();
            resetBox(1);
        });
        //
        $('#js-policies').change(function(){
            var v = $('#js-policies option[value="'+( $(this).val() )+'"]').text().toLowerCase().trim().replace();
            if(v.match(/(fmla)/g) !== null){
                $('.js-fmla-wrap').show(0);
            }else{
                $('.js-fmla-wrap').hide(0);
                $('.js-fmla-box').hide(0);
                $('.js-fmla-check[value="no"]').prop('checked', true);
                $('.js-fmla-type-check').prop('checked', false);
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
        });
        //
        function resetBox(full){
            currentEmployeeOBJ = {};
            // Reset all data
            if(full !== undefined) $('#js-select-employee').find('.dropdown-toggle').html('Select an Employee<span class="hr-select-dropdown"></span>');
            $('#js-main-box').hide();
            $('#js-partial-leave-box').hide();
            $('#js-time').html('');
            $('#js-partial-note').val('');
            $('.js-partial-leave[value="no"]').prop('checked', true);
            $('.js-send-email[value="no"]').prop('checked', true);
            $('#js-date').val(moment().format('MM-DD-YYYY'));
            $('#js-status').select2('val', 'approved');
            $('#js-btn-box').hide();
            $('.js-no-records').show();
            $('.js-attachments').remove();
            localDocument = {};
            attachedDocuments = {};
            CKEDITOR.instances['js-comment'].setData('');
            CKEDITOR.instances['js-reason'].setData('');
            $('#js-timeoff-date-box tbody').html('');
            $('#js-timeoff-date-box').hide();
        }
        // Fetch employee all policies
        function fetchEmployeeAllPolicies(employeeId, cb){
            $.post(baseURI+'handler', {
                action: 'get_employee_all_policies',
                companySid: <?=$company_sid;?>,
                employeeSid: employeeId
            }, function(resp){
                if(resp.Status === false){
                    loader('hide');
                    alertify.alert('ERROR!', 'It look like there are no policies assigned to the selected employee.');
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
                row += '        <label>Days </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }else if(employeePolicies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            //
            $(target).html(row);
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
                defaultTimeslot: employeePolicies[0].employee_timeslot,
                defaultTimeslotMinutes: (employeePolicies[0].employee_timeslot * 60),
                format: format.replace(/,$/, ''),
                formated: inText,
                requestedMinutes: (days * employeePolicies[0].employee_timeslot * 60) + (hours * 60) + minutes
            };
        }
        //
        function setPolicyTime(days, hours, minutes){
            if(format.match(/D/)) $('#js-request-days').val(days);
            if(format.match(/H/)) $('#js-request-hours').val(hours);
            if(format.match(/M/)) $('#js-request-minutes').val(minutes);
        }
        //
        function getPolicy( sid ){
            var i = 0,
            l = employeePolicies.length;
            //
            for(i; i < l; i++) if(employeePolicies[i]['sid'] == sid) return employeePolicies[i];
            return null;
        }

         // FMLAs
        $('.js-fmla-check').click(function(e){
            if($(this).val() == 'yes') $('.js-fmla-box').show();
            else{ $('.js-fmla-type-check').prop('checked', false);  fmla = {}; $('.js-fmla-box').hide(); }
        });
        //
        $('.js-fmla-type-check').click(function(e){
            //
            $('.js-form-area').hide();
            fmla = {};
            if($(this).val() !== 'designation'){
                //
                $('#js-timeoff-modal').modal('toggle');
                $('body').css('overflow', 'hidden');
                $('.js-fmla-employee-firstname').val(currentEmployeeOBJ.employeeFirstName);
                $('.js-fmla-employee-lastname').val(currentEmployeeOBJ.employeeLastName);
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
        $('#js-fmla-modal').on('hide.bs.modal', function(){ $('body').css('overflow-y', 'auto'); });
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
            beforeShowDay: unavailable
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));
        //
        $('#js-bereavement-return-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));
        //
        $('#js-compensatory-return-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable
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

        var timeRowsOBJ = {};

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
                //
                if($.inArray(sd.format('MM-DD-YYYY'), offdates) === -1 && $.inArray( sd.format('dddd').toLowerCase(), timeOffDays) === -1){
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
                    rows +=          setTimeView('#row_'+( i )+'', '-el'+i, sd.format('MMDDYYYY'));
                    rows += '        </div>';
                    rows += '    </th>';
                    rows += '</tr>';
                    //
                } 

            }
            //
            if(rows == '') return;
            //
            $('#js-timeoff-date-box tbody').html(rows);
            $('#js-timeoff-date-box').show();
        }

        $(document).on('keyup','.js-number', function() {
            timeRowsOBJ[$(this).data('ids')][$(this).data('type')] = $(this).val(); 
        });

        //
        function getTime(ind, indd){
            if(ind === undefined) return '';
            if(!timeRowsOBJ.hasOwnProperty(ind)){
                timeRowsOBJ[ind] = {};
                timeRowsOBJ[ind]['hour'] = employeePolicies[0]['user_shift_hours'];
                timeRowsOBJ[ind]['minute'] = employeePolicies[0]['user_shift_minutes'];
            }
            return timeRowsOBJ[ind][indd];
        }

        //
        function setTimeView(target, prefix, position){
            //
            var row = '';
            if(employeePolicies[0]['format'] == 'D:H:M'){
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" data-ids="'+(position)+'" data-type="day" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'"  />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" data-ids="'+(position)+'" data-type="hour" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+(getTime(position, 'hour'))+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" data-ids="'+(position)+'" data-type="minute" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+(getTime(position, 'minute'))+'" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" data-ids="'+(position)+'" data-type="day" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" value="'+(getTime(position))+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" data-ids="'+(position)+'" data-type="hour" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+(getTime(position, 'hour'))+'" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" data-ids="'+(position)+'" data-type="hour" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+(getTime(position, 'hour'))+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" data-ids="'+(position)+'" data-type="minute" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+(getTime(position, 'minute'))+'" />';
                row += '    </div>';
                row += '</div>';
            }else if(employeePolicies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" data-ids="'+(position)+'" data-type="hour" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+(getTime(position, 'hour'))+'" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" data-ids="'+(position)+'" data-type="minute" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+(getTime(position, 'minute'))+'" />';
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
                var time = getTimeInMinutes('el'+( $(this).data('id') ));
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

             //
    function remakeEmployeeName(
        o,
        d
    ){
        //
        var r = '';
        //
        if(d === undefined) r += o.first_name+' '+o.last_name;
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

        //
        <?php $this->load->view('timeoff/scripts/common'); ?>
        <?php $this->load->view('timeoff/scripts/attachments'); ?>
       
    })

</script>
<style>
.cs-required{
    color:#cc0000;
   font-weight:bold;
}
.modal-header-bg{
    background-color: #81b431;
    color: #fff;
    border-radius: 5px 5px 0 0;
}
.modal-header-bg .modal-title{
    font-weight: 600;
}
.modal-header-bg .close{
    color: #fff;
    opacity: 1;
}
.ui-datepicker-unselectable .ui-state-default{ background-color: #555555; border-color: #555555; }
</style>
