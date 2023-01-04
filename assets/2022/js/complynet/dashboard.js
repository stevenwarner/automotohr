$(function ComplyNet() {

    /**
     * CompanyID
     * @type int
     */
    let companyId = 0;

    /**
     * CompanyName
     * @type string
     */
    let companyName = '';

    /**
     * Holds loader
     * @type string
     */
    let loaderRef = '#jsMainLoader';

    /**
     * Creates a select2
     */
    $('#jsCompany').select2();

    /**
     * Captures the select2 change
     */
    $('#jsCompany').change(function () {
        //
        companyId = parseInt($(this).val().trim());
        //
        if (companyId === 0) {
            return alertify.alert('Please select a company.', CB);
        }
        // Show loader
        loader(true);
        //
        checkTheCompanyIntegration();
    });

    /**
     * Get the ComplyNet locations
     */
    $(document).on('change', '#jsCIComplyNetCompanies', function () {
        //
        if ($(this).val() === 0) {
            return alertify.alert(
                'Warning!',
                'Please select a valid ComplyNet company.',
                CB
            );
        }
        //
        ml(true, 'jsModalLoader');
        //
        $('#jsCIComplyNetLocations').closest('.row').addClass('hidden');
        //
        $('.jsCIBTNBox').addClass('hidden')
        //
        getComplyNetCompanyLocations(
            $(this).val()
        );
    });

    /**
     * Integrate event
     */
    $(document).on('click', '.jsCISubmit', function (event) {
        //
        event.preventDefault();
        //
        let obj = {
            companyId: companyId,
            companyName: companyName,
            complyCompanyId: $('#jsCIComplyNetCompanies').val(),
            complyLocationId: $('#jsCIComplyNetLocations').val()
        };
        //
        if (!obj.complyCompanyId || obj.complyCompanyId == 0) {
            return alertify.alert(
                'Warning!',
                'Please select the ComplyNet company.',
                CB
            );
        }
        //
        if (!obj.complyLocationId || obj.complyLocationId == 0) {
            return alertify.alert(
                'Warning!',
                'Please select the ComplyNet location.',
                CB
            );
        }
        //
        obj.complyCompanyName = $('#jsCIComplyNetCompanies option:selected').text();
        obj.complyLocationName = $('#jsCIComplyNetLocations option:selected').text();
        //
        ml(true, 'jsModalLoader');
        //
        integrateCompany(obj);
    });

    //
    $(document).on('click', '.jsSyncCompany', function (event) {
        //
        event.preventDefault();
        //
        syncCompany();
    });
    //
    $(document).on('click', '.jsRefreshCompany', function (event) {
        //
        event.preventDefault();
        //
        loadView();
    });

    /**
     * Checks
     */
    function checkTheCompanyIntegration() {
        //
        xhr = $.get(
            baseURI + 'cn/check_company_status/' + companyId
        ).success(function (resp) {
            //
            $('#jsIntegrationView').html(' ');
            //
            xhr = null;
            //
            if (resp.length === 0) {
                return startIntegrationProcess();
            }
            // fetch details
            loadView();
        })
            .fail(handleFailure);
    }

    /**
     * Start integration process
     */
    function startIntegrationProcess() {
        //
        loader(false);
        //
        Modal({
            Id: "jsModal",
            Title: "ComplyNet Integration",
            Loader: "jsModalLoader",
            Body: '<div class="container"><div id="jsModalBody"></div></div>'
        }, function () {
            // Get the companies and view
            getIntegrationPage();
        });
    }

    /**
     * Get the integration page
     */
    function getIntegrationPage() {
        //
        xhr = $.get(
            baseURI + 'cn/getting_started/' + companyId
        )
            .success(function (resp) {
                //
                $('#jsModalBody').html(resp.view);
                //
                $('#jsCICompanyName').select2();
                $('#jsCIComplyNetCompanies').select2();
                //
                companyName = resp.companyName;
                //
                ml(false, 'jsModalLoader');
            })
            .fail(handleFailure);
    }

    /**
     * Get ComplyNet locations
     * @param {int} complyCompanyId 
     */
    function getComplyNetCompanyLocations(complyCompanyId) {
        //
        xhr = $.get(
            baseURI + 'cn/locations/' + complyCompanyId
        )
            .success(function (resp) {
                //
                xhr = null;
                //
                if (resp.length === 0) {
                    return alertify.alert(
                        'Error!',
                        'Failed to fetch locations',
                        CB
                    );
                }
                //
                let options = '';
                options += '<option value="0">[Select location]</option>';
                //
                resp.map(function (record) {
                    //
                    options += '<option value="' + (record.Id) + '">' + (record.Name) + '</option>';
                });
                //
                $('#jsCIComplyNetLocations').html(options);
                $('#jsCIComplyNetLocations').select2();
                $('#jsCIComplyNetLocations').closest('.row').removeClass('hidden');
                //
                $('.jsCIBTNBox').removeClass('hidden');
                //
                ml(false, 'jsModalLoader');
            })
            .fail(handleFailure);
    }

    /**
     * Integrate the company
     * @param {object} integrationObject 
     */
    function integrateCompany(integrationObject) {
        //
        xhr = $.post(
            baseURI + 'cn/integrate',
            integrationObject
        )
            .success(function (resp) {
                return alertify.alert(
                    'Success',
                    'Integration process completed.',
                    function () {
                        window.location.reload();
                    }
                )
            })
            .fail(handleFailure);
    }

    function loadView() {
        loader(true, 'Please wait while we are generating view.');
        //
        xhr = $.get(
            baseURI + 'cn/integrate/view/' + companyId
        )
            .success(function (resp) {
                $('#jsIntegrationView').html(resp.view);
                loader(false);
            })
            .fail(handleFailure);
    }

    function syncCompany() {
        //
        loader(true, 'Please wait while we are syncing company.');
        //
        xhr = $.post(
            baseURI + 'cn/sync', {
            companyId: companyId
        }
        )
            .success(function (resp) {
                //
                loader(false);
                return alertify.alert(
                    'Success',
                    'Company is synced',
                    function () {

                        window.location.reload();
                    }
                )
            })
            .fail(handleFailure);
    }

    /**
     * Controls the loader
     * @param {boolean} cond 
     * @param {string} msg 
     */
    function loader(cond, msg = '') {
        if (cond) {
            $(loaderRef).show();
            $(loaderRef + ' .jsLoaderText').html(msg || "Please wait, while we process your request.");
        } else {
            $(loaderRef).hide();
            $(loaderRef + ' .jsLoaderText').html("Please wait, while we process your request.");
        }
    }

    /**
     * Handles failure
     * @param {object} resp 
     */
    function handleFailure(resp) {
        //
        xhr = null;
        //
        if (resp.status === 401) {
            return alertify.alert(
                'Error',
                'Your login session expired. Please log in!',
                function () {
                    window.location.reload();
                }
            )
        }
    }

    /**
     * Empty callback function
     */
    function CB() { }

    // companyId = 5664;
    // setInterval(syncCompany, 1000);
});