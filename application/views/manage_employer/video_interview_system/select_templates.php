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
                            <a class="dashboard-link-btn" href="<?php echo base_url('video_interview_system/send') . '/' . $applicant_sid . '/' . $job_list_sid; ?>">
                                <i class="fa fa-chevron-left"></i>
                                Back
                            </a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="row" style="margin-top: 10px;">
                            <!-- table -->
                            <div class="table-responsive table-outer">
                                <?php if (!empty($templates)) { ?>
                                        <div class="table-wrp mylistings-wrp border-none">
                                            <table class="table table-bordered table-stripped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-5">Template Title</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                        <th class="text-center">Actions</th>
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($templates as $template) { ?>
                                                        <tr 
                                                            id="<?php echo 'q_' . $template['sid']; ?>"
                                                            style="<?php echo (($template['status'] == 'inactive') ? 'background-color: #f7f7f7 !important;color:#999;' : ''); ?>">
                                                            <td>
                                                                <?php echo ucwords($template['title']); ?>
                                                            </td>
                                                            <td 
                                                                id='<?php echo 'r_' . $template['sid']; ?>' 
                                                                class="<?php echo ($template['status'] == 'active') ? 'green' : 'red'; ?>"
                                                                style="<?php echo (($template['status'] == 'inactive') ? 'color:#999;' : ''); ?>">
                                                                <?php echo ucwords($template['status']); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo date_with_time($template['created_date']); ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a 
                                                                    href="<?php 
                                                                    if ($template['status'] == 'active') { 
                                                                        echo base_url('video_interview_system/send/' . $applicant_sid . '/' . $job_list_sid . '/' . $template['sid']); 
                                                                    } else { 
                                                                        echo  '#'; 
                                                                    } ?>" 
                                                                    class="btn btn-success"
                                                                    style="<?php echo (($template['status'] == 'inactive') ? 'background-color:#999 !important;border:1px solid #999 !important' : ''); ?>">
                                                                    Select Questions
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>                                            
                                                </tbody>
                                            </table>
                                        </div>
                                <?php } else { ?>
                                    <div class="no-data">No Question Templates found!</div>
                                <?php } ?>                        
                            </div>
                            <!-- table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>