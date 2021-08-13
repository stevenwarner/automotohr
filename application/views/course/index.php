<style>

    .arrow-links {
      float: left;
      width: 100%;
      margin: 2px 0 28px 0;
    }

    .arrow-links ul {
      margin: 0;
    }

    .arrow-links ul li {
      float: left;
      /*width: 33.3333%;*/
      position: relative;
      list-style: none;
    }

    .arrow-links ul li a {
      font-size: 10px;
      display: block;
      padding: 10px 0 10px 45px;
      color: #3554dc;
      background-color: #e6e9ed;
      line-height: normal;
      position: relative;
    }

    .arrow-links ul li a span {
      font-size: 24px;
      display: block;
      width: 100%;
    }

    .arrow-links ul li:first-child a {
      padding-left: 15px !important;
    }

    .arrow-links ul li a:before {
      content: " ";
      display: block;
      width: 0;
      height: 0;
      border-top: 31px solid transparent;
      /* Go big on the size, and let overflow hide */
      border-bottom: 43px solid transparent;
      border-left: 30px solid #fff;
      position: absolute;
      top: 50%;
      margin-top: -31px;
      margin-left: 1px;
      left: 100%;
      z-index: 1;
    }

    .arrow-links ul li a:after {
      content: " ";
      display: block;
      width: 0;
      height: 0;
      border-top: 31px solid transparent;
      border-bottom: 43px solid transparent;
      border-left: 30px solid #e6e9ed;
      position: absolute;
      top: 50%;
      margin-top: -31px;
      left: 100%;
      z-index: 2;
    }

    .arrow-links ul li a:hover,
    .arrow-links ul li.active a {
      color: #fff;
      background-color: #3554dc;
    }

    .arrow-links ul li a:hover:after {
      border-left-color: #3554dc !important;
    }

    .arrow-links ul li.active a:after {
      border-left: 30px solid #3554dc !important;
    }

    .arrow-links ul li:last-child a:before,
    .arrow-links ul li:last-child a:after {
      display: none;
    }

    .arrow-links ul li.done .star {
      position: absolute;
      right: 0;
      top: 50%;
      width: 20px;
      height: 20px;
      margin-top: -10px;
    }

    .arrow-links ul li.done .star:after {
      content: "\f005";
      color: #1032c3;
      font-family: "FontAwesome";
      position: absolute;
      top: 0;
      right: 0;
      font-size: 20px;
      font-style: normal;
    }

    .arrow-links ul li.active .star:after {
      color: #1032c3;
    }

    .arrow-links ul li a:hover .star:after {
      color: #1032c3;
    }

    .nextandsave,.save_visibility{
        float: right;
        background-color: #fd7a2a;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        border: none;
        padding: 9px 5px;
        border-radius: 5px;
        text-align: center;
    }

    .previous_tab {
        float: right;
        background-color: #000000;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        border: none;
        padding: 9px 5px;
        border-radius: 5px;
        text-align: center;
        margin-right: 12px;
    }
