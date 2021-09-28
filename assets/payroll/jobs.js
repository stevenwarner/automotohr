//
var CompanyLocationsObj = {};

$(function Employee() {
    //
    var LOADER = 'employee';

    /**
     * Adds a new
     */
    $('.jsEAddNew').click(function(event) {
        //
        event.preventDefault();
        //
        Model({
            Id: 'jsEAddNewModal',
            Title: 'Add A Job For ' + employeeNameWithRole,
            Body: '<div class="jsEAddNewModalBody"></div>',
            Loader: 'jsEAddNewModalLoader'
        }, function() {
            //
            var options = '';
            options += '<option value="0">[Select]</option>';
            //
            for (var index in CompanyLocationsObj) {
                options += '<option value="' + (index) + '">' + (CompanyLocationsObj[index]) + '</option>';
            }
            //
            var html = '';
            html += '<div class="container">';
            html += '    <div class="row">';
            html += '        <div class="col-md-8 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Title&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <input type="text" class="form-control jsETitle" placeholder="Regional Manager" />';
            html += '        </div>';
            html += '        <div class="col-md-4 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Hire Date&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <input type="text" class="form-control jsEHireDate" readonly placeholder="MM/DD/YYYY" />';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-md-12 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Location&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <select class="jsELocation">' + (options) + '</select>';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-md-12 col-xs-12 text-right">';
            html += '            <button class="btn btn-success csF16 csB7 jsAddNewJob">';
            html += '               <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Add Job';
            html += '            </button>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';
            //
            $('.jsEAddNewModalBody').html(html);
            //
            $('.jsELocation').select2().select2('val', 0);
            //
            $('.jsEHireDate').datepicker({
                changeYear: true,
                changeMonth: true,
                yearRange: '-90:+0',
            });
            //
            ml(false, 'jsEAddNewModalLoader');
        });
    });

    /**
     * Adds new trigger
     */
    $(document).on('click', '.jsAddNewJob', function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Title = $('.jsETitle').val().trim();
        o.HireDate = $('.jsEHireDate').val().trim();
        o.LocationId = $('.jsELocation option:selected').val();
        // Validation
        if (!o.Title) {
            return alertify.alert('Error!', 'Title is required.');
        }
        if (!o.HireDate) {
            return alertify.alert('Error!', 'Hire date is required.');
        }
        if (o.LocationId == 0) {
            return alertify.alert('Error!', 'Location is required.');
        }
        //
        o.EmployeeId = employeeId;
        //
        ml(true, 'jsEAddNewModalLoader');
        //
        $.ajax({
            method: "POST",
            url: API_URL + '/' + employeeId + '/jobs',
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify(o)
        }).done(function(resp) {
            //
            ml(false, "jsEAddNewModalLoader");
            //
            if (!resp.status) {
                //
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br/>") : resp.response);
            } else {
                //
                return alertify.alert('Success!', resp.response, function() {
                    //
                    $('#jsEAddNewModal .jsModalCancel').click();
                    //
                    Get();
                });
            }
        });
    });

    /**
     * Edit
     */
    $(document).on('click', '.jsEEdit', function(event) {
        //
        event.preventDefault();
        //
        var jobId = $(this).closest('tr').data('id');
        //
        Model({
            Id: 'jsEEditModal',
            Title: 'Add A Job For ' + employeeNameWithRole,
            Body: '<div class="jsEEditModalBody"></div>',
            Loader: 'jsEEditModalLoader'
        }, function() {
            //
            var options = '';
            options += '<option value="0">[Select]</option>';
            //
            for (var index in CompanyLocationsObj) {
                options += '<option value="' + (index) + '">' + (CompanyLocationsObj[index]) + '</option>';
            }
            //
            var html = '';
            html += '<div class="container">';
            html += '    <div class="row">';
            html += '        <div class="col-md-8 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Title&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <input type="text" class="form-control jsETitle" placeholder="Regional Manager" />';
            html += '        </div>';
            html += '        <div class="col-md-4 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Hire Date&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <input type="text" class="form-control jsEHireDate" readonly placeholder="MM/DD/YYYY" />';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-md-12 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Location&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <select class="jsELocation">' + (options) + '</select>';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-md-12 col-xs-12 text-right">';
            html += '            <input type="hidden" class="jsEUpdateId" />';
            html += '            <button class="btn btn-success csF16 csB7 jsEUpdateJob">';
            html += '               <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Update Job';
            html += '            </button>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';
            //
            $('.jsEEditModalBody').html(html);
            //
            $('.jsELocation').select2();
            //
            $('.jsEHireDate').datepicker({
                changeYear: true,
                changeMonth: true,
                yearRange: '-90:+0',
            });
            //
            $.get(
                API_URL + '/' + employeeId + '/jobs/' + jobId
            ).done(function(resp) {
                //
                if (resp.status) {
                    //
                    $('.jsETitle').val(resp.response.Title)
                    $('.jsEHireDate').val(resp.response.HireDate)
                    $('.jsELocation').select2('val', resp.response.LocationId);
                    $('.jsEUpdateId').val(resp.response.JobId);
                }
                //
                ml(false, 'jsEEditModalLoader');
            });
        });
    });

    /**
     * Update
     */
    $(document).on('click', '.jsEUpdateJob', function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Title = $('.jsETitle').val().trim();
        o.HireDate = $('.jsEHireDate').val().trim();
        o.LocationId = $('.jsELocation option:selected').val();
        // Validation
        if (!o.Title) {
            return alertify.alert('Error!', 'Title is required.');
        }
        if (!o.HireDate) {
            return alertify.alert('Error!', 'Hire date is required.');
        }
        if (o.LocationId == 0) {
            return alertify.alert('Error!', 'Location is required.');
        }
        //
        o.EmployeeId = employeeId;
        //
        ml(true, 'jsEEditModalLoader');
        //
        $.ajax({
            method: "PUT",
            url: API_URL + '/' + employeeId + '/jobs/' + $('.jsEUpdateId').val(),
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify(o)
        }).done(function(resp) {
            //
            ml(false, "jsEEditModalLoader");
            //
            if (!resp.status) {
                //
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br/>") : resp.response);
            } else {
                //
                return alertify.alert('Success!', resp.response, function() {
                    //
                    $('#jsEEditModal .jsModalCancel').click();
                    //
                    Get();
                });
            }
        });
    });

    /**
     * Compensations
     */
    $(document).on('click', '.jsView', function(event) {
        //
        event.preventDefault();
        //
        var jobId = $(this).closest('tr').data('id');
        //
        Model({
            Id: "jsViewModal",
            Loader: "jsViewModalLoader",
            Body: '<div id="jsViewModalBody"></div>',
            Title: "Job Details"
        }, function() {
            //
            $.get(
                window.location.origin + '/get_job_detail/' + jobId
            ).done(function(html) {
                //
                $('#jsViewModalBody').html(html);
                //
                GetJobDetails(jobId);
            }).error(function() {
                //
                ml(false, 'jsViewModalLoader');
                //
                return alertify.alert("Error", "Something went wrong.");
            });
        });
    });

    /**
     * Deletes a job
     */
    $(document).on('click', '.jsEDelete', function(event) {
        //
        event.preventDefault();
        //
        var JobId = $(this).closest('tr').data('id');
        // Lets mark it the end of function
        return alertify.confirm(
            "This action will delete the job and the compensations attached to the job.<br><br>Would you like to continue?",
            function() {
                // Yes was triggered
                ml(true, LOADER, 'Please wait while we delete the job and compensations.');
                //
                DeleteJobWithCompensations(JobId);
            }
        ).setHeader('Confirm!');
    });


    /**
     * Get data
     */
    function Get() {
        //
        ml(true, LOADER);
        //
        $.get(API_URL + '/' + employeeId + '/jobs')
            .done(function(resp) {
                // Hides the loader
                ml(false, LOADER);
                //
                var rows = '';
                //
                if (resp.response.length) {
                    //
                    resp.response.map(function(record) {
                        //
                        rows += '<tr data-id="' + (record.JobId) + '">';
                        rows += '   <td class="csF16 csB7 vam">' + (record.Title) + '</td>';
                        rows += '   <td class="csF16 vam text-right">' + (CompanyLocationsObj[record.LocationId]) + '</td>';
                        rows += '   <td class="csF16 vam text-right">' + (record.HireDate) + '</td>';
                        rows += '   <td class="csF16 vam text-right">' + (record.Name) + '<br/>' + (record.LastModifiedOn) + '</td>';
                        rows += '   <td class="csF16 vam text-center">';
                        rows += '       <button class="btn btn-success csF16 csB7 jsView"><i class="fa fa-eye csF16"></i>&nbsp;Details</button>';
                        rows += '       <button class="btn btn-warning csF16 csB7 jsEEdit"><i class="fa fa-edit csF16"></i>&nbsp;Edit</button>';
                        rows += '       <button class="btn btn-danger csF16 csB7 jsEDelete"><i class="fa fa-times-circle csF16"></i>&nbsp;Delete</button>';
                        rows += '   </td>';
                        rows += '</tr>';
                    });
                } else {
                    //
                    rows += '<tr>';
                    rows += '   <td class="csF16 vam" colspan="5"><p class="alert alert-info text-center csF16 csB7">No records found</p></td>';
                    rows += '</tr>';
                }
                //
                $('.csCounter').text(resp.response.length);
                //
                $('#jsDataBody').html(rows);
            });
    }

    /**
     * Get Company Locations
     */
    function GetCompanyLocations() {
        //
        $.get(API_URL.replace(/employees/, 'company/locations'))
            .done(function(resp) {
                //
                resp.response.map(function(location) {
                    //
                    var address = location.Street1;
                    //
                    if (location.Street2) {
                        address += ' ' + location.Street2;
                    }
                    //
                    address += ', ' + location.State;
                    address += ', ' + location.City;
                    address += ', ' + location.Country;
                    address += ' (' + location.Zipcode + ')';
                    address += ' (' + location.PhoneNumber + ')';
                    CompanyLocationsObj[location.LocationCode] = address;
                });
                //
                Get();
            });
    }

    /**
     * Delete job with compensations
     * @param {Integer} JobId 
     */
    function DeleteJobWithCompensations(JobId) {
        //
        $.ajax({
            method: "DELETE",
            url: API_URL.replace(/employees/, '') + 'job/' + JobId,
        }).done(function(resp) {
            //
            ml(false, LOADER);
            //
            if (!resp.status) {
                //
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br/>") : resp.response);
            } else {
                //
                return alertify.alert('Success!', resp.response, function() {
                    //
                    Get();
                });
            }
        });
    }

    /**
     * Set AJAX default setting
     */
    $.ajaxSetup({ headers: { "Key": API_KEY } });

    /**
     * Calls
     */
    GetCompanyLocations();
});