$(function () {
    //
    let employeeId,
        employeeName;
    //
    $('#jsProfileHistory').click(getEmployeeProfileHistory);
    $('.jsProfileHistory').click(getEmployeeProfileHistory);

    /**
     * Get employee profile history
     * @param {*} e 
     */
    function getEmployeeProfileHistory(e) {
        //
        e.preventDefault();
        //
        employeeId = $(this).data('id');
        employeeName = $(this).data('name');
        //
        Model({
            Id: 'jsEmployeeProfileHistoryModel',
            Loader: 'jsEmployeeProfileHistoryLoader',
            Body: '<div class="container"><div id="jsEmployeeProfileHistoryBody"></div></div>',
            Title: 'Profile History of ' + employeeName
        }, getData);
    }

    function getData() {
        //
        let rows = '';
        //
        rows += '<div>';
        rows += '  <!-- Nav tabs -->';
        rows += '  <ul class="nav nav-tabs" role="tablist">';
        rows += '    <li role="presentation" class="active"><a href="#profile" id="profileHead" aria-controls="profile" role="tab" data-toggle="tab">Profile <span></span></a></li>';
        rows += '    <li role="presentation"><a href="#directDeposit" id="directDepositHead" aria-controls="directDeposit" role="tab" data-toggle="tab">Direct Deposit <span></span></a></li>';
        rows += '    <li role="presentation"><a href="#driversLicense" id="driversLicenseHead" aria-controls="driversLicense" role="tab" data-toggle="tab">Drivers License <span></span></a></li>';
        rows += '    <li role="presentation"><a href="#occupationalLicense" id="occupationalLicenseHead" aria-controls="occupationalLicense" role="tab" data-toggle="tab">Occupational License <span></span></a></li>';
        rows += '    <li role="presentation"><a href="#dependent" id="dependentHead" aria-controls="dependent" role="tab" data-toggle="tab">Dependents <span></span></a></li>';
        rows += '    <li role="presentation"><a href="#emergencyContact" id="emergencyContactHead" aria-controls="emergencyContact" role="tab" data-toggle="tab">Emergency Contacts <span></span></a></li>';
        rows += '    <li role="presentation"><a href="#w4" id="w4Head" aria-controls="w4" role="tab" data-toggle="tab">W4 <span></span></a></li>';

        rows += '  </ul>';
        rows += '  <!-- Tab panes -->';
        rows += '  <div class="tab-content">';
        rows += '    <div role="tabpanel" class="tab-pane active" id="profile"></div>';
        rows += '    <div role="tabpanel" class="tab-pane" id="directDeposit"></div>';
        rows += '    <div role="tabpanel" class="tab-pane" id="driversLicense"></div>';
        rows += '    <div role="tabpanel" class="tab-pane" id="occupationalLicense"></div>';
        rows += '    <div role="tabpanel" class="tab-pane" id="dependent"></div>';
        rows += '    <div role="tabpanel" class="tab-pane" id="emergencyContact"></div>';
        rows += '    <div role="tabpanel" class="tab-pane" id="w4"></div>';

        rows += '  </div>';
        rows += '</div>';
        //
        $('#jsEmployeeProfileHistoryBody').html(rows);
        //
        $.get(baseURI + 'get_employee_profile_history/' + employeeId)
            .success(loadData)
            .fail(handleError);
    }


    function loadData(response) {
        //
        let obj = {
            profile: '',
            directDeposit: '',
            driversLicense: '',
            occupationalLicense: '',
            dependent: '',
            emergencyContact: '',
            w4:''
        };
        //
        //
        response.history.map(function (record) {
            //
            let rows = '';
            //
            let data = JSON.parse(record.profile_data);

            //
            // Create head
            rows += '<br />';
            rows += '<table class="table table-bordered">';
            rows += '   <thead>';
            rows += '       <tr class="bg-primary">';
            rows += '           <th colspan="3">';
            rows += '<span class="pull-right"> ' + (moment(record.created_at).format('DD MMMM Y, dddd HH:mm:ss')) + '</span>';
            if (record.employer_sid != 0) {
                rows += ' ' + (record.full_name) + '';
            } else {
                rows += 'Self';
            }
            rows += '           </th>';
            rows += '       </tr>';
            rows += '   </thead>';
            rows += '   <tbody>';
            rows += '   {{innerRows}}';
            rows += '   </tbody>';
            rows += '</table>';
            rows += '<hr />';
            //
            let innerRows = '';
            //  console.log(data['union_member'])
            for (let index in data) {
                //
                let newData = data[index]['new'] || '-';
                let oldData = data[index]['old'] || '-';
                if (index == 'union_member') {
                    //   console.log(data)
                }
                //
                if (index.toLowerCase() == 'location_state') {
                    newData = response.states[newData] || '-';
                    oldData = response.states[oldData] || '-';
                }
                //
                if (
                    index.toLowerCase() == 'dob' ||
                    index.toLowerCase() == 'rehire_date' ||
                    index.toLowerCase() == 'joined_at' ||
                    index.toLowerCase() == 'birth_date' ||
                    index.toLowerCase() == 'license_issue_date' ||
                    index.toLowerCase() == 'license_expiration_date' ||
                    index.toLowerCase() == 'consent_date'
                ) {
                    newData = newData != '-' ? moment(newData, 'Y-MM-DD').format('DD MMMM Y, dddd') : newData;
                    oldData = oldData != '-' ? moment(oldData, 'Y-MM-DD').format('DD MMMM Y, dddd') : oldData;
                }
                //
                if (
                    index.toLowerCase() == 'profile_picture' ||
                    index.toLowerCase() == 'license_file' ||
                    index.toLowerCase() == 'voided_cheque' ||
                    index.toLowerCase() == 'user_signature'
                ) {
                    newData = newData != '-' ? '<img src="' + (index.toLowerCase() == "user_signature" ? "" : "https://automotohrattachments.s3.amazonaws.com/") + '' + (newData) + '" width="60" />' : newData;
                    oldData = oldData != '-' ? '<img src="' + (index.toLowerCase() == "user_signature" ? "" : "https://automotohrattachments.s3.amazonaws.com/") + '' + (oldData) + '" width="60" />' : oldData;
                }
                //
                innerRows += '   <tr>';
                if (index != 'union_member') {

                    if (index == 'PhoneNumber') {
                        innerRows += '<td><strong> PRIMARY NUMBER </strong></td>';
                    } else {
                        innerRows += '       <td><strong>' + (index.replace(/[^a-z]/gi, ' ').toUpperCase()) + '</strong></td>';
                    }
                }

               
                //
                if (index == 'action') {
                    innerRows += '       <td class="danger text-center" colspan="2">Deleted</td>';
                } else if (index == 'union_member') {
                    //  innerRows += '       <td class="bg-danger">' + (oldData == 1 ? 'Yes' :'No') + '</td>';
                    //   innerRows += '       <td class="bg-success">' + (newData == 1 ?'Yes':'No') + '</td>';
                } else if (index == 'union_name') {
                    innerRows += '       <td class="bg-danger">' + (oldData == '-' ? 'No' : 'Yes <br>' + oldData) + '</td>';
                    innerRows += '       <td class="bg-success">' + (newData == '-' ? 'No' : 'Yes <br>' + newData) + '</td>';
                } else {
                    innerRows += '       <td class="bg-danger">' + (oldData) + '</td>';
                    innerRows += '       <td class="bg-success">' + (newData) + '</td>';
                }
                innerRows += '   </tr>';
            }

            //
            if (innerRows.length > 0) {
                obj[record.history_type] += rows.replace(/{{innerRows}}/i, innerRows);
            }
        });
        //
        for (let index in obj) {
            // Add count
            if (obj[index].length === 0) {

                $('#' + (index) + 'Head').parent().remove();
            }
            //
            $('#' + index).html(obj[index]);
        }
        //
        ml(false, 'jsEmployeeProfileHistoryLoader');
    }

    /**
     * Handles error
     */
    function handleError() {
        //
        let rows = '';
        //
        rows += '<div class="alert alert-danger text-center">';
        rows += '   <p>Oops! Something went wrong</p>';
        rows += '</div>';
        //
        $('#jsEmployeeProfileHistoryBody').html(rows);
        //
        ml(false, 'jsEmployeeProfileHistoryLoader');
    }
});