
<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        var
        currentEmployeeOBJ = {},
        employees = [],
        employeePolicies = [],
        intervalCatcher = null;

        fetchEmployees();
        /* VIEW PAGE START */

        $('#js-status').select2();

        $('#js-date').datepicker({
            format: 'mm-dd-yy'
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

        // Save TO from modal
        $(document).on('click', '#js-save-btn', function(){
            var megaOBJ = currentEmployeeOBJ;
            megaOBJ.note = $('#js-partial-note').val().trim();
            megaOBJ.sendEmail = $('.js-send-email:checked').val() == 'no' ? 0 : 1;
            megaOBJ.action = 'create_employee_timeoff';
            megaOBJ.reason = CKEDITOR.instances['js-reason'].getData().trim();
            megaOBJ.comment = CKEDITOR.instances['js-comment'].getData().trim();
            megaOBJ.policyId = $('#js-policies').val();
            megaOBJ.status = $('#js-status').val();
            megaOBJ.policyName = $('#js-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            if(megaOBJ.policyId == 0){
                alertify.alert('WARNING!', 'Please select a Policy to continue.');
                return;
            }
            megaOBJ.isPartial = $('.js-partial-leave:checked').val() == 'no' ? 0 : 1;
            megaOBJ.companySid = <?=$companyData['sid'];?>;
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
            //
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
                        alertify.alert('ERROR!', resp.Response.replace(/{{TIMEOFFDDATE}}/, moment(megaOBJ.requestDate, 'YYYY-MM-DD').format('MM-DD-YYYY')));
                        return;
                    }
                    // On success
                    alertify.alert('SUCCESS!', resp.Response);
                    $('#js-cancel-btn').trigger('click');
                }
            );
        });
        /* ADD PAGE END*/

        loader('hide');

        // Employees
        function fetchEmployees(){
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
                        rows += '<li><a href="javascript:void(0)" data-id="'+( v.user_id )+'" data-email="'+( v.email )+'">';
                        rows += '<div class="employee-info"><figure><img src="'+( v.image == null || v.image == 'null' || v.image.trim() == '' ? "<?=base_url('assets/images');?>/img-applicant.jpg" : "<?=AWS_S3_BUCKET_URL;?>"+v.image )+'" class="img-circle emp-image"/></figure></div><h5>'+( v.full_name )+'</h5></a>';
                        rows += '</li>';
                    });
                    $('#js-employee-ul').html(rows);
            });
        }
        //
        $(".radio.radio-custom-partial input[type='radio']").click(function() {
            var current = $(this).val();

            if(current == 'yes'){
                $(".partial-leave-options").show();
            } else {
                $(".partial-leave-options").hide();
            }
        });

        // Select an employee
        $(document).on('click', "#js-employee-ul li a", function (e) {
            e.preventDefault();
            var selText = $(this).text();
            var imgSource = $(this).find('img').attr('src');
            var img = '<img src="' + imgSource + '"/>';
            $(this).parents('.img-dropdown').find('.dropdown-toggle').html(img + ' ' + selText + ' <span class="hr-select-dropdown"></span>');
            resetBox();
            //
            currentEmployeeOBJ.employeeFullName = selText;
            currentEmployeeOBJ.employeeEmail = $(this).data('email');
            currentEmployeeOBJ.employeeSid = $(this).data('id');
            //
            loadPage($(this).data('id'));
        });
        //
        function loadPage(employeeSid){
            //
            loader(true);
            
            // Fetch policies
            fetchEmployeeAllPolicies(employeeSid, function(resp){
                // Set policies
                var rows = '';
                rows += '<option value="0">Select Policy</option>';
                resp.Data.map(function(policy){
                    rows += '<option value="'+(policy.sid)+'">'+(policy.title)+' ('+(  policy.timeoff_breakdown !== undefined ? policy.timeoff_breakdown.text : 'Unlimited' )+')</option>';
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
        function resetBox(full){
            currentEmployeeOBJ = {};
            // Reset all data
            if(full !== undefined) $('#js-select-employee').find('.dropdown-toggle').html('Select an Employee<span class="hr-select-dropdown"></span>');
            $('#js-main-box').hide();
            $('#js-partial-leave-box').hide();
            $('#js-time').html('');
            $('#js-partial-note').val('');
            $('.js-partial-leave[value="no"]').prop('checked', true);
            $('.js-send-email[value="yes"]').prop('checked', true);
            $('#js-date').val(moment().format('MM-DD-YYYY'));
            $('#js-status').select2('val', 'approved');
            $('#js-btn-box').hide();
            CKEDITOR.instances['js-comment'].setData('');
            CKEDITOR.instances['js-reason'].setData('');
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
                defaultTimeslot: employeePolicies[0].employee_timeslot,
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
        //
        <?php $this->load->view('timeoff/scripts/common'); ?>
    })

</script>
<style>
.cs-required{
    color:#cc0000;
   font-weight:bold;
}
</style>