</style>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <?php if(check_access_permissions_for_view($security_details, 'add_listing')) { ?>
                                <a href="javascript:;" class="dashboard-link-btn" id="add_course_btn">Create A New Course</a>
                            <?php } ?>
                            Learning Management System
                        </span>
                    </div>
                    <div class="applicant-filter search-job-wrp">
                        <div class="row">                            
                            <div class="col-lg-8 col-md-12 col-xs-12 col-sm-12">
                                <div class="filter-form-wrp">
                                    <span>Search Course:</span>
                                    <div class="tracking-filter">
                                        <form method="GET" id="jobs_filter" name="jobs_filter">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 custom-col">
                                                    <div class="hr-select-dropdown no-aarow">
                                                        <input type="hidden" name="action" id="action" value="" />
                                                        <input type="text" name="search" id="search" value="" class="invoice-fields search-course" placeholder="Search course">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                <a class="form-btn" href="#" id="search-btn">Search</a>
                                <script>
                                    $('#search').trigger('keyup');
                                    $(document).ready(function () {
                                        $('#search').on('keyup', function () {
                                            var searchString = $('#search').val();
                                            var actionUrl = $('#action').val()+ '/' + $('#segment-2').val();
                                            $('#search-btn').attr('href', actionUrl + '/' + encodeURI(searchString));
                                        });
                                    });
                                </script>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                <a class="form-btn" href="<?= base_url('my_listings/active')?>" >Clear</a>
                            </div>
                        </div>
                    </div>
                    <div class="pagination-container" >
                        <div class="col-xs-12 col-sm-12">
                            <?php echo $links; ?>
                        </div>
                    </div>
                    <div class="panel panel-default full-width active-inactive-jobs-tab">
                        <div class="resp-tabs-container hor_1">
                            <div id="active" class="tabs-content panel-body">    
                                <div class="table-responsive table-outer">
                                    <?php if ($course_listing) { ?>
                                        <form action="selected_jobs_xml_export.php" method="POST" id="xml_form">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-6">ID</th>
                                                        <th class="col-xs-6">Title</th>
                                                        <th class="col-xs-6">Status</th>
                                                        <th class="col-xs-2 text-center">No of Employee</th>
                                                        <th class="col-xs-2 text-center">Progress</th>
                                                    
                                                        <?php $function_names = array('add_listing', 'add_listing_advertise', 'add_listing_share', 'clone_listing', 'activate_deactivate_job', 'delete_archive_job'); ?>
                                                        <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                            <th class="col-xs-3 text-center last-col">Actions</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($course_listing as $course) { ?>
                                                        <tr>
                                                            <td><?php echo $course['sid']; ?></td>
                                                            <td><?php echo $course['title']; ?></td>
                                                            <td class="text-center">
                                                                <?php
                                                                if($course["status"] == 1) {
                                                                    echo 'Active';
                                                                } else {
                                                                    echo 'Inactive';
                                                                }    
                                                                ?>
                                                                
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo $course['employees']; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo $course['progress']; ?>
                                                            </td>
                                                            <td>
                                                                <div class="job-action-btn-wrp arrow-down">
                                                                    <a class="dropdown-btn" href="javascript:;">Options</a>
                                                                    <ul class="action-options">
                                                                        <li>
                                                                            <a href="<?= base_url('edit_listing') ?>/<?= $course['sid'] ?>">
                                                                                <i class="fa fa-pencil-square-o"></i>
                                                                                Edit this Job
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="<?= base_url('preview_listing') ?>/<?= $course['sid'] ?>">
                                                                                <i class="fa fa-eye"></i>
                                                                                Preview Job
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="<?= base_url('add_listing_advertise') ?>/<?= $course['sid'] ?>">
                                                                                <i class="fa fa-random"></i> 
                                                                                Advertise this Job
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="<?= base_url('add_listing_share') ?>/<?= $course['sid'] ?>">
                                                                                <i class="fa fa-share-alt-square"></i> 
                                                                                Share this Job
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="<?= $session['company_detail']['per_job_listing_charge'] ? base_url('sponsor_listing') : base_url('clone_listing') ?>/<?= $course['sid'] ?>">
                                                                                <i class="fa fa-clone"></i> 
                                                                                Clone this Job
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </form>
                                    <?php } else { ?>
                                        <div class="no-job-found" style="background-color: transparent;">
                                            <ul>
                                                <li>
                                                    <h3 class="no-data">No Course found! </h3>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pagination-container" >
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php echo $links; ?>
                        </
                </div>
            </div>          
        </div>
    </div>
</div>

<div class="csModal" id="courseModal" style="display: none;">
    <div class="container-fluid">
        <div class="csModalHeader">
            <h3 class="csModalHeaderTitle">
                <span>
                Create a Course
                </span>
                <span class="csModalButtonWrap">
                    <button class="btn btn-orange btn-theme jsCreateTimeOffBTN dn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Create</button>
                    <button class="btn btn-black jsModalCancel" data-ask="no" title="Close this window">Cancel</button>
                </span>
                <div class="clearfix"></div>
            </h3>
        </div>
        <div class="csModalBody" style="top: 94.0057px;">
            <div class="csIPLoader jsIPLoader" data-page="addAdminModalLoader" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i></div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="arrow-links">
                        <ul>
                            <li style="width: 20%;" id="basic_section_tab" class="active">
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Basic</span>
                                    <div class="step-text">Getting Started</div>
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;" id="employee_section_tab">
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Employee(s)</span>
                                    <div class="step-text">Select Employee(s)</div>
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;" id="upload_section_tab">
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Upload</span>
                                    <div class="step-text">Upload Course</div>
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;" id="supporting_section_tab">
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Supporting Document</span>
                                    <div class="step-text">Add Supporting Document</div>
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;" id="visibility_section_tab">
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Visibility</span>
                                    <div class="step-text">Employee Visibility</div>
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 course_section" id="basic_section">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Title <span class="staric">*</span> <i class="fa fa-question-circle hit_info" data-id="info_title"></i></label>
                        <p id="info_title"><em>Course title is required.</em></p>
                        <input type="text" id="lms_title"  value="" class="invoice-fields">
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12">
                        <label>Start Date<span class="staric">*</span> <i class="fa fa-question-circle hit_info" data-id="info_start"></i></label>
                        <p id="info_start"><em>Course will be visible to employees on the selected date.</em></p>
                        <input type="text" name="lms_start_date" readonly value="" class="invoice-fields" id="lms_start_date">
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12">
                        <label>Would you like this course to expire after a certain period of time?</label>
                        <div>
                            <?php $default_check = isset($lms) ? '' : 'checked="checked"'; ?>

                            <label class="control control--radio">
                                <input type="radio" class="is_lms_expired" name="is_lms_expired" value="yes" <?php echo isset($lms) && $lms['is_lms_expired'] == 'yes' ? 'checked="checked"' : ''; ?>/> Yes &nbsp;
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio">
                                <input type="radio" class="is_lms_expired" name="is_lms_expired" value="no" <?php echo isset($lms) && $lms['is_lms_expired'] == 'no' ? 'checked="checked"' : $default_check; ?> /> No &nbsp;
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12">
                        <div class="hr-box" id="lms_expired_section" style="display: none;">
                            <div class="hr-box-header bg-header-green">
                                <span class="pull-left">
                                    <h1 class="hr-registered">Course Expiration Details</h1>
                                </span>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-sm-3 col-xs-12">
                                        <p style="margin-top: 10px;"><label>The Course will expire after</label></p>
                                    </div>
                                    <div class="col-sm-2 col-xs-12">
                                        <input type="text" name="expired_number" value="<?php echo isset($lms) ? $lms['expired_number'] : ''; ?>" class="form-control" id="expired_number" placeholder="2" /> 
                                    </div>
                                    <div class="col-sm-3 col-xs-12">
                                        <div class="hr-select-dropdown">
                                            <select class="form-control" name="expired_type" id="expired_type">
                                                <option <?php echo isset($lms) && $lms['expired_type'] == 'day' ? 'selected="selected"' : ''; ?> value="day">Days</option>
                                                <option <?php echo isset($lms) && $lms['expired_type'] == 'week' ? 'selected="selected"' : ''; ?> value="week">Weeks</option>
                                                <option <?php echo isset($lms) && $lms['expired_type'] == 'month' ? 'selected="selected"' : ''; ?> value="month">Months</option>
                                                <option <?php echo isset($lms) && $lms['expired_type'] == 'year' ? 'selected="selected"' : ''; ?> value="year">Years</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12">    
                        <div class="form-group">
                            <label>Course Status <i class="fa fa-question-circle hit_info" data-id="cource_status"></i></label>
                            <p id="cource_status"><em>Select any cource status.</em></p>
                            <div>
                                
                                <label class="control control--radio">
                                    <input type="radio" name="lma_status" value="1" checked="checked" /> Active &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio">
                                    <input type="radio" name="lma_status" value="0" /> Inactive &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12">    
                        <div class="form-group">
                            <button class="nextandsave" data-active="basic_section" data-next="employee_section"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save And Next</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 course_section" id="employee_section" style="display: none">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Select Employees <i class="fa fa-question-circle hit_info" data-id="info_select_employee"></i></label>
                        <p id="info_select_employee"><em>Select employee(s) to assign this course.</em></p>
                        <select name="selecte_employees[]" id="jsSelecctEmployees" multiple>
                        <?php 
                            //
                            $allowedOnes = empty($document_info['allowed_employees']) ? [] : explode(',', $document_info['allowed_employees']);
                            //
                            if(!empty($employeesList)){
                                foreach($employeesList as $v){
                                    ?>
                                    <option value="<?=$v['sid'];?>" <?=in_array($v['sid'], $allowedOnes) ? 'selected' : '';?>><?=remakeEmployeeName($v);?></option>
                                    <?php
                                }
                            }
                        ?>
                        </select>
                    </div>
                </div>    
                <br />
                <div class="row">
                    <div class="col-sm-12">    
                        <div class="form-group">
                            <button class="nextandsave" data-active="employee_section" data-next="upload_section"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save And Next</button>
                            <button class="previous_tab" data-active="employee_section" data-previous="basic_section"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 course_section" id="upload_section" style="display: none">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Browse Course <span class="staric">*</span> <i class="fa fa-question-circle hit_info" data-id="info_browse_doc"></i></label>
                        <p id="info_browse_doc"><em>Select course to upload.</em></p>
                        <input type="file" style="display: none;" id="jsFileUpload" />
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12">    
                        <div class="form-group">
                            <button class="nextandsave" data-active="upload_section" data-next="supporting_section"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save And Next</button>
                            <button class="previous_tab" data-active="upload_section" data-previous="employee_section"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 course_section" id="supporting_section" style="display: none">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="hr-box">
                            <div class="hr-box-header bg-header-green">
                                <span class="pull-left">
                                    <h1 class="hr-registered">Supporting Document</h1>
                                </span>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group autoheight">
                                            <label for="upload_title">Document Title :<span class="staric">*</span> <i class="fa fa-question-circle hit_info" data-id="info_supporting_title"></i></label>
                                            <p id="info_supporting_title"><em>Supporting document title required.</em></p>
                                            <input type="text" name="upload_doc_title" value="" class="form-control" id="upload_doc_title" >
                                        </div>
                                        <div class="form-group autoheight">
                                            <label>Document File:<span class="staric">*</span> <i class="fa fa-question-circle hit_info" data-id="info_supporting_doc"></i></label>
                                            <p id="info_supporting_doc"><em>Select supporting document to upload.</em></p>
                                            <div class="upload_learning_doc form-control upload-file">
                                                <span class="selected-file" id="name_learning_doc">No file selected</span>
                                                <input name="learning_doc" id="learning_doc" onchange="check_learning_doc('learning_doc')" type="file">
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                            <div id="file-upload-div" class="file-upload-box"></div>
                                            <div class="attached-files" id="uploaded-files" style="display: none;"></div>
                                            <div class="video-link" style="font-style: italic;">
                                                <b>Note.</b> Upload Multiple Documents One After Another
                                            </div>
                                            <div class="custom_loader">
                                                <div id="loader" class="loader" style="display: none">
                                                    <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                    <span>Uploading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12"> 
                        <div class="form-group autoheight table-responsive">
                            <table class="table table-bordered" id="attach_document_upload_status">
                                <thead>
                                    <tr>
                                        <th class="col-xs-5">Name</th>
                                        <th class="col-xs-3 text-center">Attached Date</th>
                                        <th class="col-xs-4 text-center" colspan="3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($attachments) && !empty($attachments)) { ?>
                                        <?php foreach ($attachments as $key => $document) { ?>
                                            <tr>
                                                <td><?php echo $document['upload_file_title']; ?></td>
                                                <td class="text-center"><?php echo  my_date_format($document['attached_date']); ?></td>
                                                <td class="text-center">                           
                                                    <a href="javascript:;" class="btn btn-block <?php echo $document['active'] == 0 ? 'btn-success' : 'btn-warning'; ?> btn-bg supporting_doc_status" src="<?php echo $document['active']; ?>" data="<?php echo $document['sid']; ?>" id="active-btn-<?php echo $document['sid']; ?>"><?php echo $document['active'] == 0 ? 'Activate' : 'De-Activate'; ?></a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?php echo base_url('learning_center/delete_attachment_document/'.$document['sid'].'/'.$lms_sid); ?>" class="btn btn-block btn-danger btn-bg" type="submit">Delete</a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:;" src="<?php echo base_url('learning_center/update_supporting_document/'.$document['sid'].'/'.$lms_sid); ?>" class="btn btn-block btn-info btn-bg update_supporting_doc" type="submit">Update</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>  
                    </div> 
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12">    
                        <div class="form-group">
                            <button class="nextandsave" data-active="supporting_section" data-next="visibility_section"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save And Next</button>
                            <button class="previous_tab" data-active="supporting_section" data-previous="upload_section"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 course_section" id="visibility_section" style="display: none">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>
                                <strong>Visibility</strong>&nbsp;<i class="fa fa-question-circle-o csClickable jsHintBtn" aria-hidden="true"  data-target="visibilty"></i>
                                <p class="jsHintBody" data-hint="visibilty"><br /><?=getUserHint('visibility_hint');?></p>
                            </h5>
                        </div>
                        <div class="panel-body">
                            <!-- Payroll -->
                            <label class="control control--checkbox">
                                Visible To Payroll
                                <input type="checkbox" name="visible_to_payroll" <?=isset($document_info['visible_to_payroll']) && $document_info['visible_to_payroll'] ? 'checked' : '';?> value="yes"/>
                                <div class="control__indicator"></div>
                            </label>
                            <hr />
                            <!-- Roles -->
                            <label>Roles</label>
                            <select name="roles[]" id="jsRoles" multiple>
                            <?php
                                //
                                $allowedOnes = empty($document_info['is_available_for_na']) ? [] : explode(',', $document_info['is_available_for_na']);
                                //
                                foreach(getRoles() as $k => $v){
                                    ?>
                                    <option value="<?=$k;?>" <?=in_array($k, $allowedOnes) ? 'selected' : '';?>><?=$v;?></option>
                                    <?php
                                }
                            ?>
                            </select>
                            <br />
                            <br />
                            <!-- Departments -->
                            <label>Departments</label>
                            <select name="departments[]" id="jsDepartments" multiple>
                            <?php 
                                //
                                $allowedOnes = empty($document_info['allowed_departments']) ? [] : explode(',', $document_info['allowed_departments']);
                                //
                                if(!empty($departments)){
                                    foreach($departments as $v){
                                        ?>
                                        <option value="<?=$v['sid'];?>" <?=in_array($v['sid'], $allowedOnes) ? 'selected' : '';?>><?=$v['name'];?></option>
                                        <?php
                                    }
                                }
                            ?>
                            </select>
                            <br />
                            <br />
                            <!-- Teams -->
                            <label>Teams</label>
                            <select name="teams[]" id="jsTeams" multiple>
                            <?php 
                                //
                                $allowedOnes = empty($document_info['allowed_teams']) ? [] : explode(',', $document_info['allowed_teams']);
                                //
                                if(!empty($teams)){
                                    foreach($teams as $v){
                                        ?>
                                        <option value="<?=$v['sid'];?>" <?=in_array($v['sid'], $allowedOnes) ? 'selected' : '';?>><?=$v['name'];?></option>
                                        <?php
                                    }
                                }
                            ?>
                            </select>
                            <br />
                            <br />
                            <!-- Employees -->
                            <label>Employees</label>
                            <select name="employees[]" id="jsEmployees" multiple>
                            <?php 
                                //
                                $allowedOnes = empty($document_info['allowed_employees']) ? [] : explode(',', $document_info['allowed_employees']);
                                //
                                if(!empty($employeesList)){
                                    foreach($employeesList as $v){
                                        ?>
                                        <option value="<?=$v['sid'];?>" <?=in_array($v['sid'], $allowedOnes) ? 'selected' : '';?>><?=remakeEmployeeName($v);?></option>
                                        <?php
                                    }
                                }
                            ?>
                            </select>
                           
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-sm-12">    
                        <div class="form-group">
                            <button class="save_visibility" data-active="employee_section"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save and Finish</button>
                            <button class="previous_tab" data-active="visibility_section" data-previous="supporting_section"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div id="my_document_loader" class="my_document_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            Please wait while Updating Supporting Document
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?=base_url('assets/mFileUploader/index.css');?>" />
<script src="<?=base_url('assets/mFileUploader/index.js');?>"></script>
<style>
    #js-fmla-modal .btn-info{ background-color: #81b431 !important; border-color: #81b431 !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow{ top: 50% !important; transform: translateY(-50%); }
</style>

<script>
    $(function(){
        //
        var config = { closeOnSelect: false };
        //
        $('#jsRoles').select2(config);
        $('#jsDepartments').select2(config);
        $('#jsTeams').select2(config);
        $('#jsSelecctEmployees').select2(config);
        $('#jsEmployees').select2(config);
        $('#jsManagers').select2(config);

        $('#lms_start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date()
        });
    });

    $("#add_course_btn").on('click', function(){
        $("#courseModal").modal('show');
    });

    $(".jsModalCancel").on('click', function(){
        $("#courseModal").modal('hide');
    });

    $(".nextandsave").on('click', function(){
        var active_tab = $(this).data('active');
        var next_tab = $(this).data('next');

        $("#"+active_tab+"_tab").removeClass('active');
        $("#"+next_tab+"_tab").addClass('active');

        console.log("#"+next_tab+"_tab")
        
        $(".course_section").hide();
        $("#"+next_tab).show();
    });

    $(".previous_tab").on('click', function(){
        var active_tab = $(this).data('active');
        var previous_tab = $(this).data('previous');

        $("#"+active_tab+"_tab").removeClass('active');
        $("#"+previous_tab+"_tab").addClass('active');
        
        $(".course_section").hide();
        $("#"+previous_tab).show();
    });

    $(".hit_info").on('click', function(){
        var pid = $(this).data('id');
        $("#"+pid).toggle();
    });

    $('#jsFileUpload').mFileUploader({
        fileLimit: -1,
        allowedTypes: ['zip'],
        text: 'Click / Drag zip file to upload',
        <?php if (isset($document_info['uploaded_document_s3_name']) && $document_info['uploaded_document_s3_name'] != "") { ?>
            placeholderImage: "<?=$document_info['uploaded_document_s3_name'];?>"
        <?php } ?>
    });

    

    $(".is_lms_expired").on("click",function(){
        var video_expired = $('input[name="is_lms_expired"]:checked').val();
        if (video_expired == "yes") {
            $("#lms_expired_section").show();
        } else {
            $("#lms_expired_section").hide();
        }
    });

    $("#save_lms_course").on("click", function () {
        var title = $("#lms_title").val();
        var title = $("#lms_title").val();
    });

    function check_learning_doc (val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            
            if (val == 'learning_doc') {
                if (ext != "PDF" && ext != "pdf" && ext != "docx" && ext != "xlsx") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid document format.");
                    $('#name_' + val).html('<p class="red">Only (PDF, Word, Excel) files are allowed!</p>');
                    return false;
                } else {
                    var selected_file = fileName;
                    var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                    $('#name_' + val).html(original_selected_file);
                    $('.upload_learning_doc').hide();
                    $('#uploaded-files').hide();
                    $('#file-upload-div').append('<div class="form-control upload-file"><span class="selected-file" id="name_learning_doc">'+original_selected_file+'</span><div class="pull-right btn-upload-filed"><input class="submit-btn btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"><input class="submit-btn btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"></div></div>');
                }
            }
        } else {
            $('#name_' + val).html('No document selected');
            alertify.error("No document selected");
            $('#name_' + val).html('<p class="red">Please select document</p>');
        }   
    }

    function CancelUpload(){
        $('.upload_learning_doc').show();
        
        if($('#uploaded-files').html() != ''){
            $('#uploaded-files').show();
        }
        
        $('#file-upload-div').html("");
        $('#name_learning_doc').html("No file selected");
    }

    function DoUpload(){
        var file_data = $('#learning_doc').prop('files')[0];
        var fileName = $('#learning_doc').val();
        var file_ext = fileName.split('.').pop();
        var file_title = $('#upload_doc_title').val();

        if (file_title == '') {
            alertify.alert("Notice", "Please Enter Upload Document Title");
        } else {
            var form_data = new FormData();
            form_data.append('docs', file_data);
            form_data.append('company_sid', <?php echo $company_sid; ?>);
            form_data.append('video_sid', video_sid);
            form_data.append('employer_sid', <?php echo $employer_sid; ?>);
            form_data.append('upload_title', file_title);
            form_data.append('file_ext', file_ext);
            form_data.append('action', 'add_attachment');

            $('#loader').show();
            $('#upload').addClass('disabled-btn');
            $('#upload').prop('disabled', true);
            $.ajax({
                url: '<?= base_url('learning_center/LMSHanduler');?>',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(return_data_array){
                    // var obj = jQuery.parseJSON(return_data_array);
                    var obj = return_data_array;
                    var Title = obj.upload_file_title;
                    var attach_date = obj.attached_date;
                    var delete_url = obj.delete_url;
                    var update_url = obj.update_url;
                    var document_sid = obj.document_sid;
                    var active_btn = obj.active_btn;
                   
                    $('#loader').hide();
                    $('#upload').removeClass('disabled-btn');
                    $('#upload').prop('disabled', false);
                    alertify.success('New document has been uploaded');
                    $('.upload_learning_doc').show();
                    $('#uploaded-files').show();
                    $('#attach_document_upload_status tr:last').after('<tr><td>'+Title+'</td><td class="text-center">'+attach_date+'</td><td class="text-center"><a href="javascript:;" class="btn btn-warning btn-bg btn-block supporting_doc_status" src="0" data="'+document_sid+'" id="'+active_btn+'">De-Activate</a></td><td class="text-center"><a href="'+delete_url+'" class="btn btn-danger btn-bg btn-block" type="submit">Delete</a></td><td class="text-center"><a href="javascript:;" class="btn btn-info btn-bg btn-block update_supporting_doc" src="'+update_url+'" type="submit">Update</a></td></td></tr>');
                    $('#file-upload-div').html("");
                    $('#upload_doc_title').val("");
                    $('#video_sid').val(obj.video_sid);
                    $('#name_learning_doc').html("No file selected");
                },
                error: function(){
                }
            });
        }
        
    }

    $(document).on('click', '#save_document_updates', function() {
        var perform_action = $('#perform_action').val();
        var document_sid = $('#update_document_sid').val();
        var video_sid = $('#update_video_sid').val();
        var file_data = $('#learning_doc_edit').prop('files')[0];
        var fileName = $('#learning_doc_edit').val();
        var file_ext = fileName.split('.').pop();
        var file_title = $('#upload_doc_title_edit').val();
        var file_status = $('input[name="edit_doc_status"]:checked').val();
        
        if (file_title == '') {
            alertify.error("Please Enter Upload Document Title");
        } else {
            var update_url = '<?php echo base_url('learning_center/update_supporting_document'); ?>'+'/'+document_sid+'/'+video_sid;
            var form_data = new FormData();
            form_data.append('perform_action', perform_action);
            form_data.append('document_sid', document_sid);
            form_data.append('video_sid', video_sid);
            form_data.append('docs', file_data);
            form_data.append('upload_title', file_title);
            form_data.append('file_ext', file_ext);
            form_data.append('status', file_status);
            form_data.append('company_sid', <?php echo $company_sid; ?>);
            $('#my_document_loader').show();

            // $.ajax({
            //     url: update_url,
            //     cache: false,
            //     contentType: false,
            //     processData: false,
            //     type: 'post',
            //     data: form_data,
            //     success: function(return_data_array){
            //         alertify.success('Supporting Document Update Successfully.');
            //         location.reload();
            //     },
            //     error: function(){
            //     }
            // });
        }
    });
    
</script>
