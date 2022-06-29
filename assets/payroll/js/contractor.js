/**
 * Process employee onboard for payroll
 *
 * @package Payroll Contractor
 * @version 1.0
 * @author  AutomotoHR <www.automotohr.com>
 */
$(function Contractor() {
    /**
     * Set modal id
     * @type {string}
     */
    var modalId = "jsContractorOnboardModal";

    /**
     * Set modal loader key
     * @type {string}
     */
    var modalLoader = modalId + "Loader";

    /**
     * Saves the XHR (AJAX) object
     * @type {null|object}
     */
    var xhr = null;

    /**
     * Saves the employee list
     * @type {array}
     */
    var employeeList = [];
    
    /**
     * Saves the employee id
     * @type {number}
     */
    var selectedEmployeeId = 0;
    
    /**
     * Saves the contractor sid
     * @type {number}
     */
    var selectedContractorId = 0;

    /**
     * Starts the process of adding employees on payroll
     * 
     */
    $('#jsPayrollContractorAddBtn').click(function(event) {
        //
        event.preventDefault();
        //
        xhr = null;
        //
        Model({
            Id: modalId,
            Title: '<span class="' + modalId + 'Title">Add Contractor To Payroll</span>',
            Body: '<div id="' + modalId + 'Body" class="csPageWrap"></div>',
            Loader: modalLoader,
            Container: 'container',
            CancelClass: 'btn-cancel csW'
        }, getEmployeesForOnboard);
    });

    /**
     * Handle employee selection
     */
    $(document).on('change', '#jsPayrollContractorEmployees', function() {
        //
        selectedEmployeeId = 0;
        //
        if ($(this).val() === 0) {
            return;
        }
        //
        var id = $(this).val();
        //
        employeeList.map(function(emp) {
            //
            if (emp.user_id == id) {
                //
                selectedEmployeeId = id;
                //
                $('#jsPayrollContractorFirstName').val(emp.first_name);
                $('#jsPayrollContractorLastName').val(emp.last_name);
                $('#jsPayrollContractorMiddleInitial').val(emp.middle_initial);
                $('#jsPayrollContractorStartDate').val(emp.start_date ? moment(emp.start_date).format('MM/DD/YYYY') : '');
                $('#jsPayrollContractorEIN').val(emp.ssn);
                $('#jsPayrollContractorEmail').val(emp.email);
            }
        });
    });

    /**
     * 
     */
    $(document).on('click', '.jsPayrollSaveContractor', function(event) {
        //
        event.preventDefault();
        //
        var obj = {
            id: selectedEmployeeId,
            type: $('#jsPayrollContractorType').val(),
            wage_type: $('#jsPayrollContractorWageType').val(),
            first_name: $('#jsPayrollContractorFirstName').val().trim(),
            last_name: $('#jsPayrollContractorLastName').val().trim() || '',
            start_date: $('#jsPayrollContractorStartDate').val().trim(),
            self_onboarding: $('#jsPayrollContractorSelfOnboarding').val(),
            email: $('#jsPayrollContractorEmail').val() || '',
            middle_initial: $('#jsPayrollContractorMiddleInitial').val()[0] || '',
            business_name: $('#jsPayrollContractorBusinessName').val() || '',
            ein: $('#jsPayrollContractorEIN').val() || ''
        };

        // Validation
        if (!obj.first_name) {
            return alertify.alert('Warning!', 'First name is required.', ECB);
        }
        if (!obj.start_date) {
            return alertify.alert('Warning!', 'Start date is required.', ECB);
        }
        if (obj.start_date == true && !obj.email) {
            return alertify.alert('Warning!', 'Email address is required.', ECB);
        }
        if (obj.type == 'Business' && !obj.business_name) {
            return alertify.alert('Warning!', 'Business name is required.', ECB);
        }
        //
        xhr = $.post(
                baseURI + 'payroll/' + (companyId) + '/contractor',
                obj
            )
            .success(function() {
                //
                xhr = null;
                //
                return alertify.alert(
                    'Success!',
                    'A new contractor is successfully added.',
                    function() {
                        //
                        $(modalId).find('.jsModalCancel').click();
                        //
                        window.location.reload();
                    }
                );
            })
            .fail(ErrorHandler);
        //
        ml(true, modalLoader);
    });
    
    /**
     * 
     */
    $(document).on('click', '.jsPayrollEditContractor', function(event) {
        //
        event.preventDefault();
        //
        var obj = {
            id: selectedContractorId,
            type: $('#jsPayrollContractorEditType').val(),
            wage_type: $('#jsPayrollContractorEditWageType').val(),
            first_name: $('#jsPayrollContractorEditFirstName').val().trim(),
            last_name: $('#jsPayrollContractorEditLastName').val().trim() || '',
            start_date: $('#jsPayrollContractorEditStartDate').val().trim(),
            self_onboarding: $('#jsPayrollContractorEditSelfOnboarding').val(),
            middle_initial: $('#jsPayrollContractorEditMiddleInitial').val()[0] || '',
            business_name: $('#jsPayrollContractorEditBusinessName').val() || '',
            ein: $('#jsPayrollContractorEditEIN').val() || '',
            hourly_rate: $('#jsPayrollContractorEditHourlyRate').val() || 0,
            edit: true
        };

        // Validation
        if (!obj.wage_type == 'Hourly' && (!obj.hourly_rate || obj.hourly_rate == 0)) {
            return alertify.alert('Warning!', 'Hourly rate is required.', ECB);
        }
        if (!obj.first_name) {
            return alertify.alert('Warning!', 'First name is required.', ECB);
        }
        if (!obj.start_date) {
            return alertify.alert('Warning!', 'Start date is required.', ECB);
        }
        if (obj.type == 'Business' && !obj.business_name) {
            return alertify.alert('Warning!', 'Business name is required.', ECB);
        }
        //
        xhr = $.post(
                baseURI + 'payroll/' + (companyId) + '/contractor',
                obj
            )
            .success(function(resp) {
                //
                xhr = null;
                //
                if(!resp.status){
                    return alertify.alert(
                        'Errors',
                        resp.errors.join('<br />'),
                        ECB
                    );
                }
                //
                return alertify.alert(
                    'Success!',
                    'A contractor is successfully updated.',
                    function() {
                        //
                        $(modalId).find('.jsModalCancel').click();
                        //
                        window.location.reload();
                    }
                );
            })
            .fail(ErrorHandler);
        //
        ml(true, modalLoader);
    });

    /**
     * 
     */
    $(document).on('click', '.jsPayrollContractorEdit', function(event){
        //
        event.preventDefault();
        //
        selectedContractorId = $(this).closest('.jsPayrollContractorTR').data('id');
        //
        Model({
            Id: modalId,
            Title: '<span class="' + modalId + 'Title">Edit Contractor</span>',
            Body: '<div id="' + modalId + 'Body" class="csPageWrap"></div>',
            Loader: modalLoader,
            Container: 'container',
            CancelClass: 'btn-cancel csW'
        }, getContractor);
    });

    /**
     * Fetched employees that are not on payroll
     */
    function getEmployeesForOnboard() {
        //
        if (employeeList.length) {
            return LoadContractors(employeeList);
        }
        //
        xhr = $.get(
                baseURI + 'payroll/get/' + (companyId) + '/employees'
            )
            .done(function(response) {
                //
                xhr = null;
                //
                LoadContractors(response)
            })
            .error(ErrorHandler);
    }

    /**
     * Generates the view
     * @param {array} employees 
     * @returns 
     */
    function LoadContractors(employees) {
        //
        if (!employees.length) {
            employeeList = [];
            return;
        }
        //
        employeeList = employees;
        //
        var rows = '';
        //
        rows += '<div class="container">';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Select Employee</label>';
        rows += '          <select id="jsPayrollContractorEmployees">';
        rows += '               <option value="0">[Select Employee]</option>';
        employeeList.map(function(emp) {
            rows += '           <option value="' + (emp.user_id) + '">' + (emp.full_name_with_role) + '</option>';
        });
        rows += '          </select>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '      <hr />';
        rows += '          <label>Type <span class="csRequired"></span></label>';
        rows += '          <select required class="form-control" id="jsPayrollContractorType">';
        rows += '               <option value="Individual">Individual</option>';
        rows += '               <option value="Business">Business</option>';
        rows += '          </select>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '      <br />';
        rows += '          <label>Wage Type <span class="csRequired"></span></label>';
        rows += '          <select required class="form-control" id="jsPayrollContractorWageType">';
        rows += '               <option value="Fixed">Fixed</option>';
        rows += '               <option value="Hourly">Hourly</option>';
        rows += '          </select>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '       <br />';
        rows += '          <label>First Name <span class="csRequired"></span></label>';
        rows += '          <input type="text" required placeholder="John" class="form-control" id="jsPayrollContractorFirstName" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Last Name</label>';
        rows += '          <input type="text" required placeholder="Doe" class="form-control" id="jsPayrollContractorLastName" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Start Date</label>';
        rows += '          <input type="text" readonly required class="form-control" id="jsPayrollContractorStartDate" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '      <br />';
        rows += '          <label>Self Onboarding</label>';
        rows += '          <select required class="form-control" id="jsPayrollContractorSelfOnboarding">';
        rows += '               <option value="false">No</option>';
        rows += '               <option value="true">Yes</option>';
        rows += '          </select>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Email Address</label>';
        rows += '          <input type="email" required placeholder="john.doe@example.com" class="form-control" id="jsPayrollContractorEmail" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Middle Initial</label>';
        rows += '          <input type="text" placeholder="N" class="form-control" id="jsPayrollContractorMiddleInitial" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Business Name</label>';
        rows += '          <input type="text"  class="form-control" id="jsPayrollContractorBusinessName" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>EIN </label>';
        rows += '          <input type="text" placeholder="XXXXXXXXX" class="form-control" id="jsPayrollContractorEIN" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12 text-right">';
        rows += '          <button class="btn btn-orange jsPayrollSaveContractor"><i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save</button>';
        rows += '          <button class="btn btn-black jsModalCancel "><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel</button>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '</div>';
        //
        $('#' + (modalId) + 'Body').html(rows);
        //
        $('#jsPayrollContractorEmployees').select2();
        //
        $('#jsPayrollContractorStartDate').datepicker({
            changeYear: true,
            format: 'mm/dd/yy',
            changeMonth: true
        });
        //
        $('#jsPayrollContractorType').select2({minimumResultsForSearch: -1});
        $('#jsPayrollContractorWageType').select2({minimumResultsForSearch: -1});
        $('#jsPayrollContractorSelfOnboarding').select2({minimumResultsForSearch: -1});
        //
        ml(false, modalLoader);
    }

    /**
     * 
     */
    function getContractors(){
        //
        $.get(
            baseURI + 'payroll/' + (companyId) + '/contractor'
        )
        .success(function(resp) {
            loadView(resp);
        })
        .fail(ErrorHandler);
    }
    
    /**
     * 
     */
    function getContractor(){
        //
        $.get(
            baseURI + 'payroll/' + (companyId) + '/contractor/'+selectedContractorId
        )
        .success(function(resp) {
            LoadContractor(resp);
        })
        .fail(ErrorHandler);
    }

    function LoadContractor(resp){
        //
        var rows = '';
        //
        rows += '<div class="container">';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Type <span class="csRequired"></span></label>';
        rows += '          <select required class="form-control" id="jsPayrollContractorEditType">';
        rows += '               <option value="Individual">Individual</option>';
        rows += '               <option value="Business">Business</option>';
        rows += '          </select>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '      <br />';
        rows += '          <label>Wage Type <span class="csRequired"></span></label>';
        rows += '          <select required class="form-control" id="jsPayrollContractorEditWageType">';
        rows += '               <option value="Fixed">Fixed</option>';
        rows += '               <option value="Hourly">Hourly</option>';
        rows += '          </select>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '       <br />';
        rows += '          <label>Hourly Rate</label>';
        rows += '          <input type="text" placeholder="0.00" class="form-control" id="jsPayrollContractorEditHourlyRate" value="'+(resp.hourly_rate || 0)+'" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '       <br />';
        rows += '          <label>First Name <span class="csRequired"></span></label>';
        rows += '          <input type="text" required placeholder="John" class="form-control" id="jsPayrollContractorEditFirstName" value="'+(resp.first_name)+'" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Last Name</label>';
        rows += '          <input type="text" required placeholder="Doe" class="form-control" id="jsPayrollContractorEditLastName" value="'+(resp.last_name || '')+'" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Start Date</label>';
        rows += '          <input type="text" readonly required class="form-control" id="jsPayrollContractorEditStartDate" value="'+(resp.start_date)+'" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '      <br />';
        rows += '          <label>Self Onboarding</label>';
        rows += '          <select required class="form-control" id="jsPayrollContractorEditSelfOnboarding">';
        rows += '               <option value="false">No</option>';
        rows += '               <option value="true">Yes</option>';
        rows += '          </select>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Middle Initial</label>';
        rows += '          <input type="text" placeholder="N" class="form-control" id="jsPayrollContractorEditMiddleInitial" value="'+(resp.middle_initial || '')+'" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Business Name</label>';
        rows += '          <input type="text"  class="form-control" id="jsPayrollContractorEditBusinessName" value="'+(resp.business_name || '')+'" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>EIN </label>';
        rows += '          <input type="text" placeholder="XXXXXXXXX" class="form-control" id="jsPayrollContractorEditEIN" value="'+(resp.ein || '')+'" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12 text-right">';
        rows += '          <button class="btn btn-orange jsPayrollEditContractor"><i class="fa fa-save" aria-hidden="true"></i>&nbsp;Update</button>';
        rows += '          <button class="btn btn-black jsModalCancel "><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel</button>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '</div>';
        //
        $('#' + (modalId) + 'Body').html(rows);
        //
        $('#jsPayrollContractorEditStartDate').datepicker({
            changeYear: true,
            format: 'mm/dd/yy',
            changeMonth: true
        });
        //
        $('#jsPayrollContractorEditType').select2({minimumResultsForSearch: -1}).select2('val', resp.type);
        $('#jsPayrollContractorEditWageType').select2({minimumResultsForSearch: -1}).select2('val', resp.wage_type);
        $('#jsPayrollContractorEditSelfOnboarding').select2({minimumResultsForSearch: -1}).select2('val', resp.self_onboarding);
        //
        ml(false, modalLoader);
    }


    function loadView(data){
        //
        var rows;
        //
        $('#jsPayrollContractorCount').text('Total: '+data.length)
        //
        if(!data){
            //
            rows += '<tr>';
            rows += '   <td colspan="8">';
            rows += '       <p class="text-center alert alert-info">';
            rows += '       No contractors found';
            rows += '       </p>';
            rows += '   </td>';
            rows += '</tr>';
            //
            return $('#jsPayrollContractorBox').html(rows);
        }
        //
        data.map(function(record){
            //
            rows += '<tr class="jsPayrollContractorTR" data-id="'+(record.sid)+'">';
            rows += '   <td class="vam">';
            rows += '       <strong>';
            rows +=         record.first_name+' '+record.last_name;
            rows += '       </strong>';
            rows += '       <p>';
            rows +=         record.email_address;
            rows += '       </p>';
            rows += '   </td>';
            rows += '   <td class="text-right vam">';
            rows += '       <p>';
            rows +=         record.type;
            rows += '       </p>';
            rows += '   </td>';
            rows += '   <td class="text-right vam">';
            rows += '       <p>';
            rows +=         record.wage_type;
            rows += '       </p>';
            rows += '   </td>';
            rows += '   <td class="text-right vam">';
            rows += '       <p>';
            rows +=         record.hourly_rate;
            rows += '       </p>';
            rows += '   </td>';
            rows += '   <td class="text-right vam">';
            rows += '       <p>';
            rows +=         record.ein;
            rows += '       </p>';
            rows += '   </td>';
            rows += '   <td class="text-right vam">';
            rows += '       <p>';
            rows +=         record.business_name;
            rows += '       </p>';
            rows += '   </td>';
            rows += '   <td class="text-right vam">';
            rows += '       <strong>';
            rows +=         record.self_onboarding == 'false' ? 'NO' : "YES";
            rows += '       </strong>';
            rows += '   </td>';
            rows += '   <td class="text-right vam">';
            rows += '       <button class="btn btn-orange jsPayrollContractorEdit">Edit';
            rows += '       </button>';
            rows += '   </td>';
            rows += '</tr>';
        });
        //
        return $('#jsPayrollContractorBox').html(rows);
    }

    /**
     * Handles XHR errors
     * @param {object} error 
     */
    function ErrorHandler(error) {
        //
        xhr = null;
        //
        alertify.alert(
            "Error!",
            "The system failed to process your request. (" + (error.status) + ")",
            ECB
        );
    }

    /**
     * Alertify callback error
     * @returns
     */
    function ECB() {}
    
    //
    getContractors();
});