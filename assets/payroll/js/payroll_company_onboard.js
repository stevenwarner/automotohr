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
     * Saves the application key object
     * @type {null|string}
     */
       var locationSid;

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
                }
                
            })
            .error(HandleError);
    }

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
     * Trigger when cancel is pressed
     */
     $(document).on('click', '.jsPayrollConfirmContinue', function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).data("id");
        //
        $.ajax({
            method: "get",
            url: GetURL('get_payroll_page/change_level/' + companyId),
            data: {next_level: id }
        })
        .done(function(resp) {
            //
            if (id == 1) {
                GetFederalTaxInfo();
            }
    
            if (id == 2) {
                GetCompanyIndustry();
            }
            
        })
        .error(HandleError);
        
    });



    /**
     * Add company location
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
                url: GetURL('get_payroll_page/fedral_tax_edit/' + companyId),
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
     * Company industry type
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
     * Trigger when cancel is pressed
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
    });

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
        return false;
    }

    //
    StartOnboardProcess();

});