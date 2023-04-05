<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">       
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        
                                            <?php if(check_access_permissions_for_view($security_details, 'create_course')) { ?>
                                                <a class="btn btn-success pull-right" id="jsAssignCourses" href="javascript:;">Assign Courses</a>
                                            <?php } ?>
                                    </div>
                                
                                    <div class="table-responsive hr-promotions">
                                        <div class="hr-displayResultsTable">
                                            <br>
                                            <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-9">Course Title</th>
                                                            <th class="col-xs-3 text-center">Actions</th>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php if(sizeof($courses) > 0) { ?>
                                                        <?php foreach ($courses as $course) { ?>
                                                            <tr id='<?php echo $course['sid']; ?>'>
                                                                <td>
                                                                    <?php echo $course['title']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($course['status'] == 1) { ?>
                                                                        <a href="javascript:;" class="btn btn-warning btn-sm deactive_course" id="<?php echo $course['sid']; ?>" title="Disable Course" data-attr="<?php echo $course['sid']; ?>">
                                                                            <i class="fa fa-ban"></i>
                                                                        </a>
                                                                    <?php   } else { ?>
                                                                        <a href="javascript:;" class="btn btn-success btn-sm active_course" id="<?php echo $course['sid']; ?>" title="Enable Course" data-attr="<?php echo $course['sid']; ?>">
                                                                            <i class="fa fa-shield"></i>
                                                                        </a>
                                                                    <?php   } ?>

                                                                    <?php if(check_access_permissions_for_view($security_details, 'manage_course')) { ?>
                                                                        <a href="<?php echo base_url() . 'manage_admin/preview_course/' . $course['sid']; ?>" class="btn btn-info btn-sm" title="Preview Course">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                    <?php } ?>

                                                                    <?php if(check_access_permissions_for_view($security_details, 'manage_course')) { ?>
                                                                        <a class="btn btn-success btn-sm" href="javascript:;">
                                                                            Quick Edit
                                                                        </a>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan='6'>No Courses found.</td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>

                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="field-row">
                                                <label></label>
                                                <label class="control control--checkbox">
                                                    Can Create Course  <small class="text-success">( Check this checkbox if the company can create its own courses.)</small>
                                                    <input class="jsCanCreateCourse" id="jsCanCreateCourse" name="can_create_courses" value="1" type="checkbox">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/SystemModel.css")?>">
<script type="text/javascript" src="<?php echo base_url("assets/js/SystemModal.js")?>"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data.js"></script>

<script type="text/javascript">
    $("#jsAssignCourses").on('click', function () {
        var dtm =  '2023-04-04 21:50:17';
        var timeZone =  Intl.DateTimeFormat().resolvedOptions().timeZone;

        console.log("The timezone is "+timeZone);
        console.log("The DB time is "+dtm);

        // Modal({
        //     Id: 'addModal',
        //     Title: `Assign Courses`,
        //     Body: '',
        //     Buttons: [
        //         '<button class="btn btn-success jsAssignCourseBTN">Assign</button>'
        //     ],
        //     Loader: 'addModalLoader',
        //     Ask: false
        // });
    });
</script>