<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h4>The Provided CSV File must be in Following Format</h4>
                                    </div>
                                    <div class="panel-body">
                                        <pre>
<b>First Name, Last Name, Email, Primary Number, Street Address, City, Zipcode, State, Country, Profile Picture URL, Resume URL, Cover Letter URL, Job Title, Source, Date Applied,Status</b><br>
Jason, Snow, jason@abc.com, +123456789, 123 Street, California, 90001, CA, United States, https://yourwebsite.com/images/profile_picture.png, https://yourwebsite.com/resume/jason_snow_resume.docx, https://yourwebsite.com/cover_letter/jason_snow_cover.docx, General Manager,Indeed,2020-01-24 12:50:56,Not Contacted Yet
Albert, King, albert@example.com, +123456789, 98 Street, California, 90001, CA, United States, https://yourwebsite.com/images/profile_picture.png, https://yourwebsite.com/resume/resume.docx, https://yourwebsite.com/cover_letter/cover.docx, Manager Sales,Indeed,2023-01-24 12:50:56,Not Contacted Yet
Nathan, Quite, nathan@example.com, +1823212129, your Street, California, 90001, CA, United States, https://yourwebsite.com/images/profile_picture.png, https://yourwebsite.com/resume/resume.docx, https://yourwebsite.com/cover_letter/cover.docx, Technical Manager,Other Website,2024-01-24 12:50:56,Submitted
Allen, Knight, allen@example.com, +1223312129, your Street, California, 90001, CA, United States, https://yourwebsite.com/images/profile_picture.png, https://yourwebsite.com/resume/resume.docx, https://yourwebsite.com/cover_letter/cover.docx, Office Assistant,Indeed,2020-01-24 12:50:56,Not Contacted Yet
Jack, Brown, jack@example.com, 013212129, your Street, California, 90001, CA, United States, https://yourwebsite.com/images/profile_picture.png, https://yourwebsite.com/resume/resume.docx, https://yourwebsite.com/cover_letter/cover.docx, Team Lead,Indeed,2020-01-24 12:50:56,Submitted
</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="universal-form-style-v2">
                            <ul>
                                <form method="post" enctype="multipart/form-data" id="js-import-form">
                                    <input type="hidden" value="upload_file" name="action" />
                                    <div>
                                        <label>Upload CSV File</label>
                                        <input type="file" id="userfile" style="display: none;" />
                                    </div>
                                    <br />
                                    <div>
                                        <input type="submit" class="btn btn-success js-submit-btn disabled" id="importSubmit" value="Import Applicants" disabled="true" />
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
<!-- Loader -->
<div id="my_loader" class="text-center js-loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text js-loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
        </div>
    </div>
</div>
<script src="<?= base_url('assets/lodash/loadash.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/alertifyjs/css/alertify.min.css'); ?>">
<script src="<?= base_url('assets/alertifyjs/alertify.min.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/waitMe.min.css">
<script src="<?= base_url() ?>assets/js/waitMe.min.js"></script>

<link rel="stylesheet" href="<?= base_url('assets/mFileUploader/index.css'); ?>" />
<script src="<?= base_url('assets/mFileUploader/index.js'); ?>"></script>

