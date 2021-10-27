/**
 * Process company onboard for payroll
 * 
 * @package Company Payroll Onboarding
 * @version 1.0
 * @author  AutomotoHR <www.automotohr.com>
 */
$(function PayrollCompanyOnboard() {
    /**
     * Set default company Id
     * @type {number}
     */
    var companyId = 15598;

    /**
     * Set modal id
     * @type {string}
     */
    var modalId = 'jsPayrollCompanyOnboardModal';

    /**
     * Set modal loader key
     * @type {string}
     */
    var modalLoader = modalId + 'Loader';

    /**
     * Saves the XHR (AJAX) object
     * @type {null|object}
     */
    var xhr = null;

    /**
     * Saves the application key object
     * @type {null|string}
     */
     var API_KEY;

     /**
     * Saves the application key object
     * @type {null|string}
     */
      var API_URL;

    /**
     * Saves the application page location
     * @type {null|int}
     */
    var locationSid;

    /**
     * Saves the application Level
     * @type {null|string}
     */
    var LEVEL;

    /**
     * Saves the employee ID
     * @type {null|int}
     */
    var employeeID;  

    /**
     * Saves the employee bank row ID
     * @type {null|int}
     */
     var addBankID;

     /**
     * Saves the split type
     * @type {null|int}
     */
      var splitType;

    /**
     * Triggers company onboard process
     */
    $('.jsPayrollCompanyOnboard').click(function(event) {
        //
        event.preventDefault();
        //
        companyId = $(this).data('cid') || 0;
        //
        if (companyId == 0) {
            return alertify.alert(
                "Warning!",
                "System failed to verify company reference."
            );
        }
        //
        StartOnboardProcess();
    });

    /**
     * Trigger when cancel is pressed
     */
    $(document).on('click', '.jsPayrollCancel', function(event) {
        //
        event.preventDefault();
        //
        return alertify.confirm('Any unsaved changes to this content will be lost. Are you sure you want to close this page?', function() {
            //
            xhr = null;
            //
            if (locationSid == undefined) {
                $('#' + modalId).find('.jsModalCancel').click();
            } else {
                CompanyDetailPage();
            }
            
        }).setHeader('Confirm!');
    });

    /**
     * Trigger when cancel is pressed
     */
    $(document).on('change', '.jsPaymentMethod', function(event) {
        //
        var type = $(this).val();
        //
        console.log(type);
        //
        if (type == "Check") {
            $(".jsBaseOnDD").hide();
        }

        if (type == "Direct Deposit") {
            $(".jsBaseOnDD").show();
        }
    });

    /**
     * Loads the onboard view
     * 
     * @method Model
     */
    function StartOnboardProcess() {
        //
        xhr = null;
        //
        Model({
            Id: modalId,
            Title: '<span class="' + modalId + 'Title"></span>',
            Body: '<div id="' + modalId + 'Body"></div>',
            Loader: modalLoader,
            Container: 'container-fluid',
            CancelClass: 'btn-cancel csW'
        }, CheckPayrollStatus);
    }

    /**
     * Check if payroll is already enabled
     * 
     * @method WelcomeJourney
     * @method CompanyDetailPage
     */
    function CheckPayrollStatus() {
        //
        $.ajax({
                method: "get",
                url: GetURL('get_payroll_page/status/' + companyId)
            })
            .done(function(resp) {
                //
                $('.' + (modalId) + 'Title').html(resp.name);
                //
                if (resp.payroll_enabled == '0') {
                    return WelcomeJourney();
                }
                //
                $('.' + (modalId) + 'Title').html(resp.name + ' - Company Details');
                //
                if (resp.onboarding_status == "incomplete") {
                    if (resp.onbording_level == "company_address") {
                        CompanyDetailPage();
                    }

                    if (resp.onbording_level == "federal_tax") {
                        GetFederalTaxInfo();
                    }

                    if (resp.onbording_level == "industry") {
                        GetCompanyIndustry();
                    }

                    if (resp.onbording_level == "bank_info") {
                        GetCompanyBankInfo();
                    }

                    if (resp.onbording_level == "employee") {
                        StartEmployeeOnboarding();
                    }

                    if (resp.onbording_level == "payroll") {
                        AddUpdateCompanyPayrollSetting();
                    }

                    LEVEL = resp.onbording_level_id;
                }
                
            })
            .error(HandleError);
    }

    /**
     * Trigger when cancel is pressed
     */
    $(document).on('click', '.jsPayrollConfirmContinue', function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).data("id");
        //
        console.log(LEVEL)
        if (LEVEL < id) {
            $.ajax({
                method: "get",
                url: GetURL('get_payroll_page/change_level/' + companyId),
                data: {next_level: id }
            })
            .done(function(resp) {
                //
                ChangeOnboardingLevel(id);
            })
            .error(HandleError);
        } else {
            ChangeOnboardingLevel(id);
        } 
    });

    function ChangeOnboardingLevel (id) {
        if (id == 1) {
            GetFederalTaxInfo();
        }

        if (id == 2) {
            GetCompanyBankInfo();
        }

        if (id == 3) {
            StartEmployeeOnboarding();
        }
    }

    /**
     * Trigger when side bar event click
     */
    $(document).on('click', '.jsNavBarAction', function(event) {
        //
        event.preventDefault();
        //
        xhr = null;
        var type = $(this).data('id');
        //
        if (type == "company_address") {
            CompanyDetailPage();
        } 

        if (type == "federal_tax_info") {
            GetFederalTaxInfo();
        }

        if (type == "industry") {
            GetCompanyIndustry();
        }

        if (type == "bank_info") {
            GetCompanyBankInfo();
        }

        if (type == "employee") {
            StartEmployeeOnboarding();
        }

        if (type == "payroll") {
            AddUpdateCompanyPayrollSetting();
        }
    });

    /**
     * Trigger when cancel is pressed
     */
     $(document).on('click', '.jsFederalTaxCancel', function(event) {
        //
        event.preventDefault();
        //
        return alertify.confirm('Any unsaved changes to this content will be lost. Are you sure you want to close this page?', function() {
            //
            xhr = null;
            //
            GetFederalTaxInfo();
            
        }).setHeader('Confirm!');
    });

    /**
     * Start the payroll process
     * @method GetURL
     */
    function WelcomeJourney() {
        // Hold until the old AJAX is completed
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/welcome'),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
                    //
                    $('.jsPayrollLoadSelectEmployees').click(EmployeeSelectPage);
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Loads Employee listing page
     * @param {object} event 
     */
    function EmployeeSelectPage(event) {
        //
        event.preventDefault();
        // Hold until the old AJAX is completed
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/employees/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
                    //
                    $('.jsPayrollBackToWelcome').click(WelcomeJourney);
                    //
                    $('.jsPayrollLoadOnboaard').click(OnboardPage);
                    //
                    var preSelected = GetItem('PayrollEmployees' + companyId);
                    //
                    if (preSelected !== null && preSelected.length > 0) {
                        //
                        for (var index in preSelected) {
                            $('.jsPayrollEmployees[value="' + (preSelected[index]) + '"]').prop('checked', true);
                        }
                    }
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Loads Employee listing page
     * @param {object} event 
     */
    function OnboardPage(event) {
        //
        if (event) {
            event.preventDefault();
            //
            var ids = [];
            //
            if ($('.jsPayrollEmployees:checked').length) {
                $('.jsPayrollEmployees:checked').map(function() { ids.push($(this).val()); });
            }
            //
            SaveItem('PayrollEmployees' + companyId, ids);
        }
        // Hold until the old AJAX is completed
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/onboard/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
                    //
                    if ($('.jsPayrollMoveCompanyToPayroll').length) {
                        $('.jsPayrollMoveCompanyToPayroll').click(InitialOnboard);
                    }
                    //
                    if ($('.jsPayrollSetAdmin').length) {
                        $('.jsPayrollSetAdmin').click(PayrollAdminPage);
                    }
                    if ($('.jsPayrollViewAdmin').length) {
                        $('.jsPayrollViewAdmin').click(PayrollAdminViewPage);
                    }
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Load primary admin page
     * @param {object} event 
     */
    function PayrollAdminPage(event) {
        //
        event.preventDefault();
        // Hold until the old AJAX is completed
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/admin/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
                    //
                    $('.jsPayrollSaveAdmin').click(SavePayrollAdmin);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Create a new admin
     * @param {object} event 
     * @returns 
     */
    function SavePayrollAdmin(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.first_name = $('.jsAdminFirstName').val().replace(/[^a-zA-Z]/g, '');
        o.last_name = $('.jsAdminLastName').val().replace(/[^a-zA-Z]/g, '');
        o.email_address = $('.jsAdminEmailAddress').val().trim();
        o.phone_number = $('.jsAdminPhoneNumber').val().replace(/[^\d]/g, '');
        // Validate incoming data
        if (o.first_name.length === 0) {
            return alertify.alert("Warning!", "First name is mendatory.", AlertifyHandler);
        }
        //
        if (o.last_name.length === 0) {
            return alertify.alert("Warning!", "Last name is mendatory.", AlertifyHandler);
        }
        //
        if (o.email_address.length === 0) {
            return alertify.alert("Warning!", "Email is mendatory.", AlertifyHandler);
        }
        //
        if (!validateEmail(o.email_address)) {
            return alertify.alert("Warning!", "Email is not valid.", AlertifyHandler);
        }
        //
        if (o.phone_number && o.phone_number.length !== 10) {
            return alertify.alert("Warning!", "Phone number must be 10 digits long.", AlertifyHandler);
        }
        //
        ml(true, modalLoader);
        //
        $.ajax({
                method: "POST",
                url: GetURL('save_payroll_admin/' + companyId),
                data: o
            })
            .done(function() {
                //
                return alertify.alert('Success', 'You have successfully added the admin.', function() {
                    OnboardPage();
                });
            })
            .error(HandleError);
    }

    /**
     * Load primary admin page
     * @param {object} event 
     */
    function PayrollAdminViewPage(event) {
        //
        event.preventDefault();
        // Hold until the old AJAX is completed
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/admin_view/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
                    //
                    $('.jsPayrollBackToOnboard').click(OnboardPage);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Move company to payroll and 
     * save token to DB
     * @param {object} event
     */
    function InitialOnboard(event) {
        //
        event.preventDefault();
        //
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "post",
                url: GetURL('get_payroll_page/company_address/' + companyId),
                data: { employees: GetItem('PayrollEmployees' + companyId) || [] }
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
                // 
                if (resp.errors) {
                    return alertify.alert('Error!', typeof resp.errors !== undefined ? resp.errors.join('<br/>') : resp.errors);
                }
                CompanyDetailPage();
            })
            .error();
    }

    /**
     * Company details
     */
    function CompanyDetailPage() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/company_details/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.location_url;
                var location_type = resp.location_type;
                //
                LoadContent(resp.html, function() {
                    //
                    if (location_type == "new") {
                        $('.jsPayrollSaveCompanyLocation').click(SaveCompanyLocation);
                    }

                    if (location_type == "listing") {
                        $('.jsPayrollAddCompanyLocation').click(AddUpdateCompanyLocation);
                        $('.jsPayrollUpdateCompanyLocation').click(AddUpdateCompanyLocation);
                    }
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Add company location 
     * get company location add view
     * @param {object} event
     */
    function AddUpdateCompanyLocation(event) {
        //
        event.preventDefault();
        //
        if (xhr !== null) {
            return;
        }
        //
        var locationId = $(this).data("location_id");
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "get",
                url: GetURL('get_payroll_page/add_company_location/' + companyId),
                data: { location_id: locationId }
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.location_url;
                //
                LoadContent(resp.html, function() {
                    //
                    locationSid = locationId;
                    if (locationId == 0) {
                        $('.jsPayrollSaveCompanyLocation').click(SaveCompanyLocation);
                    } else {
                        $('.jsPayrollSaveCompanyLocation').click(UpdateCompanyLocation);
                        $('#jsSaveBtnTxt').text("Update & Continue");
                    }
                    
                    //  
                    ml(false, modalLoader);
                });
            })
            .error();
    }


    /**
     * Save company location
     * @param {object} event 
     * @returns 
     */
    function SaveCompanyLocation(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Street1 = $('.jsStreet1').val().trim();
        o.Street2 = $('.jsStreet2').val().trim();
        o.Country = "USA";
        o.City = $('.jsCity').val().trim();
        o.State = $('.jsState option:selected').val();
        o.Zipcode = $('.jsZip').val().trim();
        o.PhoneNumber = $('.jsPhoneNumber').val().replace(/[^\d]/g,'');
        o.MailingAddress = $('.jsMailingAddress').prop('checked') ? 1 : 0;
        o.FillingAddress = $('.jsFilingAddress').prop('checked') ? 1 : 0;
        o.CompanyId = companyId;
        // Validation
        if (!o.Street1) {
            return alertify.alert('Warning!', 'Street 1 is mendatory.',AlertifyHandler);
        }
        if (!o.City) {
            return alertify.alert('Warning!', 'City is mendatory.',AlertifyHandler);
        }
        if (!o.State) {
            return alertify.alert('Warning!', 'State is mendatory.',AlertifyHandler);
        }
        if (!o.Zipcode) {
            return alertify.alert('Warning!', 'Zip is mendatory.',AlertifyHandler);
        }
        if (!o.PhoneNumber) {
            return alertify.alert('Warning!', 'Phone number is mendatory.',AlertifyHandler);
        }
        if (o.PhoneNumber.length != 10) {
            return alertify.alert('Warning!', 'Phone number must be of 10 digits.',AlertifyHandler);
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL,
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response);
            } 
            
            return alertify.alert('Success!',  resp.response, CompanyDetailPage);
        })
        .error(HandleError);
        //
    }

    /**
     * Update company location
     * @param {object} event 
     * @returns 
     */
    function UpdateCompanyLocation(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Street1 = $('.jsStreet1').val().trim();
        o.Street2 = $('.jsStreet2').val().trim();
        o.Country = "USA";
        o.City = $('.jsCity').val().trim();
        o.State = $('.jsState option:selected').val();
        o.Zipcode = $('.jsZip').val().trim();
        o.PhoneNumber = $('.jsPhoneNumber').val().replace(/[^\d]/g,'');
        o.MailingAddress = $('.jsMailingAddress').prop('checked') ? 1 : 0;
        o.FillingAddress = $('.jsFilingAddress').prop('checked') ? 1 : 0;
        o.CompanyId = companyId;
        o.LocationId = locationSid;
        // Validation
        if (!o.Street1) {
            return alertify.alert('Warning!', 'Street 1 is mendatory.',AlertifyHandler);
        }
        if (!o.City) {
            return alertify.alert('Warning!', 'City is mendatory.',AlertifyHandler);
        }
        if (!o.State) {
            return alertify.alert('Warning!', 'State is mendatory.',AlertifyHandler);
        }
        if (!o.Zipcode) {
            return alertify.alert('Warning!', 'Zip is mendatory.',AlertifyHandler);
        }
        if (!o.PhoneNumber) {
            return alertify.alert('Warning!', 'Phone number is mendatory.',AlertifyHandler);
        }
        if (o.PhoneNumber.length != 10) {
            return alertify.alert('Warning!', 'Phone number must be of 10 digits.',AlertifyHandler);
        }

        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "PUT",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL,
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response);
            } 
            
            return alertify.alert('Success!',  resp.response, CompanyDetailPage);
        })
        .error(HandleError);
        //
    }

    /**
     * Company Federal Taxd details
     */
    function GetFederalTaxInfo() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/fedral_tax_detail/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.TAX_URL;
                var page_type = resp.page_type;
                //
                LoadContent(resp.html, function() {
                    //
                    if (page_type == "tax_detail") {
                        $('.jsFederalTaxEdit').click(EditFederalTaxInfo);
                        $('.jsFederalTaxConfirm').click(GetCompanyIndustry);
                    }

                    if (page_type == "tax_form") {
                        $('.jsFederalTaxUpdate').click(UpdateCompanyFederalTax);
                    }
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Company Federal Taxd Edit Function
     */
    function EditFederalTaxInfo() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/edit-fedral-tax/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.TAX_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    $('.jsFederalTaxUpdate').click(UpdateCompanyFederalTax);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Update company federal tax information
     * @param {object} event 
     * @returns 
     */
    function UpdateCompanyFederalTax(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.EIN = $('.jsTaxEIN').val().replace(/[^\d]/g,'');
        o.LegalName = $('.jsTaxLegalName').val().trim();
        o.TaxPayerType = $('.jsTaxPayerType option:selected').val();
        o.FillingForm = $('.jsTaxFillingForm option:selected').val();
        o.Scorp = $('.jsTaxableAsScorp').prop('checked') ? 1 : 0;
        o.CompanyId = companyId;
        // Validation
        
        if (!o.TaxPayerType) {
            return alertify.alert('Warning!', 'Please, select a tax payer type.', AlertifyHandler);
        }
        if (!o.FillingForm) {
            return alertify.alert('Warning!', 'Please, select the filling form.', AlertifyHandler);
        }
        if (!o.LegalName) {
            return alertify.alert('Warning!', 'Legal name is mendatory.', AlertifyHandler);
        }
        if (o.LegalName.length < 3) {
            return alertify.alert('Warning!', 'The legal name must be of minimum 3 characters..', AlertifyHandler);
        }
        if (!o.EIN) {
            return alertify.alert('Warning!', 'EIN number is mendatory.', AlertifyHandler);
        }
        if (o.EIN.length !== 9) {
            return alertify.alert('Warning!', 'EIN number must be of 9 digits.', AlertifyHandler);
        }

        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL,
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response);
            } 
            
            return alertify.alert('Success!',  resp.response, GetFederalTaxInfo);
        })
        .error(HandleError);
        //
    }
    
    /**
     * Company industry type
     */
    function GetCompanyIndustry() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/company_industry/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null
                //
                LoadContent(resp.html, function() {
                    //
                    $('.jsSaveCompanyIndustry').click(UpdateCompanyIndustry);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    
    /**
     * Update company federal tax information
     * @param {object} event 
     * @returns 
     */
    function UpdateCompanyIndustry(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Industry = $('.jsCompanyIndustry option:selected').val();
        o.CompanyId = companyId;
        // Validation
        
        if (!o.Industry) {
            return alertify.alert('Warning!', 'Please, select industry type.', AlertifyHandler);
        }

        //
        ml(true, modalLoader);
        //
        // xhr = $.ajax({
        //     method: "POST",
        //     headers: { "Content-Type": "application/json", "Key" : API_KEY },
        //     url: API_URL,
        //     data: JSON.stringify(o)
        // })
        // .done(function(resp) {
        //     //
        //     xhr = null;
        //     //
        //     ml(false, modalLoader);
        //     // 
        //     if (!resp.status) {
        //         return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response);
        //     } 
            
        //     return alertify.alert('Success!',  resp.response, GetFederalTaxInfo);
        // })
        // .error(HandleError);
        //
    }

    /**
     * Company Bank Info
     */
    function GetCompanyBankInfo() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/company_bank_info/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.BANK_URL;
                var page_type = resp.page_type;
                //
                LoadContent(resp.html, function() {
                    //
                    if (page_type == 'new') {
                        $('.jsBankInfoUpdate').click(UpdateCompanyBankInfo);
                        $('.jsBankInfoCancel').click(GetCompanyBankInfo);
                    }
                    //
                    if (page_type == 'detail') {
                        $('.jsEditBankInfo').click(EditCompanyBankInfo);
                        $('.jsBankInfoCancel').click(GetFederalTaxInfo);
                    }
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Company Bank info Edit Function
     */
    function EditCompanyBankInfo() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/edit_bank_info/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.BANK_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    $('.jsBankInfoUpdate').click(UpdateCompanyBankInfo);
                    $('.jsBankInfoCancel').click(GetCompanyBankInfo);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Update company bank information
     * @param {object} event 
     * @returns 
     */
    function UpdateCompanyBankInfo(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.RoutingNumber = $('.jsRoutingNumber').val().replace(/[^\d]/g,'');
        o.AccountNumber = $('.jsAccountNumber').val().replace(/[^\d]/g,'');
        o.AccountType = $('.jsAccountType option:selected').val();
        o.CompanyId = companyId;
        // Validation
        
        if (!o.RoutingNumber) {
            return alertify.alert('Warning!', 'Routing number is mendatory.', AlertifyHandler);
        }
        if (o.RoutingNumber.length !== 9) {
            return alertify.alert('Warning!', 'Routing number must be of 9 digits.', AlertifyHandler);
        }
        if (!o.AccountNumber) {
            return alertify.alert('Warning!', 'Account number is mendatory.', AlertifyHandler);
        }
        if (o.AccountNumber.length !== 9) {
            return alertify.alert('Warning!', 'Account number must be of 9 digits.', AlertifyHandler);
        }
        if (!o.AccountType) {
            return alertify.alert('Warning!', 'Please, select the account type.', AlertifyHandler);
        }

        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL,
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response);
            } 
            
            return alertify.alert('Success!',  resp.response, GetCompanyBankInfo);
        })
        .error(HandleError);
        //
    }

    /**
     * Start employee onboarding
     */
    function StartEmployeeOnboarding() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/start_employee_onboarding/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    $('.jsEmployeeOnboardCancel').click(GetCompanyBankInfo);
                    $('.jsAddCompanyEmployee').click(ShowCompanyEmployeeList);
                    $('.jsPayrollEmployeeOnboard').click(SendEmployeeToOnboardProcess);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Start employee onboarding
     */
    function ShowCompanyEmployeeList() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/show_company_employee_list/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    $('.jsPayrollEmployeeOnboard').click(SendEmployeeToOnboardProcess);
                    $('.jsEmployeeListCancel').click(StartEmployeeOnboarding);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Add employee onboarding
     */
     function SendEmployeeToOnboardProcess() {
        employeeID = $(this).data("employee_id");
        console.log(employeeID)
        var level = $(this).data("level");

        if (level == 0) {
            AddUpdateCompanyEmployeeProfile();
        } else if (level == 1) {
            UpdateCompanyEmployeeAddress();
        } else if (level == 2) {
            UpdateCompanyEmployeeCompensation();
        }  else if (level == 3) {
            UpdateEmployeeFederalTax();
        } else if (level == 4) {
            UpdateEmployeeStateTax();
        } else if (level == 5) {
            UpdateEmployeePaymentMethod();
        }
     }

    /**
     * Add employee onboarding
     */
    function AddUpdateCompanyEmployeeProfile() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/get_company_employee_profile/' + companyId),
                data: { employee_id: employeeID }
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    $('.jsAddEmployeeCancel').click(ShowCompanyEmployeeList);
                    $('.jsPayrollSaveCompanyEmployee').click(SaveCompanyEmployeeProfile);
                    //
                    $('.jsDatePicker').datepicker({
                        format: 'm/d/Y',
                        changeMonth: true,
                        changeYear: true,
                    });
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Save company Employee
     * @param {object} event 
     * @returns 
     */
    function SaveCompanyEmployeeProfile(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.FirstName = $('.jsFirstName').val().trim();
        o.MiddleInitial = $('.jsMiddleName').val().trim();
        o.LastName = $('.jsLastName').val().trim();
        o.StartDate = $('.jsStartDate').val().trim();
        o.SSN = $('.jsEmployeeSSN').val().replace(/[^\d]/g,'');
        o.DOB = $('.jsEDOB').val().trim();
        o.EWA = $('.jsEWD option:selected').val();
        o.Email = $('.jsEmail').val().trim();
        o.CompanyId = companyId;
        // Validation
        if (!o.FirstName) {
            return alertify.alert('Warning!', 'First name is mendatory.',AlertifyHandler);
        }
        if (!o.LastName) {
            return alertify.alert('Warning!', 'Last name is mendatory.',AlertifyHandler);
        }
        if (!o.Email) {
            return alertify.alert('Warning!', 'Email address is mendatory.',AlertifyHandler);
        }
        if (!o.SSN) {
            return alertify.alert('Warning!', 'SSN is mendatory.',AlertifyHandler);
        }
        if (o.SSN.length != 9) {
            return alertify.alert('Warning!', 'SSN number must be of 9 digits.',AlertifyHandler);
        }
        if (!o.DOB) {
            return alertify.alert('Warning!', 'Date of birth is mendatory.',AlertifyHandler);
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL+"/"+employeeID,
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response, AlertifyHandler);
            } 
            
            return alertify.alert('Success!',  resp.response, UpdateCompanyEmployeeAddress);
        })
        .error(HandleError);
        //
    }

    /**
     * Get employee onboarding address
     */
    function UpdateCompanyEmployeeAddress() {
        //
        ml(true, modalLoader);
        //
        console.log(employeeID)
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/get_company_employee_address/' + companyId),
                data: { employee_id: employeeID }
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    $('.jsPayrollEmployeeOnboard').click(SendEmployeeToOnboardProcess);
                    $('.jsPayrollSaveEmployeeAddressInfo').click(SaveCompanyEmployeeAddress);
                    //
                    $('.jsDatePicker').datepicker({
                        format: 'm/d/Y',
                        changeMonth: true,
                        changeYear: true,
                    });
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Save company Employee address
     * @param {object} event 
     * @returns 
     */
    function SaveCompanyEmployeeAddress(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Street1 = $('.jsStreet1').val().trim();
        o.Street2 = $('.jsStreet2').val().trim();
        o.Country = "USA";
        o.City = $('.jsCity').val().trim();
        o.State = $('.jsState option:selected').val();
        o.Zipcode = $('.jsZip').val().trim();
        o.PhoneNumber = $('.jsPhoneNumber').val().replace(/[^\d]/g,'');
        o.CompanyId = companyId;
        // Validation
        if (!o.Street1) {
            return alertify.alert('Warning!', 'Street 1 is mendatory.',AlertifyHandler);
        }
        if (!o.City) {
            return alertify.alert('Warning!', 'City is mendatory.',AlertifyHandler);
        }
        if (!o.State) {
            return alertify.alert('Warning!', 'State is mendatory.',AlertifyHandler);
        }
        if (!o.Zipcode) {
            return alertify.alert('Warning!', 'Zip is mendatory.',AlertifyHandler);
        }
        
        if (o.PhoneNumber && o.PhoneNumber.length != 10) {
            return alertify.alert('Warning!', 'Phone number must be of 10 digits.',AlertifyHandler);
        }
        //
        ml(true, modalLoader);
        console.log(employeeID)
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL+"/"+employeeID+"/home_address",
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response, AlertifyHandler);
            } 
            
            return alertify.alert('Success!',  resp.response, UpdateCompanyEmployeeCompensation);
        })
        .error(HandleError);
        //
    }

    /**
    * Get employee onboarding compensation
    */
    function UpdateCompanyEmployeeCompensation() {
       //
       ml(true, modalLoader);
       //
       xhr = $.ajax({
               method: "GET",
               url: GetURL('get_payroll_page/get_company_employee_compensation/' + companyId),
               data: { employee_id: employeeID }
           })
           .done(function(resp) {
               //
               xhr = null;
               API_KEY = resp.API_KEY;
               API_URL = resp.EMPLOYEE_URL;
               //
               LoadContent(resp.html, function() {
                   //
                   $('.jsPayrollEmployeeOnboard').click(SendEmployeeToOnboardProcess);
                   $('.jsPayrollSaveEmployeeJobInfo').click(SaveCompanyEmployeeJob);
                   //  
                   ml(false, modalLoader);
               });
           })
           .error(HandleError);
    }

    /**
     * Save company Employee job
     * @param {object} event 
     * @returns 
     */
    function SaveCompanyEmployeeJob(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Title = $('.jsJobTitle').val().trim();
        o.Rate = $('.jsAmount').val().trim();
        o.FlsaStatus = $('.jsEmployeeType option:selected').val();
        o.PaymentUnit = $('.jsSalaryType option:selected').val();
        o.CompanyId = companyId;
        // Validation
        if (!o.Title) {
            return alertify.alert('Warning!', 'Job title is mendatory.',AlertifyHandler);
        }
        if (!o.FlsaStatus) {
            return alertify.alert('Warning!', 'Employee type is mendatory.',AlertifyHandler);
        }
        if (!o.Rate) {
            return alertify.alert('Warning!', 'Salary amount is mendatory.',AlertifyHandler);
        }
        if (!o.PaymentUnit) {
            return alertify.alert('Warning!', 'Salary type is mendatory.',AlertifyHandler);
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL+"/"+employeeID+"/jobs",
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response, AlertifyHandler);
            } 
            
            return alertify.alert('Success!',  resp.response, UpdateEmployeeFederalTax);
        })
        .error(HandleError);
        //
    }

    function UpdateEmployeeFederalTax () {
         //
       ml(true, modalLoader);
       //
       xhr = $.ajax({
               method: "GET",
               url: GetURL('get_payroll_page/get_company_employee_federal_tax/' + companyId),
               data: { employee_id: employeeID }
           })
           .done(function(resp) {
               //
               xhr = null;
               API_KEY = resp.API_KEY;
               API_URL = resp.EMPLOYEE_URL;
               //
               LoadContent(resp.html, function() {
                   //
                   $('.jsPayrollEmployeeOnboard').click(SendEmployeeToOnboardProcess);
                   $('.jsPayrollSaveEmployeeFederalTax').click(SaveCompanyEmployeeFederalTax);
                   // 
                   ml(false, modalLoader);
               });
           })
           .error(HandleError);
    }

    /**
     * Save company Employee Federal tax
     * @param {object} event 
     * @returns 
     */
     function SaveCompanyEmployeeFederalTax(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.FederalFilingStatus = $('.jsFederalFilingStatus option:selected').val();
        o.MultipleJobs = $('.jsMultipleJobs option:selected').val();
        o.DependentTotal = $('.jsDependentTotal').val();
        o.OtherIncome = $('.jsOtherIncome').val();
        o.Deductions = $('.jsDeductions').val();
        o.ExtraWithholding = $('.jsExtraWithholding').val();
        o.CompanyId = companyId;
        // Validation
        if (!o.FederalFilingStatus) {
            return alertify.alert('Warning!', 'Federal Filing Status is mendatory.',AlertifyHandler);
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL+"/"+employeeID+"/federal_tax",
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response, AlertifyHandler);
            } 
            
            return alertify.alert('Success!',  resp.response, UpdateEmployeeStateTax);
        })
        .error(HandleError);
        //
    }

    function UpdateEmployeeStateTax () {
        //
      ml(true, modalLoader);
      //
      xhr = $.ajax({
              method: "GET",
              url: GetURL('get_payroll_page/get_company_employee_state_tax/' + companyId),
              data: { employee_id: employeeID }
          })
          .done(function(resp) {
              //
              xhr = null;
              API_KEY = resp.API_KEY;
              API_URL = resp.EMPLOYEE_URL;
              //
              LoadContent(resp.html, function() {
                  //
                  $('.jsPayrollEmployeeOnboard').click(SendEmployeeToOnboardProcess);
                  $('.jsPayrollSaveEmployeeStateTax').click(SaveCompanyEmployeeStateTax);
                  //  
                  ml(false, modalLoader);
              });
          })
          .error(HandleError);
    }

    /**
     * Save company Employee Federal tax
     * @param {object} event 
     * @returns 
     */
    function SaveCompanyEmployeeStateTax(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.FilingStatus = $('.jsFilingStatus option:selected').val();
        o.WithholdingAllowance = $('.jsWithholdingAllowance').val();
        o.AdditionalWithholding = $('.jsAdditionalWithholding').val();
        o.CompanyId = companyId;
        // Validation
        if (!o.WithholdingAllowance) {
            return alertify.alert('Warning!', 'Withholding Allowance is mendatory.',AlertifyHandler);
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL+"/"+employeeID+"/state_tax",
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response, AlertifyHandler);
            } 
            
            return alertify.alert('Success!',  resp.response, UpdateEmployeePaymentMethod);
        })
        .error(HandleError);
        //
    }

    function UpdateEmployeePaymentMethod () {
        //
      ml(true, modalLoader);
      //
      xhr = $.ajax({
              method: "GET",
              url: GetURL('get_payroll_page/get_company_employee_payment_method/' + companyId),
              data: { employee_id: employeeID }
          })
          .done(function(resp) {
              //
              xhr = null;
              API_KEY = resp.API_KEY;
              API_URL = resp.EMPLOYEE_URL;
              //
              LoadContent(resp.html, function() {
                  //
                  $('.jsPayrollEmployeeOnboard').click(SendEmployeeToOnboardProcess);
                  $('.jsPayrollEmployeePaymentMethod').click(SaveCompanyEmployeePaymentMethod);
                  $('.jsAddEmployeeBankAccount').click(AddEmployeeBankAccount);
                  $('.jsDeleteEmployeeBankAccount').click(DeleteEmployeeoBankAccount);
                  //  
                  ml(false, modalLoader);
              });
          })
          .error(HandleError);
    }

    /**
     * Save company Employee payment method
     * @param {object} event 
     * @returns 
     */
    function SaveCompanyEmployeePaymentMethod(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.PaymentMethod = $('.jsPaymentMethod option:selected').val();
        o.SplitType = $('.jsSplitType option:selected').val();
        o.CompanyId = companyId;
        // Validation
        if (!o.PaymentMethod || o.PaymentMethod == 0) {
            return alertify.alert('Warning!', 'Please select payment method.',AlertifyHandler);
        }
        //
        if (o.PaymentMethod == "Direct Deposit") {
            if (!o.SplitType || o.SplitType == 0) {
                return alertify.alert('Warning!', 'Please select split type.',AlertifyHandler);
            }
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL+"/"+employeeID+"/payment_method",
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response, AlertifyHandler);
            } 
            
            return alertify.alert('Success!',  resp.response, ShowCompanyEmployeeList);
        })
        .error(HandleError);
        //
    }

    function AddEmployeeBankAccount () {
        //
        ml(true, modalLoader);
        //
        addBankID = $(this).data("account_id");
        splitType = $('.jsSplitType option:selected').val();
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/get_company_employee_bank_detail/' + companyId),
                data: { employee_id: employeeID, row_id: addBankID, type: splitType}
            })
        .done(function(resp) {
            //
            xhr = null;
            API_KEY = resp.API_KEY;
            API_URL = resp.EMPLOYEE_URL;
            //
            LoadContent(resp.html, function() {
                //
                $('.jsBackToPaymentMethod').click(UpdateEmployeePaymentMethod);
                $('.jsSaveEmployeeBankInfo').click(SaveEmployeeBankDetail);
                //  
                ml(false, modalLoader);
            });
        })
        .error(HandleError);
    }

    /**
     * Save company Employee Bank Detail
     * @param {object} event 
     * @returns 
     */    
    function SaveEmployeeBankDetail (event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.RoutingNumber = $('.jsRoutingNumber').val().replace(/[^\d]/g,'');
        o.AccountNumber = $('.jsAccountNumber').val().replace(/[^\d]/g,'');
        o.AccountType = $('.jsAccountType option:selected').val();
        o.AccountName = $('.jsAccountName').val().trim();
        o.SplitType = splitType;
        o.SplitAmount = $('.jsSplitAmount').val().replace(/[^\d]/g,'');
        o.CompanyId = companyId;
        o.DDSID = addBankID;
        // Validation

        if (!o.AccountName) {
            return alertify.alert('Warning!', 'Account name is mendatory.', AlertifyHandler);
        }
        if (!o.RoutingNumber) {
            return alertify.alert('Warning!', 'Routing number is mendatory.', AlertifyHandler);
        }
        if (o.RoutingNumber.length !== 9) {
            return alertify.alert('Warning!', 'Routing number must be of 9 digits.', AlertifyHandler);
        }
        if (!o.AccountNumber) {
            return alertify.alert('Warning!', 'Account number is mendatory.', AlertifyHandler);
        }
        if (o.AccountNumber.length !== 9) {
            return alertify.alert('Warning!', 'Account number must be of 9 digits.', AlertifyHandler);
        }
        if (!o.AccountType) {
            return alertify.alert('Warning!', 'Please, select the account type.', AlertifyHandler);
        }
        if (!o.SplitAmount) {
            return alertify.alert('Warning!', 'Split amount or percentage is mendatory.', AlertifyHandler);
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "POST",
            headers: { "Content-Type": "application/json", "Key" : API_KEY },
            url: API_URL+"/"+employeeID+"/bank_accounts",
            data: JSON.stringify(o)
        })
        .done(function(resp) {
            //
            xhr = null;
            //
            ml(false, modalLoader);
            // 
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response, AlertifyHandler);
            } 
            
            return alertify.alert('Success!',  resp.response, UpdateEmployeePaymentMethod);
        })
        .error(HandleError);
        //
    }

    /**
     * Delete company Employee Bank Detail
     * @param {object} event 
     * @returns 
     */    
     function DeleteEmployeeoBankAccount (event) {
        //
        event.preventDefault();
        //

        //
        var o = {};
        o.payroll_bank_uuid = $(this).data("account_id");
        o.DDID = $(this).data("ddid");
        o.CompanyId = companyId;
        var message = [];
        message.push("Are you sure you want to delete this bank Account?");
        console.log(o.DDID)
        if (o.DDID > 0) {
            message.push("This action may also delete the direct deposit bank account.");
        }
        alertify.confirm('Confirmation', message.join('<br/>'),
            function () {
                ml(true, modalLoader);
                //
                xhr = $.ajax({
                    method: "DELETE",
                    headers: { "Content-Type": "application/json", "Key" : API_KEY },
                    url: API_URL+"/"+employeeID+"/bank_accounts/"+o.payroll_bank_uuid,
                    data: JSON.stringify(o)
                })
                .done(function(resp) {
                    //
                    xhr = null;
                    //
                    ml(false, modalLoader);
                    // 
                    if (!resp.status) {
                        return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response, AlertifyHandler);
                    } 
                    
                    return alertify.alert('Success!',  resp.response, UpdateEmployeePaymentMethod);
                })
                .error(HandleError);
            },
            function () {

            });
    }

    /**
     * Add company payroll
     */
    function AddUpdateCompanyPayrollSetting() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/get_company_payroll_setting/' + companyId),
                data: { employee_id: employeeID }
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.COMPANIES_URL;
                var page_type = resp.page_type;
                //
                LoadContent(resp.html, function() {
                    //
                    if (page_type == "payroll_detail") {
                        $('.jsPayrollInfoCancel').click(StartEmployeeOnboarding);
                        $('.jsEditPayrollInfo').click(UpdatePayrollSetting);
                    }

                    if (page_type == "payroll_form") {
                        $('.jsPayrollSave').click(SaveCompaniesPayrollSetting);
                        ('.jsPayrollCancel').click(StartEmployeeOnboarding);

                        $('.jsDatePicker').datepicker({
                            format: 'm/d/Y',
                            changeMonth: true,
                            changeYear: true,
                        });
                    }
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    function UpdatePayrollSetting() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/update_company_payroll_setting/' + companyId),
                data: { employee_id: employeeID }
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.COMPANIES_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    
                        $('.jsPayrollSave').click(SaveCompaniesPayrollSetting);
                        $('.jsPayrollCancel').click(AddUpdateCompanyPayrollSetting);

                        $(".jsFrequencyDays").show();
                        $(".jsTwicePerMonth").hide();
                        $(".jsMonthlyCycle").hide();
                        $(".jsQuarterlyCycle").hide();
                        $("#jsPayrollDeadline").hide();
                        $("#jsPayrollPayPeriod").hide();
                        $(".jsOtherHalfMonthCycle").hide();
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Trigger when Pay frequency change
     */
     $(document).on('change', '.jsPayFrequency', function(event) {
        //
        var type = $(this).val();
        //
        $("#jsPayrollDeadline").hide();
        $("#jsPayrollPayPeriod").hide();
        //
        if (type == "Every week") {
            $(".jsFrequencyDays").show();
            $(".jsTwicePerMonth").hide();
            $(".jsMonthlyCycle").hide();
            $(".jsQuarterlyCycle").hide();
        }

        if (type == "Every other week") {
            $(".jsFrequencyDays").show();
            $(".jsTwicePerMonth").hide();
            $(".jsMonthlyCycle").hide();
            $(".jsQuarterlyCycle").hide();
        }

        if (type == "Twice per month") {
            $(".jsFrequencyDays").hide();
            $(".jsTwicePerMonth").show();
            $(".jsMonthlyCycle").hide();
            $(".jsQuarterlyCycle").hide();

            $('.jsWeekCalendar').html("");
            $(".jsOtherHalfMonthCycle").hide();
            //
            xhr = $.ajax({
                    method: "GET",
                    url: GetURL('get_payroll_page/get_twice_month_dates/' + companyId)
                })
                .done(function(resp) {
                    //
                    xhr = null;
                    var weekdates = resp.rows;
                    //
                    $('.jsWeekCalendar').html(weekdates);
                    //  
                    ml(false, modalLoader);
                })
                .error(HandleError);
        }

        if (type == "Monthly") {
            $(".jsFrequencyDays").hide();
            $(".jsTwicePerMonth").hide();
            $(".jsMonthlyCycle").show();
            $(".jsQuarterlyCycle").hide();
        }

        if (type == "Quarterly") {
            $(".jsFrequencyDays").hide();
            $(".jsTwicePerMonth").hide();
            $(".jsMonthlyCycle").hide();
            $(".jsQuarterlyCycle").show();

            $('.jsUpcomingMonth').html("");
            //
            xhr = $.ajax({
                    method: "GET",
                    url: GetURL('get_payroll_page/get_upcoming_months/' + companyId)
                })
                .done(function(resp) {
                    //
                    xhr = null;
                    var get_upcoming_months = resp.rows;
                    //
                    $('.jsUpcomingMonth').html(get_upcoming_months);
                    //  
                    ml(false, modalLoader);
                })
                .error(HandleError);
        }
    });

    $(document).on('change', '.jsDayOfWeek', function(event) {
        ml(true, modalLoader);
        //
        var selectedDay = $(this).val();
        $('.jsWeekCalendar').html("");
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/get_date_list/' + companyId),
                data: { day: selectedDay }
            })
            .done(function(resp) {
                //
                xhr = null;
                var weekdates = resp.rows;
                //
                $('.jsWeekCalendar').html(weekdates);
                //  
                ml(false, modalLoader);
            })
            .error(HandleError);
    });

    $(document).on('change', '.jsDayOfMonth', function(event) {
        ml(true, modalLoader);
        //
        var selectedDay = $(this).val();
        $('.jsWeekCalendar').html("");
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/get_next_month_dates/' + companyId),
                data: { day: selectedDay }
            })
            .done(function(resp) {
                //
                xhr = null;
                var weekdates = resp.rows;
                //
                $('.jsWeekCalendar').html(weekdates);
                //  
                ml(false, modalLoader);
            })
            .error(HandleError);
    });

    $(document).on('change', '.jsUpcomingMonth', function(event) {
        ml(true, modalLoader);
        //
        var selectedMonth = $(this).val();
        $('.jsWeekCalendar').html("");
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/get_selected_month_dates/' + companyId),
                data: { value: selectedMonth }
            })
            .done(function(resp) {
                //
                xhr = null;
                var weekdates = resp.rows;
                //
                $('.jsWeekCalendar').html(weekdates);
                //  
                ml(false, modalLoader);
            })
            .error(HandleError);
    });

    $(document).on('change', '.jsWeekCalendar', function(event) {
        ml(true, modalLoader);
        //
        var selectedDate = $(this).val();
        var frequency = $('.jsPayFrequency option:selected').val();
        $('.jsPayrollDeadlineDate').text("");
        $('.jsPayPeriods').html("");
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/get_payroll_deadline/' + companyId),
                data: { date: selectedDate, type: frequency }
            })
            .done(function(resp) {
                //
                xhr = null;
                var deadline = resp.deadline;
                var payPeriods = resp.row;
                //
                $("#jsPayrollDeadline").show();
                $("#jsPayrollPayPeriod").show();
                $(".jsPayrollDeadlineDate").text(deadline);
                $('.jsPayPeriods').html(payPeriods);
                //  
                ml(false, modalLoader);
            })
            .error(HandleError);
    });

    $('input[type=radio][name=default_semimonthly_pay_days]').change(function() {
        if (this.value == 'default') {
            $(".jsOtherHalfMonthCycle").hide();
        }
        else if (this.value == 'other') {
            $(".jsOtherHalfMonthCycle").show();
        }
    });

    /**
     * Delete company Employee Bank Detail
     * @param {object} event 
     * @returns 
     */    
    function SaveCompaniesPayrollSetting (event) {
        //
        event.preventDefault();
        //

        //
        var o = {};
        o.payroll_bank_uuid = $(this).data("account_id");
        o.DDID = $(this).data("ddid");
        o.CompanyId = companyId;
        var message = [];
        message.push("Are you sure you want to delete this bank Account?");
        console.log(o.DDID)
        if (o.DDID > 0) {
            message.push("This action may also delete the direct deposit bank account.");
        }
        alertify.confirm('Confirmation', message.join('<br/>'),
            function () {
                ml(true, modalLoader);
                //
                xhr = $.ajax({
                    method: "DELETE",
                    headers: { "Content-Type": "application/json", "Key" : API_KEY },
                    url: API_URL+"/"+employeeID+"/bank_accounts/"+o.payroll_bank_uuid,
                    data: JSON.stringify(o)
                })
                .done(function(resp) {
                    //
                    xhr = null;
                    //
                    ml(false, modalLoader);
                    // 
                    if (!resp.status) {
                        return alertify.alert('Error!', typeof resp.response === "object" ? resp.response.join('<br/>') : resp.response, AlertifyHandler);
                    } 
                    
                    return alertify.alert('Success!',  resp.response, UpdateEmployeePaymentMethod);
                })
                .error(HandleError);
            },
            function () {

            });
    }

    /**
     * Get the base URL for the current
     * site
     * @param {string} url 
     * @returns {string} generated url
     */
    function GetURL(url) {
        return window.location.origin + '/' + (url || '');
    }

    /**
     * Loads page onto the modal
     * @param {string}   content 
     * @param {function} cb 
     */
    function LoadContent(content, cb) {
        //
        $('#' + (modalId) + 'Body').html(content);
        //
        !cb ? ml(false, modalLoader) : cb();
    }

    /**
     * Handles XHR errors
     * @param {object} error 
     */
    function HandleError(error) {
        //
        xhr = null;
        //
        alertify.alert(
            "Error!",
            "The system failed to process your request. (" + (error.status) + ")"
        );
    }

    /**
     * validate email
     * @param {string} email 
     * @returns 
     */
    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    /**
     * Saves data to local storage
     * @param {string} slug 
     * @param {any}    data 
     */
    function SaveItem(slug, data) {
        //
        localStorage.setItem(slug, JSON.stringify(data));
        //
        return GetItem(slug);
    }

    /**
     * Gets data from local storage
     * @param {string} slug 
     */
    function GetItem(slug) {
        //
        return JSON.parse(localStorage.getItem(slug));
    }

    /**
     * Alertify callback error
     * @returns 
     */
    function AlertifyHandler() {
        //
        return true;
    }

    //
    StartOnboardProcess();

});