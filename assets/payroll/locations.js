$(function CompanyLocation() {
    //
    var LOADER = 'company_locations';
    //
    var CompanyStates;

    /**
     * Add a company location
     */
    $('.jsLocationAdd').click(function(event) {
        // Stop the default action
        event.preventDefault();
        //
        Modal({
            Id: "jsLocationAddModal",
            Title: "Add A Company Location",
            Loader: "jsLocationAddModalLoader",
            Body: '<div id="jsLocationAddModalBody">' + (GetLocationBody('Add')) + '</div>'
        }, async function() {
            //
            if (!CompanyStates) {
                await GetCompanyStates();
            }
            //
            var rows = '<option value="0">[Select]</option>';
            //
            CompanyStates.map(function(state) {
                rows += '<option value="' + (state.Code) + '">' + (state.Name) + '</option>';
            });
            //
            $('.jsAddLocationState').html(rows).select2();
            $('.jsAddLocationCountry').select2({
                minimumResultsForSearch: -1
            });
            //
            ml(false, "jsLocationAddModalLoader");
        });
    });

    /**
     * Save location
     */
    $(document).on('click', '.jsModalSave', function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        //
        o.Country = $('.jsAddLocationCountry').val();
        //
        o.State = $('.jsAddLocationState').val();
        //
        o.City = $('.jsAddLocationCity').val().trim();
        //
        o.Street1 = $('.jsAddLocationStreet1').val().trim();
        //
        o.Street2 = $('.jsAddLocationStreet2').val().trim();
        //
        o.Zipcode = $('.jsAddLocationZipcode').val().trim();
        //
        o.PhoneNumber = $('.jsAddLocationPhoneNumber').val().trim().replace(/[^0-9]/g, '');
        //
        o.MailingAddress = $('.jsAddLocationMailingAddress').prop('checked') ? 1 : 0;
        //
        o.FillingAddress = $('.jsAddLocationFillingAddress').prop('checked') ? 1 : 0;
        //
        if (o.State == "0") {
            return alertify.alert('Error!', "Please select a state.");
        }
        //
        if (!o.City) {
            return alertify.alert('Error!', "City is required.");
        }
        //
        if (!o.Street1) {
            return alertify.alert('Error!', "Street 1 is required.");
        }
        //
        if (!o.Zipcode) {
            return alertify.alert('Error!', "Zipcode is required.");
        }
        //
        if (!o.PhoneNumber) {
            return alertify.alert('Error!', "Phone Number is required.");
        }
        //
        if (o.PhoneNumber.length !== 10) {
            return alertify.alert('Error!', "Phone Number must be of 10 digits long.");
        }
        //
        ml(true, "jsLocationAddModalLoader");
        //
        $.ajax({
            url: API_URL,
            method: "POST",
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify(o)
        }).done(function(resp) {
            //
            ml(false, "jsLocationAddModalLoader");
            //
            if (!resp.status) {
                //
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br/>") : resp.response);
            } else {
                //
                return alertify.alert('Success!', resp.response, function() {
                    //
                    $('#jsLocationAddModal .jsModalCancel').click();
                });
            }
        });
    });

    /**
     * Edit a company location
     */
    $(document).on('click', '.jsLocationEdit', function(event) {
        // Stop the default action
        event.preventDefault();
        //
        var locationId = $(this).closest('tr').data('id');
        //
        Modal({
            Id: "jsLocationEditModal",
            Title: "Add A Company Location",
            Loader: "jsLocationEditModalLoader",
            Body: '<div id="jsLocationEditModalBody">' + (GetLocationBody('Edit')) + '</div>'
        }, async function() {
            //
            if (!CompanyStates) {
                await GetCompanyStates();
            }
            //
            var rows = '<option value="0">[Select]</option>';
            //
            CompanyStates.map(function(state) {
                rows += '<option value="' + (state.Code) + '">' + (state.Name) + '</option>';
            });
            //
            $('.jsEditLocationState').html(rows).select2();
            $('.jsEditLocationCountry').select2({
                minimumResultsForSearch: -1
            });
            // Get the single location
            var location = await GetSingleLocation(locationId);
            //
            $('.jsEditLocationState').select2('val', location.response.State);
            //
            $('.jsEditLocationCity').val(location.response.City);
            $('.jsEditLocationStreet1').val(location.response.Street1);
            $('.jsEditLocationStreet2').val(location.response.Street2);
            $('.jsEditLocationZipcode').val(location.response.Zipcode);
            $('.jsEditLocationPhoneNumber').val(location.response.PhoneNumber);
            $('.jsEditLocationMailingAddress').prop("checked", location.response.MailingAddress);
            $('.jsEditLocationFillingAddress').prop("checked", location.response.FilingAddress);
            //
            $('.jsModifiedBy').text(location.response.Name);
            $('.jsModifiedOn').text(location.response.LastModifiedOn);
            //
            $('.jsModalUpdateId').val(location.response.LocationId);
            //
            ml(false, "jsLocationEditModalLoader");
        });
    });

    /**
     * Update location
     */
    $(document).on('click', '.jsModalUpdate', function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        //
        o.Country = $('.jsEditLocationCountry').val();
        //
        o.State = $('.jsEditLocationState').val();
        //
        o.City = $('.jsEditLocationCity').val().trim();
        //
        o.Street1 = $('.jsEditLocationStreet1').val().trim();
        //
        o.Street2 = $('.jsEditLocationStreet2').val().trim();
        //
        o.Zipcode = $('.jsEditLocationZipcode').val().trim();
        //
        o.PhoneNumber = $('.jsEditLocationPhoneNumber').val().trim().replace(/[^0-9]/g, '');
        //
        o.MailingAddress = $('.jsEditLocationMailingAddress').prop('checked') ? 1 : 0;
        //
        o.FillingAddress = $('.jsEditLocationFillingAddress').prop('checked') ? 1 : 0;
        //
        if (o.State == "0") {
            return alertify.alert('Error!', "Please select a state.");
        }
        //
        if (!o.City) {
            return alertify.alert('Error!', "City is required.");
        }
        //
        if (!o.Street1) {
            return alertify.alert('Error!', "Street 1 is required.");
        }
        //
        if (!o.Zipcode) {
            return alertify.alert('Error!', "Zipcode is required.");
        }
        //
        if (!o.PhoneNumber) {
            return alertify.alert('Error!', "Phone Number is required.");
        }
        //
        if (o.PhoneNumber.length !== 10) {
            return alertify.alert('Error!', "Phone Number must be of 10 digits long.");
        }
        //
        o.Id = $('.jsModalUpdateId').val();
        //
        ml(true, "jsLocationEditModalLoader");
        //
        $.ajax({
            url: API_URL,
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify(o)
        }).done(function(resp) {
            //
            ml(false, "jsLocationEditModalLoader");
            //
            if (!resp.status) {
                //
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br/>") : resp.response);
            } else {
                //
                return alertify.alert('Success!', resp.response, function() {
                    //
                    $('#jsLocationEditModal .jsModalCancel').click();
                });
            }
        });
    });

    /**
     * Location history
     */
    $(document).on('click', '.jsLocationHistory', function(event) {
        // Stop the default action
        event.preventDefault();
        //
        var locationId = $(this).closest('tr').data('id');
        //
        Modal({
            Id: "jsLocationHistoryModal",
            Title: "Edit A Company Location",
            Loader: "jsLocationHistoryModalLoader",
            Body: '<div id="jsLocationHistoryModalBody"></div>'
        }, async function() {
            // Get the single location
            var location = await GetLocationHistory(locationId);
            //
            location = location.response;
            //
            var rows = '';
            if (location.length) {
                //
                location.map(function(v) {
                    //
                    rows += '<tr data-id="' + (v.LocationId) + '">';
                    rows += '   <td class="vam csF14">' + (v.LocationCode != 0 ? v.LocationCode + '<br/>' + v.Version : 'N/A') + '</td>';
                    rows += '   <td class="vam csF14 text-right">' + (v.Country) + '</td>';
                    rows += '   <td class="vam csF14 text-right">' + (v.State) + '</td>';
                    rows += '   <td class="vam csF14 text-right">' + (v.City) + '</td>';
                    rows += '   <td class="vam csF14 text-right">' + (v.Zipcode) + '</td>';
                    rows += '   <td class="vam csF14 text-right">' + (v.Street1) + '</td>';
                    rows += '   <td class="vam csF14 text-right">' + (v.Street2 ? v.Street2 : 'N/A') + '</td>';
                    rows += '   <td class="vam csF14 text-right">' + (v.PhoneNumber) + '</td>';
                    rows += '   <td class="vam csF14 text-right">' + (v.Name) + '<br/>' + (v.LastModifiedOn) + '</td>';
                    rows += '</tr>';
                });
            } else {
                //
                rows += '<tr>';
                rows += '   <td colspan="9"><p class="alert alert-info csF16 csB7 text-center">No history found</p></td>';
                rows += '</tr>';
            }
            //
            var html = '';
            //
            html += '<div class="container">';
            html += '<table class="table table-striped">';
            html += '    <caption></caption>';
            html += '    <thead>';
            html += '        <tr>';
            html += '            <th class="csF16 csB7 csW csBG2 vam">Code/Version</th>';
            html += '            <th class="csF16 csB7 csW csBG2 vam text-right">Country</th>';
            html += '            <th class="csF16 csB7 csW csBG2 vam text-right">State</th>';
            html += '            <th class="csF16 csB7 csW csBG2 vam text-right">City</th>';
            html += '            <th class="csF16 csB7 csW csBG2 vam text-right">Zipcode</th>';
            html += '            <th class="csF16 csB7 csW csBG2 vam text-right">Street 1</th>';
            html += '            <th class="csF16 csB7 csW csBG2 vam text-right">Street 2</th>';
            html += '            <th class="csF16 csB7 csW csBG2 vam text-right">Phone Number</th>';
            html += '            <th class="csF16 csB7 csW csBG2 vam text-right">Last Modified</th>';
            html += '        </tr>';
            html += '    </thead>';
            html += '    <tbody>' + (rows) + '</tbody>';
            html += '</table>';
            html += '<div>';
            //
            $('#jsLocationHistoryModalBody').html(html);
            //
            ml(false, "jsLocationHistoryModalLoader");
        });
    });

    /**
     * Get Company Locations
     */
    function GetCompanyLocations() {
        //
        ml(true, LOADER);
        //
        $.get(API_URL)
            .done(function(resp) {
                //
                var rows = '';
                //
                if (resp.status) {
                    //
                    resp.response.map(function(v) {
                        //
                        rows += '<tr data-id="' + (v.LocationId) + '">';
                        rows += '   <td class="vam csF14">' + (v.LocationId) + '' + (v.LocationCode != 0 ? '<br/>' + (v.LocationCode) : '') + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.Country) + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.State) + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.City) + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.Zipcode) + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.Street1) + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.Street2 ? v.Street2 : 'N/A') + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.PhoneNumber) + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.Name) + '<br/>' + (v.LastModifiedOn) + '</td>';
                        rows += '   <td class="vam csF14 text-right ">';
                        rows += '       <button class="btn btn-warning csF14 csB7 jsLocationEdit"><i class="fa fa-edit csF14" aria-hidden="true"></i>&nbsp;Edit</button>';
                        rows += '       <button class="btn btn-success csF14 csB7 jsLocationHistory"><i class="fa fa-history csF14" aria-hidden="true"></i>&nbsp;View History</button>';
                        rows += '   </td>';
                        rows += '</tr>';
                    });

                } else {
                    //
                    rows += '<tr>';
                    rows += '   <td colspan="10">';
                    rows += '       <p class="alert alert-info text-center csF16 csB7">No locations found.</p>';
                    rows += '   </td>';
                    rows += '</tr>';
                }
                //
                $("#jsLocationBody").html(rows);
                // Hides the loader
                ml(false, LOADER);
            });
    }

    /**
     * Get Location Body
     * 
     * @param {String} prefix
     * @returns
     */
    function GetLocationBody(prefix) {
        //
        var html = '';
        //
        html += '<div class="container">';
        if (prefix == 'Edit') {
            html += '    <!-- Last Modified -->';
            html += '    <div class="row">';
            html += '        <div class="col-md-12">';
            html += '            <p class="csF16">Last Modified By <span class="csB7 jsModifiedBy"></span> On <span class="csB7 jsModifiedOn"></span></p>';
            html += '        </div>';
            html += '    </div><hr/>';

        }
        html += '    <!-- Country -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Country <span class="csRequired"></span></label>';
        html += '            <select class="form-control js' + (prefix) + 'LocationCountry">';
        html += '               <option value="USA">United States</option>';
        html += '            </select>';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- State -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">State <span class="csRequired"></span></label>';
        html += '            <select class="form-control js' + (prefix) + 'LocationState">';
        html += '            </select>';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- City -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">City <span class="csRequired"></span></label>';
        html += '            <input type="text" class="form-control js' + (prefix) + 'LocationCity" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- Street 1 -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Street 1 <span class="csRequired"></span></label>';
        html += '            <input type="text" class="form-control js' + (prefix) + 'LocationStreet1" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- Street 2 -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Street 2 </label>';
        html += '            <input type="text" class="form-control js' + (prefix) + 'LocationStreet2" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- Zipcode -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Zipcode <span class="csRequired"></span></label>';
        html += '            <input type="text" class="form-control js' + (prefix) + 'LocationZipcode" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- Phone number -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Phone number <span class="csRequired"></span></label>';
        html += '            <input type="text" class="form-control js' + (prefix) + 'LocationPhoneNumber" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- Mailing Address -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7 control control--checbox">Mailing Address <br><span class="csInfo csF14 csB2">Specify if this location is the company\'s mailing address.</span>';
        html += '               <input type="checkbox" class="form-control js' + (prefix) + 'LocationMailingAddress"  name="mailing_address"/>';
        html += '               <div class="control__indicator"></div>';
        html += '            </label>';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- Filling Address -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7 control control--checbox">Filling Address <br><span class="csInfo csF14 csB2">Specify if this location is the company\'s filing address.</span>';
        html += '               <input type="checkbox" class="form-control js' + (prefix) + 'LocationFillingAddress"  name="filling_address"/>';
        html += '               <div class="control__indicator"></div>';
        html += '            </label>';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12 text-right">';
        html += '            <button class="btn btn-cancel csW csF16 csB7 jsModalCancel">';
        html += '                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancel';
        html += '            </button>';
        if (prefix == 'Add') {
            html += '            <button class="btn btn-success csF16 csB7 jsModalSave">';
            html += '                <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save Location';
            html += '            </button>';
        } else {
            html += '            <button class="btn btn-success csF16 csB7 jsModalUpdate">';
            html += '                <i class="fa fa-update" aria-hidden="true"></i>&nbsp;Update Location';
            html += '            </button>';
            html += '            <input type="hidden" class="jsModalUpdateId">';
        }
        html += '        </div>';
        html += '    </div>';
        html += '</div>';

        //
        return html;
    }

    /**
     * Get company states
     * @returns
     */
    function GetCompanyStates() {
        return new Promise(function(res, rej) {
            //
            $.get(API_URL.replace(/company\/locations/, 'states'))
                .done(function(resp) {
                    //
                    CompanyStates = resp.response;
                    //
                    res(CompanyStates);
                });
        });
    }

    /**
     * Get single location
     * @returns
     */
    function GetSingleLocation(locationId) {
        return new Promise(function(res, rej) {
            //
            $.get(API_URL + '/' + locationId)
                .done(function(resp) {
                    //
                    res(resp);
                });
        });
    }

    /**
     * Get location history
     * @returns
     */
    function GetLocationHistory(locationId) {
        return new Promise(function(res, rej) {
            //
            $.get(API_URL + '/history/' + locationId)
                .done(function(resp) {
                    //
                    res(resp);
                });
        });
    }

    /**
     * Set AJAX default setting
     */
    $.ajaxSetup({ headers: { "Key": API_KEY } });

    /**
     * Call
     */
    GetCompanyLocations();
});