<script>
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

    // $('#importSubmit').click(function (e) {
    //     $('body').waitMe({
    //         effect: 'timer',
    //         text: 'Please Wait!',
    //         bg: 'rgba(255, 255, 255, 0.7)',
    //         color: '#81b431',
    //         maxSize: '',
    //         textPos: 'vertical',
    //         fontSize: '30px',
    //         source: ''
    //     });
    // });
    //
    $(function importCSV() {
        var
            file = [],
            jobTitles = <?= json_encode(array('jobtitle', 'job')); ?>,
            cityTitles = <?= json_encode(array('city', 'employeecity')); ?>,
            stateTitles = <?= json_encode(array('state', 'employeestate')); ?>,
            resumeTitles = <?= json_encode(array('resume', 'cv', 'resumeurl')); ?>,
            countryTitles = <?= json_encode(array('country', 'employeecountry')); ?>,
            addressTitles = <?= json_encode(array('address', 'streetaddress', 'employeeaddressline1', 'addressline1', 'employeeaddress')); ?>,
            zipCodeTitles = <?= json_encode(array('zipcode', 'zip', 'employeezip')); ?>,
            lastNameTitles = <?= json_encode(array('lastname', 'lname')); ?>,
            firstNameTitles = <?= json_encode(array('firstname', 'fname')); ?>,
            coverLetterTitles = <?= json_encode(array('coverletter', 'coverletterurl')); ?>,
            phoneNumberTitles = <?= json_encode(array('phonenumber', 'contactnumber', 'contact', 'employeetelephonenumber', 'telephonenumber', 'primarynumber')); ?>,
            accessLevelTitles = <?= json_encode(array('accesslevel')); ?>,
            emailAddressTitles = <?= json_encode(array('email', 'emailaddress')); ?>,
            profilePictureTitles = <?= json_encode(array('profile', 'profilepicture', 'profilepictureurl')); ?>;

        dateOfBirthTitles = <?= json_encode(array('dateofbirth', 'dob')); ?>;
        socialSecurityNumberTitles = <?= json_encode(array('socialsecuritynumber', 'ssn')); ?>;
        maritalStatusTitles = <?= json_encode(array('maritalstatus')); ?>;
        genderTitles = <?= json_encode(array('gender')); ?>;



        jobSourceTitles = <?= json_encode(array('Source', 'source', 'jobsource', 'JobSource', 'JobSource', 'jobsource')); ?>,
            dateAppliedTitles = <?= json_encode(array('DateApplied', 'dateapplied', 'Dateapplied', 'dateApplied', 'AppliedDate', 'applieddate', 'appliedDate', 'Applieddate')); ?>;
        statusAppliedTitles = <?= json_encode(array('Status', 'status')); ?>;





        loader('hide');
        // 
        // $('#userfile').change(fileChanged);
        //
        $('#js-import-form').submit(formHandler);
        //
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
        }

        //
        function formHandler(e) {
            e.preventDefault();
            var fileData = file.target.result.split(/\n/g);
            // Get header
            var indexes = fileData[0].split(',');
            // Reset index
            indexes = indexes.map(function(v, i) {
                var index = in_array(v.toLowerCase().replace(/[^a-z]/g, '').trim());
                return index === -1 ? 'extra' : index;
            });
            // Remove head
            fileData.splice(0, 1);
            //
            var records = [];
            //
            fileData.map(function(v) {
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
            len = firstNameTitles.length;
            array = firstNameTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'first_name';
            // Reset start and length
            i = 0;
            len = lastNameTitles.length;
            array = lastNameTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'last_name';
            // Reset start and length
            i = 0;
            len = phoneNumberTitles.length;
            array = phoneNumberTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'phone_number';
            // Reset start and length
            i = 0;
            len = emailAddressTitles.length;
            array = emailAddressTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'email';
            // Reset start and length
            i = 0;
            len = addressTitles.length;
            array = addressTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'address';
            // Reset start and length
            i = 0;
            len = countryTitles.length;
            array = countryTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'country';
            // Reset start and length
            i = 0;
            len = cityTitles.length;
            array = cityTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'city';
            // Reset start and length
            i = 0;
            len = stateTitles.length;
            array = stateTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'state';
            // Reset start and length
            i = 0;
            len = zipCodeTitles.length;
            array = zipCodeTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'zipcode';
            // Reset start and length
            i = 0;
            len = jobTitles.length;
            array = jobTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'desired_job_title';
            // Reset start and length
            i = 0;
            len = profilePictureTitles.length;
            array = profilePictureTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'pictures';
            // Reset start and length
            i = 0;
            len = resumeTitles.length;
            array = resumeTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'resume';
            // Reset start and length
            i = 0;
            len = dateOfBirthTitles.length;
            array = dateOfBirthTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'dob';

            // Reset start and length
            i = 0;
            len = socialSecurityNumberTitles.length;
            array = socialSecurityNumberTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'ssn';

            // Reset start and length
            i = 0;
            len = maritalStatusTitles.length;
            array = maritalStatusTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'marital_status';


            // Reset start and length
            i = 0;
            len = genderTitles.length;
            array = genderTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'gender';


            // Reset start and length
            i = 0;
            len = coverLetterTitles.length;
            array = coverLetterTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'cover_letter';

            i = 0;
            len = jobSourceTitles.length;
            array = jobSourceTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'applicant_source';

            i = 0;
            len = dateAppliedTitles.length;
            array = dateAppliedTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'date_applied';

            i = 0;
            len = statusAppliedTitles.length;
            array = statusAppliedTitles;
            for (i; i < len; i++)
                if (index == array[i]) return 'status';


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
                alertify.alert('SUCCESS!', 'You have successfully imported <b>' + (insertedCount) + '</b> new applicants, <b>' + (existedCount) + '</b> applicants already exists and <b>' + (failedCount) + '</b> applicants failed to add.');
                failedCount = existedCount = insertedCount = chunkOBJ.loaded = chunkOBJ.current = chunkOBJ.totalChunks = chunkOBJ.recordsLength = 0;
                return;
            }
            //
            chunkOBJ.loaded += chunk.length;
            var
                messageRow = "Please, be patient as we import applicants.<br />";
            messageRow += "It may take a few minutes<br />";
            messageRow += 'Importing ' + (chunkOBJ.loaded) + ' of ' + (chunkOBJ.recordsLength) + ' applicants.';
            //
            loader('show', messageRow);
            xhr = $.post("<?= base_url('import-applicant-csv/handler'); ?>", {
                action: 'add_applicants',
                applicants: chunk
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
                    loader('hide');
                    alertify.alert('SUCCESS!', 'You have successfully imported <b>' + (insertedCount) + '</b> new applicants, <b>' + (existedCount) + '</b> applicants already exists and <b>' + (failedCount) + '</b> applicants failed to add.');
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
    })
</script>