<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$activeCompanies = '';
$activeCompanies .= '<option value="0">[Select Company]</option>';
foreach ($active_companies as $company)
    $activeCompanies .= '<option value="' . ($company['sid']) . '">' . ($company['CompanyName']) . '</option>';
?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title" style="width: 100%;"><i class="fa fa-users"></i><?php echo $page_title; ?><a href="<?= base_url('manage_admin/report/copy_applicants_report/'); ?>" class="btn btn-success pull-right">Copy Applicant Report</a></h1>
                                    </div>
                                    <!-- Main Page -->
                                    <div id="js-main-page">
                                        <div class="hr-setting-page">
                                            <?php echo form_open(base_url('manage_admin/copy_applicants/'), array('id' => 'copy-form')); ?>
                                            <ul>
                                                <li>
                                                    <label>Copy From <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-from-company">
                                                            <?= $activeCompanies; ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Job Type</label>
                                                    <div class="hr-fields-wrap">
                                                        <select id="js-job-type" style="width: 100%;">
                                                            <option value="-1">All</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">InActive</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Copy To <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-to-company">
                                                            <?= $activeCompanies; ?>
                                                        </select>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Keywords</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" placeholder="Search by applicant email and  name or Search by job title" name="keyword" class="invoice-fields search-job" value="" id="keyword">
                                                        <strong class="text-danger">
                                                            You can search multiple applicants at once. <br />E.G. john.doe@example.com, john smith
                                                        </strong>
                                                        <input type="hidden" name="transferred_note" value="" id="transferred_note">

                                                    </div>
                                                </li>


                                                <div id="btn-right" style="float:right">
                                                    <li style="width:auto">
                                                        <a class="site-btn" id="js-fetch-jobs" href="#">Fetch All Jobs </a>
                                                    </li>
                                                    <li style="width:auto">
                                                        <a class="site-btn" id="js-fetch-applicants" href="#">fetch Applicants</a>
                                                    </li>
                                                </div>
                                            </ul>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>

                                    <!-- Report Page -->
                                    <div id="js-report-page" style="display: none;">
                                        <div class="hr-setting-page">
                                            <button class="btn btn-default pull-right js-reset-view"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                                            <br />
                                            <br />
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Job Title</th>
                                                            <th>Copied Applicants</th>
                                                            <th>Existed Applicants</th>
                                                            <th>Failed Applicants</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="js-report-area"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Applicant Report Page -->
                            <div id="js-Applicantreport-page" style="display: none;">
                                <div class="hr-setting-page">
                                    <button class="btn btn-default pull-right js-reset-view"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                                    <br />
                                    <br />
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Job Title</th>
                                                    <th>Name/Email </th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="js-Applicantreport-area"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Jobs listing Block -->
                    <div id="js-job-list-block" style="margin: 10px;">
                        <h4 class="js-hide-fetch"><b>Total</b>: <span><span id="js-total-jobs">0</span> jobs found</span></h4>
                        <div class="hr-box js-hide-fetch">
                            <div class="hr-box-header">
                                <h4>Copy Specific Applicants</h4>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="table-responsive">
                                    <form action="javascript:void(0)" id="js-job-form" method="POST">
                                        <button type="button" class="btn btn-success pull-right js-copy-applicant-btn" style="margin-bottom: 10px;">Copy Selected Applicants</button>
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="top_checkbox" class="js-check-all" /></th>
                                                    <th class="text-center">ID</th>
                                                    <th>Job Title</th>
                                                    <th>Status</th>
                                                    <th>Applicants</th>
                                                    <th>Applicant Type</th>
                                                </tr>
                                            </thead>
                                            <tbody id="js-job-list-show-area"></tbody>
                                        </table>
                                        <input type="hidden" name="copy_to" id="form-copy" />
                                        <input type="hidden" name="form_action" />
                                        <button type="button" class="btn btn-success pull-right js-copy-applicant-btn">Copy Selected Applicants</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Applicant listing Block -->
                    <div id="js-applicant-list-block" style="margin: 10px;">
                        <h4 class="js-hide-fetch"><b>Total</b>: <span><span id="js-total-applicant">0</span> applicants found</span></h4>
                        <div class="hr-box js-hide-fetch">
                            <div class="hr-box-header">
                                <h4>Copy Specific Applicants</h4>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="table-responsive">
                                    <form action="javascript:void(0)" id="js-job-form" method="POST">
                                        <button type="button" class="btn btn-success pull-right js-selected-applicant-btn" style="margin-bottom: 10px;">Copy Selected Applicants</button>
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="top_checkbox" class="js-check-all" /></th>
                                                    <th class="text-center applicant_id">Applicant ID</th>
                                                    <th class="job_title">Job Title</th>
                                                    <th class="applicant_info">Applicant Name/Email</th>
                                                    <th class="job_status">Job Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="js-applicant-list-show-area"></tbody>
                                        </table>
                                        <input type="hidden" name="copy_to" id="form-copy" />
                                        <input type="hidden" name="form_action" />
                                        <button type="button" class="btn btn-success pull-right js-selected-applicant-btn">Copy Selected Applicants</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<style>
    .my_loader {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99;
        background-color: rgba(0, 0, 0, .7);
    }

    .loader-icon-box {
        position: absolute;
        top: 50%;
        left: 50%;
        width: auto;
        z-index: 9999;
        -webkit-transform: translate(-50%, -50%);
        -moz-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .loader-icon-box i {
        font-size: 14em;
        color: #81b431;
    }

    .loader-text {
        display: inline-block;
        padding: 10px;
        color: #000;
        background-color: #fff !important;
        border-radius: 5px;
        text-align: center;
        font-weight: 600;
    }
</style>

<!-- Loader -->
<div id="js-loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader cs-loader-file" style="display: none; height: 1353px;"></div>
    <div class="loader-icon-box cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text cs-loader-text" id="js-loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
        </div>
    </div>
</div>

<style>
    #js-job-list-block {
        display: none;
    }

    #js-applicant-list-block {
        display: none;
    }

    .cs-required {
        font-weight: bolder;
        color: #cc0000;
    }

    /* Alertify CSS */
    .ajs-header {
        background-color: #81b431 !important;
        color: #ffffff !important;
    }

    .ajs-ok {
        background-color: #81b431 !important;
        color: #ffffff !important;
    }

    .ajs-cancel {
        background-color: #81b431 !important;
        color: #ffffff !important;
    }
