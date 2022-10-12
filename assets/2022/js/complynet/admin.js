$(function ComplyNetManagement(){
    /**
     * Set parent company name
     *
     * @type {int}
     */
    let parentCompanyId = 0;

    /**
     * Holds AJAX call object
     *
     * @type {null|object}
     */
    let XHR = null;

    //
    $('#jsParentCompany').select2();

    /**
     * Company change event
     */
    $('#jsParentCompany').change(function(){
        // set the company id
        parentCompanyId = parseInt($(this).val());
        // reset the view
        $('.jsContentArea').addClass('hidden');
        $('.jsContentArea .panel').addClass('hidden');
        $('.jsContentArea .panel tbody').html('');
        // when no company is selected
        if(parentCompanyId === 0){
            return alertify.alert('Warning!', 'Please select a company to begin.', CB);
        }
        //
        startProcess();
    });

    /**
     * Terminates the call
     */
    $(document).on('click', '#jsCancelAjaxCall', function(event){
        //
        event.preventDefault();
        //
        terminateCall();
    });

    /**
     * Starts the process
     */
    function startProcess(){
        //
        loader(
            true,
            'Please wait while we are fetching details.<br /><button class="btn btn-danger btn-xs" id="jsCancelAjaxCall">Cancel Request</button>'
        );
        //
        XHR = getCompanyDetails();
    }

    /**
     * Check and get company details
     * for ComplyNet
     *
     * @depends showCompanyOnboardPopup
     */
    function getCompanyDetails(){
        //
        return $
        .get(window.location.origin+'/complynet/get_company_details/'+parentCompanyId)
        .done(function(resp){
            //
            if(resp.code == 'NR'){
                return showCompanyOnboardPopup();
            }
        })
        .fail(terminateCall);
    }

    /**
     * Show company link popup
     *
     * @depends Modal
     */
    function showCompanyOnboardPopup(){
        //
        Modal({
            Id: 'jsCompanyOnboardModal',
            Loader: 'jsCompanyOnboardModalLoader',
            Title: 'Company Onboard',
            Body: '<div class="container"><div id="jsCompanyOnboardModalBody"></div></div>'
        }, function(){
            //
            loader(false);
            //
            let rows = '';
            rows +='<div class="row">';
            rows +='    <div class="col-sm-12">';
            rows +='        <p class="text-danger">';
            rows +='            <strong>';
            rows +='            Please select the ComplyNet company. The system will automatically synced all departments, locations, and employees with ComplyNet. However, if any employee is missing details, that employee will not be synced.';
            rows +='            </strong>';
            rows +='        </p>';
            rows +='        <p class="text-danger">';
            rows +='            <strong>';
            rows +='            Make sure this company has departments added and each employee belongs to some department.';
            rows +='            </strong>';
            rows +='        </p>';
            rows +='    </div>';
            rows +='</div>';
            rows +='<div class="row"><br />';
            rows +='    <div class="col-sm-12">';
            rows +='        <label>AutomotoHR Company</label>';
            rows +='        <select id="jsAutomotoHrCompany" style="width: 100%;" disabled>';
            rows +='            <option value="0">Company</option>';
            rows +='        </select>';
            rows +='    </div>';
            rows +='    <div class="col-sm-12"><br />';
            rows +='        <label>ComplyNet Company</label>';
            rows +='        <select id="jsComplyNetCompany" style="width: 100%;">';
            rows +='            <option value="0">[Please select a company]</option>';
            rows +='        </select>';
            rows +='    </div>';
            rows +='    <div class="col-sm-12 text-right">';
            rows +='        <hr />';
            rows +='        <button class="btn btn-success">Save</button>';
            rows +='        <button class="btn btn-black jsModalCancel">Cancel</button>';
            rows +='    </div>';
            rows +='</div>';
            //
            $('#jsCompanyOnboardModalBody').html(rows)
            //
            $('#jsComplyNetCompany').select2();
            $('#jsAutomotoHrCompany').select2();
            $('[data-page="jsCompanyOnboardModalLoader"]').hide(0);
            
        });
    }
    
    /**
     * Empty callback
     */
    function CB(){}

    /**
     * Loader
     *
     * @param {boolean|undefined} doShow
     * @param {string} msg
     */
    function loader(doShow, msg = ''){
        //
        if(doShow === true){
            $('#jsManageComplyNet').show();
            $('#jsManageComplyNet .jsLoaderText').html(msg || 'Please wait while we process your request.');
        } else{
            $('#jsManageComplyNet').hide();
            $('#jsManageComplyNet .jsLoaderText').html('Please wait while we process your request.');
        }
    }

    /**
     * Terminates the AJAX call and
     * cleans the views
     */
    function terminateCall(){
        //
        parentCompanyId = 0;
        //
        $('#jsParentCompany').select2('val', parentCompanyId);
        //
        if(XHR !== null){
            XHR.abort();
        }
        //
        XHR = null;
        // reset the view
        $('.jsContentArea').addClass('hidden');
        $('.jsContentArea .panel').addClass('hidden');
        $('.jsContentArea .panel tbody').html('');
        // hides the loader
        loader(false);
    }
});