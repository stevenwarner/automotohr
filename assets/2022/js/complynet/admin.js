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

    window.getLocationDetails = getCompanyLocationDetails;

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
                $('.jsContentArea .companyInfo').removeClass('hidden');
                $('.jsContentArea .locationInfo').removeClass('hidden');
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
                $('.jsContentArea .companyInfo tbody').html(companyRow);

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
    function getCompanyLocationDetails(){ alert('sdf');
        //
        loader(true,"Please wait while fetching company location");
        //
        return $
        .get(window.location.origin+'/complynet/get_company_locations_details/'+parentCompanyId)
        .done(function(resp){
            loader(false);
            if (resp.code == 'LNF') {
               $('.jsContentArea .locationInfo tbody').html('<tr><td class="text-center" colspan="3">'+resp.message+'</td></tr>'); 
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
                $('.jsContentArea .locationInfo tbody').html(locationRow);

                $('.jsContentArea .departmentInfo').removeClass('hidden');

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
                        $("#jsComplyNetLocation").select2("val", CN_location_id);
                    }
                    //
                    $('#jsAutomotoHRLocation').select2();
                    //
                    if (AHR_location_id != "not_selected") {
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
        $('[data-page="jsLocationOnboardModalLoader"]').show(0);
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
            $('[data-page="jsLocationOnboardModalLoader"]').hide(0);
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
               $('.jsContentArea .departmentInfo tbody').html('<tr><td class="text-center" colspan="3">'+resp.message+'</td></tr>'); 
            }

            if (resp.code == 'DF') {
                let departmentRow = '';
                //
                resp.departmentsDetails.map(function(department){
                    departmentRow +='<tr>';
                    departmentRow +='  <td class="csVm">';
                    departmentRow +='      <strong>'+department.automotohr_department_name+'</strong> <br>';
                    departmentRow +='      <span>Id: '+department.automotohr_department_id+'</span>';
                    departmentRow +='  </td>';
                    departmentRow +='  <td class="csVm">';
                    departmentRow +='      <strong>'+department.complynet_department_name+'</strong><br />';
                    departmentRow +='      <span>Id: '+department.complynet_department_id+'</span>';
                    departmentRow +='  </td>';
                    departmentRow +='  <td class="csVm">';
                    departmentRow +='      <span>'+ moment(department.created_at).format('MMM Do YYYY, ddd H:m:s')+'</span>';
                    departmentRow +='  </td>';
                    departmentRow +='  <td class="csVm">';
                    if (department.status == 1) {
                        departmentRow +='      <strong class="text-success">ACTIVE</strong>';     
                    } else {
                        departmentRow +='      <strong class="text-warning">DEACTIVE</strong>';
                    }
                    departmentRow +='  </td>';
                    departmentRow +='  <td class="csVm">';
                    departmentRow +='      <button class="btn btn-danger jsDeleteDepartmentLink" data-row_id="'+department.sid+'">Delete</button>';
                    departmentRow +='  </td>';
                    departmentRow +='</tr>';
                });
                //
                $('.jsContentArea .departmentInfo tbody').html(departmentRow);
                //
                $('.jobRoleInfo').removeClass('hidden');
                //
                getCompanyJobRolesDetail();
            }
        })
        .fail(terminateCall);
    }

    /**
     * Get complynet and AutoMotoHR departments to link 
     */
    $(document).on('click', '.jsAddDepartment', function(event) {
        //
        loader(true,"Please wait while fetching complynet company location");
        //
        $
        .get(window.location.origin+'/complynet/get_complynet_linked_locations/'+parentCompanyId)
        .done(function(resp){
            //
            if(resp.code == 'FS'){
                Modal({
                    Id: 'jsDepartmentOnboardModal',
                    Loader: 'jsCreateLinkOnboardModalLoader',
                    Title: 'Company Onboard',
                    Body: '<div class="container"><div id="jsDepartmentOnboardModalBody"></div></div>'
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
                    rows +='<div class="row"><br />';
                    rows +='    <div class="col-sm-12">';
                    rows +='        <label>AutomotoHR Location</label>';
                    rows +='        <select id="jsAutomotoHRSelectLocation" data-type="department" style="width: 100%;">';
                    rows +='            <option value="not_selected">Select Location</option>';
                    resp.locations.map(function(location){
                        rows +='            <option value="'+location.sid+'">'+location.automotohr_location_name+'</option>';
                    });
                    rows +='        </select>';
                    rows +='    </div>';
                    rows +='    <div class="jsLinkComplynetSection hidden">';
                    rows +='        <div class="col-sm-12"><br />';
                    rows +='            <label>AutomotoHR Department</label>';
                    rows +='            <select id="jsAutomotoHRDepartment" style="width: 100%;" multiple>';
                    rows +='                <option value="0">Select Department</option>';
                    rows +='            </select>';
                    rows +='        </div>';
                    rows +='        <div class="col-sm-12"><br />';
                    rows +='            <label>ComplyNet Department</label>';
                    rows +='            <select id="jsComplyNetDepartment" style="width: 100%;">';
                    rows +='                <option value="0">Select Department</option>';
                    rows +='            </select>';
                    rows +='        </div>';
                    rows +='        <div class="col-sm-12"><br />';
                    rows +='            <label class="control control--checkbox"><input type="checkbox" class="jsCreateNewDepartmentCB"> Create New Department<div class ="control__indicator"></div</label>';
                    rows +='        </div>';
                    rows +='    </div>';
                    rows +='    <div class="col-sm-12 text-right">';
                    rows +='        <hr />';
                    rows +='        <button class="btn btn-success jsSaveDepartment">Save</button>';
                    rows +='        <button class="btn btn-black jsModalCancel">Cancel</button>';
                    rows +='    </div>';
                    rows +='</div>';
                    //
                    $('#jsDepartmentOnboardModalBody').html(rows)
                    //
                    $('#jsAutomotoHRSelectLocation').select2();
                    //
                    $('[data-page="jsCreateLinkOnboardModalLoader"]').hide(0);
                    //
                    
                });
            }

            if (resp.code == 'LNF') {
                alertify.alert('Notice!', resp.message);
            }
            //
            loader(false);
        })
        .fail(terminateCall);
    });

    $(document).on('click', '.jsCreateNewDepartmentCB', function(event) {
        //
        if ($(".jsCreateNewDepartment").is(":checked")) {
            $('#jsComplyNetDepartment').select2("enable", false)
        } else {
            $('#jsComplyNetDepartment').select2("enable", true);
        }
    });

    /**
     * Get departments against selected location
     */
    $(document).on("change","#jsAutomotoHRSelectLocation",function(){
        var selectedLocationId = (this.value);
        var type = $("#jsAutomotoHRSelectLocation").data("type");
        //
        $('[data-page="jsCreateLinkOnboardModalLoader"]').show(0);
        //
        $
        .get(window.location.origin+'/complynet/get_specific_location_departments/'+selectedLocationId+'/'+type)
        .done(function(resp){
            //
            $('[data-page="jsCreateLinkOnboardModalLoader"]').hide(0);
            //
            if(resp.code == 'LNF') {
                alertify.alert('Notice!', resp.message);
            }

            if (resp.code == 'FS') {
                if (type == "job_role") {
                    if (typeof resp.selectedDepartment !== 'undefined' && resp.selectedDepartment.length > 0) {
                        $('.jsDepartmentSection').removeClass('hidden');
                        //
                        ahrDepartments = '<option value="0">Select Departments</option>';
                        //
                        resp.selectedDepartment.map(function(department){
                            ahrDepartments += '<option value="'+department.sid+'">'+department.automotohr_department_name+'</option>';
                        });
                        //
                        $('#jsAutomotoHRDepartment').html(ahrDepartments);
                        $('#jsAutomotoHRDepartment').select2();
                    } else {
                        alertify.alert('Notice!', "Please link department with this location first.");
                        $('#jsAutomotoHRDepartment').html("");
                        $('.jsDepartmentSection').addClass('hidden');
                    }
                   
                } else {
                    if ((typeof resp.complyNetDepartments !== 'undefined' && resp.complyNetDepartments.length > 0) && (typeof resp.automotoHRDepartments !== 'undefined' && resp.automotoHRDepartments.length > 0)) {
                        $('.jsLinkComplynetSection').removeClass('hidden');
                        //
                        cnDepartments = '<option value="0">Select Department</option>';
                        //
                        resp.complyNetDepartments.map(function(department){
                            cnDepartments +='            <option value="'+department.Id+'">'+department.Name+'</option>';
                        });
                        //
                        $('#jsComplyNetDepartment').html(cnDepartments);
                        $('#jsComplyNetDepartment').select2();
                        //
                        ahrDepartments = '';
                        //
                        resp.automotoHRDepartments.map(function(department){
                            var disable = '';
                            //
                            if (resp.selectedDepartmentSids.includes(department.sid)) {
                                disable = 'disabled';
                            }
                            //
                            ahrDepartments +='            <option value="'+department.sid+'" '+disable+'>'+department.name+'</option>';
                        });
                        //
                        $('#jsAutomotoHRDepartment').html(ahrDepartments);
                        $('#jsAutomotoHRDepartment').select2();
                    } else {
                        alertify.alert('Notice!', "No departments found.");
                        $('#jsComplyNetDepartment').html("");
                        $('#jsAutomotoHRDepartment').html("");
                        $('.jsLinkComplynetSection').addClass('hidden');
                    }    
                }    
            }
        })
        .fail(terminateCall);
    });

    /**
     * Save new department link
     */
    $(document).on('click', '.jsSaveDepartment', function(event) {
        //
        event.preventDefault();
        //
        var locationRowId = $('#jsAutomotoHRSelectLocation').val();
        var automotoHRDepartmentID = $('#jsAutomotoHRDepartment').val();
        //
        //
        if (automotoHRDepartmentID == 0 || automotoHRDepartmentID == undefined) {
            alertify.alert("Notice","Please select AutomotoHR department to link");
            return;
        }
        //
        var obj = {
            companySid: parentCompanyId,
            locationRowSid: locationRowId,
            AHRDepartmentSid: automotoHRDepartmentID
        };
        //
        if (!$(".jsCreateNewDepartment").is(":checked")) {
            //
            var complyNetDepartmentID = $('#jsComplyNetDepartment').val();
            var complyNetDepartmentName = $('#jsComplyNetDepartment option:selected').text();
            //
            if (complyNetDepartmentID == 0 || complyNetDepartmentID == undefined) {
                alertify.alert("Notice","Please select ComplyNet department to link");
                return;
            }

            obj.complyNetDepartmentSiD = complyNetDepartmentID;
            obj.complyNetDepartmentName = complyNetDepartmentName;
        } 
        //
        $('[data-page="jsDepartmentOnboardModalLoader"]').show(0);
        //
        $.post(
            window.location.origin+'/link_department',
            obj
        )
        .done(function(resp){
            $('[data-page="jsDepartmentOnboardModalLoader"]').hide(0);
            //
            if(resp.code == 'RF'){
                alertify.alert('Notice!', resp.message);
                return;
            }
            //
            if(resp.code == 'RS'){
                alertify.alert('Success!', resp.message, function(){
                    getCompanyDepartmentsDetail();
                    $("#jsDepartmentOnboardModal").hide();
                });
                return;
            }
            //
           
        })
        .fail(terminateCall);
    });

    /**
     * Delete company linked department
     */
    $(document).on('click', '.jsDeleteDepartmentLink', function(event) {
        //
        var departmentRowSid = $(this).data("row_id");
        //
        alertify.confirm('Confirmation', "Are you sure you want to delete this department?",
            function () {
                //
                loader(true);
                //
                var obj = {
                    rowSid: departmentRowSid
                };
                //
                $.post(
                    window.location.origin+'/delete_department_link',
                    obj
                )
                .done(function(resp){
                    loader(false);
                    //
                    if(resp.code == 'DD'){

                        alertify.alert('Success!', resp.message, function(){
                            getCompanyDepartmentsDetail();
                        });
                    }
                   
                })
                .fail(terminateCall);
            },
            function () {

            })
    });

    /**
     * Get company jobroles from DB
     */
    function getCompanyJobRolesDetail () {
        //
        loader(true,"Please wait while fetching company location");
        //
        return $
        .get(window.location.origin+'/complynet/get_company_job_role_details/'+parentCompanyId)
        .done(function(resp){
            loader(false);
            if (resp.code == 'JRNF') {
               $('.jsContentArea .jobRoleInfo tbody').html('<tr><td class="text-center" colspan="3">'+resp.message+'</td></tr>'); 
            }

            if (resp.code == 'JRF') {
               let jobRoleRow = '';
                //
                resp.jobRolesDetails.map(function(jobRole){
                    jobRoleRow +='<tr>';
                    jobRoleRow +='  <td class="csVm">';
                    jobRoleRow +='      <strong>'+jobRole.automotohr_jobRole_name+'</strong> <br>';
                    jobRoleRow +='  </td>';
                    jobRoleRow +='  <td class="csVm">';
                    jobRoleRow +='      <strong>'+jobRole.complynet_jobRole_name+'</strong><br />';
                    jobRoleRow +='      <span>Id: '+jobRole.complynet_jobRole_id+'</span>';
                    jobRoleRow +='  </td>';
                    jobRoleRow +='  <td class="csVm">';
                    jobRoleRow +='      <span>'+ moment(jobRole.created_at).format('MMM Do YYYY, ddd H:m:s')+'</span>';
                    jobRoleRow +='  </td>';
                    jobRoleRow +='  <td class="csVm">';
                    if (jobRole.status == 1) {
                        jobRoleRow +='      <strong class="text-success">ACTIVE</strong>';     
                    } else {
                        jobRoleRow +='      <strong class="text-warning">DEACTIVE</strong>';
                    }
                    jobRoleRow +='  </td>';
                    jobRoleRow +='  <td class="csVm">';
                    jobRoleRow +='      <button class="btn btn-danger jsDeletejobRoleLink" data-row_id="'+jobRole.sid+'">Delete</button>';
                    jobRoleRow +='  </td>';
                    jobRoleRow +='</tr>';
                });
                //
                $('.jsContentArea .jobRoleInfo tbody').html(jobRoleRow);
                //
                $('.jobEmployeeInfo').removeClass('hidden');
                //
                getCompanyEmployeesDetail();
            }
        })
        .fail(terminateCall);
    }

    /**
     * Create jobRole link
     */
    $(document).on('click', '.jsAddJobRole', function(event) {
        loader(true,"Please wait while fetching complynet company location");
        //
        $
        .get(window.location.origin+'/complynet/get_complynet_linked_locations/'+parentCompanyId)
        .done(function(resp){
            //
            if(resp.code == 'FS'){
                Modal({
                    Id: 'jsJobRoleOnboardModal',
                    Loader: 'jsCreateLinkOnboardModalLoader',
                    Title: 'Company Onboard',
                    Body: '<div class="container"><div id="jsJobRoleOnboardModalBody"></div></div>'
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
                    rows +='<div class="row"><br />';
                    rows +='    <div class="col-sm-12">';
                    rows +='        <label>AutomotoHR Location</label>';
                    rows +='        <select id="jsAutomotoHRSelectLocation" data-type="job_role" style="width: 100%;">';
                    rows +='            <option value="not_selected">Select Location</option>';
                    resp.locations.map(function(location){
                        rows +='            <option value="'+location.sid+'">'+location.automotohr_location_name+'</option>';
                    });
                    rows +='        </select>';
                    rows +='    </div>';
                    rows +='    <div class="jsDepartmentSection hidden">';
                    rows +='        <div class="col-sm-12"><br />';
                    rows +='            <label>AutomotoHR Department</label>';
                    rows +='            <select class="jsAutomotoHRSelectDepartment" data-type="job_role" id="jsAutomotoHRDepartment" style="width: 100%;">';
                    rows +='                <option value="0">Select Department</option>';
                    rows +='            </select>';
                    rows +='        </div>';
                    rows +='    </div>';
                    rows +='    <div class="jsJobRoleSection hidden">';
                    rows +='        <div class="col-sm-12"><br />';
                    rows +='            <label>AutomotoHR JobRole</label>';
                    rows +='            <select id="jsAutomotoHRJobRole" style="width: 100%;" multiple>';
                    rows +='                <option value="0">Select JobRole</option>';
                    rows +='            </select>';
                    rows +='        </div>';
                    rows +='        <div class="col-sm-12"><br />';
                    rows +='            <label>ComplyNet JobRole</label>';
                    rows +='            <select id="jsComplyNetJobRole" style="width: 100%;">';
                    rows +='                <option value="0">Select JobRole</option>';
                    rows +='            </select>';
                    rows +='        </div>';
                    rows +='        <div class="col-sm-12"><br />';
                    rows +='            <label class="control control--checkbox"><input type="checkbox" class="jsCreateNewJobRoleCB"> Create New JobRole<div class ="control__indicator"></div</label>';
                    rows +='        </div>';
                    rows +='    </div>';
                    rows +='    <div class="col-sm-12 text-right">';
                    rows +='        <hr />';
                    rows +='        <button class="btn btn-success jsSaveJobRole">Save</button>';
                    rows +='        <button class="btn btn-black jsModalCancel">Cancel</button>';
                    rows +='    </div>';
                    rows +='</div>';
                    //
                    $('#jsJobRoleOnboardModalBody').html(rows)
                    //
                    $('#jsAutomotoHRSelectLocation').select2();
                    //
                    $('[data-page="jsCreateLinkOnboardModalLoader"]').hide(0);
                    //
                    
                });
            }

            if (resp.code == 'LNF') {
                alertify.alert('Notice!', resp.message);
            }
            //
            loader(false);
        })
        .fail(terminateCall);
    });
    
    /**
     * Get departments against selected location
     */
    $(document).on("change",".jsAutomotoHRSelectDepartment",function(){
        var selectedDepartmentId = (this.value);
        var type = $("#jsAutomotoHRDepartment").data("type");
        //
        $('[data-page="jsCreateLinkOnboardModalLoader"]').show(0);
        //
        $
        .get(window.location.origin+'/complynet/get_specific_job_roles/'+selectedDepartmentId+'/'+type)
        .done(function(resp){
            //
            $('[data-page="jsCreateLinkOnboardModalLoader"]').hide(0);
            //
            if(resp.code == 'LNF') {
                alertify.alert('Notice!', resp.message);
            }

            if (resp.code == 'FS') {
                //
                $('.jsJobRoleSection').removeClass('hidden');
                //
                if (type == "employee") {
                    if (typeof resp.selectedJobRoles !== 'undefined' && resp.selectedJobRoles.length > 0) {
                        //
                        ahrJobRoles = '';
                        //
                        resp.selectedJobRoles.map(function(jobrole){
                            ahrJobRoles += '<option value="'+jobrole.sid+'">'+jobrole.automotohr_jobRole_name+'</option>';
                        });
                        //
                        $('#jsAutomotoHRJobRole').html(ahrJobRoles);
                        $('#jsAutomotoHRJobRole').select2();
                    } else {
                        $('#jsAutomotoHRJobRole').html("");
                        $('.jsJobRoleSection').addClass('hidden');
                        alertify.alert('Notice!', "Please link job role with this department first.");
                    }    
                } else {
                     
                    if ((typeof resp.complyNetDepartments !== 'undefined' && resp.complyNetDepartments.length > 0) && (typeof resp.automotoHRDepartments !== 'undefined' && resp.automotoHRDepartments.length > 0)) {
                        //
                        cnJobRoles = '<option value="0">Select Department</option>';
                        //
                        resp.complyNetJobRoles.map(function(jobRole){
                            cnJobRoles +='            <option value="'+jobRole.Id+'">'+jobRole.Name+'</option>';
                        });
                        //
                        $('#jsComplyNetJobRole').html(cnJobRoles);
                        $('#jsComplyNetJobRole').select2();
                        //
                        ahrJobRoles = '';
                        //
                        resp.automotoHRJobRoles.map(function(jobRole){
                            var disable = '';
                            //
                            if (resp.selectedJobRoles.includes(jobRole)) {
                                disable = 'disabled';
                            }
                            //
                            ahrJobRoles +='            <option value="'+jobRole+'" '+disable+'>'+jobRole+'</option>';
                        });
                        //
                        $('#jsAutomotoHRJobRole').html(ahrJobRoles);
                        $('#jsAutomotoHRJobRole').select2();
                    } else {
                        alertify.alert('Notice!', "No job roles found.");
                        $('#jsComplyNetJobRole').html("");
                        $('#jsAutomotoHRJobRole').html("");
                        $('.jsJobRoleSection').addClass('hidden');
                    }  
                }     
            }
        })
        .fail(terminateCall);
    });

    $(document).on('click', '.jsCreateNewJobRoleCB', function(event) {
        //
        if ($(".jsCreateNewJobRole").is(":checked")) {
            $('#jsComplyNetJobRole').select2("enable", false)
        } else {
            $('#jsComplyNetJobRole').select2("enable", true);
        }
    });
   
    /**
     * Save new jobRole link
     */
    $(document).on('click', '.jsSaveJobRole', function(event) {
        //
        event.preventDefault();
        //
        var departmentRowId = $('#jsAutomotoHRDepartment').val();
        var automotoHRJobRoleList = $('#jsAutomotoHRJobRole').val();
        //
        //
        if (automotoHRJobRoleList == undefined) {
            alertify.alert("Notice","Please select AutomotoHR JobRole to link");
            return;
        }
        //
        var obj = {
            companySid: parentCompanyId,
            departmentRowSid: departmentRowId,
            jobRoleList: automotoHRJobRoleList
        };
        //
        if (!$(".jsCreateNewJobRole").is(":checked")) {
            //
            var complyNetJobRoleId = $('#jsComplyNetJobRole').val();
            var complyNetJobRoleName = $('#jsComplyNetJobRole option:selected').text();
            //
            if (complyNetJobRoleId == 0 || complyNetJobRoleId == undefined) {
                alertify.alert("Notice","Please select ComplyNet JobRole to link");
                return;
            }

            obj.complyNetJobRoleSid = complyNetJobRoleId;
            obj.complyNetJobRoleName = complyNetJobRoleName;
        } 
        //
        $('[data-page="jsCreateLinkOnboardModalLoader"]').show(0);
        //
        $.post(
            window.location.origin+'/link_job_role',
            obj
        )
        .done(function(resp){
            $('[data-page="jsCreateLinkOnboardModalLoader"]').hide(0);
            //
            if(resp.code == 'RF'){
                alertify.alert('Notice!', resp.message);
                return;
            }
            //
            if(resp.code == 'RS'){
                alertify.alert('Success!', resp.message, function(){
                    getCompanyJobRolesDetail();
                    $("#jsJobRoleOnboardModal").hide();
                });
                return;
            }
            //
           
        })
        .fail(terminateCall);
    });

    /**
     * Delete company linked department
     */
    $(document).on('click', '.jsDeletejobRoleLink', function(event) {
        //
        var departmentRowSid = $(this).data("row_id");
        //
        alertify.confirm('Confirmation', "Are you sure you want to delete this jobRole?",
            function () {
                //
                loader(true);
                //
                var obj = {
                    rowSid: departmentRowSid
                };
                //
                $.post(
                    window.location.origin+'/delete_job_role_link',
                    obj
                )
                .done(function(resp){
                    loader(false);
                    //
                    if(resp.code == 'DJR'){

                        alertify.alert('Success!', resp.message, function(){
                            getCompanyJobRolesDetail();
                        });
                    }
                   
                })
                .fail(terminateCall);
            },
            function () {

            })
    });

    /**
     * Get company complynet employees from DB
     */
    function getCompanyEmployeesDetail () {
        //
        loader(true,"Please wait while fetching company complynet employees");
        //
        return $
        .get(window.location.origin+'/complynet/get_company_employees_details/'+parentCompanyId)
        .done(function(resp){
            loader(false);
            if (resp.code == 'ENF') {
               $('.jsContentArea .jobEmployeeInfo tbody').html('<tr><td class="text-center" colspan="3">'+resp.message+'</td></tr>'); 
            }

            if (resp.code == 'EF') {
               let employeeRow = '';
                //
                resp.employeesDetails.map(function(employee){
                    employeeRow +='<tr>';
                    employeeRow +='  <td class="csVm">';
                    employeeRow +='      <strong>'+employee.automotohr_employee_name+'</strong> <br>';
                    employeeRow +='      <span>Id: '+employee.automotohr_employee_id+'</span>';
                    employeeRow +='  </td>';
                    employeeRow +='  <td class="csVm">';
                    employeeRow +='      <strong>'+employee.firstName+' '+employee.lastName+'</strong><br />';
                    employeeRow +='      <span>Id: '+employee.userName+'</span>';
                    employeeRow +='  </td>';
                    employeeRow +='  <td class="csVm">';
                    employeeRow +='      <span>'+ moment(employee.created_at).format('MMM Do YYYY, ddd H:m:s')+'</span>';
                    employeeRow +='  </td>';
                    employeeRow +='  <td class="csVm">';
                    if (employee.status == 1) {
                        employeeRow +='      <strong class="text-success">ACTIVE</strong>';     
                    } else {
                        employeeRow +='      <strong class="text-warning">DEACTIVE</strong>';
                    }
                    employeeRow +='  </td>';
                    employeeRow +='  <td class="csVm">';
                    if (employee.status == 1) {
                        employeeRow +='      <button class="btn btn-danger jsDisableEmployeeLink" data-row_id="'+employee.sid+'" data-status="0" data-name="'+employee.automotohr_employee_name+'">Disable</button>';    
                    } else {
                        employeeRow +='      <button class="btn btn-success jsDisableEmployeeLink" data-row_id="'+employee.sid+'" data-status="1" data-name="'+employee.automotohr_employee_name+'">Enable</button>';
                    }
                    employeeRow +='  </td>';
                    employeeRow +='</tr>';
                });
                //
                $('.jsContentArea .jobEmployeeInfo tbody').html(employeeRow);
            }
        })
        .fail(terminateCall);
    }

    /**
     * Create employee link
     */
    $(document).on('click', '.jsAddEmployee', function(event) {
        loader(true,"Please wait while fetching complynet company employees");
        //
        $
        .get(window.location.origin+'/complynet/get_company_employees/'+parentCompanyId)
        .done(function(resp){
            //
            if(resp.code == 'FS'){
                Modal({
                    Id: 'jsEmployeeOnboardModal',
                    Loader: 'jsCreateLinkOnboardModalLoader',
                    Title: 'Company Onboard',
                    Body: '<div class="container"><div id="jsEmployeeOnboardModalBody"></div></div>'
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
                    rows +='<div class="row"><br />';
                    rows +='    <div class="col-sm-12"><br />';
                    rows +='        <label>Employees</label>';
                    rows +='        <select id="jsAutomotoHREmployees" style="width: 100%;" multiple>';
                    resp.automotoHREmployees.map(function(employee){
                        var disable = '';
                        //
                        if (resp.selectedEmployees.includes(employee.sid)) {
                            disable = 'disabled';
                        }
                        //
                        rows += '<option value="'+employee.sid+'" '+disable+'>'+remakeEmployeeName(employee)+'</option>';
                    });
                    rows +='        </select>';
                    rows +='    </div>';
                    rows +='    <div class="col-sm-12">';
                    rows +='        <label>AutomotoHR Location</label>';
                    rows +='        <select id="jsAutomotoHRSelectLocation" data-type="job_role" style="width: 100%;">';
                    rows +='            <option value="not_selected">Select Location</option>';
                    resp.linkedLocations.map(function(location){
                        rows +='            <option value="'+location.sid+'">'+location.automotohr_location_name+'</option>';
                    });
                    rows +='        </select>';
                    rows +='    </div>';
                    rows +='    <div class="jsDepartmentSection hidden">';
                    rows +='        <div class="col-sm-12"><br />';
                    rows +='            <label>AutomotoHR Department</label>';
                    rows +='            <select class="jsAutomotoHRSelectDepartment" data-type="employee" id="jsAutomotoHRDepartment" style="width: 100%;">';
                    rows +='                <option value="0">Select Department</option>';
                    rows +='            </select>';
                    rows +='        </div>';
                    rows +='    </div>';
                    rows +='    <div class="jsJobRoleSection hidden">';
                    rows +='        <div class="col-sm-12"><br />';
                    rows +='            <label>AutomotoHR JobRole</label>';
                    rows +='            <select id="jsAutomotoHRJobRole" style="width: 100%;">';
                    rows +='                <option value="0">Select JobRole</option>';
                    rows +='            </select>';
                    rows +='        </div>';
                    rows +='    </div>';
                    rows +='    <div class="col-sm-12 text-right">';
                    rows +='        <hr />';
                    rows +='        <button class="btn btn-success jsSaveEmployee">Save</button>';
                    rows +='        <button class="btn btn-black jsModalCancel">Cancel</button>';
                    rows +='    </div>';
                    rows +='</div>';
                    //
                    $('#jsEmployeeOnboardModalBody').html(rows)
                    //
                    $('#jsAutomotoHREmployees').select2();
                    $('#jsAutomotoHRSelectLocation').select2();
                    //
                    $('[data-page="jsCreateLinkOnboardModalLoader"]').hide(0);
                    //
                    
                });
            }

            if (resp.code == 'LNF' || resp.code == 'ENF') {
                alertify.alert('Notice!', resp.message);
            }
            //
            loader(false);
        })
        .fail(terminateCall);
    });

    /**
     * Save new Employee link
     */
    $(document).on('click', '.jsSaveEmployee', function(event) {
        //
        event.preventDefault();
        //
        var locationRowId = $('#jsAutomotoHRSelectLocation').val();
        var departmentRowId = $('#jsAutomotoHRDepartment').val();
        var jobRoleRowId = $('#jsAutomotoHRJobRole').val();
        var automotoHREmployeesList = $('#jsAutomotoHREmployees').val();
        //
        //
        if (automotoHREmployeesList == undefined) {
            alertify.alert("Notice","Please select AutomotoHR employee to link");
            return;
        }
        //
        if (locationRowId == undefined || locationRowId == "not_selected") {
            alertify.alert("Notice","Please select AutomotoHR Location to link");
            return;
        }
        //
        if (departmentRowId == undefined || departmentRowId == 0) {
            alertify.alert("Notice","Please select AutomotoHR Department to link");
            return;
        }
        //
        if (jobRoleRowId == undefined || jobRoleRowId == 0) {
            alertify.alert("Notice","Please select AutomotoHR jobRole to link");
            return;
        }
        //
        var obj = {
            companySid: parentCompanyId,
            locationRowSid: locationRowId,
            departmentRowSid: departmentRowId,
            jobRoleRowSid: jobRoleRowId,
            employeesList: automotoHREmployeesList,
        };
        //
        $('[data-page="jsCreateLinkOnboardModalLoader"]').show(0);
        //
        $.post(
            window.location.origin+'/link_employees',
            obj
        )
        .done(function(resp){
            $('[data-page="jsCreateLinkOnboardModalLoader"]').hide(0);
            //
            if(resp.code == 'RF' || resp.code == 'CNF' || resp.code == 'LNF' || resp.code == 'DNF' || resp.code == 'JRNF'){
                alertify.alert('Notice!', resp.message);
                return;
            }
            //
            if(resp.code == 'RS'){
                alertify.alert('Success!', resp.message, function(){
                    getCompanyEmployeesDetail();
                    $("#jsEmployeeOnboardModal").hide();
                });
                return;
            }
            //
           
        })
        .fail(terminateCall);
    });

    /**
     * Delete company linked department
     */
    $(document).on('click', '.jsDisableEmployeeLink', function(event) {
        //
        var employeeRowSid = $(this).data("row_id");
        var employeeName = $(this).data("name");
        var employeeStatus = $(this).data("status");
        //
        alertify.confirm('Confirmation', "Are you sure you want to disable <b>"+employeeName +"</b>?",
            function () {
                //
                loader(true);
                //
                var obj = {
                    rowSid: employeeRowSid,
                    name: employeeName,
                    status: employeeStatus
                };
                //
                $.post(
                    window.location.origin+'/disable_employee_link',
                    obj
                )
                .done(function(resp){
                    loader(false);
                    //
                    if(resp.code == 'DE'){

                        alertify.alert('Success!', resp.message, function(){
                            getCompanyEmployeesDetail();
                        });
                    }
                   
                })
                .fail(terminateCall);
            },
            function () {

            })
    });

    /**
     * Remake employee name
     */
    function remakeEmployeeName(o, i) {
        //
        var r = '';
        //
        if (i == undefined) r += o.first_name + ' ' + o.last_name;
        //
        if (o.job_title != '' && o.job_title != null) r += ' (' + (o.job_title) + ')';
        //
        r += ' [';
        //
        if (typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
        //
        if (o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1) r += o['access_level'] + ' Plus / Payroll';
        else if (o['access_level_plus'] == 1) r += o['access_level'] + ' Plus';
        else if (o['pay_plan_flag'] == 1) r += o['access_level'] + ' Payroll';
        else r += o['access_level'];
        //
        r += ']';
        //
        return r;
    }

    $(document).on('click', '.jsModalCancel', function(event) {
        $('select').select2('destroy');
        //
    
        // $("#jsAutomotoHRSelectLocation").select2('destroy'); 
        // $('#jsAutomotoHREmployees').select2('destroy');
        // $('#jsComplyNetCompany').select2('destroy');
        // $('#jsAutomotoHrCompany').select2('destroy');
        // $('#jsComplyNetLocation').select2('destroy');
        // $('#jsAutomotoHRLocation').select2('destroy');
        // $('#jsAutomotoHRDepartment').select2('destroy');
        // $('#jsComplyNetDepartment').select2('destroy');
        // $('#jsAutomotoHRJobRole').select2('destroy');
        // $('#jsComplyNetJobRole').select2('destroy');
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