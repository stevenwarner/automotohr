<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="applicant-filter">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="filter-form-wrp">

                                    <span>Select Job Posting:</span>
                                    <div class="tracking-filter">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                                <div class="hr-select-dropdown">

                                                    <select class="invoice-fields" name="jobs_list" id="jobs_list">
                                                        <option value="all">All Jobs</option>
                                                        <?php foreach ($all_jobs as $job) {
                                                                if($jobs_approval_module_status == '1') {
                                                                    if($job['approval_status'] == 'approved') { ?>
                                                                        <option value="<?= $job["sid"] ?>" <?php if ($job_sid == $job["sid"]) { ?> selected="selected" <?php } ?>><?= $job["Title"] ?></option>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                        <option value="<?= $job["sid"] ?>" <?php if ($job_sid == $job["sid"]) { ?> selected="selected" <?php } ?>><?= $job["Title"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 custom-col">
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="status" id="status">
                                                        <option value="all" selected>All Statuses</option>
                                                        <?php if($have_status == false) { ?>
                                                        <option value="not_contacted_yet" <?php if(isset($status) && $status == "not_contacted_yet"){ echo "selected"; } ?>>Not Contacted Yet</option>
                                                        <option value="left_message" <?php if(isset($status) && $status == "left_message"){ echo "selected"; } ?>>Left Message</option>
                                                        <option value="contacted" <?php if(isset($status) && $status == "contacted"){ echo "selected"; } ?>>Contacted</option>
                                                        <option value="candidate_responded" <?php if(isset($status) && $status == "candidate_responded"){ echo "selected"; } ?>>Candidate Responded</option>
                                                        <option value="interviewing" <?php if(isset($status) && $status == "interviewing"){ echo "selected"; } ?>>Interviewing</option>
                                                        <option value="submitted" <?php if(isset($status) && $status == "submitted"){ echo "selected"; } ?>>Submitted</option>
                                                        <option value="qualifying" <?php if(isset($status) && $status == "qualifying"){ echo "selected"; } ?>>Qualifying</option>
                                                        <option value="ready_to_hire" <?php if(isset($status) && $status == "ready_to_hire"){ echo "selected"; } ?>>Ready to Hire</option>
                                                        <option value="offered_job" <?php if(isset($status) && $status == "offered_job"){ echo "selected"; } ?>>Offered Job</option>
                                                        <option value="client_declined" <?php if(isset($status) && $status == "client_declined"){ echo "selected"; } ?>>Client Declined</option>
                                                        <option value="not_in_consideration" <?php if(isset($status) && $status == "not_in_consideration"){ echo "selected"; } ?>>Not In Consideration</option>
                                                        <option value="future_opportunity" <?php if(isset($status) && $status == "future_opportunity"){ echo "selected"; } ?>>Future Opportunity</option>
                                                        <?php } else { ?>
                                                            <?php foreach($company_statuses as $company_status) { ?>
                                                                <option value="<?php echo isset($company_status['name']) ? $company_status['name'] : ''; ?>" <?php if(isset($status) && (urldecode($status) == $company_status['name'])){ echo "selected"; } ?>>
                                                                    <?php echo isset($company_status['name']) ? $company_status['name'] : ''; ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                <a class="form-btn" href="" id="filter-btn" name="filter-btn">Filter</a>
                                            </div>
                                        </div>
                                        
                                        <script>
                                            $(document).ready(function () {
                                                $('#jobs_list, #status').on('change', function () { 
                                                    var js_job_id = $('#jobs_list').val();
                                                    var status = $('#status').val();
                                                    status = encodeURIComponent(status);
                                                   
                                                    var str = window.location.href;
                                                    var archive = str.indexOf('archived_applicants');
                                                    
                                                    if(archive > -1) {
                                                        var myUrl = "<?php echo base_url('archived_applicants/all'); ?>" + "/" + js_job_id + "/" + status;
                                                    } else {
                                                        var myUrl = "<?php echo base_url('application_tracking_system/active/all/all/all/all'); ?>" + "/" + js_job_id + "/" + status;
                                                    }

                                                    $('#filter-btn').attr('href', myUrl);
                                                    console.log(myUrl);
                                                });
                                            });
                                        </script>
                                        
                                    </div>
                                </div>
                                <div class="filter-form-wrp">
                                    <span>Search Applicant(s)</span>
                                    <div class="tracking-filter">
                                        <form method="GET" name="applicant_filter" action="<?php echo base_url() . ( intval($archive) == 1 ? 'archived_applicants' : 'application_tracking'); ?>">
                                            <div class="row">
                                                <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">
                                                    <div class="hr-select-dropdown no-aarow">
                                                        <input type="text" placeholder="Search applicants by Name or Email"
                                                               name="keyword" id="keyword" value="<?php echo $keyword; ?>"
                                                               class="invoice-fields search-candidate">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">

                                                    <a id="my_submit_btn" name="my_submit_btn" class="form-btn" href="#" style="min-width: auto;">Search</a>
                                                </div>
                                            </div>
                                        </form>
                                        <script>
                                            $(document).ready(function () {
                                                $('#keyword, #my_submit_btn').on('keyup, click', function () {
                                                    var keyword = $('#keyword').val();

                                                    if(keyword == '' || keyword == undefined || keyword == null){
                                                        keyword = 'all';
                                                    }

                                                    var myurl = '<?php echo base_url() . ( intval($archive) == 1 ? 'archived_applicants' : 'application_tracking'); ?>' + '/' + keyword + '/all/all/';

                                                    $('#my_submit_btn').attr('href', myurl);
                                                    console.log(myurl);
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-panel text-right">
                        <div class="row">
                            <a href="<?= base_url() ?>manual_candidate" class="btn btn-success">+ Add Manual Candidate</a>
                            <?php if ($archive) { ?>
                                <a href="<?= base_url('application_tracking/all/all/all') ?>" class="btn btn-success">Show Active Applicants</a>
                            <?php } else { ?>
                                <a href="<?= base_url('archived_applicants/all/all/all') ?>" class="btn btn-warning">Show Archived Applicants</a>
                            <?php } ?>
                            <!--<a href="javascript:;" class="btn btn-info" id="send_email">Send Email</a>-->
                            <a href="javascript:;" class="btn btn-danger" id="send_rej_email">Send Rejection Email</a>
                            <a href="javascript:;" class="btn btn-info" id="send_ack_email">Send Acknowledgement Email</a>
                            <a href="javascript:;" class="btn btn-success" id="send_bulk_email" data-toggle="modal" data-target="#myModal">Send Bulk Email</a>
                        </div>
                    </div>
                    <?php echo $links; ?>
                    <?php if ($archive == 0 && base_url() != 'http://localhost/automotoCI/') { ?>
                        <!-- <script type="text/javascript" src="<?= base_url() ?>assets/js/jsapi.js"></script> -->
                        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                        <div class="applicant-applied">
                            <div class="applicant-graphic-info">
                                <div class="col-lg-7 col-md-6 col-xs-12 col-sm-6">
                                    <div class="graphical-info">
                                        <script type="text/javascript">
                                                google.load("visualization", "1", {packages: ["corechart"]});
                                                google.setOnLoadCallback(drawChart);
                                                function drawChart() {
                                                    var data = google.visualization.arrayToDataTable(<?php echo $graph; ?>);
                                                    var options = {
                                                        title: 'Company Performance',
                                                        hAxis: {title: 'Year', titleTextStyle: {color: '#333'}},
                                                        vAxis: {minValue: 0}
                                                    };
                                                    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                                                    chart.draw(data, options);
                                                }
                                        </script>
                                        <div id="chart_div" style="width: 100%; height: 300px;"></div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6 col-xs-12 col-sm-6">
                                    <div class="graphical-info">
                                        <script type="text/javascript">
                                            google.load("visualization", "1", {packages: ["corechart"]});
                                            google.setOnLoadCallback(drawChart);
                                            function drawChart() {
                                                var data = google.visualization.arrayToDataTable(<?php echo $chart; ?>);
                                                var options = {
                                                    'title': '',
                                                    'width': 380,
                                                };
                                                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                                                chart.draw(data, options);
                                            }
                                        </script>
                                        <div id="piechart" style="width: 400px; height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="applicant-count-wrp">
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="row">
                                        <div class="applicant-count"
                                             style="background-color:#162c3a; border-radius:0 0 0 5px;">
                                            <p>Total Job Applicants</p>
                                            <span>
                                                <?php   if (is_admin($employer_sid)) {
                                                            echo $all_job_applicants;
                                                        } else {
                                                            echo $applicant_total;
                                                        } ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="row">
                                        <div class="applicant-count" style="background-color:#980b1e;">
                                            <p>Total Talent Network</p>
                                            <span><?php echo $all_talent_applicants; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="row">
                                        <div class="applicant-count" style="background-color:#4f8d09;">
                                            <p>Total Manual Contacts</p>
                                            <span><?php echo $all_manual_applicants; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="applicant-box-wrp" id="show_no_jobs">
                        <?php if (empty($employer_jobs)) { ?>
                            <span class="applicant-not-found">No Applicants found!</span>
                        <?php } else { ?>
                            <form method="POST" name="ej_form" id="ej_form">
                                <?php foreach ($employer_jobs as $employer_job) { ?>
                                    <article id="manual_row<?php echo $employer_job["sid"]; ?>" class="applicant-box onboarding">
                                        <div class="box-head">
                                            <div class="row date-bar">
                                                <div class="col-lg-1 col-md-1 col-xs-1 col-sm-1">
                                                    <label class="control control--checkbox"><input name="ej_check[]" type="checkbox" value="<?php echo $employer_job["sid"]; ?>" class="ej_checkbox">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-xs-7 col-sm-7">
                                                    <time class="date-applied">Date Applied: <?php echo my_date_format($employer_job["date_applied"]); ?></time>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                                <?php if($archive) { ?>
                                                        <a class="float-right aplicant-documents delete-text-color" onclick="delete_single_applicant(<?php echo $employer_job["sid"]; ?>)" href="javascript:;">
                                                            <i class="fa fa-times"></i><span class="btn-tooltip">Delete</span>
                                                        </a>
                                                        <a class="float-right aplicant-documents" onclick="active_single_applicant(<?php echo $employer_job["sid"]; ?>)" href="javascript:;">
                                                            <i class="fa fa-undo"></i><span class="btn-tooltip">Re-Activate</span>
                                                        </a>
                                                <?php } else { ?>
                                                        <a class="pull-right aplicant-documents" onclick="archive_single_applicant(<?php echo $employer_job["sid"]; ?>)" href="javascript:;"><i class="fa fa-archive"></i><span class="btn-tooltip">Archive</span></a>
                                                <?php } ?>
                                                    <a href="javascript:void(0);" onclick="fLaunchModal(this);" class="pull-right aplicant-documents" data-preview-url="<?php echo $employer_job["resume_direct_link"]; ?>" data-download-url="<?php echo $employer_job["resume_download_link"]; ?>" data-file-name="<?php echo $employer_job['resume']; ?>" data-document-title="Resume" ><i class="fa fa-file-text-o"></i><span class="btn-tooltip">Resume</span></a>
                                                    <a href="javascript:void(0);" onclick="fLaunchModal(this);" class="pull-right aplicant-documents" data-preview-url="<?php echo $employer_job["cover_letter_direct_link"]; ?>" data-download-url="<?php echo $employer_job["cover_letter_download_link"]; ?>" data-file-name="<?php echo $employer_job['cover_letter']; ?>" data-document-title="Cover Letter" ><i class="fa fa-file-text-o"></i><span class="btn-tooltip">Cover Letter</span></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="start-rating applicant-rating">                                            
                                            <input readonly="readonly"
                                            id="input-21b" <?php if (!empty($employer_job['applicant_average_rating'])) { ?> value="<?php echo $employer_job['applicant_average_rating']; ?>" <?php } ?>
                                            type="number" name="rating" class="rating" min=0 max=5 step=0.2
                                            data-size="xs">
                                            <span class="review-score">Review Score : <?php echo ($employer_job['applicant_average_rating'] > 0) ? $employer_job['applicant_average_rating'] : '0'; ?> with <?php echo $employer_job['reviews_count']; ?> Review(s)</span>
                                        </div>
                                        <div class="applicant-info">
                                            <figure><a href="<?php echo base_url('/applicant_profile/' . $employer_job["sid"]); ?>" title="<?php echo $employer_job["first_name"] . ' ' . $employer_job["last_name"]; ?>">
                                                        <?php   if(empty($employer_job["pictures"])) { ?>
                                                                    <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                        <?php   } else { ?>
                                                                    <img src="<?php echo AWS_S3_BUCKET_URL . $employer_job["pictures"]; ?>">
                                                        <?php   } ?>
                                                    </a>
                                            </figure>
                                            <div class="text">
                                                <p>
                                                    <a href="<?php echo base_url('/applicant_profile/' . $employer_job["sid"]); ?>" title="<?php echo $employer_job["first_name"] . ' ' . $employer_job["last_name"]; ?>">
                                                        <?php echo $employer_job["first_name"] . ' ' . $employer_job["last_name"]; ?>
                                                    </a>
                                                </p>
                                                <div class="phone-number">
                                                    <?php if(isset($employer_job['phone_number']) && $employer_job['phone_number'] != '') { 
                                                        echo '<a class="theme-color" href="tel:'.$employer_job['phone_number'].'"><strong><i class="fa fa-phone"></i> ' . $employer_job['phone_number'] . '</strong></a>'; 
                                                    } ?>
                                                </div> 
                                                <span><?php echo $employer_job["applicant_type"]; ?></span>
                                                <div class="candidate-status applicat-status-edit">
                                                    <div class="label-wrapper-outer">
                                                        <?php if($have_status == false) { ?>
                                                        <?php if ($employer_job["status"] == 'Contacted') { ?>
                                                        <div class="selected contacted"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Candidate Responded') { ?>
                                                        <div class="selected responded"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Qualifying') { ?>
                                                        <div class="selected qualifying"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Submitted') { ?>
                                                        <div class="selected submitted"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Interviewing') { ?>
                                                        <div class="selected interviewing"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Offered Job') { ?>
                                                        <div class="selected offered"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Not In Consideration') { ?>
                                                        <div class="selected notin"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Client Declined') { ?>
                                                        <div class="selected decline"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Placed/Hired' || $employer_job["status"] == 'Ready to Hire') { ?>
                                                        <div class="selected placed">Ready to Hire</div>
                                                        <?php } elseif ($employer_job["status"] == 'Not Contacted Yet') { ?>
                                                        <div class="selected not_contacted"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Future Opportunity') { ?>
                                                        <div class="selected future_opportunity"><?= $employer_job["status"] ?></div>
                                                        <?php } elseif ($employer_job["status"] == 'Left Message') { ?>
                                                        <div class="selected left_message"><?= $employer_job["status"] ?></div>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                        <div class="selected <?php echo (isset($employer_job['status_css_class'])) ? $employer_job['status_css_class'] : ''; ?>">
                                                            <?php echo (isset($employer_job['status_name'])) ? $employer_job['status_name'] : ''; ?>
                                                        </div>
                                                        <?php } ?>
                                                        <div class="show-status-box" title="Edit Applicant Status"><i class="fa fa-pencil"></i></div>
                                                        <div class="lable-wrapper">
                                                            <div id="id" style="display:none;"><?= $employer_job["sid"] ?></div>
                                                            <div style="height:20px;"><i class="fa fa-times cross"></i></div>
                                                            <?php if($have_status == false) { ?>
                                                            <div class="label applicant not_contacted">
                                                                <div id="status">Not Contacted Yet</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant left_message">
                                                                <div id="status">Left Message</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant contacted">
                                                                <div id="status">Contacted</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant responded">
                                                                <div id="status">Candidate Responded</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant interviewing">
                                                                <div id="status">Interviewing</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant submitted">
                                                                <div id="status">Submitted</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant qualifying">
                                                                <div id="status">Qualifying</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant placed">
                                                                <div id="status">Ready to Hire</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant offered">
                                                                <div id="status">Offered Job</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant decline">
                                                                <div id="status">Client Declined</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant notin">
                                                                <div id="status">Not In Consideration</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <div class="label applicant future_opportunity">
                                                                <div id="status">Future Opportunity</div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                            <?php } else { ?>
                                                                <?php foreach($company_statuses as $status) { ?>
                                                                    <div class="label applicant <?php echo $status['css_class']; ?>">
                                                                        <div id="status"><?php echo $status['name']; ?></div>
                                                                        <i class="fa fa-check-square check"></i>
                                                                    </div>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="interview-scoreing">
                                                <ul>
                                                    <!--<li>
                                                        <span>Interview Scores:</span> 59 out of 100
                                                    </li>-->
                                                    <li>
                                                        <span>Questionnaire Score: </span> 
                                                        <?php if($employer_job['questionnaire'] == '' || $employer_job['questionnaire'] == NULL) { ?>
                                                        <p class="fail">N/A</p>
                                                        <?php } else { ?>
                                                            <?php echo $employer_job['score']; ?>
                                                            <?php if ($employer_job['score'] >= $employer_job['passing_score']) { ?>
                                                            <p class="pass">(Pass)</p>
                                                            <?php } else { ?>
                                                            <p class="fail">(Fail)</p>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="applicant-job-description">                                            
                                            <div class="applicant-job-title"><span>Job Title</span></div>
                                            <div class="text">
                                                <p><?php echo $employer_job['Title']; ?></p>
                                            </div>
                                        </div>
                                    </article>
                                <?php } ?>
                            </form>
                        <?php } ?>
                    </div>
                 <?php  if ($archive) {
                            if ($applicant_total > 0) { ?>
                            <input type="hidden" name="countainer_count" id="countainer_count" value="<?php echo $applicant_total; ?>">
                            <div class="pagination-container" id="hide_del_row">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                        <div class="delete-all">
                                            <a class="delete-all-btn" href="javascript:;" id="ej_controll_delete">Delete Selected</a>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <?php //echo $links;  ?>
                                    </div>
                                </div>
                            </div>
                    <?php   }
                        } // else { ?>
                        <div class="pagination-container" id="hide_del_row">
                            <div class="col-xs-12 col-sm-12">
                                <?php if (isset($job_sid) && $job_sid == 'all') { ?>
                                    <?php echo $links; ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php // } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<button style='display:none;' type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#bulk_email_modal" id="bulk_email_button">Open Bulk Email Modal</button>
<div id="bulk_email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Bulk Email to Applicants</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">                            
                        <ul>
                            <form method='post' id='register-form' name='register-form'>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-3"><b>Subject</b><span class="hr-required red"> * </span></div>
                                        <div class="col-md-9">
                                            <input type='text' class="hr-form-fileds invoice-fields" id="bulk_email_subject" name='subject' />
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-3"><b>Message</b><span class="hr-required red"> * </span></div>
                                        <div class="col-md-9">                      
                                            <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" id="bulk_email_message" name="bulk_email_message"></textarea>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="message-action-btn">
                                        <input type="submit" value="Send Message" class="submit-btn" onclick="bulk_email_form_validate()"> 
                                    </div>
                                </li>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');

        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);

        var modal_content = '';
        var footer_content = '';

        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                    console.log('in office docs check');
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    console.log('in images check');
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                default :
                    console.log('in google docs check');
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a class="submit-btn" href="' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);


                //document.getElementById('preview_iframe').contentWindow.location.reload();
            }
        });


    }

    function delete_single_applicant(id) {
        alertify.confirm("Please Confirm Delete", "Are you sure you want to delete applicant?",
            function () {
                url = "<?= base_url('applicant_profile/delete_single_applicant') ?>";
                $.post(url, {del_id: id, action: "del_single_applicant"})
                    .done(function (data) {
                        $('#manual_row' + id).hide();
                        var total_rows = $('#countainer_count').val();
                        total_rows = total_rows - 1;
                        $('#countainer_count').val(total_rows);
                        if (total_rows <= 0) {
                            $('#hide_del_row').hide();
                            $('#show_no_jobs').html('<span class="applicant-not-found">No Applicants found!</span>');
                        }
                        alertify.notify(data, 'success');
                    });
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    function archive_single_applicant(id) {
        alertify.confirm("Please Confirm Archive", "Are you sure you want to archive applicant?",
            function () {
                var myUrl = "<?= base_url('applicant_profile/archive_single_applicant') ?>";

                var myRequest;
                myRequest = $.ajax({
                    url: myUrl,
                    type: 'post',
                    data: {arch_id: id, action: "arch_single_applicant"}
                });

                myRequest.done(function (response) {
                    $('#manual_row' + id).hide();
                    var total_rows = $('#countainer_count').val();
                    total_rows = total_rows - 1;
                    $('#countainer_count').val(total_rows);

                    if (total_rows <= 0) {
                        $('#hide_del_row').hide();
                        $('#show_no_jobs').html('<span class="applicant-not-found">No Applicants found!</span>');
                    }

                    alertify.notify(response, 'success');
                });
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    function active_single_applicant(id) {
        alertify.confirm("Please Confirm Reactive", "Are you sure you want to reactive applicant?",
            function () {
                url = "<?= base_url('applicant_profile/active_single_applicant'); ?>";
                $.post(url, {active_id: id, action: "active_single_applicant"})
                    .done(function (data) {
                        $('#manual_row' + id).hide();
                        var total_rows = $('#countainer_count').val();
                        total_rows = total_rows - 1;
                        $('#countainer_count').val(total_rows);

                        if (total_rows <= 0) {
                            $('#hide_del_row').hide();
                            $('#show_no_jobs').html('<span class="applicant-not-found">No Applicants found!</span>');
                        }
                        alertify.notify(data, 'success');
                    });
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    function bulk_email_form_validate() {
        $("#register-form").validate({
            ignore: [],
            rules: {
                subject: {
                    required: true
                },
                bulk_email_message: {
                    required: function () {
                        CKEDITOR.instances.bulk_email_message.updateElement();
                    },
                    minlength: 10
                }
            },
            messages: {
                subject: {
                    required: 'E-Mail Subject is required'
                },
                bulk_email_message: {
                    required: "E-Mail Message is required",
                    minlength: "Please enter few characters"
                }
            },
            submitHandler: function () {
                var ids = [{}];
                var counter = 0;
                $.each($(".ej_checkbox:checked"), function () {
                    ids[counter++] = $(this).val();
                });
                var subject = ($('#bulk_email_subject').val()).trim();
                var message = ($('#bulk_email_message').val()).trim();
                url_to = "<?= base_url() ?>send_manual_email/send_bulk_email";
                $.post(url_to, {action: "bulk_email", ids: ids, subject: subject, message: message})
                    .done(function (response) {
                        $("#bulk_email_modal .close").click();
                        alertify.success('Bulk email sent to selected applicant(s).');
                    });
                return false;
            }
        });
    }

    $(document).ready(function () {
        $('.show-status-box').click(function(){
            $(this).next().show();
        });
        $('#send_rej_email').click(function () {
            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirmation', "Are you sure you want to send rejection email to selected Applicant(s)?",
                    function () {
                        var ids = [{}];
                        var counter = 0;
                        $.each($(".ej_checkbox:checked"), function () {
                            ids[counter++] = $(this).val();
                        });

                        url_to = "<?= base_url() ?>send_manual_email";
                        $.post(url_to, {action: "rejection_letter", ids: ids})
                            .done(function (response) {
                                // do something after successful
                            });
                        alertify.success('Rejection email sent to selected applicants.');
                    },
                    function () {
                        alertify.error('Cancelled');
                    });
            } else {
                alertify.alert('Send Rejection Email Error', 'Please select Applicant(s) to send rejection email.');
            }
        });

        $('#send_ack_email').click(function () {
            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirmation', "Are you sure you want to send acknowledgement email to selected Applicant(s)?",
                    function () {
                        var ids = [{}];
                        var counter = 0;
                        $.each($(".ej_checkbox:checked"), function () {
                            ids[counter++] = $(this).val();
                        });

                        url_to = "<?= base_url() ?>send_manual_email";
                        $.post(url_to, {action: "application_acknowledgement_letter", ids: ids})
                            .done(function (response) {
                                // do something after successful
                            });

                        alertify.success('Acknowledgement email sent to selected applicants.');
                    },
                    function () {
                        alertify.error('Cancelled');
                    });
            } else {
                alertify.alert('Send Acknowledgment Email Error', 'Please select Applicant(s) to send acknowledgement email.');
            }
        });

        $('#send_bulk_email').click(function () {
            if ($(".ej_checkbox:checked").size() > 0) {
                alertify.confirm('Confirm Bulk E-Mail', "Are you sure you want to send bulk email to selected Applicant(s)?",
                    function () {
                        $('#bulk_email_button').click();

                    },
                    function () {
                        alertify.error('Cancelled');
                    });
            } else {
                alertify.alert('Send Bulk Email Error', 'Please select Applicant(s) to send bulk email.');
            }
        });

        $('#ej_controll_delete').click(function () {
            var butt = $(this);
            if ($(".ej_checkbox:checked").size() > 0) {
                if (butt.attr("id") == "ej_controll_mark") {
                    $("#ej_action").val("mark");
                } else {
                    alertify.confirm("Are you sure you want to delete application(s)?",
                        function () {
                            $('#ej_form').append('<input type="hidden" name="delete_contacts" value="true" />');
                            $("#ej_form").submit();
                            alertify.success('Deleted');

                        },
                        function () {
                            alertify.error('Cancelled');
                        });
                }
            } else {
                alertify.alert('Please select application(s) to delete');
            }
        });

        $('.selected').click(function () {
            $(this).next().next().css("display", "block");
        });

        $('.candidate').click(function () {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            var status = $(this).find('#status').html();
            var id = $(this).parent().find('#id').html();

            url = "<?= base_url() ?>application_tracking/update_status";
            $.post(url, {"id": id, "status": status, "action": "ajax_update_status_candidate"})
                .done(function (data) {
                    alertify.success("Candidate status updated successfully.");
                });
        });

        $('.candidate').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.applicant').click(function () { 
           
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).find('.check').css("visibility", "visible");
            
            $(this).parent().prev().prev().html($(this).find('#status').html());
            $(this).parent().prev().prev().css("background-color", $(this).css("background-color"));
            
            var status = $(this).find('#status').html();
            var id = $(this).parent().find('#id').html();

            var my_url = "<?= base_url() ?>application_tracking_system/update_status";

            //console.log(my_url);

            var my_request;
            my_request = $.ajax({
                url : my_url,
                type: 'POST',
                data: { "id" : id, "status" : status, "action": "ajax_update_status" }
            });

            my_request.done(function (response) {
                if(response == 'success' || response == 'Done'){
                    alertify.success("Candidate status updated successfully.");
                } else {
                    alertify.success("Could not update Candidate Status.");
                }
            });
        });

        $('.applicant').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");

        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.cross').click(function () {
            $(this).parent().parent().css("display", "none");
        });

        $('.label').click(function () {
            $(this).parent().css("display", "none");
        });

        $.each($(".selected"), function () {
            class_name = $(this).attr('class').split(' ');
            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
        });
    });
</script>