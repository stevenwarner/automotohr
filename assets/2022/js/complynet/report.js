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
    $('#jsStatusCompany').select2({
        minimumResultsForSearch: -1
    });

    /**
     * Company change event
     */
    $('#jsParentCompany').change(function(){
        // // set the company id
        // parentCompanyId = parseInt($(this).val());
        // // reset the view
        // $('.jsContentArea').addClass('hidden');
        // $('.jsContentArea .panel').addClass('hidden');
        // $('.jsContentArea .panel tbody').html('');
        // // when no company is selected
        // if(parentCompanyId === 0){
        //     return alertify.alert('Warning!', 'Please select a company to begin.', CB);
        // }
        // //
        // startProcess();
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
                return showCompanyOnboardPopup(resp.complynetCompanies);
            }

            if(resp.code == 'AR'){
                loader(false)
                $('.jsContentArea').removeClass('hidden');
                $('.jsContentArea .company_info').removeClass('hidden');
                $('.jsContentArea .location_info').removeClass('hidden');
                //
                let companyRow = '';
                //
                if (resp.compantDetail.status == 1) {
                    statusText ='      <strong class="text-success">ACTIVE</strong>';
                    statusButton ='    <button class="btn btn-danger jsChangeCompanyStatus" data-row_id="'+resp.compantDetail.sid+'" data-status="0">Disable</button>';
                } else {
                    statusText ='      <strong class="text-warning">DEACTIVE</strong>';
                    statusButton ='    <button class="btn btn-success jsChangeCompanyStatus" data-row_id="'+resp.compantDetail.sid+'" data-status="1">Enable</button>';
                }
                //
                companyRow +='<tr>';
                companyRow +='  <td class="csVm">';
                companyRow +='      <strong>'+resp.compantDetail.automotohr_name+'</strong> <br>';
                companyRow +='      <span>Id: '+resp.compantDetail.automotohr_id+'</span>';
                companyRow +='  </td>';
                companyRow +='  <td class="csVm">';
                companyRow +='      <strong>'+resp.compantDetail.complynet_name+'</strong><br />';
                companyRow +='      <span>Id: '+resp.compantDetail.complynet_id+'</span>';
                companyRow +='  </td>';
                companyRow +='  <td class="csVm">';
                companyRow +='      <span>'+ moment(resp.compantDetail.created_at).format('MMM Do YYYY, ddd H:m:s')+'</span>';
                companyRow +='  </td>';
                companyRow +='  <td class="csVm status_'+resp.compantDetail.sid+'">';
                companyRow +=       statusText;
                companyRow +='  </td>';
                companyRow +='  <td class="csVm">';
                companyRow +='      <button class="btn btn-success">View</button>';
                companyRow +='      <button class="btn btn-warning jsEditCompany">Edit</button>';
                companyRow +=       statusButton;
                companyRow +='  </td>';
                companyRow +='</tr>';
                //
                $('.jsContentArea .company_info tbody').html(companyRow);
            }
        })
        .fail(terminateCall);
    }

    /**
     * Show company link popup
     *
     * @depends Modal
     */
    function showCompanyOnboardPopup(complynetCompanies){
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
            rows +='            <option value="'+$("#jsParentCompany").val()+'">'+$("#jsParentCompany option:selected").text()+'</option>';
            rows +='        </select>';
            rows +='    </div>';
            rows +='    <div class="col-sm-12"><br />';
            rows +='        <label>ComplyNet Company</label>';
            rows +='        <select id="jsComplyNetCompany" style="width: 100%;">';
            rows +='            <option value="0">Select Company</option>';
            $.each(complynetCompanies, function(k,v){
                rows +='            <option value="'+v.Id+'">'+v.Name+'</option>';
            });
            rows +='        </select>';
            rows +='    </div>';
            rows +='    <div class="col-sm-12 text-right">';
            rows +='        <hr />';
            rows +='        <button class="btn btn-success jsLinkCompany">Save</button>';
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
     * Link AutomotoHR company with ComplyNet
     */
    $(document).on('click', '.jsLinkCompany', function(event) {
        //
        event.preventDefault();
        //
        var CNCompany = $('#jsComplyNetCompany').val();
        //
        if (CNCompany == 0 || CNCompany == undefined) {
            alertify.alert("Notice","Please select ComplyNet Company to link");
            return;
        }
        $('[data-page="jsCompanyOnboardModalLoader"]').show(0);
        //
        var obj = {
            AHRCompanySid: $('#jsAutomotoHrCompany').val(),
            AHRCompanyName: $('#jsAutomotoHrCompany option:selected').text().trim(),
            CNCompanySid: CNCompany,
            CNCompanyName: $('#jsComplyNetCompany option:selected').text().trim()
        };
        //
        $.post(
            window.location.origin+'/link_company',
            obj
        )
        .done(function(resp){
            $('[data-page="jsCompanyOnboardModalLoader"]').hide(0);
            //
            if(resp.code == 'RS'){
                alertify.alert('Success!', resp.message, function(){
                    parentCompanyId = $('#jsAutomotoHrCompany').val();
                    $("#jsCompanyOnboardModal").hide();
                    getCompanyDetails();
                });
                return;
            }
            //
            alertify.alert('Notice!', resp.message);
           
        })
        .fail(terminateCall);
    });

     /**
     * Disable complynet company against AutoMotoHR
     */
    $(document).on('click', '.jsEditCompany', function(event) {
        console.log("pakistan")
        //
        event.preventDefault();
        //
        $.get(window.location.origin+'/get_complynet_companies')
        .done(function(resp){
            //
            if(resp.code == 'SF'){
                return showCompanyOnboardPopup(resp.complynetCompanies);
            }
        })
        .fail(terminateCall);
        
    });


    /**
     * Disable complynet company against AutoMotoHR
     */
    $(document).on('click', '.jsChangeCompanyStatus', function(event) {
        //
        event.preventDefault();
        var companyRowSid = $(this).data("row_id");
        var changeStatus = $(this).data("status");
        //
        $('[data-page="jsCompanyOnboardModalLoader"]').show(0);
        //
        var obj = {
            rowSid: companyRowSid,
            status: changeStatus
        };
        //
        $.post(
            window.location.origin+'/change_link_company_status',
            obj
        )
        .done(function(resp){
            $('[data-page="jsCompanyOnboardModalLoader"]').hide(0);
            //
            if(resp.code == 'CS'){
                if (changeStatus == 1) {
                    $('.status_'+companyRowSid).html('<strong class="text-success">ACTIVE</strong>');
                    $('.jsChangeCompanyStatus').removeClass('btn-success');
                    $('.jsChangeCompanyStatus').addClass('btn-danger');
                    $('.jsChangeCompanyStatus').data('status','0');
                    $(".jsChangeCompanyStatus").html('Disable');
                } else {
                    $('.status_'+companyRowSid).html('<strong class="text-warning">DEACTIVE</strong>');
                    $('.jsChangeCompanyStatus').removeClass('btn-danger');
                    $('.jsChangeCompanyStatus').addClass('btn-success');
                    $('.jsChangeCompanyStatus').data('status','1');
                    $(".jsChangeCompanyStatus").html('Enable');
                }
                //
                alertify.alert('Success!', resp.message);
                return;
            }
           
        })
        .fail(terminateCall);
    });
    
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