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
                if (resp.compantDetail.status == 1) {
                    companyRow +='      <strong class="text-success">ACTIVE</strong>';     
                } else {
                    companyRow +='      <strong class="text-warning">DEACTIVE</strong>';
                }
                companyRow +='  </td>';
                companyRow +='  <td class="csVm">';
                companyRow +='      <button class="btn btn-success">View</button>';
                companyRow +='      <button class="btn btn-warning jsEditCompany">Edit</button>';
                companyRow +='       <button class="btn btn-danger jsDeleteCompanyLink" data-row_id="'+resp.compantDetail.sid+'">Delete</button>';
                companyRow +='  </td>';
                companyRow +='</tr>';
                //
                $('.jsContentArea .company_info tbody').html(companyRow);

                getCompanyLocationDetails();
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
     * Create new link of AutomotoHR company with ComplyNet company
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
     * link new complynet company against AutoMotoHR company
     */
    $(document).on('click', '.jsEditCompany', function(event) {
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
     * Delete complynet company against AutoMotoHR company
     */
    $(document).on('click', '.jsDeleteCompanyLink', function(event) {
        //
        var companyRowSid = $(this).data("row_id");
        //
        $('[data-page="jsCompanyOnboardModalLoader"]').show(0);
        //
        var obj = {
            rowSid: companyRowSid
        };
        //
        $.post(
            window.location.origin+'/delete_company_link',
            obj
        )
        .done(function(resp){
            $('[data-page="jsCompanyOnboardModalLoader"]').hide(0);
            //
            if(resp.code == 'DC'){
                alertify.alert('Success!', resp.message, function(){
                    terminateCall();
                });
            }
           
        })
        .fail(terminateCall);
    });


    /**
     * Check and get company locations details
     * for ComplyNet
     *
     * @depends showCompanyOnboardPopup
     */
    function getCompanyLocationDetails(){
        //
        loader(true,"Please wait while fetching company location");
        //
        return $
        .get(window.location.origin+'/complynet/get_company_locations_details/'+parentCompanyId)
        .done(function(resp){
            loader(false);
            if (resp.code == 'LNF') {
               $('.jsContentArea .location_info tbody').html('<tr><td class="text-center" colspan="3">'+resp.message+'</td></tr>'); 
            }

            if (resp.code == 'LF') {
                let locationRow = '';
                //
                resp.locationDetails.map(function(location){
                    locationRow +='<tr>';
                    locationRow +='  <td class="csVm">';
                    locationRow +='      <strong>'+location.automotohr_location_name+'</strong> <br>';
                    locationRow +='      <span>Id: '+location.automotohr_location_id+'</span>';
                    locationRow +='  </td>';
                    locationRow +='  <td class="csVm">';
                    locationRow +='      <strong>'+location.complynet_location_name+'</strong><br />';
                    locationRow +='      <span>Id: '+location.complynet_location_id+'</span>';
                    locationRow +='  </td>';
                    locationRow +='  <td class="csVm">';
                    locationRow +='      <span>'+ moment(location.created_at).format('MMM Do YYYY, ddd H:m:s')+'</span>';
                    locationRow +='  </td>';
                    locationRow +='  <td class="csVm">';
                    if (location.status == 1) {
                        locationRow +='      <strong class="text-success">ACTIVE</strong>';     
                    } else {
                        locationRow +='      <strong class="text-warning">DEACTIVE</strong>';
                    }
                    locationRow +='  </td>';
                    locationRow +='  <td class="csVm">';
                    locationRow +='      <button class="btn btn-warning jsEditLocationLink" data-row_id="'+location.sid+'" data-ahr_id="'+location.automotohr_location_id+'" data-cn_id="'+location.complynet_location_id+'">Edit</button>';
                    locationRow +='      <button class="btn btn-danger jsDeleteLocationLink" data-row_id="'+location.sid+'">Delete</button>';
                    locationRow +='  </td>';
                    locationRow +='</tr>';
                });
                
                //
                $('.jsContentArea .location_info tbody').html(locationRow);

                getCompanyDepartmentsDetail();
            }
        })
        .fail(terminateCall);
    }

    /**
     * Get complynet and AutoMotoHR company locations to link 
     */
    $(document).on('click', '.jsLinkLocation', function(event) {
        showCompanyLocationOnboardPopup(0,0,0);
    });

    /**
     * Show company location link popup
     *
     * @depends Modal
     */
    function showCompanyLocationOnboardPopup(row_id, AHR_location_id, CN_location_id){
        loader(true,"Please wait while fetching complynet company location");
        //
        $
        .get(window.location.origin+'/complynet/get_complynet_locations/'+parentCompanyId)
        .done(function(resp){
            //
            if(resp.code == 'FS'){
                Modal({
                    Id: 'jsLocationOnboardModal',
                    Loader: 'jsLocationOnboardModalLoader',
                    Title: 'Company Onboard',
                    Body: '<div class="container"><div id="jsLocationOnboardModalBody"></div></div>'
                }, function(){
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
                    rows +='    <div class="col-sm-12">';
                    rows +='        <p class="text-info" style="font-size: 22px !important;">';
                    rows +='            <strong text-center>';
                    rows +=                 $("#jsParentCompany option:selected").text();
                    rows +='            </strong>';
                    rows +='        </p>';
                    rows +='    </div>';
                    rows +='</div>';
                    rows +='<input type="hidden" id="jsLocationRowId" value="'+row_id+'">';
                    rows +='<div class="row"><br />';
                    rows +='    <div class="col-sm-12">';
                    rows +='        <label>AutomotoHR Location</label>';
                    rows +='        <select id="jsAutomotoHRLocation" style="width: 100%;">';
                    rows +='            <option value="not_selected">Select Location</option>';
                    resp.automotoHRLocations.map(function(location){
                        rows +='            <option value="'+location.sid+'">'+location.address+'</option>';
                    });
                    rows +='        </select>';
                    rows +='    </div>';
                    rows +='    <div class="col-sm-12"><br />';
                    rows +='        <label>ComplyNet location</label>';
                    rows +='        <select id="jsComplyNetLocation" style="width: 100%;">';
                    rows +='            <option value="0">Select Location</option>';
                    $.each(resp.complynetLocations, function(k,v){
                        rows +='            <option value="'+v.Id+'">'+v.Name+'</option>';
                    });
                    rows +='        </select>';
                    rows +='    </div>';
                    rows +='    <div class="col-sm-12 text-right">';
                    rows +='        <hr />';
                    rows +='        <button class="btn btn-success jsSaveLinkLocation">Save</button>';
                    rows +='        <button class="btn btn-black jsModalCancel">Cancel</button>';
                    rows +='    </div>';
                    rows +='</div>';
                    //
                    $('#jsLocationOnboardModalBody').html(rows)
                    //
                    $('#jsComplyNetLocation').select2();
                    //
                    if (CN_location_id != 0) {
                        console.log(CN_location_id)
                        $("#jsComplyNetLocation").select2("val", CN_location_id);
                    }
                    //
                    $('#jsAutomotoHRLocation').select2();
                    //
                    if (AHR_location_id != "not_selected") {
                        console.log(AHR_location_id)
                        $("#jsAutomotoHRLocation").select2("val", AHR_location_id);
                    }
                    //
                    $('[data-page="jsLocationOnboardModalLoader"]').hide(0);
                    //
                    loader(false);
                    
                });
            }
        })
        .fail(terminateCall);
    }

    /**
     * Add or Edit location
     */
    $(document).on('click', '.jsSaveLinkLocation', function(event) {
        //
        event.preventDefault();
        //
        var CNLocation = $('#jsComplyNetLocation').val();
        var AHRLocation = $('#jsAutomotoHRLocation').val();
        var locationRowId = $('#jsLocationRowId').val();
        //
        if (AHRLocation == "not_selected" || AHRLocation == undefined) {
            alertify.alert("Notice","Please select AutomotoHR location to link");
            return;
        }
        //
        if (CNLocation == 0 || CNLocation == undefined) {
            alertify.alert("Notice","Please select ComplyNet location to link");
            return;
        }
        //
        loader(true,'Please wait');
        //
        var obj = {
            companySid: $("#jsParentCompany").val(),
            AHRLocationSid: AHRLocation,
            rowSid: locationRowId,
            AHRLocationName: $('#jsAutomotoHRLocation option:selected').text().trim(),
            CNLocationSid: CNLocation,
            CNLocationName: $('#jsComplyNetLocation option:selected').text().trim()
        };
        //
        $.post(
            window.location.origin+'/link_location',
            obj
        )
        .done(function(resp){
            loader(false);
            //
            if(resp.code == 'AR'){
                alertify.alert('Notice!', resp.message);
                return;
            }
            //
            if(resp.code == 'RS'){
                alertify.alert('Success!', resp.message, function(){
                    getCompanyLocationDetails();
                    $("#jsLocationOnboardModal").hide();
                });
                return;
            }
            //
            alertify.alert('Notice!', resp.message);
           
        })
        .fail(terminateCall);
    });

    /**
     * onClick function to edit location
     */
    $(document).on('click', '.jsEditLocationLink', function(event) {
        //
        event.preventDefault();
        //
        var row_id = $(this).data("row_id");
        var AHR_location_id = $(this).data("ahr_id");
        var CN_location_id = $(this).data("cn_id");
        //
        showCompanyLocationOnboardPopup(row_id, AHR_location_id, CN_location_id);
    });

    /**
     * Delete company linked location
     */
    $(document).on('click', '.jsDeleteLocationLink', function(event) {
        //
        var locationRowSid = $(this).data("row_id");
        //
        alertify.confirm('Confirmation', "Are you sure you want to delete this location?",
            function () {
                //
                loader(true);
                //
                var obj = {
                    rowSid: locationRowSid
                };
                //
                $.post(
                    window.location.origin+'/delete_location_link',
                    obj
                )
                .done(function(resp){
                    loader(false);
                    //
                    if(resp.code == 'DL'){

                        alertify.alert('Success!', resp.message, function(){
                            getCompanyLocationDetails();
                        });
                    }
                   
                })
                .fail(terminateCall);
            },
            function () {

            })
    });

    function getCompanyDepartmentsDetail () {
         //
        loader(true,"Please wait while fetching company location");
        //
        return $
        .get(window.location.origin+'/complynet/get_company_departments_details/'+parentCompanyId)
        .done(function(resp){
            loader(false);
            if (resp.code == 'DNF') {
               $('.jsContentArea .location_info tbody').html('<tr><td class="text-center" colspan="3">'+resp.message+'</td></tr>'); 
            }

            if (resp.code == 'DF') {
                let locationRow = '';
                //
                resp.locationDetails.map(function(location){
                    locationRow +='<tr>';
                    locationRow +='  <td class="csVm">';
                    locationRow +='      <strong>'+location.automotohr_location_name+'</strong> <br>';
                    locationRow +='      <span>Id: '+location.automotohr_location_id+'</span>';
                    locationRow +='  </td>';
                    locationRow +='  <td class="csVm">';
                    locationRow +='      <strong>'+location.complynet_location_name+'</strong><br />';
                    locationRow +='      <span>Id: '+location.complynet_location_id+'</span>';
                    locationRow +='  </td>';
                    locationRow +='  <td class="csVm">';
                    locationRow +='      <span>'+ moment(location.created_at).format('MMM Do YYYY, ddd H:m:s')+'</span>';
                    locationRow +='  </td>';
                    locationRow +='  <td class="csVm">';
                    if (location.status == 1) {
                        locationRow +='      <strong class="text-success">ACTIVE</strong>';     
                    } else {
                        locationRow +='      <strong class="text-warning">DEACTIVE</strong>';
                    }
                    locationRow +='  </td>';
                    locationRow +='  <td class="csVm">';
                    locationRow +='      <button class="btn btn-warning jsEditLocationLink" data-row_id="'+location.sid+'" data-ahr_id="'+location.automotohr_location_id+'" data-cn_id="'+location.complynet_location_id+'">Edit</button>';
                    locationRow +='      <button class="btn btn-danger jsDeleteLocationLink" data-row_id="'+location.sid+'">Delete</button>';
                    locationRow +='  </td>';
                    locationRow +='</tr>';
                });
                
                //
                $('.jsContentArea .location_info tbody').html(locationRow);

                // getCompanyDepartmentsDetail();
            }
        })
        .fail(terminateCall);
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