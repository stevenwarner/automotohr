$(function importHistoricalTimeOffs() {
    /**
     * Loader box
     * 
     * @type string
     */
    const loader = '#jsImportHistoricLoader';

    /**
     * Loader text box
     * 
     * @type string
     */
    const loaderText = '.jsLoaderText';

    /**
     * The uploaded file
     * 
     * @type string
     */
    let fileData = {};

    /**
     * The preview
     * 
     * @type array
     */
    let tableData = [];

    /**
     * Set records
     * 
     * @type array
     */
    let records = [];

    /**
     * Set error check
     * 
     * @type array
     */
    let hasFalse = false;

    /**
     * Set ids
     * 
     * @type object
     */
    let fileDataVerificationObj = {};

    /**
     * Holds the xhr object
     */
    var xhr = null;

    /**
     * Holds the missing data
     */
    let missingEmployeesObj = {},
        missingPoliciesObj = {};

    /**
     * Holds the chunk uploader
     */
    var chunkOBJ = {
        current: 0,
        chunkSize: 100,
        loaded: 0,
        records: [],
        totalChunks: 0,
        recordsLength: 0
    };

    /**
     * Set default values
     * 
     * @type array
     */
    let
        firstName = ['employeefirstname', 'firstname'],
        LastName = ['employeelastname', 'lastname'],
        approvedBy = ['approvedby', 'approvername'],
        policyName = ['policy', 'policyName', 'leavetype'],
        requestedHours = ['requestedhours', 'requesthours'],
        leaveFrom = ['startdate', 'leavefrom'],
        leaveTo = ['enddate', 'leaveto'],
        status = ['status'],
        submittedDate = ['createddate', 'actiondate', 'submitteddate'],
        statusDate = ['approveddeclineddate', 'statusdate', 'approvedate', 'declinedate'],
        employeeComment = ['employeecomment', 'employeecomments', 'comment'],
        statusComment = ['managercomment', 'managercomments'];

    /**
     * Initialize the file uploading
     * @method readFile
     */
    $('#jsFileInput').mFileUploader({
        fileLimit: -1,
        allowedTypes: ['csv'],
        onSuccess: readFile,
        onError: function () {
            // Clear the file
            fileData = '';
            //
            $('.js-submit-btn').prop('disabled', true).addClass('disabled');
        },
        onClear: function () {
            // Clear the file
            fileData = '';
            //
            $('.js-submit-btn').prop('disabled', true).addClass('disabled');
        }
    });

    /**
     * Handles import action
     * @method generateTableForMissingData
     */
    $('#jsImportBtn').click(function (e) {
        //
        e.preventDefault();
        //
        if (hasFalse === 1) {
            return generateTableForMissingData();
        }
        //
        return checkIfEmployeeExists();
    });

    /**
     * Lets start importing
     */
    $(document).on('click', '#jsImportForce', function (event) {
        //
        event.preventDefault();
        //
        return alertify.confirm(
            'This action will skip rows whose record doesn\'t exists. <br> Do you want to continue?',
            startImportProcess,
            cb
        );
    });

    /**
     * Reads the file
     * @param {*} f 
     */
    function readFile(f) {
        //
        fileData = {};
        records = [];
        //
        $('.js-submit-btn').prop('disabled', true).addClass('disabled');
        //
        if (Object.keys(f).length === 0) {
            return alertify.alert('WARNING!', 'Please, select a file.', cb);
        }
        //
        if (f.hasError === true) {
            return alertify.alert('WARNING!', 'Invalid file is uploaded.', cb);
        }
        //
        $('.js-submit-btn').removeAttr('disabled').removeClass('disabled');
        //
        let fileReader = new FileReader();
        //
        fileReader.onload = function (uploadedFileReference) {
            // Saves the file
            fileData = uploadedFileReference.target.result;
            //
            processFile();
        };
        //
        fileReader.readAsText(f);
    }

    /**
     * Process the file
     */
    function processFile() {
        // Check for empty file
        if (!fileData) {
            return alertify.alert(
                'Warning!',
                'File is empty.',
                cb
            );
        }
        //
        hasFalse = false;
        //
        tableData = [];
        //
        let parsedData = fileData.split(/\n/g);
        // Get header
        let indexes = parsedData[0].split(',');
        // Reset index
        indexes = indexes.map(function (v) {
            // Check indexes
            let index = checkIndex(v.toLowerCase().replace(/[^a-z]/g, '').trim());
            return index === -1 ? 'extra' : index;
        });
        // Remove columns
        parsedData.splice(0, 1);
        //
        let columnLength = indexes.length;
        //
        parsedData.map(function (v, rowId) {
            var tmp = v.split(','),
                record = new Object(),
                i = 0,
                len = tmp.length;

            //
            if (!v) {
                return false;
            }
            //
            for (i; i < len; i++) {
                record[indexes[i]] = tmp[i];
            }
            //
            if (tmp.length != columnLength) {
                hasFalse = 1;
            }
            //
            tableData.push({
                row_id: rowId + 1,
                is_faulty: tmp.length == columnLength,
                data: record
            });
            //
            records.push(record);
        });
        //
        if (hasFalse === 1) {
            generateTableForMissingData();
        }
    }

    /**
     * Check for employee exists
     */
    function checkIfEmployeeExists() {
        //
        let employeeNameArray = {};
        let policyNameArray = {};
        //
        tableData.map(function (record) {
            //
            let fullName = record.data.employee_first_name + ' ' + record.data.employee_last_name;
            let fullNameSlug = fullName.toLowerCase().trim().replace(/[^a-z]/ig, '');
            //
            let approverFullName = record.data.approver_full_name;
            let approverFullNameSlug = approverFullName.toLowerCase().trim().replace(/[^a-z]/ig, '');
            //
            let policy = record.data.policy;
            let policySlug = policy.toLowerCase().trim().replace(/[^a-z]/ig, '');
            //timeoff_policy
            employeeNameArray[fullNameSlug] = fullName;
            employeeNameArray[approverFullNameSlug] = approverFullName;
            policyNameArray[policySlug] = policy;
        });
        //
        $(loader).show();
        $(loaderText).text('Please wait while we are process the file.');
        //
        fileDataVerificationObj.employees = employeeNameArray;
        fileDataVerificationObj.policies = policyNameArray;
        //
        verifyData(
            Object.keys(fileDataVerificationObj.employees),
            Object.keys(fileDataVerificationObj.policies)
        );
    }

    /**
     * Verify employees and policies
     * @param {*} employeeList 
     * @param {*} policyList 
     */
    function verifyData(
        employeeList,
        policyList
    ) {
        //
        $.post(baseURI + 'timeoff/import/historic/verify', {
            employees: employeeList,
            policies: policyList
        }).success(function (resp) {
            $(loader).hide();
            //
            let missingEmployees = [];
            let me = '';
            //
            for (let index in resp.employees) {
                if (resp.employees[index] === 0) {
                    me += '<tr class="bg-danger">';
                    me += ' <td>' + (fileDataVerificationObj.employees[index]) + '</td>';
                    me += '</tr>';
                    missingEmployees.push(fileDataVerificationObj.employees[index]);
                } else {
                    missingEmployeesObj[index] = resp.employees[index];
                }
            }
            //
            let missingPolicies = [];
            let me2 = '';
            //
            for (let index in resp.policies) {
                if (resp.policies[index] === 0) {
                    me2 += '<tr class="bg-danger">';
                    me2 += ' <td>' + (fileDataVerificationObj.policies[index]) + '</td>';
                    me2 += '</tr>';
                    missingPolicies.push(fileDataVerificationObj.policies[index]);
                } else {
                    missingPoliciesObj[index] = resp.policies[index];
                }
            }
            let t1 = '',
                t2 = '';
            //
            if (missingEmployees.length) {
                t1 = getTable(
                    'jsEmployeeTable',
                    'The following employees are missing.', [
                    'Employee Name'
                ]
                )
                //
                t1 = t1.replace(/{{TABLE_ROWS}}/i, me);
            }
            //
            if (missingPolicies.length) {
                t2 = getTable(
                    'jsEmployeeTable',
                    'The following policies are missing.', [
                    'Policy Name'
                ]
                )
                t2 = t2.replace(/{{TABLE_ROWS}}/i, me2);
            }

            //
            if (missingEmployees.length || missingPolicies.length) {
                Modal({
                    Id: "jsModal",
                    Loader: "jsModalLoader",
                    Title: "Missing Employees / Policies",
                    Buttons: ['<button class="btn btn-success" id="jsImportForce">Continue</button>'],
                    Body: '<div class="container-fluid"><div id="jsModalBody"></div></div>'
                }, function () {
                    //
                    $('#jsModalBody').html(t1 + t2);
                    //
                    ml(false, 'jsModalLoader');
                });
            }
        });
    }

    /**
     * Starts the time off import process
     */
    function startImportProcess() {
        //
        $('#jsModal').remove();
        //
        $(loader).show();
        $(loaderText).text('Please wait, while we import time offs. It may take several minutes.');
        //
        chunkOBJ.records = [];
        //
        chunkOBJ.recordsLength = records.length;
        //
        chunkOBJ.totalChunks = Math.ceil(chunkOBJ.recordsLength / Math.ceil(chunkOBJ.recordsLength / chunkOBJ.chunkSize));
        //
        if (chunkOBJ.totalChunks != 1)
            chunkOBJ.records = _.chunk(records, chunkOBJ.totalChunks);
        else chunkOBJ.records.push(records);
        //
        uploadChunk();
    }

    /**
     * Lets start importing
     * @returns 
     */
    function uploadChunk() {
        if (chunkOBJ.records[chunkOBJ.current] === undefined) {
            // TODO
            console.log('All chunks are being uploaded..');
            return;
        }
        startAddProcess(chunkOBJ.records[chunkOBJ.current]);
    }

    /**
     * Start the uploading process
     * @param {*} chunk
     * @returns 
     */
    function startAddProcess(chunk) {
        //
        if (xhr !== null) {
            return;
        }
        //
        if (chunkOBJ.records[chunkOBJ.current] === undefined) {
            $(loader).hide();
            alertify.alert('SUCCESS!', 'You have successfully imported time offs.', function () {
                window.location.reload();
            });
            return;
        }
        //
        chunkOBJ.loaded += chunk.length;
        var
            messageRow = "Please, be patient as we import time offs.<br />";
        messageRow += "It may take a few minutes<br />";
        messageRow += 'Importing ' + (chunkOBJ.loaded) + ' of ' + (chunkOBJ.recordsLength) + ' time-offs.';
        //
        $(loader).show();
        $(loaderText).html(messageRow);
        //
        xhr = $.post(baseURI + 'timeoff/import/historic/upload', {
            chunk: chunk,
            data: {
                employees: missingEmployeesObj,
                policies: missingPoliciesObj
            }
        }, function (resp) {
            //
            xhr = null;
            //
            if (resp.Status === false) {
                alertify.alert('ERROR!', 'Something went wrong.');
                $(loader).hide();
                return;
            }
            // Update current and hit uploadChunk function
            chunkOBJ.current++;
            if (chunkOBJ.current >= chunkOBJ.records.length) {
                $(loader).hide();
                alertify.alert('SUCCESS!', 'You have successfully imported time offs.', function () {
                    window.location.reload();
                });
                return;
            }
            // To stop the server break
            setTimeout(function () {
                uploadChunk();
            }, 1000);
        });
    }

    /**
     * Generate missing data table
     */
    function generateTableForMissingData() {
        //
        let table = getTable('jsMDTable',
            'Please fix the "," issue in order to proceed with the import.', [
            'Row #',
            'Employee Name'
        ]);
        //
        let rows = '';
        //
        tableData.map(function (v) {
            //
            if (v.is_faulty === false) {
                rows += '<tr class="bg-danger">';
                rows += '   <td>' + (v.row_id + 1) + '</td>';
                rows += '   <td>' + (v.data.employee_first_name) + ' ' + (v.data.employee_last_name) + '</td>';
                rows += '</tr>';
            }
        });
        //
        Modal({
            Id: 'jsMDModel',
            Title: 'Improper Data (fix "," issue)',
            Body: '<div class="container-fluid"><div id="jsMDModelBody"></div></div>',
            Loader: 'jsMDModelLoader'
        }, function () {
            //
            $('#jsMDModelBody').html(table.replace(/{{TABLE_ROWS}}/ig, rows));
            //
            ml(false, 'jsMDModelLoader');
        });
    }

    /**
     * Get the table data
     * 
     * @param {string} tableId
     * @param {string} tableMessage
     * @param {array} columns
     * @returns
     */
    function getTable(tableId, tableMessage, columns) {
        //
        let table = '';
        //
        table += '<p class="alert alert-danger"><strong><em>' + (tableMessage) + '</em></strong></p>';
        //
        table += '<div class="table-responsive">';
        table += '  <table class="table table-condensed table-bordered" id="' + (tableId) + '">';
        table += '      <thead>';
        table += '          <tr>';
        columns.map(function (column) {
            table += '              <th scope="col">' + (column) + '</th>';
        });
        table += '          </tr>';
        table += '      </thead>';
        table += '      <tbody>{{TABLE_ROWS}}</tbody>';
        table += '  </table>';
        table += '</div>';
        //
        return table;
    }

    /**
     * Check the index in default objects
     * @param {string} index 
     * @returns
     */
    function checkIndex(index) {
        let i, len, array;
        // Reset start and length
        i = 0;
        len = firstName.length;
        array = firstName;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'employee_first_name';
            }
        }
        // Reset start and length
        i = 0;
        len = LastName.length;
        array = LastName;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'employee_last_name';
            }
        }
        // Reset start and length
        i = 0;
        len = approvedBy.length;
        array = approvedBy;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'approver_full_name';
            }
        }
        // Reset start and length
        i = 0;
        len = policyName.length;
        array = policyName;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'policy';
            }
        }
        // Reset start and length
        i = 0;
        len = requestedHours.length;
        array = requestedHours;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'requested_hours';
            }
        }
        // Reset start and length
        i = 0;
        len = leaveFrom.length;
        array = leaveFrom;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'leave_from';
            }
        }
        // Reset start and length
        i = 0;
        len = leaveTo.length;
        array = leaveTo;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'leave_to';
            }
        }
        // Reset start and length
        i = 0;
        len = status.length;
        array = status;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'status';
            }
        }
        // Reset start and length
        i = 0;
        len = submittedDate.length;
        array = submittedDate;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'submitted_date';
            }
        }
        // Reset start and length
        i = 0;
        len = statusDate.length;
        array = statusDate;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'status_date';
            }
        }
        // Reset start and length
        i = 0;
        len = employeeComment.length;
        array = employeeComment;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'employee_comment';
            }
        }
        // Reset start and length
        i = 0;
        len = statusComment.length;
        array = statusComment;
        for (i; i < len; i++) {
            if (index == array[i]) {
                return 'status_comment';
            }
        }

        return -1;
    }

    /**
     * Empty callback
     */
    function cb() { }
});