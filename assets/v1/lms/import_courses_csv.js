/**
 * Import courses
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function importCoursesCSV() {
    //
    var
        file = [],
        employeeId = ['employeeid', 'employeesid', 'employeeId'],
        employeeNumber = ['employeenumber', 'employeeno'],
        employeeSSN = ['employee_ssn', 'socialsecurity', 'socialsecuritynumber', 'socialsecurity#', 'ssn', 'ss', 'ss#'],
        employeeEmail = ['employeeemail', 'email', 'emailaddress', 'personalemail', 'e-mail'],
        employeePhone = ['employeephonenumber', 'phonenumber', 'contactnumber', 'contact', 'employeetelephonenumber', 'telephonenumber', 'primarynumber'],
        title = ['coursetitle', 'title'],
        type = ['coursetype', 'type'],
        progress = ['lessonstatus', 'progress'],
        status = ['coursestatus', 'status'],
        count = ['coursetaken', 'attemptcount', 'coursetakencount'],
        startPeriod = ['start', 'startdate', 'startduration', 'startperiod', 'coursestartdate', 'coursestartperiod'],
        endPeriod = ['end', 'enddate', 'endduration', 'endperiod', 'courseenddate', 'courseendperiod'];

    loader('hide');
    // 
    // $('#userfile').change(fileChanged);
    //
    $('#js-import-form').submit(formHandler);
    //
    function readFile(f) {
        //
        $('.js-submit-btn').prop('disabled', true).addClass('disabled');
        //
        if (Object.keys(f).length === 0) {
            alertify.alert('WARNING!', 'Please, select a file.', () => { });
            return;
        }
        //
        if (f.hasError === true) {
            alertify.alert('WARNING!', 'Invalid file is uploaded.', () => { });
            return;
        }
        //
        $('.js-submit-btn').removeAttr('disabled').removeClass('disabled');
        //
        var fileReader = new FileReader();
        fileReader.onload = fileLoaded;
        fileReader.readAsText(f);
    }

    //
    $('#userfile').mFileUploader({
        fileLimit: -1,
        allowedTypes: ['csv'],
        onSuccess: readFile,
        onError: () => {
            $('.js-submit-btn').prop('disabled', true).addClass('disabled');
        },
        onClear: () => {
            $('.js-submit-btn').prop('disabled', true).addClass('disabled');
        }
    });

    //
    function fileLoaded(e) {
        file = e;
    }

    //
    function formHandler(e) {
        e.preventDefault();
        var fileData = file.target.result.split(/\r\n|\n/g);
        //Check if is it right format

        var format_index = fileData[0].toLowerCase().replace(/[^a-z]/g, '').trim();
        //
        //console.log(format_index);

        if (!format_index.includes("coursetitle") && !format_index.includes("title")) {
            alertify.alert('Not a valid format Course Title');
            return false;
        }
        if (!format_index.includes("lessonstatus") && !format_index.includes("progress")) {
            alertify.alert('Not a valid format lesson Status');
            return false;
        }

        if (!format_index.includes("coursestatus") && !format_index.includes("status")) {
            alertify.alert('Not a valid format Course Status');
            return false;
        }

        if (!format_index.includes("coursetaken") && !format_index.includes("attemptcount")) {
            alertify.alert('Not a valid format  Course Taken Count');
            return false;
        }

        if (!format_index.includes("startdate") && !format_index.includes("startperiod") && !format_index.includes("startduration")) {
            alertify.alert('Not a valid format  Course Start Date');
            return false;
        }

        if (!format_index.includes("enddate") && !format_index.includes("endperiod") && !format_index.includes("endduration")) {
            alertify.alert('Not a valid format  Course End Date');
            return false;
        }

        // Get header
        var indexes = fileData[0].split(',');
        // Reset index
        indexes = indexes.map(function (v, i) {
            var index = in_array(v.toLowerCase().replace(/[^a-z]/g, '').trim());
            return index === -1 ? 'extra' : index;
        });
        // Remove head
        fileData.splice(0, 1);
        //
        var errorRows = "";
        //
        fileData.map((row, index) => {
            var er = row.split(',');
            //
            if (er.length != indexes.length && er.length !== 1) {
                errorRows += '<p>';
                errorRows += '  <strong>Row #: </strong>' + (index + 2) + '<br />';
                errorRows += '  <strong>Name: </strong>' + (er[0] + er[1]) + '';
                errorRows += '</p>';
            }
        });
        //
        if (errorRows.length) {
            //
            return alertify.alert(
                "Error!",
                "<p>Please make sure there are no <strong>','</strong> in values.</p><br />" +
                "<p><strong>For instance </strong>422 Street, 3rd floor -> 422 Street 3rd floor</p><br />" +
                errorRows,
                function () { }
            );
        }
        //
        var records = [];
        //
        const errorArray = [];
        //
        fileData.map(function (v, rowNo) {
            var tmp = v.split(','),
                record = new Object(),
                i = 0,
                len = tmp.length;

            if (len > 2) {
                //
                for (i; i < len; i++) {
                    record[indexes[i]] = tmp[i].trim();
                }
                //
                var employeeIdFlag = true;
                var employeeNumberFlag = true;
                var employeeSSNFlag = true;
                var employeeEmailFlag = true;
                var employeePhoneFlag = true;
                var courseTitleFlag = true;
                var courseTypeFlag = true;
                var dateFlag = true;
                var countFlag = true;
                //
                if (!record.employee_id) {
                    employeeIdFlag = false;
                }
                //
                if (!record.employee_number) {
                    employeeNumberFlag = false;
                }
                //
                if (!record.employee_ssn) {
                    employeeSSNFlag = false;
                }
                //
                if (!record.employee_email) {
                    employeeEmailFlag = false;
                }
                //
                if (!record.employee_phone) {
                    employeePhoneFlag = false;
                }
                //
                if (!record.title) {
                    courseTitleFlag = false;
                }
                //
                if (!record.type) {
                    courseTypeFlag = false;
                }
                //
                //  console.log(record)
                //
                if (!isValidDate(record.start_date)) {
                    dateFlag = false;
                    errorArray.push("The course start date is not valid for row " + (rowNo + 1) + ".");
                }
                //
                if (!isValidDate(record.end_date)) {
                    dateFlag = false;
                    errorArray.push("The course end date is not valid for row " + (rowNo + 1) + ".");
                }
                //
                if (!isValidNumber(record.count)) {
                    countFlag = false;
                    errorArray.push("The course attempted count is not valid for row " + (rowNo + 1) + ".");
                }
                //
                if (
                    employeeIdFlag ||
                    employeeNumberFlag ||
                    employeeSSNFlag ||
                    employeeEmailFlag ||
                    employeePhoneFlag
                ) {
                    if (dateFlag && countFlag) {
                        records.push(record);
                    }
                } else if (
                    !courseTitleFlag
                ) {
                    errorArray.push("The course title is missing for row " + (rowNo + 1) + ".");
                } else if (
                    !courseTypeFlag
                ) {
                    errorArray.push("The course type is missing for row " + (rowNo + 1) + ".");
                } else {
                    errorArray.push("The employee identity is missing for row " + (rowNo + 1) + ".");
                }
            }
        });
        //
        if (errorArray.length) {
            if (records.length == 0) {
                alertify.alert(
                    getErrorsStringFromArray(
                        errorArray,
                        "Uploaded file doesn\'t contain any valid courses info."
                    ),
                    function () {
                        return;
                    }
                ).set(
                    'title', 'Warning!'
                );
                return;
            } else {
                alertify.confirm(
                    getErrorsStringFromArray(
                        errorArray,
                        "Are you sure you want to continue, or would you prefer to fix the issue first?"
                    ),
                    () => {
                        uploadRecords(records);
                    }
                ).set('labels', {
                    ok: 'Continue',
                    cancel: 'Cancel'
                }).set(
                    'title', 'Notice!'
                );
            }
        } else {
            uploadRecords(records);
        }
        //   
    }

    //
    function in_array(index) {
        var i, len, array;
        // Reset start and length
        i = 0;
        len = employeeId.length;
        array = employeeId;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'employee_id';
        // Reset start and length
        i = 0;
        len = employeeNumber.length;
        array = employeeNumber;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'employee_number';
        // Reset start and length
        i = 0;
        len = employeeSSN.length;
        array = employeeSSN;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'employee_ssn';
        // Reset start and length
        i = 0;
        len = employeeEmail.length;
        array = employeeEmail;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'employee_email';
        // Reset start and length
        i = 0;
        len = employeePhone.length;
        array = employeePhone;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'employee_phone';
        // Reset start and length
        i = 0;
        len = title.length;
        array = title;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'title';
        // Reset start and length
        i = 0;
        len = type.length;
        array = type;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'type';
        // Reset start and length
        i = 0;
        len = progress.length;
        array = progress;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'progress';
        // Reset start and length
        i = 0;
        len = status.length;
        array = status;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'status';
        // Reset start and length
        i = 0;
        len = count.length;
        array = count;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'count';
        // Reset start and length
        i = 0;
        len = startPeriod.length;
        array = startPeriod;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'start_date';
        // Reset start and length
        i = 0;
        len = endPeriod.length;
        array = endPeriod;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'end_date';

        return -1;
    }

    //
    var chunkOBJ = {
        current: 0,
        chunkSize: 8,
        // chunkSize: 50,
        loaded: 0,
        records: [],
        totalChunks: 0,
        recordsLength: 0
    };
    //
    var failedCount = 0,
        insertedCount = 0,
        existedCount = 0;

    //
    function uploadRecords(records) {
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

        // console.log(records);

        showCourseModel(records);

        //  uploadChunk();
    }

    //
    function uploadChunk() {
        if (chunkOBJ.records[chunkOBJ.current] === undefined) {
            // TODO
            console.log('All chunks are being uploaded..');
            return;
        }
        startAddProcess(chunkOBJ.records[chunkOBJ.current]);
    }

    var xhr = null;
    //
    function startAddProcess(chunk) {
        //
        if (xhr !== null) return;
        //
        if (chunkOBJ.records[chunkOBJ.current] === undefined) {
            loader('hide');
            alertify.alert('SUCCESS!', 'You have successfully imported  courses.<br>Inserted records: <b>' + (insertedCount) + '</b><br>Duplicated records: <b>' + (existedCount) + '</b> <br> Failed records: <b>' + (failedCount) + '</b>');
            failedCount = existedCount = insertedCount = chunkOBJ.loaded = chunkOBJ.current = chunkOBJ.totalChunks = chunkOBJ.recordsLength = 0;
            return;
        }
        //
        chunkOBJ.loaded += chunk.length;
        var
            messageRow = "Please, be patient as we import payrolls.<br />";
        messageRow += "It may take a few minutes<br />";
        messageRow += 'Importing ' + (chunkOBJ.loaded) + ' of ' + (chunkOBJ.recordsLength) + ' payrolls.';
        //
        loader('show', messageRow);
        xhr = $.post(baseURI + 'lms/courses/import_course_csv_handler', {
            action: 'add_courses',
            courses: chunk
        }, function (resp) {
            xhr = null;
            if (resp.Status === false) {
                alertify.alert('ERROR!', 'Something went wrong');
                loader('hide');
                return;
            }
            failedCount += parseInt(resp.Failed);
            existedCount += parseInt(resp.Existed);
            insertedCount += parseInt(resp.Inserted);
            // Update current and hit uploadChunk function
            chunkOBJ.current++;
            if (chunkOBJ.current >= chunkOBJ.records.length) {
                loader('hide');
                alertify.alert('SUCCESS!', 'You have successfully imported  courses.<br>Inserted records: <b>' + (insertedCount) + '</b><br>Duplicated records: <b>' + (existedCount) + '</b> <br> Failed records: <b>' + (failedCount) + '</b>', function () {
                    window.location = baseURI + 'lms/courses/import_course_csv';
                });
                failedCount = existedCount = insertedCount = chunkOBJ.loaded = chunkOBJ.current = chunkOBJ.totalChunks = chunkOBJ.recordsLength = 0;
                return;
            }
            uploadChunk();
        });
    }

    //
    function loader(show_it, msg) {
        show = show_it === undefined || show_it === true || show_it === 'show' ? 'show' : show_it;
        msg = msg === undefined ? '' : msg;
        if (show_it == 'show') {
            $('.js-loader-text').html(msg);
            $('.js-loader').show();
        } else {
            $('.js-loader-text').html('');
            $('.js-loader').fadeOut(150);
        }
    }

    function isValidDate(dateString) {
        // console.log(dateString)
        //
        var regEx = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
        return dateString.match(regEx) != null;
    }

    function isValidNumber(value) {
        var regex = new RegExp(/^\+?[0-9(),.-]+$/);
        //
        if (value.match(regex)) {
            return true;
        }
        //
        return false;
    };

    function getErrorsStringFromArray(errorArray, errorMessage) {
        return (
            "<strong><p>" +
            (errorMessage
                ? errorMessage
                : "Please, resolve the following errors") +
            "</p></strong><br >" +
            errorArray.join("<br />")
        );
    }


    //
    function showCourseModel(records) {
        // event.preventDefault();
        //
        Modal({
            Id: "jsCheckCourseTitleModal",
            Loader: "jsCheckCourseTitleLoader",
            Title: "Employees Course Titles Selection",
            Body: '<div id="jsCheckCourseTitleModalBody"></div>'
        }, function () {

            // console.log(records.companyCourses);

            let companyCoursesList = {};
            let options = '';
            let trs = '';

            $.get(baseURI + 'lms/courses/company_courses_list', function (resp) {

                companyCoursesList = resp.companyCourses;
                companyCoursesList.map(function (v, i) {
                    options += '<option value="' + v.sid + '" >' + v.course_title + '</option>';
                });

                indexes = records.map(function (v, i) {
                    trs += '<tr class="jsCourseRow" data-recordid="'+i+'" data-coursetitle="'+v.title+'" >';
                    trs += '<td>' + v.title + '</td>';
                    trs += '<td><select name="companycourses[]" class="jscompanycourses invoice-fields"><option value="">Please Select</option>' + options + '</select></td>';
                    trs += '</tr>';
                });

                let importbtn = '<br><div class="row"><div class="col-sm-9"></div><div class="col-sm-3 text-right"><button class="btn btn-success jsTransferPolicyBTN">Import</button></div></div>';

                let tblData = '<br><br><div class="table-responsive">';
                tblData += '<table class="table table-striped ">'
                tblData += '<thead>';
                tblData += '<tr>';
                tblData += '<th>Import Data Course Title</th>';
                tblData += '<th>Company Course Title</th>';
                tblData += '</tr>';
                tblData += '</thead>';
                tblData += '<tbody>';
                tblData += trs;
                tblData += '</tbody>';
                tblData += '</table>';
                tblData += '</div>';
                tblData += importbtn;

                $("#jsCheckCourseTitleModalBody").html(tblData);

                ml(
                    false,
                    "jsCheckCourseTitleLoader"
                );

            });

        });
    }









});
