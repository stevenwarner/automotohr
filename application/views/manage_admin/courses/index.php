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
                                                <a class="btn btn-success pull-right" href="<?php echo base_url() . 'manage_admin/add_course' ?>">Add a New Course</a>
                                            <?php } ?>
                                    </div>
                                
                                    <div class="table-responsive hr-promotions">
                                        <div class="hr-displayResultsTable">
                                            <br>
                                            <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-6">Course Title</th>
                                                            <th class="text-center col-xs-6">Actions</th>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php if(sizeof($courses) > 0) { ?>
                                                        <?php foreach ($courses as $course) { ?>
                                                            <tr id='<?php echo $course['sid']; ?>'>
                                                                <td>
                                                                    <?php 
                                                                        $version = $course['type'] == 'Scorm' ? ' ['.$course['version'].']': '';

                                                                        echo $course['title'].' ('.$course['type'].')'.$version; 
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($course['status'] == 1) { ?>
                                                                        <a href="javascript:;" class="btn btn-danger btn-sm deactive_course" id="<?php echo $course['sid']; ?>" data-toggle="tooltip" data-placement="top" data-original-title="Disable Course" data-attr="<?php echo $course['sid']; ?>">
                                                                            <i class="fa fa-ban"></i>
                                                                        </a>
                                                                    <?php   } else { ?>
                                                                        <a href="javascript:;" class="btn btn-success btn-sm active_course" id="<?php echo $course['sid']; ?>" data-container="body" data-toggle="tooltip" data-placement="top" data-original-title="Enable Course" data-attr="<?php echo $course['sid']; ?>">
                                                                            <i class="fa fa-shield"></i>
                                                                        </a>
                                                                    <?php   } ?>

                                                                    <?php if(check_access_permissions_for_view($security_details, 'manage_course')) { ?>
                                                                        <a href="<?php echo base_url() . 'manage_admin/edit_course/' . $course['sid']; ?>" class="btn btn-success btn-sm" title="Edit Course">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                    <?php } ?>

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

                                                                    <?php if(check_access_permissions_for_view($security_details, 'manage_course')) { ?>
                                                                        <a class="btn btn-success btn-sm" href="javascript:;">
                                                                            Bulk Assign
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

                                    <br>

                                    <?php if(!empty($standard_companies)) { ?>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="heading-title">
                                                    <h1 class="page-title"></h1>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="table-responsive">
                                                    <table class="table table-stripped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left col-xs-4">Company Name</th>
                                                                <th class="text-center col-xs-2">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($standard_companies as $company) { ?>
                                                                <tr>
                                                                    <td><?php echo ucwords($company['CompanyName']); ?></td>
                                                                    <?php if(check_access_permissions_for_view($security_details, 'manage_executive_admins')) { ?>
                                                                        <td>
                                                                            <a class="btn btn-success btn-sm" href="<?php echo base_url() . 'manage_admin/manage_course/' . $company['sid']; ?>">
                                                                                Manage
                                                                            </a>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>