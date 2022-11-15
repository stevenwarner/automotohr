<?php $this->load->view('timeoff/includes/header'); ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h4>The Provided CSV File must be in Following Format</h4>
                                    </div>
                                    <div class="panel-body">
                                        <pre>

<b>Employee Email Address, Approved By Email Address, Policy, Requested Hours , Leave From,Leave To, Status, Submitted Date,Approved/Declined Date ,Employee Comments,Manager Comments</b>
<br>
jhondoe@example.dev,jhondoe@example.dev, PTO,8 ,01-06-2020,01-06-2020,01-06-2020, Approved,01-06-2020,01-06-2020, Sons High school bowling match, Make sure to submit tickets


                                </pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="universal-form-style-v2">
                            <ul>
                                <form method="post" action="javascript:void(0)" id="js-import-form" enctype="multipart/form-data">
                                    <input type="hidden" value="upload_file" name="action" id="action" />
                                    <div>
                                        <label>Upload CSV File</label>
                                        <input type="file" id="userfile" style="display: none;" />
                                    </div>
                                    <br />

                                    <br>

                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="overflow-x: auto;overflow-y: auto; display: none;" id='importhistoricaldiv'>

                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
                                            <p class="alert-danger" id="emailnotexist"></p>
                                            <p class="alert-danger" id="approveremailnotexist"></p>
                                            <p class="alert-danger" id="policynotexist"></p>
                                        </div>



                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
                                            <p class="alert-success" id="msgrecordsimported"></p>
                                            <p class="alert-danger" id="msgrecordsnotimported"></p>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> <span class="alert-danger"><b>Note:</b> Records that are highlighted in red  will not import </span></div>

                                        <table id="importhistoricaltable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Employee Email Address</th>
                                                    <th>Department</th>
                                                    <th>Approved By</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Leave Type</th>
                                                    <th>Requested</th>
                                                    <th>Hours</th>
                                                    <th>Leave To</th>
                                                    <th>Status</th>
                                                    <th>Available</th>
                                                    <th>Hours</th>
                                                    <th>Approved/Declined Date</th>
                                                    <th>Employee Comments</th>
                                                    <th>Manager Comments</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>


                                    <div>
                                        <input type="submit" class="btn btn-success js-submit-btn disabled" id="importSubmit" value="Import Time Offs" disabled="true" />
                                    </div>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
    </div>
</div>


<script src="<?= base_url('assets/lodash/loadash.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/alertifyjs/css/alertify.min.css'); ?>">
<script src="<?= base_url('assets/alertifyjs/alertify.min.js'); ?>"></script>

<link rel="stylesheet" href="<?= base_url('assets/mFileUploader/index.css'); ?>" />
<script src="<?= base_url('assets/mFileUploader/index.js'); ?>"></script>