</style>


<script>
    var applicant_info_array = [];
    // Copy Applicants IIFE

    $('#js-fetch-applicants').click(function() {
        $('#js-job-list-block').hide();
        $('#js-applicant-list-block').show();
    });
    $('#js-fetch-jobs').click(function() {
        $('#js-job-list-block').show();
        $('#js-applicant-list-block').hide();
    });
    $(function copyApplicants() {



        var targets = {
                fromCompany: $('#js-from-company'),
                toCompany: $('#js-to-company'),
                jobType: $('#js-job-type'),
                mainLoader: $('#js-loader'),
                mainLoaderText: $('#js-loader-text'),
                mainPage: $('#js-main-page'),
                reportPage: $('#js-report-page'),
                ApplicantreportPage: $('#js-Applicantreport-page'),
                jobListBlock: $('#js-job-list-block'),
                applicantListBlock: $('#js-applicant-list-block'),
                jobForm: $('#js-job-form'),
                jobTotalArea: $('#js-total-jobs'),
                applicantTotalArea: $('#js-total-applicant'),
                jobListShowArea: $('#js-job-list-show-area'),
                applicantListShowArea: $('#js-applicant-list-show-area'),
                fetchJobBTN: $('#js-fetch-jobs'),
                fetchApplicantBTN: $('#js-fetch-applicants'),
                selectAllInput: $('.js-check-all'),
                selectSingleTR: $('.js-tr'),
                copiedApplicantTotal: $('#js-copied-applicants'),
                reportShowArea: $('#js-report-area'),
                ApplicantreportShowArea: $('#js-Applicantreport-area'),
                resetView: $('.js-reset-view'),
                progressBar: $('.js-progress-bar'),
                progressBarText: $('#js-progress-bar-text'),
                copyApplicantBTN: $('.js-copy-applicant-btn'),
                copyselectedApplicantBTN: $('.js-selected-applicant-btn'),
                applicantKeyword: $("#keyword"),
            },
            defaults = {
                mainLoaderText: 'Please, wait while we are processing request.'
            },
            xhr = {
                jobs: null,
                selectedJob: null,
                applicant: null
            },
            paginate = {
                jobs: {
                    currentPage: 1,
                    totalRecords: 0,
                    totalPages: 0,
                    limit: 0,
                    loadedRecords: 0,
                    records: []
                },
                //applicants_jobs same as jobs
                applicants_jobs: {
                    currentPage: 1,
                    totalRecords: 0,
                    totalPages: 0,
                    limit: 0,
                    loadedRecords: 0,
                    records: []
                },
                applicant: {
                    currentPage: 1,
                    totalRecords: 0,
                    totalPages: 0,
                    limit: 0,
                    loadedRecords: 0,
                    existedApplicants: 0,
                    failedApplicants: 0,
                    copiedApplicants: 0,
                    records: []
                },
                //Applicant_Selected same as applicant added on 12/18/2019
                Applicant_Selected: {
                    currentPage: 1,
                    totalRecords: 0,
                    totalPages: 0,
                    limit: 0,
                    loadedRecords: 0,
                    existedApplicants: 0,
                    failedApplicants: 0,
                    copiedApplicants: 0,
                    records: []
                },
                selectedJobs: {
                    currentJob: 0,
                    limit: 10,
                    totalJobs: 0
                },
                //Applicant_Selected same as selectedJobs added on 12/18/2019
                selectedApplicants: {
                    currentAppliant: 0,
                    limit: 10,
                    totalApplicants: 0
                },
                progress: {
                    totalChunks: 0,
                    progressStatus: 0,
                    progressStatusBase: 0
                },
                //Applicant_progress same as progress added on 12/18/2019
                Applicant_progress: {
                    totalChunks: 0,
                    progressStatus: 0,
                    progressStatusBase: 0
                }
            },
            baseURI = "<?= base_url('manage-admin/copy-applicants'); ?>/",
            reportArray = [],
            selectedJobs = [],
            megaArray = [],
            toCompanyId = null,
            jobId = null,
            stopRequest = false,
            token = new Date().getTime();

        var jobtimeout = '',
            apptimeout = '',
            copyjobtimeout = '',
            copyapptimeout = '';

        // Select 2
        targets.fromCompany.select2();
        targets.toCompany.select2();
        targets.jobType.select2();

        // Document events
        targets.fetchJobBTN.click(fetchJobEvent);
        targets.fetchApplicantBTN.click(fetchApplicantEvent);
        targets.selectAllInput.click(selectAllInputs);
        targets.resetView.click(resetView);
        // $(document).on('click', '.js-tr', selectSingleInput);
        $(document).on('click', '.js-copy-applicant-btn', startCopyProcess);
        $(document).on('click', '.js-selected-applicant-btn', startCopying);
        $(document).on('click', '.js-stop-btn', stopProcess);
        $(document).on('click', '#stop-fetch-app', stopFetchingApplicants);
        $(document).on('click', '#stop-fetch-job', stopFetchingJobs);

        // Functions
        function stopFetchingApplicants() {
            if (xhr.jobs != null) xhr.jobs.abort();
            if (xhr.applicant != null) xhr.applicant.abort();
            clearTimeout(apptimeout);
            loader('hide');
        }

        function stopFetchingJobs() {
            if (xhr.jobs != null) xhr.jobs.abort();
            if (xhr.applicant != null) xhr.applicant.abort();
            clearTimeout(jobtimeout);
            loader('hide');
        }
        // Start fetch jobs process
        function fetchJobEvent(e) {
            e.preventDefault();
            var obj = {};
            obj.jobType = parseInt(targets.jobType.val());
            obj.toCompanyId = targets.toCompany.val();
            obj.fromCompanyId = parseInt(targets.fromCompany.val());
            obj.applicantKeyword = targets.applicantKeyword.val();

            if (obj.fromCompanyId == 0) {
                alertify.alert('ERROR!', 'From company is mandatory.');
                return;
            }
            if (obj.toCompanyId == 0) {
                alertify.alert('ERROR!', 'To company is mandatory.');
                return;
            }
            if (obj.fromCompanyId === obj.toCompanyId) {
                alertify.alert('ERROR!', 'From and To company can not be same.');
                return;
            }
            // Reset all setting and flush local data
            resetAll();
            loader('show', 'Please, wait we are fetching all jobs of <strong>' + (targets.fromCompany.find('[value="' + (obj.fromCompanyId) + '"]').text()) + '</strong>');
            // Fetch all jobs from server
            fetchJobs(obj);
        }

        // fetchApplicantEvent added_on_12/16/19
        function fetchApplicantEvent(e) {
            e.preventDefault();


            var applicant_keyword = targets.applicantKeyword.val();
            //
          
            var obj = {};
            obj.jobType = parseInt(targets.jobType.val());
            obj.toCompanyId = targets.toCompany.val();
            obj.fromCompanyId = parseInt(targets.fromCompany.val());
            obj.applicantKeyword = applicant_keyword;

            if (obj.fromCompanyId == 0) {
                alertify.alert('ERROR!', 'From company is mandatory.');
                return;
            }
            if (obj.toCompanyId == 0) {
                alertify.alert('ERROR!', 'To company is mandatory.');
                return;
            }
            if (obj.fromCompanyId === obj.toCompanyId) {
                alertify.alert('ERROR!', 'From and To company can not be same.');
                return;
            }
            // Reset all setting and flush local data
            resetAll();
            loader('show', 'Please, wait we are fetching all jobs of <strong>' + (targets.fromCompany.find('[value="' + (obj.fromCompanyId) + '"]').text()) + '</strong>');
            // Fetch all jobs from server

            fetch_applicants(obj);
        }
        /////////////////
        // AJAX requests
        // Fetch jobs from server
        function fetchJobs(filter) {
            if (xhr.jobs !== null) return;
            filter.page = paginate.jobs.currentPage;
            xhr.jobs =
                $.post(baseURI + 'fetch-jobs', filter, function(resp, textStatus) {
                    xhr.jobs = null;
                    if (textStatus != 'success') {
                        jobtimeout = setTimeout(function() {
                            fetchJobs(filter);
                        }, 1500);
                        return;
                    }
                    if (resp.Status === false) {
                        loader('hide');
                        alertify.alert('NOTICE', resp.Response);
                        return;
                    }
                    // Check for pages
                    if (paginate.jobs.currentPage == 1) {
                        paginate.jobs.limit = resp.Limit;
                        paginate.jobs.records = resp.Records;
                        paginate.jobs.totalPages = resp.TotalPages;
                        paginate.jobs.totalRecords = resp.TotalRecords;
                    } else paginate.jobs.records = paginate.jobs.records.concat(resp.Records);
                    //
                    //
                    var row = '';
                    row += 'Please wait, we are loading jobs <br />';
                    row += 'This may take a few minutes <br />';
                    row += 'Fetching <strong>' + (paginate.jobs.records.length) + '</strong> of <strong>' + (paginate.jobs.totalRecords) + '</strong>';
                    row += '<button id="stop-fetch-job" class="btn btn-success btn-sm btn-block">Stop Fetching</button>';
                    targets.mainLoaderText.html(row);
                    //
                    makeJobView(resp.Records, paginate.jobs.currentPage);
                    if (paginate.jobs.currentPage < paginate.jobs.totalPages) {
                        paginate.jobs.currentPage++;
                        jobtimeout = setTimeout(function() {
                            fetchJobs(filter);
                        }, 1500);
                    } else {
                        loader('hide');
                        the_counted_values = $('.js-count-unchecked').length;
                    };
                });
        }



        $(document).on("click", '.js-count-unchecked', function() {
            var the_new_counted_values = $('.js-count-unchecked:checked').length;
            $("#top_checkbox").prop("checked", the_counted_values != the_new_counted_values ? false : true);
        })


        // Start Copy Process
        function startCopyProcess(e) {
            e.preventDefault();
            //
            selectedJobs = getAllSelectedInputsVal();
            //
            if (selectedJobs.length === 0) {
                alertify.alert('ERROR!', 'Please select atleast one job to start the process.');
                return;
            }
            paginate.selectedJobs.totalJobs = selectedJobs.length;
            //
            copyApplicants();
        }

        //Start copying made in 12/17/2019

        function startCopying(e) {
            e.preventDefault();
            //
            selectedApplicants = getAllSelectedApplicantsVal();

            // paginate.Applicant_Selected.totalPages=selectedApplicants.length;
            if (selectedApplicants.length === 0) {
                alertify.alert('ERROR!', 'Please select atleast one Applicant to start the process.');
                return;
            }
            paginate.selectedApplicants.totalApplicants = selectedApplicants.length;

            copySelectedApplicants();
        }

        //
        function copyApplicants() {
            //
            if (selectedJobs[paginate.selectedJobs.currentJob] === undefined) {
                // TODO job are uploaded
                loader('hide');
                loadReport();
                return;
            }

            var job = selectedJobs[paginate.selectedJobs.currentJob];
            //
            loader('show', 'Please wait, we are copy applicant for job <strong>' + (job.jobTitle) + '</strong> ');
            //
            copyJobWithApplicant(job);
        }

        // copySelectedApplicants added on 12/17/2019
        function copySelectedApplicants() {
            if (selectedApplicants[paginate.selectedApplicants.currentApplicant] === undefined) {
                // TODO job are uploaded
                loader('hide');
                loadReport();
                return;
            }
            var applicant_gathered = selectedApplicants[paginate.selectedApplicants.currentApplicant];

            //
            let rows = '';
            rows += 'Please wait, we  copy applicant <strong id="applicant_loader">' + (selectedApplicants[paginate.selectedApplicants.currentApplicant].applicant_name) + '</strong>';
            rows += '<button class="btn btn-success btn-sm btn-block js-stop-btn">Stop</button>';
            loader('show', rows);
            //
            moveApplicantByJobId_new(applicant_gathered);
        }
        //
        function copyJobWithApplicant(job) {

            if (xhr.selectedJob !== null || stopRequest === true) return;
            job.toCompanyId = targets.toCompany.val();
            job.fromCompanyId = targets.fromCompany.val();
            xhr.selectedJob =
                $.post(baseURI + 'fetch-applicants-by-job', job, function(resp, textStatus) {
                    xhr.selectedJob = null;
                    if (textStatus != 'success') {
                        setTimeout(function() {
                            copyJobWithApplicant(job);
                        }, 1500);
                        return;
                    }
                    //
                    job.failedApplicants = job.existedApplicants = job.copiedApplicants = 0;
                    //
                    if (resp.Status === false) {
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    //
                    paginate.applicant.limit = resp.Limit;
                    paginate.applicant.totalPages = resp.TotalPages;
                    paginate.applicant.currentPage = 1;
                    paginate.applicant.totalRecords = resp.TotalRecords;
                    //
                    var rows = '',
                        toUpload = paginate.applicant.limit > paginate.applicant.totalRecords ? paginate.applicant.totalRecords : paginate.applicant.limit;
                    //
                    paginate.progress.totalChunks = paginate.applicant.totalPages;
                    paginate.progress.progressStatusBase = parseFloat(100 / paginate.progress.totalChunks);
                    paginate.progress.progressStatus = 0;
                    //
                    rows += 'Please wait, we are copying applicant for job <strong>' + (job.jobTitle) + '</strong> <br />';
                    rows += 'This may take a few minutes <br />';
                    rows += 'Copying applicants <strong id="js-copied-applicants">' + (toUpload) + '</strong> of <strong>' + (paginate.applicant.totalRecords) + '</strong> <br /><br />';
                    rows += '<div class="progress">';
                    rows += '   <div class="progress-bar progress-bar-success progress-bar-striped active js-progress-bar" role="progressbar" aria-valuenow="' + (paginate.progress.progressStatus) + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + (paginate.progress.progressStatus) + '%">';
                    rows += '   </div>';
                    rows += '</div>';
                    rows += '<p style="margin-top: -14px;" id="js-progress-bar-text">' + (paginate.progress.progressStatus) + '%</p>';
                    rows += '<button class="btn btn-success btn-sm btn-block js-stop-btn">Stop</button>';
                    //
                    jobId = job.jobId;
                    toCompanyId = job.toCompanyId;
                    loader('show', rows);
                    //
                    moveApplicantByJobId(job);
                });
        }


        // moveApplicantByJobId_new added on 12/18/2019
        function moveApplicantByJobId_new(applicant_gathered) {


            if (paginate.selectedApplicants.currentApplicant == paginate.selectedApplicants.totalApplicants) {

                loadCopiedApplicantsReport(applicant_info_array);
                paginate.Applicant_Selected.totalRecords = paginate.Applicant_Selected.totalApplicants = paginate.Applicant_Selected.currentApplicant = paginate.Applicant_Selected.existedApplicants = paginate.Applicant_Selected.failedApplicants = paginate.Applicant_Selected.copiedApplicants = 0;
                //  loadCopiedApplicantsReport(); applicant_info_array

            } else {

                if (xhr.Applicant_Selected !== null) return;
                if (stopRequest) {
                    loadCopiedApplicantsReport(applicant_info_array);
                    return;
                }
                applicant_gathered.toCompanyId = targets.toCompany.val();
                applicant_gathered.fromCompanyId = targets.fromCompany.val();
                $("#applicant_loader").text(applicant_gathered.applicant_name);
                xhr.Applicant_Selected =
                    $.post(baseURI + 'move-applicants-new', applicant_gathered, function(resp, textStatus, reqst) {
                        xhr.Applicant_Selected = null;
                        if (textStatus != 'success') {
                            copyapptimeout = setTimeout(function() {
                                moveApplicantByJobId_new(selectedApplicants[paginate.selectedApplicants.currentApplicant]);
                            }, 1500);
                            return;
                        }
                        if (resp.copiedApplicants == 1) {
                            applicant_gathered.Status = "copied";
                            applicant_info_array.push(applicant_gathered);
                        }
                        if (resp.existedApplicants == 1) {
                            applicant_gathered.Status = "existed";
                            applicant_info_array.push(applicant_gathered);

                        }
                        if (resp.failedApplicants == 1) {
                            applicant_gathered.Status = "failed";
                            applicant_info_array.push(applicant_gathered);
                        }

                        paginate.selectedApplicants.currentApplicant++;

                        setTimeout(function() {
                            copyapptimeout = moveApplicantByJobId_new(selectedApplicants[paginate.selectedApplicants.currentApplicant]);
                        }, 1500);
                    });
            }
        }

        function moveApplicantByJobId(job) {
            //
            if (xhr.applicant !== null || stopRequest === true) return;
            job.page = paginate.applicant.currentPage;
            job.limit = paginate.applicant.limit;
            //
            xhr.applicant =
                $.post(baseURI + 'copy-job-with-applicants', job, function(resp, textStatus) {
                    xhr.applicant = null;
                    if (textStatus != 'success') {
                        copyjobtimeout = setTimeout(function() {
                            moveApplicantByJobId(job);
                        }, 1500);
                        return;
                    }
                    if (paginate.applicant.currentPage <= paginate.applicant.totalPages) {
                        paginate.applicant.currentPage++;
                        paginate.applicant.copiedApplicants += parseInt(resp.copiedApplicants);
                        paginate.applicant.failedApplicants += parseInt(resp.failedApplicants);
                        paginate.applicant.existedApplicants += parseInt(resp.existedApplicants);
                        var applicantMoved = parseInt($('#js-copied-applicants').text()) + paginate.applicant.limit;
                        if (applicantMoved > paginate.applicant.totalRecords) applicantMoved = paginate.applicant.totalRecords;
                        //
                        $('#js-copied-applicants').text(applicantMoved);
                        paginate.progress.progressStatus += paginate.progress.progressStatusBase;
                        $('.js-progress-bar').css('width', '' + (paginate.progress.progressStatus) + '%');
                        $('#js-progress-bar-text').text(parseInt(paginate.progress.progressStatus).toString() + '%');
                        copyjobtimeout = setTimeout(function() {
                            moveApplicantByJobId(job);
                        }, 1500);
                    } else {
                        // Track record in database
                        $.post(baseURI + 'track-job', {
                            jobId: job.jobId,
                            toCompanyId: parseInt(targets.toCompany.val()),
                            fromCompanyId: parseInt(targets.fromCompany.val()),
                            jobTitle: job.jobTitle,
                            token: token,
                            copiedApplicants: paginate.applicant.copiedApplicants,
                            existedApplicants: paginate.applicant.existedApplicants,
                            failedApplicants: paginate.applicant.failedApplicants
                        });
                        var newOBJ = {
                            jobTitle: job.jobTitle,
                            copiedApplicants: paginate.applicant.copiedApplicants,
                            failedApplicants: paginate.applicant.failedApplicants,
                            existedApplicants: paginate.applicant.existedApplicants
                        };
                        //
                        megaArray.push(
                            newOBJ
                        );
                        //
                        paginate.applicant.totalRecords = paginate.applicant.totalPages = paginate.applicant.currentPage = paginate.applicant.existedApplicants = paginate.applicant.failedApplicants = paginate.applicant.copiedApplicants = 0;
                        //
                        paginate.selectedJobs.currentJob++;
                        copyApplicants();
                    }
                });
        }

        // Helpers
        // Make job view
        function makeJobView(records, page) {
            targets.jobListBlock.show();
            var rows = '';
            $.each(records, function(i, v) {
                var totalApplicants = parseInt(v.total_applicants.archived) + parseInt(v.total_applicants.active);
                rows += '<tr class="' + (totalApplicants != 0 ? 'js-tr' : '') + '">';
                rows += '   <td><input type="checkbox" class="checking_chkbox_class ' + (totalApplicants == 0 ? '' : 'js-count-unchecked') + '" id="checking_chkbox" ' + (totalApplicants == 0 ? 'disabled="true"' : '') + ' name="txt_ids[]" value="' + (v.sid) + '" /></td>';
                rows += '   <td>' + (v.sid) + '</td>';
                rows += '   <td class="js-job-title">' + (v.new_job_title) + '</td>';
                rows += '   <td class="' + (v.job_status == 1 ? 'text-success' : 'text-danger') + '">' + (v.job_status == 1 ? 'Active' : 'InActive') + '</td>';
                rows += '   <td class="js-applicants">' + (totalApplicants) + '</td>';
                rows += '   <td>';
                rows += '       <div class="checkbox">';
                rows += '           <label>';
                rows += '               <input type="checkbox" name="txt_archieved" checked="true" ' + (totalApplicants == 0 ? 'disabled="true"' : '') + ' />Archived (' + (v.total_applicants.archived) + ')';
                rows += '           </label>';
                rows += '       </div>';
                rows += '       <div class="checkbox" style="margin: 10px;">';
                rows += '           <label>';
                rows += '               <input type="checkbox" name="txt_active" checked="true" ' + (totalApplicants == 0 ? 'disabled="true"' : '') + ' />Active (' + (v.total_applicants.active) + ')';
                rows += '           </label>';
                rows += '       </div>';
                rows += '   </td>';
                rows += '</tr>';
            });
            //
            if (page == 1) targets.jobListShowArea.html(rows);
            else targets.jobListShowArea.append(rows);

            targets.jobTotalArea.text(paginate.jobs.records.length);
        }

        function makeApplicantView(records, totalrecord, page) {
            targets.applicantListBlock.show();
            var rows = '';
            $.each(records, function(i, v) {
                totalRecords = parseInt(totalrecord);
                if (v.city != '') {
                    city = ' - ' + v.city;
                }
                if (v.State != '') {
                    State = ', ' + v.State;
                }
                rows += '<tr class="' + (totalrecord != 0 ? 'js-tr' : '') + '">';
                rows += '   <td><input type="checkbox" class="checking_chkbox_class ' + (totalrecord == 0 ? '' : 'js-count-unchecked') + '" id="checking_chkbox" ' + (totalrecord == 0 ? 'disabled="true"' : '') + ' name="txt_ids[]" value="' + (v.sid) + '" /></td>';
                rows += '   <td class="js_applicant_id">' + (v.applicant_sid) + '</td>';
                // rows += '   <td class="js_job_title">'+(v.job_title)+(city)+(State)+'</td>';
                rows += '   <td class="js_job_title">' + (v.new_job_title) + '</td>';
                rows += '   <td class="js_applicant_info"><b>' + (v.full_name) + '</b><br/>' + (v.email) + '</td>';
                rows += '   <td style="display:none" class="js_job_id">' + (v.job_id) + '</td>';
                rows += '   <td class="' + (v.job_status == 1 ? 'text-success' : 'text-danger') + ' js_job_status">' + (v.job_status == 1 ? 'Active' : 'InActive') + '</td>';
                rows += '</tr>';
            });
            //
            if (page == 1) targets.applicantListShowArea.html(rows);
            else targets.applicantListShowArea.append(rows);
            targets.applicantTotalArea.text(totalrecord);
        }

        // Select all input: checkbox
        function selectAllInputs() {
            $('.js-tr').find('input[name="txt_ids[]"]').prop('checked', $(this).prop('checked'));
        };

        var the_counted_values = 0;
        // Select single input: checkbox
        // function selectSingleInput(){
        //     $(this).find('input[name="txt_ids[]"]').prop('checked', !$(this).find('input[name="txt_ids[]"]').prop('checked'));
        // }

        // Reset local data
        function resetAll() {
            paginate.jobs.records = [];
            paginate.jobs.totalPages = 0;
            paginate.jobs.currentPage = 1;
            paginate.jobs.loadedRecords = 0;
            paginate.jobs.totalRecords = 0;
            /////
            paginate.applicants_jobs.records = [];
            paginate.applicants_jobs.totalPages = 0;
            paginate.applicants_jobs.currentPage = 1;
            paginate.applicants_jobs.loadedRecords = 0;
            paginate.applicants_jobs.totalRecords = 0;
            ///
            if (xhr.jobs != null) xhr.jobs.abort();
            if (xhr.applicant != null) xhr.applicant.abort();
            if (xhr.selectedJob != null) xhr.selectedJob.abort();
            ////
            if (xhr.applicants_jobs != null) xhr.applicants_jobs.abort();
            if (xhr.Applicant_Selected != null) xhr.Applicant_Selected.abort();
            if (xhr.selectedApplicant != null) xhr.selectedApplicant.abort();
            ///
            xhr.jobs = null;
            xhr.applicant = null;
            xhr.selectedJob = null;
            ////
            xhr.applicants_jobs = null;
            xhr.Applicant_Selected = null;
            xhr.selectedApplicant = null;
            //
            targets.jobListBlock.hide();
            targets.applicantListBlock.hide();
            targets.jobTotalArea.text(0);
            targets.applicantTotalArea.text(0);
            targets.jobListShowArea.html('');
            targets.applicantListShowArea.html('');
            targets.ApplicantreportShowArea.html('');
            //
            selectedJobs = [];
            ////
            selectedApplicants = [];

            //
            megaArray = [];
            //
            paginate.applicant.totalPages = 0;
            paginate.applicant.currentPage = 1;
            paginate.applicant.totalRecords = 0;
            paginate.applicant.copiedApplicants = 0;
            paginate.applicant.existedApplicants = 0;
            paginate.applicant.failedApplicants = 0;
            ////
            paginate.Applicant_Selected.totalPages = 0;
            paginate.Applicant_Selected.currentPage = 1;
            paginate.Applicant_Selected.totalRecords = 0;
            paginate.Applicant_Selected.copiedApplicants = 0;
            paginate.Applicant_Selected.existedApplicants = 0;
            paginate.Applicant_Selected.failedApplicants = 0;
            //
            paginate.selectedJobs.currentJob = 0;
            paginate.selectedJobs.limit = 0;
            paginate.selectedJobs.totalJobs = 0;
            targets.selectAllInput.prop('checked', false);
            stopRequest = false;
            ////
            paginate.selectedApplicants.currentApplicant = 0;
            paginate.selectedApplicants.limit = 0;
            paginate.selectedApplicants.totalApplicants = 0;
            targets.selectAllInput.prop('checked', false);
            stopRequest = false;
            //
            paginate.progress.totalChunks = 0;
            paginate.progress.progressStatus = 0;
            paginate.progress.progressStatusBase = 0;
            ////
            paginate.Applicant_progress.totalChunks = 0;
            paginate.Applicant_progress.progressStatus = 0;
            paginate.Applicant_progress.progressStatusBase = 0;
            //
            jobId = null;
            toCompanyId = null;
            token = new Date().getTime();
        }

        // Get ids of checked inputs
        function getAllSelectedInputsVal() {
            var tmp = [];
            $.each($('input[name="txt_ids[]"]:checked'), function() {
                var obj = {};
                obj.jobId = parseInt($(this).val());
                obj.active = Number($(this).closest('tr').find('input[name="txt_active"]').prop('checked'));
                obj.jobTitle = $(this).closest('tr').find('td.js-job-title').text();
                obj.archieved = Number($(this).closest('tr').find('input[name="txt_archieved"]').prop('checked'));
                obj.totalApplicants = parseInt($(this).closest('tr').find('td.js-applicants').text());
                if (obj.archieved !== 0 || obj.active !== 0) tmp.push(obj);
            });
            return tmp;
        }

        function getAllSelectedApplicantsVal() {
            var tmp = [];
            $.each($('input[name="txt_ids[]"]:checked'), function() {
                var Applicant_info = $(this).closest('tr').find('td.js_applicant_info').html().split('<br>');
                var applicant_name = $(Applicant_info[0]).text();
                var applicant_email = Applicant_info[1];
                var obj = {};
                obj.sid = parseInt($(this).val());
                obj.applicantId = parseInt($(this).closest('tr').find('td.js_applicant_id').text());
                obj.jobTitle = $(this).closest('tr').find('td.js_job_title').text();
                obj.applicant_name = applicant_name;
                obj.applicant_email = applicant_email;
                obj.job_id = parseInt($(this).closest('tr').find('td.js_job_id').text());
                obj.JobStatus = $(this).closest('tr').find('td.js_job_status').text();
                tmp.push(obj);
            });
            return tmp;
        }
        //
        function loadReport() {
            //
            targets.jobListBlock.hide(0);
            targets.applicantListBlock.hide(0);
            targets.mainPage.fadeOut(0);
            targets.reportPage.fadeIn(150);
            //
            var rows = '';
            if (megaArray.length !== 0) {
                $.each(megaArray, function(i, v) {
                    rows += '<tr>';
                    rows += '   <td>' + (v.jobTitle) + '</td>';
                    rows += '   <td class="text-success">' + (v.copiedApplicants) + '</td>';
                    rows += '   <td class="text-warning">' + (v.existedApplicants) + '</td>';
                    rows += '   <td class="text-danger">' + (v.failedApplicants) + '</td>';
                    rows += '</tr>';
                });
            } else {
                rows += '<tr><td colspan="4"><p class="alert alert-info text-center">Nothing copied!</p></td></tr>'
            }
            targets.reportShowArea.html(rows);
        }
        ////loadCopiedApplicantsReport added on 12/19/2019
        function loadCopiedApplicantsReport(applicant_info_array) {
            loader('hide');
            targets.jobListBlock.hide(0);
            $("#js-report-page").hide();
            targets.applicantListBlock.hide(0);
            targets.mainPage.fadeOut(0);
            targets.ApplicantreportPage.fadeIn(150);
            // $("#js-Applicantreport-page").show();
            //
            var rows = '';
            if (applicant_info_array.length !== 0) {
                $.each(applicant_info_array, function(i, v) {

                    rows += '<tr>';
                    rows += '   <td>' + (v.jobTitle) + '</td>';
                    rows += '   <td><b>' + (v.applicant_name) + "</b><br>" + (v.applicant_email) + '</td>';
                    rows += '   <td class="' + (v.Status == "failed" ? +'text-danger' : 'text-success') + '">' + (v.Status) + '</td>';
                    rows += '</tr>';
                });
            } else {
                rows += '<tr><td colspan="4"><p class="alert alert-info text-center">Nothing copied!</p></td></tr>'
            }
            targets.ApplicantreportShowArea.html(rows);
        }

        // Loader
        function loader(show_it, msg) {
            msg = msg === undefined ? defaults.mainLoaderText : msg;
            show_it = show_it === undefined || show_it == true || show_it === 'show' ? 'show' : show_it;
            if (show_it === 'show') {
                targets.mainLoader.show();
                targets.mainLoaderText.html(msg);
            } else {
                targets.mainLoader.hide();
                targets.mainLoaderText.html('');
            }
        }
        //
        function resetView() {

            targets.reportPage.fadeOut(0);
            targets.mainPage.fadeIn(150);
            targets.reportShowArea.html('');
            targets.ApplicantreportShowArea.html('');
            targets.fromCompany.select2('val', 0);
            targets.toCompany.select2('val', 0);
            $("#js-Applicantreport-page").hide();
            applicant_info_array = [];
            $("#js-Applicantreport-area").html('');
            resetAll();
        }

        // $(document).on('click','')

        function stopProcess() {
            stopRequest = true;
            if (xhr.jobs != null) xhr.jobs.abort();
            if (xhr.applicant != null) xhr.applicant.abort();
            if (xhr.selectedJob != null) xhr.selectedJob.abort();
            //            $.post(baseURI+'revoke-job',{jobId: jobId, toCompanyId: toCompanyId});

            if (copyapptimeout != '') {
                clearTimeout(copyapptimeout);
                loadCopiedApplicantsReport(applicant_info_array);
            } else {
                clearTimeout(copyjobtimeout);
                loadReport();
            }
            loader('hide');
        }

        //added on 12/16/2019 copy applicant process

        function fetch_applicants(filter) {
            if (xhr.jobs !== null) return;
            filter.page = paginate.jobs.currentPage;
            xhr.jobs =
                $.post(baseURI + 'fetch-applicants-new', filter, function(resp, textStatus, reqst) {
                    xhr.jobs = null;
                    if (textStatus != 'success') {
                        apptimeout = setTimeout(function() {
                            fetch_applicants(filter);
                        }, 1500);
                        return;
                    }
                    if (resp.Status === false) {
                        loader('hide');
                        alertify.alert('NOTICE', resp.Response);
                        return;
                    }
                    // Check for pages
                    if (paginate.jobs.currentPage == 1) {
                        paginate.jobs.limit = resp.Limit;
                        paginate.jobs.records = resp.Records;
                        paginate.jobs.totalPages = resp.TotalPages;
                        paginate.jobs.totalRecords = resp.TotalRecords;
                    } else paginate.jobs.records = paginate.jobs.records.concat(resp.Records);
                    //
                    //
                    var row = '';
                    row += 'Please wait, we are loading jobs <br />';
                    row += 'This may take a few minutes <br />';
                    row += 'Fetching <strong>' + (paginate.jobs.records.length) + '</strong> of <strong>' + (paginate.jobs.totalRecords) + '</strong>';
                    row += '<button id="stop-fetch-app" class="btn btn-success btn-sm btn-block">Stop Fetching</button>';
                    targets.mainLoaderText.html(row);
                    //
                    makeApplicantView(resp.Records, paginate.jobs.records.length, paginate.jobs.currentPage);
                    if (paginate.jobs.currentPage < paginate.jobs.totalPages) {
                        paginate.jobs.currentPage++;
                        apptimeout = setTimeout(function() {
                            fetch_applicants(filter);
                        }, 1500);
                    } else {
                        loader('hide');
                        the_counted_values = $('.js-count-unchecked').length;
                    };
                });
        }

    })
</script>