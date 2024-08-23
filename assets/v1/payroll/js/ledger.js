/**
 * Import payroll ledger
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function importLedger() {
    //
    var
        file = [],
        employeeId = ['employeeid', 'employeesid', 'employeeId'],
        employeeNumber = ['employeenumber', 'employeeno'],
        employeeSSN = ['employee_ssn', 'socialsecurity', 'socialsecuritynumber', 'socialsecurity#', 'ssn', 'ss', 'ss#'],
        employeeEmail = ['employeeemail', 'email', 'emailaddress', 'personalemail', 'e-mail'],
        employeePhone = ['employeephonenumber', 'phonenumber', 'contactnumber', 'contact', 'employeetelephonenumber', 'telephonenumber', 'primarynumber'],
        debit = ['debit', 'debitamount'],
        credit = ['credit', 'creditamount'],
        startPeriod = ['start', 'startdate', 'startduration', 'startperiod'],
        endPeriod = ['end', 'enddate', 'endduration', 'endperiod'],
        transactionDate = ['transaction', 'transactiondate', 'transactiontime', 'paydate'],
        firstName = ['employeefirstname', 'firstname', 'fname'],
        lastName = ['employeelastname', 'lastname', 'fname'],
        department = ['departmentname', 'department', 'homedepartmentdescription'],
        jobTitles = ['jobtitle', 'job', 'position'],
        grossPay = ['grosspay', 'gross', 'grosssalary'],
        netPay = ['netpay', 'net', 'netsalary'],
        taxes = ['taxes'],
        description = ['description, note'],
        accountname = ['accountname'],
        accountNumber = ['accountnumber'],
        referenceNumber = ['referencenumber'],
        generalEntrynumber = ['generalentrynumber'];


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


        if (!format_index.includes("firstname") && !format_index.includes("first-name") && !format_index.includes("fname") && !format_index.includes("first_name")) {
            alertify.alert('Not a valid format');
            return false;
        }

        if (!format_index.includes("debitamount") && !format_index.includes("debit")) {
            alertify.alert('Not a valid format');
            return false;
        }
        if (!format_index.includes("creditamount") && !format_index.includes("credit")) {
            alertify.alert('Not a valid format');
            return false;
        }


        // Get header
        var indexes = fileData[0].split(',');

        // Reset index
        indexes = indexes.map(function (v, i) {
            // console.log(v);
            var index = in_array(v.toLowerCase().replace(/[^a-z]/g, '').trim());
            //  console.log(index);
            return index === -1 ? '#' + v : index;
           // return index === -1 ? 'extra' : index;
        });

        //  return;

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

            record['extra'] = [];
            var objextra = {};
            if (len > 2) {
                //
                for (i; i < len; i++) {
                    // console.log(indexes[i]);
                    let extchk = indexes[i];
                    extchk = extchk.replace("#", "");
                    if (indexes[i] === '#' + extchk) {
                        // record['extra'].push(tmp[i]);
                        objextra[extchk] = tmp[i];
                    } else {
                        record[indexes[i]] = tmp[i].trim();
                    }
                }

                record['extra'].push(objextra);

                console.log(record);

                //
                var employeeIdFlag = true;
                var employeeNumberFlag = true;
                var employeeSSNFlag = true;
                var employeeEmailFlag = true;
                var employeePhoneFlag = true;
                var employeeFirstNameFlag = true;
                var employeeLastNameFlag = true;
                var dateFlag = true;
                var amountFlag = true;
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
                if (!record.first_name) {
                    employeeFirstNameFlag = false;
                }
                //
                if (!record.last_name) {
                    employeeLastNameFlag = false;
                }
                //
                if (!isValidDate(record.start_period)) {
                    dateFlag = false;
                    errorArray.push("The start period date is not valid for row " + (rowNo + 1) + ".");
                }
                //
                if (!isValidDate(record.end_period)) {
                    dateFlag = false;
                    errorArray.push("The end period date is not valid for row " + (rowNo + 1) + ".");
                }
                //
                if (!isValidDate(record.transaction_date)) {
                    dateFlag = false;
                    errorArray.push("The transaction date is not valid for row " + (rowNo + 1) + ".");
                }
                //
                if (!isValidNumber(record.debit)) {
                    amountFlag = false;
                    errorArray.push("The debit amount is not valid for row " + (rowNo + 1) + ".");
                }
                if (!isValidNumber(record.credit)) {
                    amountFlag = false;
                    errorArray.push("The credit amount is not valid for row " + (rowNo + 1) + ".");
                }
                //
                if (record.debit == 0 && record.credit == 0) {
                    amountFlag = false;
                    errorArray.push("The debit or credit amount must be greater then zero for row " + (rowNo + 1) + ".");
                }
                //
                if (
                    employeeIdFlag ||
                    employeeNumberFlag ||
                    employeeSSNFlag ||
                    employeeEmailFlag ||
                    employeePhoneFlag
                ) {
                    if (dateFlag && amountFlag) {
                        records.push(record);
                    }
                } else if (
                    !employeeFirstNameFlag &&
                    !employeeLastNameFlag
                ) {
                    if (dateFlag && amountFlag) {
                        records.push(record);
                    }
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
                        "Uploaded file doesn\'t contain any valid payroll ledger info."
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
        len = debit.length;
        array = debit;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'debit';
        // Reset start and length
        i = 0;
        len = credit.length;
        array = credit;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'credit';
        // Reset start and length
        i = 0;
        len = startPeriod.length;
        array = startPeriod;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'start_period';
        // Reset start and length
        i = 0;
        len = endPeriod.length;
        array = endPeriod;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'end_period';
        // Reset start and length
        i = 0;
        len = transactionDate.length;
        array = transactionDate;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'transaction_date';
        // Reset start and length
        i = 0;
        len = firstName.length;
        array = firstName;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'first_name';
        // Reset start and length
        i = 0;
        len = lastName.length;
        array = lastName;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'last_name';
        // Reset start and length
        i = 0;
        len = department.length;
        array = department;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'department';
        // Reset start and length
        i = 0;
        len = jobTitles.length;
        array = jobTitles;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'job_title';
        // Reset start and length
        i = 0;
        len = grossPay.length;
        array = grossPay;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'gross_pay';
        // Reset start and length
        i = 0;
        len = netPay.length;
        array = netPay;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'net_pay';
        // Reset start and length
        i = 0;
        len = taxes.length;
        array = taxes;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'taxes';
        // Reset start and length
        i = 0;
        len = description.length;
        array = description;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'description';

        i = 0;
        len = accountname.length;
        array = accountname;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'account_name';

        i = 0;
        len = accountNumber.length;
        array = accountNumber;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'account_number';
        i = 0;
        len = referenceNumber.length;
        array = referenceNumber;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'reference_number';
        i = 0;
        len = generalEntrynumber.length;
        array = generalEntrynumber;
        for (i; i < len; i++)
            if (index == array[i].trim()) return 'general_entry_number';

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
        uploadChunk();
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
            alertify.alert('SUCCESS!', 'You have successfully imported  payroll ledger.<br>Inserted records: <b>' + (insertedCount) + '</b><br>Duplicated records: <b>' + (existedCount) + '</b> <br> Failed records: <b>' + (failedCount) + '</b>');
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
        xhr = $.post(baseURI + 'import-ledger/handler', {
            action: 'add_payrolls',
            payrolls: chunk
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
                alertify.alert('SUCCESS!', 'You have successfully imported  payroll ledger.<br>Inserted records: <b>' + (insertedCount) + '</b><br>Duplicated records: <b>' + (existedCount) + '</b> <br> Failed records: <b>' + (failedCount) + '</b>', function () {
                    window.location = baseURI + 'payrolls/ledger/import';
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

});