<script>
    let approversData = [];


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
        var
            file = [],
            emailAddressTitles = <?= json_encode(array('email', 'emailaddress', 'employeeemailaddress')); ?>,
            approvedByemailAddressTitles = <?= json_encode(array('approvedby', 'approvedbyemail', 'approvedbyemployeeemailaddress, approved_by_email_address')); ?>,
            policyTitles = <?= json_encode(array('policy', 'policyname', 'requesttype', 'requestedtype', 'leavetype')); ?>,
            departmentName = <?= json_encode(array('department', 'department', 'departmentname')); ?>,
            firstName = <?= json_encode(array('firstname', 'employeefirstname')); ?>,
            lastName = <?= json_encode(array('lastname', 'employeelastname')); ?>,
            requestedHoures = <?= json_encode(array('requestedhours')); ?>,
            requestedFromDate = <?= json_encode(array('requestedfromdate', 'leavefrom')); ?>,
            requestedToDate = <?= json_encode(array('requestedtodate', 'leaveto')); ?>,
            requestStatusTitles = <?= json_encode(array('requestedstatus', 'requeststatus', 'status')); ?>,
            availableHoursTiles = <?= json_encode(array('available', 'availablehours')); ?>,
            submitedDate = <?= json_encode(array('submitteddate','createddate')); ?>,
            approvedDate = <?= json_encode(array('approveddate', 'declineddate', 'approveddeclineddate')); ?>,
            employeeComments = <?= json_encode(array('employeecomments')); ?>,
            managerComments = <?= json_encode(array('managercomments')); ?>;

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
                alertify.alert('WARNING!', 'Please, select a file.', () => {});
                return;
            }
            //
            if (f.hasError === true) {
                alertify.alert('WARNING!', 'Invalid file is uploaded.', () => {});
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

            formHandlerHilight();
        }

        //
        function formHandlerHilight(e) {
            var fileData = file.target.result.split(/\n/g);
            // Get header

            var indexes = fileData[0].split(',');

            var columnIndex = {};

            // Reset index
            indexes = indexes.map(function(v, i) {
                var index = in_array(v.toLowerCase().replace(/[^a-z]/g, '').trim());
                // console.log({v})
                columnIndex[index === -1 ? 'extra' : index] = i;
                return index === -1 ? 'extra' : index;
            });

           //console.log({indexes})
           //return;

            // Remove head
            fileData.splice(0, 1);
            //
            var records = [];
            //

            var employeesEmail = [];
            var employeeApprovers = [];
            var incomingPolicies = {};

            fileData.map(function(v) {

                v = removecoma(v);
                //

                var index = in_array(v.toLowerCase().replace(/[^a-z]/g, '').trim());

                var tmp = v.split(','),
                    record = new Object(),
                    i = 0,
                    len = tmp.length;
                for (i; i < len; i++) record[indexes[i]] = tmp[i];
                records.push(record);

                // Echeck Start end dat

                if (tmp[1]) {

                    var cp = tmp[columnIndex['policy']];
                    //
                    if (!incomingPolicies[cp]) {
                        incomingPolicies[cp] = cp;
                    }

                    //
                    var empEmail = tmp[columnIndex['email_address']];
                    //
                    if (!employeesEmail[empEmail]) {
                        employeesEmail[empEmail] = empEmail;
                    }

                    var approverEmail = tmp[columnIndex['approved_by_email_address']];
                    //
                    if (!employeeApprovers[approverEmail]) {
                        employeeApprovers[approverEmail] = approverEmail;
                    }

                }

            });
            //

            // Send Ajax
            var empurl = '<?= base_url('timeoff/getemployeesdata') ?>';
            var form_data = new FormData();
            form_data.append('employeesEmail', Object.keys(employeesEmail));
            form_data.append('policy', Object.keys(incomingPolicies));
            form_data.append('approvedbyEmail', Object.keys(employeeApprovers));
            ///

            $.ajax({
                url: empurl,
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                beforeSend: function() {
                    $('#loader_text_div').text('Processing');
                    $('#document_loader').show();
                },
                success: function(resp) {

                    $data = JSON.parse(resp);

                    approversData = $data;
                    //
                    var html = '';
                    var emailAddressNotexist = 0;
                    var approvarAddressNotexist = 0;
                    var policyNotExist = 0;
                    fileData.map(function(v) {

                        v = removecoma(v);



                        var index = in_array(v.toLowerCase().replace(/[^a-z]/g, '').trim());


                        var tmp = v.split(','),
                            record = new Object(),
                            i = 0,
                            len = tmp.length;

                        //
                        var emailFlag = 0;
                        var emailApproverFlag = 0;
                        var policyFlag = 0;

                        var columns = '';
                      //  console.log({tmp})


                        if (tmp[1]) {
                            for (i; i < len; i++) {
                                // vs
                                if (i == columnIndex['email_address']) {
                                    if ($data.employeesApprovers.includes(tmp[columnIndex['email_address']])) {
                                        //   console.log('Found');
                                        emailFlag = 1;
                                    } else {
                                        emailAddressNotexist++;
                                    }
                                }
                                //
                                if (i == columnIndex['approved_by_email_address']) {
                                    if ($data.employeesApprovers.includes(tmp[columnIndex['approved_by_email_address']])) {
                                     //   console.log('address');
                                        emailApproverFlag = 1;
                                    } else {
                                        approvarAddressNotexist++;

                                    }
                                }

                                //
                                if (i == columnIndex['policy']) {
                                    if ($data.timeoffPolices.includes(tmp[columnIndex['policy']])) {
                                        policyFlag = 1;

                                    } else {
                                        policyNotExist++;
                                    }
                                }

                                columns += '<td >  ' + tmp[i] + '  </td>';


                            }
                        }
                        if (emailFlag == 1 && emailApproverFlag == 1 && policyFlag == 1) {
                            html += '<tr>' + columns + '</tr>';
                        } else {
                            html += '<tr style="background-color:#f2dede !important">' + columns + '</tr>';
                        }

                    });



                    tableBody = $("#importhistoricaltable tbody");
                    tableBody.append(html);
                    $('#importhistoricaldiv').show();
                    $('#emailnotexist').html("<b>Employee email address not exist (" + emailAddressNotexist + ")</b>");
                    $('#approveremailnotexist').html("<b>Approved by not exist (" + approvarAddressNotexist + ")</b>");
                    $('#policynotexist').html("<b>Leave type not exist (" + policyNotExist + ")</b>");
                    $('#loader_text_div').text('');
                    $('#document_loader').hide();
                },
                error: function() {}
            });

            //
        }



        //
        function formHandler(e) {

            var fileData = file.target.result.split(/\n/g);
            // Get header

            var indexes = fileData[0].split(',');

            var columnIndex = {};

            // Reset index
            indexes = indexes.map(function(v, i) {
                var index = in_array(v.toLowerCase().replace(/[^a-z]/g, '').trim());
                columnIndex[index === -1 ? 'extra' : index] = i;
                return index === -1 ? 'extra' : index;
            });

            // Remove head
            fileData.splice(0, 1);
            //
            var records = [];
            //

            var employeesEmail = [];
            var employeeApprovers = [];
            var incomingPolicies = {};

            fileData.map(function(v) {

                v = removecoma(v);
                //

                var index = in_array(v.toLowerCase().replace(/[^a-z]/g, '').trim());

                var tmp = v.split(','),
                    record = new Object(),
                    i = 0,
                    len = tmp.length;
                for (i; i < len; i++) record[indexes[i]] = tmp[i];
                records.push(record);

                // Echeck Start end dat

                if (tmp[1]) {

                    var cp = tmp[columnIndex['policy']];
                    //
                    if (!incomingPolicies[cp]) {
                        incomingPolicies[cp] = cp;
                    }

                    //
                    var empEmail = tmp[columnIndex['email_address']];
                    //
                    if (!employeesEmail[empEmail]) {
                        employeesEmail[empEmail] = empEmail;
                    }

                    var approverEmail = tmp[columnIndex['approved_by_email_address']];
                    //
                    if (!employeeApprovers[approverEmail]) {
                        employeeApprovers[approverEmail] = approverEmail;
                    }

                }

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

            // Reset start and length
            i = 0;
            len = approvedByemailAddressTitles.length;
            array = approvedByemailAddressTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'approved_by_email_address';
            // Reset start and length

            i = 0;
            len = policyTitles.length;
            array = policyTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'policy';
            // Reset start and length
            i = 0;
            len = departmentName.length;
            array = departmentName;
            for (i; i < len; i++)
                if (index == array[i]) return 'department_name';
            // Reset start and length
            i = 0;
            len = firstName.length;
            array = firstName;
            for (i; i < len; i++)
                if (index == array[i]) return 'first_name';
            // Reset start and length
            i = 0;
            len = lastName.length;
            array = lastName;
            for (i; i < len; i++)
                if (index == array[i]) return 'last_name';
            // Reset start and length
            i = 0;
            len = requestedHoures.length;
            array = requestedHoures;
            for (i; i < len; i++)
                if (index == array[i]) return 'requested_hours';
            // Reset start and length
            i = 0;
            len = requestedFromDate.length;
            array = requestedFromDate;
            for (i; i < len; i++)
                if (index == array[i]) return 'requested_from_date';
            // Reset start and length
            i = 0;
            len = requestedToDate.length;
            array = requestedToDate;
            for (i; i < len; i++)
                if (index == array[i]) return 'requested_to_date';
            // Reset start and length
            i = 0;
            len = requestStatusTitles.length;
            array = requestStatusTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'status';
            // Reset start and length
            i = 0;
            len = availableHoursTiles.length;
            array = availableHoursTiles;
            for (i; i < len; i++)
                if (index == array[i]) return 'available_hours';
            // Reset start and length
            i = 0;
            len = submitedDate.length;
            array = submitedDate;
            for (i; i < len; i++)
                if (index == array[i]) return 'submited_date';

            // Reset start and length
            i = 0;
            len = approvedDate.length;
            array = approvedDate;
            for (i; i < len; i++)
                if (index == array[i]) return 'approved_date';


            // Reset start and length
            i = 0;
            len = employeeComments.length;
            array = employeeComments;
            for (i; i < len; i++)
                if (index == array[i]) return 'employee_comment';


            // Reset start and length
            i = 0;
            len = managerComments.length;
            array = managerComments;
            for (i; i < len; i++)
                if (index == array[i]) return 'manager_comments';

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
         //   console.log({records})
           // return;
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
            $('#loader_text_div').html(messageRow);
            $('#document_loader').show();


            xhr = $.post("<?= base_url('timeoff/importHistoricalTimeOff'); ?>", {
                companySid: <?= $session['company_detail']['sid']; ?>,
                employeeSid: <?= $session['employer_detail']['sid']; ?>,
                approversPolicyDatacheck: approversData,
                timeoffs: chunk
            }, function(resp) {
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
                   $('#loader_text_div').text();
                   $('#document_loader').hide();

                    $('#msgrecordsimported').html("Records sucessfully imported (" + insertedCount + ")");
                    $('#msgrecordsnotimported').html("Records Not imported (" + failedCount + ")");

                 }
                // To stop the server break
                setTimeout(function() {
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



    //
    function removecoma(v) {
        return v.replace(/("(.+?)")|('(.+?)')/g, function(v) {
            return v.replace(/,/g, ' ')
        });
    }
</script>