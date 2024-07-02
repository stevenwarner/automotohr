$(function importLedger() {
    function check_file(val) {
        $('.js-submit-btn').addClass('disabled').prop('disabled', true);
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();

            if (val == 'userfile') {
                if (ext != "csv") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid File format.");
                    $('#name_' + val).html('<p class="red">Only ( .csv ) allowed!</p>');
                    return false;
                } else {
                    $('.js-submit-btn').removeClass('disabled').prop('disabled', false);
                    return true;
                }
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    //
    $(function importCSV() {
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
            var fileData = file.target.result.split(/\n/g);
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
            var records = [];
            //
            fileData.map(function (v) {
                var tmp = v.split(','),
                    record = new Object(),
                    i = 0,
                    len = tmp.length;
                for (i; i < len; i++) record[indexes[i]] = tmp[i];
                records.push(record);
            });
            //
            uploadRecords(records);
        }

        //
        function in_array(index) {
            var i, len, array;
            // Reset start and length
            i = 0;
            len = emailAddressTitles.length;
            array = emailAddressTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'email_address';
            // Reset start and length
            i = 0;
            len = policyTitles.length;
            array = policyTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'policy';
            // Reset start and length
            i = 0;
            len = partialTitles.length;
            array = partialTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'partial_leave';
            // Reset start and length
            i = 0;
            len = partialNoteTitles.length;
            array = partialNoteTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'partial_leave_note';
            // Reset start and length
            i = 0;
            len = requestedDateTitles.length;
            array = requestedDateTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'requested_date';
            // Reset start and length
            i = 0;
            len = requestedFromDateTitles.length;
            array = requestedFromDateTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'requested_from_date';
            // Reset start and length
            i = 0;
            len = requestedToDateTitles.length;
            array = requestedToDateTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'requested_to_date';
            // Reset start and length
            i = 0;
            len = requestedHoursTiles.length;
            array = requestedHoursTiles;
            for (i; i < len; i++)
                if (index == array[i]) return 'requested_hours';
            // Reset start and length
            i = 0;
            len = requestStatusTitles.length;
            array = requestStatusTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'status';
            // Reset start and length
            i = 0;
            len = reasonTitles.length;
            array = reasonTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'reason';
            // Reset start and length
            i = 0;
            len = commentTitles.length;
            array = commentTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'comment';
            return -1;
        }

        //
        var chunkOBJ = {
            current: 0,
            // chunkSize: 2,
            chunkSize: 50,
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
                alertify.alert('SUCCESS!', 'You have successfully imported <b>' + (insertedCount) + '</b> new employees, <b>' + (existedCount) + '</b> employees already exists and <b>' + (failedCount) + '</b> employees failed to add.');
                failedCount = existedCount = insertedCount = chunkOBJ.loaded = chunkOBJ.current = chunkOBJ.totalChunks = chunkOBJ.recordsLength = 0;
                return;
            }
            //
            chunkOBJ.loaded += chunk.length;
            var
                messageRow = "Please, be patient as we import time offs.<br />";
            messageRow += "It may take a few minutes<br />";
            messageRow += 'Importing ' + (chunkOBJ.loaded) + ' of ' + (chunkOBJ.recordsLength) + ' employees.';
            //
            loader('show', messageRow);
            xhr = $.post("<?= base_url('timeoff/handler'); ?>", {
                action: 'import',
                companySid: 15708,
                employeeSid: 15717,
                timeoffs: chunk
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
                    alertify.alert('SUCCESS!', 'You have successfully imported <b>' + (insertedCount) + '</b> new employees, <b>' + (existedCount) + '</b> employees already exists and <b>' + (failedCount) + '</b> employees failed to add.', function () {
                        window.location.reload();
                    });
                    failedCount = existedCount = insertedCount = chunkOBJ.loaded = chunkOBJ.current = chunkOBJ.totalChunks = chunkOBJ.recordsLength = 0;
                    return;
                }
                // To stop the server break
                setTimeout(function () {
                    uploadChunk();
                }, 1000);
            });
        }

    })
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
});