$(function () {
    //
    let employeeId,
        employeeName;
    //
    $('#jsProfileHistory').click(getEmployeeProfileHistory);

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
        $.get(baseURI + 'get_employee_profile_history/' + employeeId)
            .success(loadData)
            .fail(handleError);
    } 


    function loadData(response) {
        //
        let rows = '';
        //
        response.history.map(function (record) {
            //
            let data = JSON.parse(record.profile_data);
            //
            // Create head
            rows += '<table class="table table-bordered">';
            rows += '   <thead>';
            rows += '       <tr class="bg-primary">';
            rows += '           <th colspan="3">';
            rows += '<span class="pull-right"> ' + (moment(record.created_at).format('DD MMMM Y, dddd HH:mm:ss')) + '</span>';
            if (record.employer_sid != 0) {
                rows += ' ' + (record.full_name) + '';
            } else {
                row += 'Self';
            }
            rows += '           </th>';
            rows += '       </tr>';
            rows += '   </thead>';
            rows += '   <tbody>';
            for (let index in data) {
                //
                let newData = data[index]['new'] || '-';
                let oldData = data[index]['old'] || '-';
                //
                if (index.toLowerCase() == 'location_state') {
                    newData = response.states[newData];
                    oldData = response.states[oldData];
                }
                //
                if (index.toLowerCase() == 'dob' || index.toLowerCase() == 'rehire_date' || index.toLowerCase() == 'joined_at') {
                    newData = newData != '-' ? moment(newData, 'Y-MM-DD').format('DD MMMM Y, dddd') : newData;
                    oldData = oldData != '-' ? moment(oldData, 'Y-MM-DD').format('DD MMMM Y, dddd') : oldData;
                }
                //
                if (index.toLowerCase() == 'profile_picture') {
                    newData = newData != '-' ? '<img src="https://automotohrattachments.s3.amazonaws.com/' + (newData) + '" width="60" />' : newData;
                    oldData = oldData != '-' ? '<img src="https://automotohrattachments.s3.amazonaws.com/' + (oldData) + '" width="60" />' : oldData;
                }
                //
                rows += '   <tr>';
                rows += '       <td><strong>' + (index.replace(/[^a-z]/gi, ' ').toUpperCase()) + '</strong></td>';
                rows += '       <td class="bg-danger">' + (oldData) + '</td>';
                rows += '       <td class="bg-success">' + (newData) + '</td>';
                rows += '   </tr>';
            }
            rows += '   </tbody>';
            rows += '</table>';
            rows += '<hr />';
        });
        //
        $('#jsEmployeeProfileHistoryBody').html(rows);
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