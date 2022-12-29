$(function ComplyNet() {

    /**
     * CompanyID
     * @type int
     */
    let companyId = 0;

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
     * Checks
     */
    function checkTheCompanyIntegration() {
        //
        xhr = $.get(
            baseURI+'cn/check_company_status/'+companyId
        ).success(function (resp) {
            //
            xhr = null;
            //
            if (resp.length === 0) {
                return startIntegrationProcess();
            }
            // fetch details
        })
        .fail(handleFailure);
    }

    /**
     * Start integration process
     */
    function startIntegrationProcess(){
        //
        loader(false);
        //
        Modal({
            Id: "jsModal",
            Title: "ComplyNet Integration",
            Loader: "jsModalLoader",
            Body: '<div class="container-fluid"><div class="jsModalBody"></div></div>'
        }, function () {
            // Get the companies and view
            getIntegrationPage();
        });
    }

    /**
     * 
     */
    function getIntegrationPage(){
        //
        xhr = $.get(
            baseURI+'cn/getting_started/'+companyId
        )
        .success(function(resp){
            console.log(resp)
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
    function handleFailure (resp) {
        //
        xhr = null;
        //
        if(resp.status === 401) {
            return alertify.alert(
                'Error',
                'Your login session expired. Please log in!',
                function() {
                    window.location.reload();
                }
            )
        }
    }

    /**
     * Empty callback function
     */
    function CB() { }
});