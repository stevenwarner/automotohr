<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view');?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('learning_center/online_videos'); ?>">
                                        <i class="fa fa-chevron-left"></i>
                                        Back
                                    </a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="my_loader" class="text-center my_loader" style="display: none;">
                        <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
                        <div class="loader-icon-box">
                            <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                            <div class="loader-text" style="display:block; margin-top: 35px;">
                                <?php echo VIDEO_LOADER_MESSAGE; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-wrp">
                        <form id="form_save_video" method="post" enctype="multipart/form-data" >
                            <input type="hidden" id="perform_action" name="perform_action" value="save_video_training_info" />
                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                            <input type="hidden" id="video_sid" name="video_sid" value="<?php echo isset($video_sid) ? $video_sid : 0; ?>"
                             />
                            <!-- <input type="hidden" id="video_id" name="video_url" value="" /> -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> 
                                    
                                    <div class="form-group autoheight">
                                        <?php $field_name = 'video_title' ?>
                                        <?php $temp = isset($video[$field_name]) && !empty($video[$field_name]) ? $video[$field_name] : ''; ?>
                                        <?php echo form_label('Video Title <span class="staric">*</span>', $field_name); ?>
                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control" id="' . $field_name . '" data-rule-required="true"'); ?>
                                        <?php echo form_error($field_name); ?>
                                    </div>
                                    <div class="form-group autoheight">
                                        <?php $field_name = 'video_description' ?>
                                        <?php $temp = isset($video[$field_name]) && !empty($video[$field_name]) ? $video[$field_name] : ''; ?>
                                        <?php echo form_label('Video Description', $field_name); ?>
                                        <?php echo form_textarea($field_name, set_value($field_name, $temp), 'class="form-control autoheight" id="' . $field_name . '"'); ?>
                                        <?php echo form_error($field_name); ?>
                                    </div>
                                    <?php if (isset($video_source)) { ?>
                                        <div class="form-group video_preview autoheight">
                                            <label>Video Preview </label>
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <?php if($video['video_source'] == 'youtube') { ?>
                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $video_url ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                <?php } elseif($video['video_source'] == 'vimeo') { ?>
                                                    <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $video_url ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                <?php } else {?>
                                                    <video controls style="width:100%; height:auto;">
                                                        <source src="<?php echo $video_url ?>" type='video/mp4'>
                                                    </video>
                                                <?php } ?>
                                            </div>
                                        </div>  
                                    <?php } ?>
                                    <?php if (isset($video_source)) { ?>
                                        <div class="form-group edit_filter_check autoheight">
                                            <label class="control control--radio" style="margin-left:0px; margin-top:10px;">
                                                Change Video Source
                                                <input class="" type="radio" id="change_video_source" name="want_change"/>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    <?php } ?>
                                    <div class="radio_btn_video_source">
                                            <div class="form-group edit_filter autoheight">
                                            <?php $field_name = 'video_source' ?>
                                            <?php $source = isset($video[$field_name]) && !empty($video[$field_name]) ? $video[$field_name] : 'youtube'; ?>
                                            <?php $temp = isset($video[$field_name]) && !empty($video[$field_name]) ? $video[$field_name] : 'youtube'; ?>
                                            <?php echo form_label('Video Source', $field_name); ?>
                                            <?php $default_selected = $temp == 'youtube' ? true : false; ?>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                <?php echo YOUTUBE_VIDEO; ?>
                                                <input checked="checked" class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube" <?php echo set_radio($field_name, 'youtube', $default_selected); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <?php $default_selected = $temp == 'vimeo' ? true : false; ?>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                <?php echo VIMEO_VIDEO; ?>
                                                <input class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo" <?php echo set_radio($field_name, 'vimeo', $default_selected); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <?php $default_selected = $temp == 'upload' ? true : false; ?>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                <?php echo UPLOAD_VIDEO; ?>
                                                <input class="video_source" type="radio" id="video_source_upload" name="video_source" value="upload" <?php echo set_radio($field_name, 'upload', $default_selected); ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <?php if ($this->uri->segment(2) == 'edit_online_video') { ?>
                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                    Do Not Change
                                                    <input class="video_source" type="radio" id="do_not_change" name="video_source" value="do_not_change" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <?php if (isset($video_source)) { 
                                        if ($video_source == 'upload') {?>
                                            <div class="radio_video_source_upload">
                                                <div class="form-group autoheight edit_filter" id="up_video_container">
                                                    <label>Upload Video <span class="staric">*</span></label>
                                                    <?php 
                                                        if (!empty($video['video_id']) && $video['video_source'] == 'uploaded') {
                                                    ?>
                                                            <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="<?php echo $video['video_id']; ?>">
                                                    <?php        
                                                        } else {
                                                    ?>
                                                        <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="">
                                                    <?php        
                                                        }
                                                    ?>
                                                    <div class="upload-file form-control">
                                                        <span class="selected-file" id="name_video"></span>
                                                        <input type="file" name="video_upload" id="video" onchange="check_file('video')" >
                                                        <a href="javascript:;">Choose Video</a>
                                                    </div>
                                                </div>
                                                <div class="form-group autoheight edit_filter" id="yt_vm_video_container">
                                                    <?php $field_name = 'video_id' ?>
                                                    <?php echo form_label('Video Url <span class="staric">*</span>', $field_name); ?>
                                                    <?php echo form_input($field_name, set_value($field_name, ''), 'class="form-control" id="' . $field_name . '" data-rule-required="true"'); ?>
                                                    <?php echo form_error($field_name); ?>
                                                </div>
                                            </div>
                                            <?php if ($this->uri->segment(2) == 'edit_online_video') {?>
                                                    <input type="hidden" id="old_upload_video" name="old_upload_video" value="<?php echo $old_upload_video; ?>">  
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="radio_video_source_links">
                                                <div class="form-group autoheight edit_filter" id="yt_vm_video_container">
                                                    <?php $field_name = 'video_id' ?>
                                                    <?php echo form_label('Video Url <span class="staric">*</span>', $field_name); ?>
                                                    <?php echo form_input($field_name, set_value($field_name, ''), 'class="form-control" id="' . $field_name . '" data-rule-required="true"'); ?>
                                                    <?php echo form_error($field_name); ?>
                                                </div>
                                                <div class="form-group autoheight edit_filter" id="up_video_container">
                                                    <label>Upload Video <span class="staric">*</span></label>
                                                    <div class="upload-file form-control">
                                                        <span class="selected-file" id="name_video"></span>
                                                        <input type="file" name="video_upload" id="video" onchange="check_file('video')" >
                                                        <a href="javascript:;">Choose Video</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }  ?>   
                                    <?php } else { ?>
                                        <div class="form-group autoheight" id="yt_vm_video_container">
                                            <?php $field_name = 'video_id' ?>
                                            <?php echo form_label('Video Url <span class="staric">*</span>', $field_name); ?>
                                            <?php echo form_input($field_name, set_value($field_name, $video_url), 'class="form-control" id="' . $field_name . '" data-rule-required="true"'); ?>
                                            <?php echo form_error($field_name); ?>
                                        </div>
                                        <div class="form-group autoheight" id="up_video_container">
                                            <label>Upload Video <span class="staric">*</span></label>
                                            <div class="upload-file form-control">
                                                <span class="selected-file" id="name_video">No video selected</span>
                                                <input type="file" name="video_upload" id="video" onchange="check_file('video')" <?php echo set_value($video_url); ?> >
                                                <a href="javascript:;">Choose Video</a>
                                            </div>
                                        </div>
                                     <?php }  ?>   
                                    
                                    <div class="form-group autoheight">
                                    <?php $field_name = 'employees_assigned_to' ?>
                                    <?php $temp = isset($video[$field_name]) && !empty($video[$field_name]) ? $video[$field_name] : 'all'; ?>
                                    <?php echo form_label('Assigned To Employees', $field_name); ?>
                                    <?php $default_selected = $temp == 'all' ? true : false; ?>
                                    <?php $temp = empty($selected_employees) ? "none" : $temp; ?>
                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                            All
                                            <input class="employees_assigned_to" type="radio" id="employees_assigned_to_all" name="employees_assigned_to" value="all" <?php echo ($temp == 'all') ?  "checked" : "" ;  ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                    <?php $default_selected = $temp == 'specific' ? true : false; ?>
                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                            Specific
                                            <input class="employees_assigned_to" type="radio" id="employees_assigned_to_specific" name="employees_assigned_to" value="specific" <?php echo ($temp == 'specific') ?  "checked" : "" ;  ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                            None
                                            <input class="employees_assigned_to" type="radio" id="employees_assigned_to_none" name="employees_assigned_to" value="none" <?php echo ($temp == 'none') ?  "checked" : "" ;  ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    
                                    <!--<input class="employees_assigned_to" type="hidden" id="employees_assigned_to_specific" name="employees_assigned_to" value="specific"  />-->
                                    
                                    <!-- <div class="form-group autoheight assign_option">
                                        <?php $field_name = 'employees_assigned_sid'; $selected_employees = $temp == 'all' ? array() : $selected_employees; ?>
                                        <?php echo form_label('Assigned To Employees', $field_name); ?>
                                        <div class="hr-select-dropdown">
                                            <select data-rule-required="false" class="" name="employees_assigned_sid[]" id="employees_assigned_sid" multiple="multiple" >
                                                <option value="">Please Select</option>
                                                <?php if (!empty($employees)) { ?>
                                                    <?php foreach ($employees as $employee) { ?>
                                                        <option <?php echo set_select($field_name, $employee['sid'], in_array($employee['sid'], $selected_employees)); ?>  value="<?php echo $employee['sid']; ?>" ><?=remakeEmployeeName($employee);?></option>
                                                    <?php } ?>
                                                <?php } ?>      

                                   

                                    <div class="form-group autoheight assign_option">
                                        <label>Assigned To Employees<span class="staric">*</span></label>
                                        <div class="">
                                            <select name="employees_assigned_sid[]" class="invoice-fields" id="employees_assigned_sid" multiple="true">
                                                <?php foreach ($employees as $employee): ?>
                                                    <option <?php echo set_select($field_name, $employee['sid'], in_array($employee['sid'], $selected_employees)); ?>  value="<?php echo $employee['sid']; ?>" ><?=remakeEmployeeName($employee);?></option>
                                                <?php endforeach ?>

                                            </select>
                                            <span id="employees_assigned_error" class="text-danger person_error"></span>
                                        </div>
                                    </div> -->

                                    <div class="form-group autoheight assign_option">
                                        <label>Assigned To Employees<span class="staric">*</span></label>
                                        <div class="">
                                            <select name="employees_assigned_sid[]" class="invoice-fields" id="employees_assigned_sid" multiple="true">
                                                <?php foreach ($employees as $employee): ?>
                                                    <option <?php echo set_select($field_name, $employee['sid'], in_array($employee['sid'], $selected_employees)); ?>  value="<?php echo $employee['sid']; ?>" ><?=remakeEmployeeName($employee);?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <span id="employees_assigned_error" class="text-danger person_error"></span>
                                        </div>
                                    </div> 

                                    <!--<input class="employees_assigned_to" type="hidden" id="employees_assigned_to_specific" name="employees_assigned_to" value="specific"  />-->
                                    
                                    <div class="form-group autoheight assign_option">
                                        <?php $field_name = 'departments_assigned_sid';?>
                                        <?php $selected_departments = isset($selected_departments) ? ($selected_departments[0] == 'all' ? array('-1') : $selected_departments) : array(); ?>
                                        <?php echo form_label('Assigned To Departments', $field_name); ?>
                                        <div class="hr-select-dropdown">
                                            <select class="" name="departments_assigned_sid[]" id="departments_assigned_sid" multiple="multiple" >
                                                <option value="">Please Select</option>
                                                <?php 
                                                    // Push all to department
                                                    $departments[] = array('sid' => -1, 'name'=> 'All'); 
                                                    // Resort the array
                                                    asort($departments);
                                                ?>
                                                <?php if (!empty($departments)) { ?>
                                                    <!-- <option value="-1">All</option> -->
                                                    <?php foreach ($departments as $department) { ?>
                                                        <option <?php echo set_select(
                                                            $field_name, $department['sid'], array_search($department['sid'], $selected_departments) !== false ? true : false); ?>  value="<?php echo $department['sid']; ?>" ><?=$department['name'];?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="form-group autoheight">
                                        <?php $video_start_date = isset($video) && !empty($video['video_start_date']) ? date('d-m-Y', strtotime($video['video_start_date'])) : ''; ?>
                                        <label>Video Start Date<span class="staric">*</span></label>
                                        <input type="text" name="video_start_date" value="<?php echo $video_start_date; ?>" class="form-control" id="video_start_date">
                                    </div> 

                                    <div class="form-group">
                                        <label>Would you like this video to expire after a certain period of time?</label>
                                        <div>
                                            <?php $default_check = isset($video) ? '' : 'checked="checked"'; ?>
                                            <br />
                                            <label class="control control--radio">
                                                <input type="radio" class="is_video_expired" name="is_video_expired" value="yes" <?php echo isset($video) && $video['is_video_expired'] == 'yes' ? 'checked="checked"' : ''; ?>/> Yes &nbsp;
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio">
                                                <input type="radio" class="is_video_expired" name="is_video_expired" value="no" <?php echo isset($video) && $video['is_video_expired'] == 'no' ? 'checked="checked"' : $default_check; ?> /> No &nbsp;
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="hr-box" id="video_expired_section" style="display: none;">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Video Expiration Details</h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="form-group autoheight">

                                                        <label for="upload_title">Number :<span class="staric">*</span></label>
                                                        <input type="text" name="expired_number" value="<?php echo isset($video) ? $video['expired_number'] : ''; ?>" class="form-control" id="expired_number" >
                                                    </div>
                                                    <div class="form-group autoheight">
                                                        <label>Expiration Type:<span class="staric">*</span></label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="form-control" name="expired_type" id="expired_type">
                                                                <option value="0">Please Select type</option>
                                                                <option <?php echo isset($video) && $video['expired_type'] == 'day' ? 'selected="selected"' : ''; ?> value="day">Day</option>
                                                                <option <?php echo isset($video) && $video['expired_type'] == 'week' ? 'selected="selected"' : ''; ?> value="week">Week</option>
                                                                <option <?php echo isset($video) && $video['expired_type'] == 'month' ? 'selected="selected"' : ''; ?> value="month">Month</option>
                                                                <option <?php echo isset($video) && $video['expired_type'] == 'year' ? 'selected="selected"' : ''; ?> value="year">Year</option>
                                                            </select>
                                                        </div>    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($screening_questions)) { ?>
                                        <div class="form-group">
                                            <label>Learning Management Questionnaire:</label>
                                            <div class="hr-select-dropdown">
                                                <select class="form-control" name="questionnaire_sid" id="questionnaire_sid">
                                                    <option value="">Select Screening Questionnaire</option>
                                                    <?php foreach ($screening_questions as $screening_question) { ?>
                                                        <option <?php echo set_select('questionnaire_sid', $screening_question['sid'], $screening_question['sid'] == $questionnaire_sid); ?> value="<?php echo $screening_question['sid']; ?>"><?php echo $screening_question['caption']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="form-group">
                                        <label>Would you like to Send a Notification Email?</label>
                                        <div>
                                            <br />
                                            <label class="control control--radio">
                                                <input type="radio" name="send_email" value="yes" /> Yes &nbsp;
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio">
                                                <input type="radio" name="send_email" value="no" checked="false" /> No &nbsp;
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                   
                                    <!----><?php //if ($this->uri->segment(2) == 'edit_online_video') { ?>
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
                                                            <label for="upload_title">Document Title :<span class="staric">*</span></label>
                                                            <input type="text" name="upload_doc_title" value="" class="form-control" id="upload_doc_title" >
                                                        </div>
                                                        <div class="form-group autoheight">
                                                            <label>Document File:<span class="staric">*</span></label>
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
                                                    <?php 
                                                        foreach ($attachments as $key => $document) { ?>
                                                        <tr>
                                                            <td><?php echo $document['upload_file_title']; ?></td>
                                                            <td class="text-center"><?php echo  my_date_format($document['attached_date']); ?></td>
                                                            <td class="text-center">                           
                                                                <a href="javascript:;" class="btn btn-block <?php echo $document['active'] == 0 ? 'btn-success' : 'btn-warning'; ?> btn-bg supporting_doc_status" src="<?php echo $document['active']; ?>" data="<?php echo $document['sid']; ?>" id="active-btn-<?php echo $document['sid']; ?>"><?php echo $document['active'] == 0 ? 'Activate' : 'De-Activate'; ?></a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="<?php echo base_url('learning_center/delete_attachment_document/'.$document['sid'].'/'.$video_sid); ?>" class="btn btn-block btn-danger btn-bg" type="submit">Delete</a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="javascript:;" src="<?php echo base_url('learning_center/update_supporting_document/'.$document['sid'].'/'.$video_sid); ?>" class="btn btn-block btn-info btn-bg update_supporting_doc" type="submit">Update</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <!----><?php //} ?>
                                    <!--
                                    <li class="form-col-100 autoheight">
                                    <?php $field_name = 'applicants_assigned_to' ?>
                                    <?php $temp = isset($video[$field_name]) && !empty($video[$field_name]) ? $video[$field_name] : 'all'; ?>
                                    <?php echo form_label('Assigned To Onboarding Employees', $field_name); ?>
                                    <?php $default_selected = $temp == 'all' ? true : false; ?>
                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                            All
                                            <input class="applicants_assigned_to" type="radio" id="applicants_assigned_to_all" name="applicants_assigned_to" value="all" <?php echo set_radio($field_name, 'all', $default_selected); ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                    <?php $default_selected = $temp == 'specific' ? true : false; ?>
                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                            Specific
                                            <input class="applicants_assigned_to" type="radio" id="applicants_assigned_to_specific" name="applicants_assigned_to" value="specific" <?php echo set_radio($field_name, 'specific', $default_selected); ?> />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </li>
                                    -->
                                    <input class="applicants_assigned_to" type="hidden" id="applicants_assigned_to" name="applicants_assigned_to" value="specific" />
                                    <!--
                                    <li class="form-col-100 autoheight">
                                    <?php $field_name = 'applicants_assigned_sid' ?>
                                    <?php echo form_label('Assigned To Onboarding Employees', $field_name); ?>
                                    <?php $temp = isset($video[$field_name]) && !empty($video[$field_name]) ? unserialize($video[$field_name]) : array(); ?>
                                        <div class="hr-select-dropdown">
                                            <select data-rule-required="true" class="" name="applicants_assigned_sid[]" id="applicants_assigned_sid" multiple="multiple" >
                                                <option value="">Please Select</option>
                                    <?php if (!empty($applicants)) { ?>
                                        <?php foreach ($applicants as $applicant) { ?>
                                                                <option <?php echo set_select($field_name, $applicant['sid'], in_array($applicant['sid'], $selected_applicants)); ?> value="<?php echo $applicant['sid']; ?>" ><?php echo $applicant['first_name'] . ' ' . $applicant['last_name'] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                            </select>
                                        </div>
                                    </li>
                                    -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                    <button id="add_edit_submit" class="btn btn-success" type="submit">Save Video</button>
                                    <a href="<?php echo base_url('learning_center/online_videos'); ?>" class="btn black-btn" type="submit">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php //if ($this->uri->segment(2) == 'edit_online_video') { ?>
    <div id="update_supporting_document_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-header-green">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Supported Document</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="perform_action" name="perform_action" value="update_supported_document" />
                    <input type="hidden" id="update_document_sid" value="" />
                    <input type="hidden" id="update_video_sid" value="" />
                    <div class="row">
                        <div class="col-xs-12 universal-form-style-v2">
                            <ul>
                                <li class="form-col-100 autoheight">
                                    <label for="upload_title">Document Title :<span class="staric">*</span></label>
                                    <input type="text" value="" class="form-control" id="upload_doc_title_edit" >
                                </li>
                                <li class="form-col-100 autoheight ">
                                    <label>Document File:</label>
                                    <div class="upload_learning_doc form-control upload-file">
                                        <span class="selected-file" id="name_learning_doc_edit">No file selected</span>
                                        <input name="learning_doc_edit" id="learning_doc_edit" onchange="check_learning_doc_edit('learning_doc_edit')" type="file">
                                        <a href="javascript:;">Choose File</a>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <label>Document Status</label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        Activated
                                        <input type="radio" id="sup_doc_active" name="edit_doc_status" value="0"/>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        De-Activated
                                        <input type="radio" id="sup_doc_deactive" name="edit_doc_status" value="1" />
                                         <div class="control__indicator"></div>
                                    </label>
                                </li>
                                <li class="form-col-100 autoheight text-right">
                                    <button id="save_document_updates" class="btn btn-success">Update Document</button>
                                    <button type="button" class="btn black-btn" data-dismiss="modal">Cancel</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
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
<?php //} ?>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    $(".is_video_expired").on("click",function(){
        var video_expired = $('input[name="is_video_expired"]:checked').val();
        if (video_expired == "yes") {
            $("#video_expired_section").show();
        } else {
            $("#video_expired_section").hide();
        }
    });

    $(function(){
        $('#employees_assigned_sid').select2({ closeOnSelect: true });
        //
        $('#video_start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date()
        });
    });

    $(".employees_assigned_to").on("click",function(){
        var value = $(this).val();
        if (value == 'specific' ) {
            $(".assign_option").show();
        } else {
            $(".assign_option").hide();
        }
    });

    function check_file(val) {
        var fileName  = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            
            if (val == 'video') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else {
                    var file_size = Number(($("#" + val)[0].files[0].size/1024/1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        alertify.error('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                        $('#name_' + val).html('');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                    
                }
            }
        } else {
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');
        }    
    }

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
        var video = $('#video').prop('files')[0];
        var fileName = $('#learning_doc').val();
        var file_ext = fileName.split('.').pop();
        var file_title = $('#upload_doc_title').val();
        var video_sid = $('#video_sid').val();
        var video_title = $('#video_title').val();
        var video_description = $('#video_description').val();
        // var employees_assigned_sid = $('#employees_assigned_sid').val();
        var questionnaire_sid = $('#questionnaire_sid').val();
        var video_source = $('input[name="video_source"]:checked').val();
        var video_id = $('#video_id').val();
        var assigned_employee = $('input[name="employees_assigned_to"]:checked').val();

        if (file_title == '') {
            alertify.error("Please Enter Upload Document Title");
        } else {
            var form_data = new FormData();
            form_data.append('docs', file_data);
            form_data.append('video_upload', video);
            form_data.append('company_sid', <?php echo $company_sid; ?>);
            form_data.append('video_sid', video_sid);
            form_data.append('attached_by', <?php echo $employer_sid; ?>);
            form_data.append('upload_title', file_title);
            form_data.append('file_ext', file_ext);

            if(video_sid == 0){
                form_data.append('video_title', video_title);
                form_data.append('video_description', video_description);
                form_data.append('video_id', video_id);
                form_data.append('video_source', video_source);
                // form_data.append('employees_assigned_sid', employees_assigned_sid);
                form_data.append('questionnaire_sid', questionnaire_sid);
                form_data.append('assigned_employee', assigned_employee);
            }
            $('#loader').show();
            $('#upload').addClass('disabled-btn');
            $('#upload').prop('disabled', true);
            $.ajax({
                url: '<?= base_url('learning_center/ajax_handler');?>',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(return_data_array){
                    var obj = jQuery.parseJSON(return_data_array);
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
                    $('#attach_document_upload_status tr:last').after('<tr><td>'+Title+'</td><td class="text-center">'+attach_date+'</td><td class="text-center col-xs-12"><div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"><a href="javascript:;" class="btn btn-warning btn-bg supporting_doc_status" src="0" data="'+document_sid+'" id="'+active_btn+'">De-Activate</a></div><div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"><a href="'+delete_url+'" class="btn btn-danger btn-bg" type="submit">Delete</a></div><div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"><a href="javascript:;" src="'+update_url+'" class="btn btn-info btn-bg update_supporting_doc" type="submit">Update</a></div></td></tr>');
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

    $(document).on('click', '.supporting_doc_status', function() {
        var active_status = $(this).attr('src');
        var document_sid = $(this).attr('data');
        var button_id = $(this).attr('id');
        var video_sid = $('#video_sid').val();
        var form_data = new FormData();

        form_data.append('company_sid', <?php echo $company_sid; ?>);
        form_data.append('video_sid', video_sid);
        form_data.append('active_status', active_status);
        form_data.append('document_sid', document_sid);
        $.ajax({
            url: '<?= base_url('learning_center/active_diactive_document') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data){
                var obj = jQuery.parseJSON(data);
                var new_state = obj.active;
               
                if (new_state == 1) {
                    $('#'+button_id).attr('src', new_state);
                    alertify.success('Supporting Document Active Successfully');
                    $('#'+button_id).removeClass();
                    $('#'+button_id).addClass('btn btn-warning btn-bg supporting_doc_status');
                    $('#'+button_id).text('De-Activate');
                } else {
                    $('#'+button_id).attr('src', new_state);
                    alertify.success('Supporting Document Deactive Successfully');
                    $('#'+button_id).removeClass();
                    $('#'+button_id).addClass('btn btn-success btn-bg supporting_doc_status');
                    $('#'+button_id).text('Activate');
                }
               
            },
            error: function(){
            }
        });
    });

    $(document).on('click', '.update_supporting_doc', function() {
        var update_url = $(this).attr('src');
        $.ajax({
            url: update_url,
            cache: false,
            contentType: false,
            processData: false,
            type: 'get',
            success: function(data){
                var obj = jQuery.parseJSON(data);
                var supported_document_sid = obj.sid;
                var supported_video_sid = obj.video_sid;
                var supported_document_title = obj.upload_file_title;
                var supported_document_state = obj.active;
               
                $('#update_supporting_document_modal').modal('show');
                $('#update_document_sid').val(supported_document_sid);
                $('#update_video_sid').val(supported_video_sid);
                $('#upload_doc_title_edit').val(supported_document_title);
                if (supported_document_state == 1) {
                    $('#sup_doc_active').prop('checked', true);
                } else {
                    $('#sup_doc_deactive').prop('checked', true);
                }
            },
            error: function(){
            }
        });
    });

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

            $.ajax({
                url: update_url,
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(return_data_array){
                    alertify.success('Supporting Document Update Successfully.');
                    location.reload();
                },
                error: function(){
                }
            });
        }
    });

    function check_learning_doc_edit (val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            
            if (val == 'learning_doc_edit') {
                if (ext != "PDF" && ext != "pdf" && ext != "docx" && ext != "xlsx") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid document format.");
                    $('#name_' + val).html('<p class="red">Only (PDF, Word, Excel) files are allowed!</p>');
                    return false;
                } else {
                    var selected_file = fileName;
                    var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                    $('#name_' + val).html(original_selected_file);
                }
            }
        } else {
            $('#name_' + val).html('No document selected');
            alertify.error("No document selected");
            $('#name_' + val).html('<p class="red">Please select document</p>');
        } 
    }    

    $('#add_edit_submit').click(function () {
        var required_condition = true;
        if($('input[name="video_source"]:checked').val() == 'do_not_change'){
            required_condition = false;
        }

        $("#form_save_video").validate({
            ignore: [],
            rules: {
                video_title: {
                    required: true,
                },
                video_id: {
                    required: required_condition,
                },
                video_upload:{
                    required: required_condition,
                }
            },
            messages: {
                title: {
                    required: 'Video Title Is Required',
                },
                video_id: {
                    required: 'Please provide Valid Video URL',
                },
                video_upload: {
                    required: 'Please upload video',
                }
            },
            submitHandler: function (form) {
                var flag = 0;

                if($('input[name="video_source"]:checked').val() == 'youtube'){
                    if($('#video_id').val() != '') { 
                        var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                        if (!$('#video_id').val().match(p)) {
                            alertify.alert('Error','Not a Valid Youtube URL');
                            flag = 1;
                            return false;
                        }
                    }   
                } 

                if($('input[name="video_source"]:checked').val() == 'vimeo'){
                    if($('#video_id').val() != '') {    
                        var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                        $.ajax({
                            type: "POST",
                            url: myurl,
                            data: {url: $('#video_id').val()},
                            async : false,
                            success: function (data) {
                                if (data == false) {
                                    alertify.alert('Error','Not a Valid Vimeo URL');
                                    flag = 1;
                                    return false;
                                }
                            },
                            error: function (data) {
                            }
                        });
                    }
                }

                var assign_employee_type = $('input[name="employees_assigned_to"]:checked').val();
                if (assign_employee_type == "specific") {
                    var selected_employees = $("#employees_assigned_sid").val();
                    if (selected_employees == "" || selected_employees == null || selected_employees == undefined) {
                        flag = 1;
                        alertify.alert('Error','Please select an employees');
                        return false;
                    }
                }

                var video_expired = $('input[name="is_video_expired"]:checked').val();
                if (video_expired == "yes") {
                    var expired_number = $("#expired_number").val();
                    var expired_type = $("#expired_type").val();

                    if (expired_number == undefined || expired_number == 0 || expired_number == '') {
                        flag = 1;
                        alertify.alert('Error','Please enter any number');
                        return false;
                    } else if (expired_number != '' && !/^[0-9]+$/.test(expired_number)) {
                        flag = 1;
                        alertify.alert('Error','Only number are accepted.');
                        return false;
                    } else if (expired_type == undefined || expired_type == 0 || expired_type == '') {
                        flag = 1;
                        alertify.alert('Error','please select any type.');
                        return false;
                    }
                    if (expired_number == undefined || expired_number == 0 || expired_number == '') {
                        flag = 1;
                        alertify.alert('Error','Please select video start date.');
                        return false;
                    } 
                }

                if(flag == 0){
                    $('#my_loader').show(); 
                    $("#add_edit_submit").attr("disabled", true); 
                    form.submit();
                }
                        
                
            }
        });        
        
    });
    
    $(document).ready(function () {

        
        
        $('select[multiple]').select2({closeOnSelect: false});
        // $('#my_loader').hide();

        <?php   if (isset($video_source)) { ?>
             $('.radio_btn_video_source').hide(); 
                    <?php if ($video_source == 'upload') {?>
                        $('#yt_vm_video_container input').prop('disabled', true);
                        $('#yt_vm_video_container').hide();

                        $('#up_video_container input').prop('disabled', true);
                        $('#up_video_container').hide();

            <?php } else { ?>
                        $('#yt_vm_video_container input').prop('disabled', true);
                        $('#yt_vm_video_container').hide();

                        $('#up_video_container input').prop('disabled', true);
                        $('#up_video_container').hide();
                        $('#add_edit_submit').removeAttr('onClick');
        <?php } }else {?>  
                    $('#up_video_container input').prop('disabled', true);
                    $('#up_video_container').hide();
                    $('#add_edit_submit').removeAttr('onClick');
        <?php } ?>   
        

        $('.employees_assigned_to').on('click', function () {
            if ($(this).prop('checked') == true) {
                var value = $(this).val();
                if (value == 'all') {
//                    console.log('All');
                    $('#employees_assigned_sid').prop('disabled', true).trigger("chosen:updated");

                } else {
//                    console.log('specific');
                    $('#employees_assigned_sid').prop('disabled', false).trigger("chosen:updated");
                }
            }
//            $('#form_save_video').valid();
        });

        $('.applicants_assigned_to').on('click', function () {
            if ($(this).prop('checked') == true) {
                var value = $(this).val();
                if (value == 'all') {
//                    console.log('All');
                    $('#applicants_assigned_sid').prop('disabled', true).trigger("chosen:updated");
                } else {
//                    console.log('specific');
                    $('#applicants_assigned_sid').prop('disabled', false).trigger("chosen:updated");
                }
            }

//            $('#form_save_video').valid();
        });

        $('input[type=radio]:checked').trigger('click');

        $('.edit_filter_check').on('click', function(){
            $('.edit_filter_check').hide();
            <?php if (isset($video_source)) { ?>
                <?php if ($video_source == 'upload') { ?>

                    $('.radio_btn_video_source').show();
                    $('#yt_vm_video_container input').prop('disabled', true);
                    $('#yt_vm_video_container').hide();

                    $('#up_video_container input').prop('disabled', false);
                    $('#up_video_container').show();

                    $('#add_edit_submit').attr('onClick', 'check_file("video");');
                <?php } else {  ?>

                    $('#yt_vm_video_container input').prop('disabled', false);
                    $('#yt_vm_video_container').show();
                    $('.radio_btn_video_source').show();

                    $('#up_video_container input').prop('disabled', true);
                    $('#up_video_container').hide();
                    $('#add_edit_submit').removeAttr('onClick');
                <?php } ?>
            <?php } else {  ?>  
                $('#up_video_container input').prop('disabled', true);
                $('#up_video_container').hide();
                $('#add_edit_submit').removeAttr('onClick');
            <?php } ?> 
        });


        $('.video_source').on('click', function(){
            var selected = $(this).val();
            if(selected == 'youtube'){
                $('#yt_vm_video_container input').prop('disabled', false);
                $('#yt_vm_video_container').show();

                $('#up_video_container input').prop('disabled', true);
                $('#up_video_container').hide();
                $('#add_edit_submit').removeAttr('onClick');
            } else if(selected == 'vimeo'){
                $('#yt_vm_video_container input').prop('disabled', false);
                $('#yt_vm_video_container').show();

                $('#up_video_container input').prop('disabled', true);
                $('#up_video_container').hide();
                $('#add_edit_submit').removeAttr('onClick');
            } else if(selected == 'upload'){
                $('#yt_vm_video_container input').prop('disabled', true);
                $('#yt_vm_video_container').hide();

                $('#up_video_container input').prop('disabled', false);
                $('#up_video_container').show();

                $('#add_edit_submit').attr('onClick', 'check_file("video");');
            } else if(selected == 'do_not_change'){
                $('#up_video_container input').prop('disabled', true);
                $('#yt_vm_video_container input').prop('disabled', true);
                $('#add_edit_submit').removeAttr('onClick');
            }
        });
    });

    // To make sure we get a number
    $('#expired_number').keyup(function(){
        $(this).val(
           $(this).val().trim() != '' ?  $(this).val().replace(/[^0-9]/ig, '') : ''
        );
    });
</script>
