
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <div class="form-wrp">
                        <form id="add_new_event" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Announcement Type: </label>
                                        <div class="hr-select-dropdown">
                                            <select class="form-control" name="type" id="type">
                                                <option value="General"
                                                    <?php   if ($this->uri->segment(2) == 'add') {
                                                        if (set_value('type') == "General") { ?>
                                                            selected

                                                        <?php       }
                                                    } elseif ($this->uri->segment(2) == 'edit') {
                                                        if ($event[0]['type'] == "General") { ?>
                                                            selected
                                                        <?php       }
                                                    } ?>
                                                    >
                                                    General Event
                                                </option>
                                                <option value="New Hire"
                                                    <?php   if ($this->uri->segment(2) == 'add') {
                                                                if (set_value('type') == "New Hire") { ?>
                                                                    selected

                                                    <?php       }
                                                            } elseif ($this->uri->segment(2) == 'edit') {
                                                                if ($event[0]['type'] == "New Hire") { ?>
                                                                    selected
                                                    <?php       }
                                                            } ?>

                                                >
                                                    New Hire Event
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group" id="custom_ticket_cat">
                                        <label>Title : <span class="staric">*</span></label>
                                        <input type="text"
                                            class="form-control" 
                                            name="title" 
                                            id="title" 
                                            value="<?php if ($this->uri->segment(2) == 'add'){?><?php echo set_value('custom_category'); ?><?php } elseif ($this->uri->segment(2) == 'edit'){?><?php echo $event[0]['title']; ?><?php } ?>"/>
                                        <?php echo form_error('custom_category'); ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Display Start Date: <span class="staric">*</span></label>
                                        <input class="form-control"
                                            type="text"
                                            name="display_start_date"
                                            id="display_start_date"
                                            value="<?php if ($this->uri->segment(2) == 'edit'){echo $event[0]['display_start_date'] != NULL && !empty($event[0]['display_start_date']) ? date('m-d-Y', strtotime($event[0]['display_start_date'])) : ''; 
                                                } ?>"/>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Display End Date: </label>
                                        <input class="form-control"
                                           type="text"
                                           name="display_end_date"
                                           id="display_end_date"
                                           value="<?php if ($this->uri->segment(2) == 'edit'){echo $event[0]['display_end_date'] != NULL && !empty($event[0]['display_end_date']) ? date('m-d-Y', strtotime($event[0]['display_end_date'])) : '';
                                                } ?>
                                        "/>
                                        <div class="video-link float-right" style='font-style: italic;'><b>Note:</b> If the date box is left blank, the Announcement will run indefinitely! </div>
                                    </div>
                                </div>

                                <?php if ($this->uri->segment(2) == 'edit') { ?>
                                    <input type="hidden" name="status" value="<?php echo $event[0]['status'] ? 1 : 0; ?>">
                                <?php } ?>
<!--                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
<!--                                    <div class="form-group">-->
<!--                                        <label>Status :</label>-->
<!--                                        <div class="hr-select-dropdown">-->
<!--                                            <select class="form-control" name="status" id="status">-->
<!--                                                <option value="1">Enable</option>-->
<!--                                                <option value="0">Disable</option>-->
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
                                <input type="hidden" name="status" value="1">
                                <div class="new-hire-div">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>New Hire Name: <span class="staric">*</span></label>
                                            <div class="hr-select-dropdown">
                                                <select class="form-control" name="new_hire_name" id="new_hire_name">
                                                    <option value="">Please Select</option>
                                                    <?php foreach($all_emp as $emp){?>
                                                        <option value="<?php echo ucwords($emp['first_name']. ' ' . $emp['last_name'])?>"
                                                            <?php if ($this->uri->segment(2) == 'edit') { 
                                                                if ($event[0]['new_hire_name'] == ucwords($emp['first_name'] . ' ' . $emp['last_name'])) 
                                                                { ?>selected<?php } 
                                                            } ?>
                                                        >
                                                            <?=remakeEmployeeName($emp);?>
                                                        </option>
                                                           
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="event-div">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>General Event Start Date :</label>
                                            <input class="form-control"
                                                type="text"
                                                name="event_start_date"
                                                id="event_start_date"
                                                value="<?php if ($this->uri->segment(2) == 'edit'){echo $event[0]['event_start_date'] != NULL && !empty($event[0]['event_start_date']) ? date('m-d-Y', strtotime($event[0]['event_start_date'])) : '';} ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>General Event End Date :</label>
                                            <input class="form-control"
                                               type="text"
                                               name="event_end_date"
                                               id="event_end_date"
                                               value="<?php if ($this->uri->segment(2) == 'edit'){echo $event[0]['event_end_date'] != NULL && !empty($event[0]['event_end_date']) ? date('m-d-Y', strtotime($event[0]['event_end_date'])) : '';} ?>"/>
                                            <div class="video-link float-right" style='font-style: italic;'><b>Note:</b> If the date box is left blank, the Announcement will run indefinitely! </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Select Department's Team:</label>
                                        <div class="hr-select-dropdown">
                                            <select class="form-control" name="dep-team" id="dep-team">
                                                <option value="" selected>Please Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6" id="announce-for">
                                    <div class="form-group">
                                        <label>Announcement For: </label>
                                        <div class="hr-select-dropdown">
                                            <select class="chosen-select" multiple="multiple" name="announcement_for[]" id="announcement_for">
                                            <?php if ($this->uri->segment(2) == 'add') { ?>
                                                <option value="0" selected>All</option>
                                                <?php foreach($all_emp as $emp){?>
                                                    <option value="<?= $emp['sid']?>"><?=remakeEmployeeName($emp);?></option>
                                                <?php }?>
                                            <?php } elseif ($this->uri->segment(2) == 'edit') { ?>
                                                <?php $for_array = explode(',', $event[0]['announcement_for']); ?>
                                                <option value="0" <?php echo in_array(0, $for_array) ? 'selected' : '' ?>>All</option>
                                                <?php foreach ($all_emp as $emp) { ?>
                                                    <option value="<?= $emp['sid'] ?>" <?php echo in_array($emp['sid'], $for_array) ? 'selected' : '' ?>><?=remakeEmployeeName($emp);?></option>
                                                <?php } ?>
                                            <?php } ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="new-hire-div">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>New Hire Start Date: </label>
                                            <input class="form-control"
                                               type="text"
                                               name="new_hire_joining_date"
                                               id="new_hire_joining_date"
                                               value="<?php if ($this->uri->segment(2) == 'edit'){echo $event[0]['new_hire_joining_date'] != NULL && !empty($event[0]['new_hire_joining_date']) ? date('m-d-Y', strtotime($event[0]['new_hire_joining_date'])) : '';} ?>"/> 
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>New Hire Job Position: </label>
                                            <input class="form-control"
                                               type="text"
                                               name="new_hire_job_position"
                                               id="new_hire_job_position"
                                               value="<?php if ($this->uri->segment(2) == 'edit'){echo $event[0]['new_hire_job_position'];} ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>New Hire Bio: </label>
                                            <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                            <textarea class="ckeditor" name="new_hire_bio" id="new_hire_bio" cols="60" rows="10"><?php if ($this->uri->segment(2) == 'edit') {?><?= $event[0]['new_hire_bio'] ?><?php } ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Message: </label>
                                        <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                        <textarea class="ckeditor" name="message" id="message" cols="60" rows="10">
                                            <?php if ($this->uri->segment(2) == 'edit') {?><?= $event[0]['message'] ?><?php } ?>
                                        </textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">

                                    <div>
                                        <label>Video Source: </label>
                                        <label for="youtube" class="control control--radio">
                                            <?php echo YOUTUBE_VIDEO; ?>
                                            <input id="youtube" class="video_source" name="video_source" value="youtube" type="radio" checked>
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label for="vimeo" class="control control--radio">
                                            <?php echo VIMEO_VIDEO; ?>
                                            <input id="vimeo" class="video_source" name="video_source" 
                                            <?php if ($this->uri->segment(2) == 'edit') {?>
                                                <?= $event[0]['section_video_source'] == 'vimeo' ? 'checked="checked"' : '' ?>
                                            <?php } ?> value="vimeo" type="radio">
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <?php echo UPLOAD_VIDEO; ?>
                                            <input id="upload_video" class="video_source" name="video_source"
                                            <?php if ($this->uri->segment(2) == 'edit') {?>
                                                <?= $event[0]['section_video_source'] == 'upload_video' ? 'checked="checked"' : '' ?>
                                            <?php } ?> value="upload_video" type="radio">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <label>Video status: </label>
                                    <label class="control control--radio">
                                        Disable
                                        <input id="disable_video" class="video_status" name="video_status" checked="checked" value="0" type="radio">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio">
                                        Enable
                                        <input id="enable_video" class="video_status" name="video_status" value="1" type="radio"
                                        <?php if ($this->uri->segment(2) == 'edit') {?>
                                            <?= $event[0]['section_video_status'] == '1' ? 'checked="checked"' : '' ?>
                                        <?php } ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>

                                <?php if ($this->uri->segment(2) == 'edit') {?>
                                    <?php $temp = (isset($event[0]['section_video']) ? html_entity_decode($event[0]['section_video']) : ''); ?>
                                    <?php if ($event[0]['section_video_source'] == 'youtube') { ?>
                                        <?php $temp = empty($temp) ? '' : 'https://www.youtube.com/watch?v=' . $temp; ?>
                                    <?php } else if ($event[0]['section_video_source'] == 'vimeo') { ?>
                                        <?php $temp = empty($temp) ? '' : 'https://vimeo.com/' . $temp; ?>
                                    <?php } else if ($event[0]['section_video_source'] == 'upload_video') { ?>
                                        <?php $up_temp = empty($temp) ? '' : $temp; ?>    
                                    <?php } ?>
                                <?php } ?>    

                                <div class="col-lg-12 col-md-6 col-xs-12 col-sm-12" id="yt_video_container" <?php if ($this->uri->segment(2) == 'edit') {?><?php echo $event[0]['section_video_source'] == 'youtube' ? 'style="display: none"' : '' ?><?php } ?>>
                                    <div class="form-group">
                                        <label>Youtube Video: </label>
                                        <input class="form-control" type="text" name="video_url" id="video_url_youtube" value="<?php if ($this->uri->segment(2) == 'edit') {?><?= $event[0]['section_video_source'] == 'youtube' ? $temp : '' ?><?php } ?>"/>
                                        <?php echo form_error('video_url'); ?>
                                        <div class="video-link float-right" style='font-style: italic;'><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="display: none;" id="vm_video_container" <?php if ($this->uri->segment(2) == 'edit') {?><?php echo $event[0]['section_video_source'] == 'vimeo' ? 'style="display: none"' : '' ?><?php } ?>>
                                    <div class="form-group">
                                        <label>Vimeo Video: </label>
                                        <input class="form-control" type="text" name="video_url" id="video_url_vimeo" value="<?php if ($this->uri->segment(2) == 'edit') {?><?= $event[0]['section_video_source'] == 'vimeo' ? $temp : '' ?><?php } ?>"/>
                                        <?php echo form_error('video_url'); ?>
                                        <div class="video-link float-right" style='font-style: italic;'><b>e.g.</b> https://vimeo.com/XXXXXXX </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="display: none;" id="ul_video_container" <?php if ($this->uri->segment(2) == 'edit') {?><?php echo $event[0]['section_video_source'] == 'upload_video' ? 'style="display: none"' : '' ?><?php } ?>>
                                    <div class="form-group universal-form-style-v2">
                                        <label>Upload Video <span class="hr-required">*</span></label>
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_video">No file selected</span>
                                            <input class="customImage" type="file" name="video_upload" id="video" onchange="check_upload_video('video')">
                                            <a href="javascript:;">Choose Video</a>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($this->uri->segment(2) == 'edit') {?>
                                    <?php if ($event[0]['section_video_source'] == 'upload_video') { ?>
                                        <input type="hidden" id="old_upload_video" name="old_upload_video" value="<?php echo $up_temp ?>">  
                                    <?php } ?>
                                <?php } ?>

                                <?php if ($this->uri->segment(2) == 'edit') {?>
                                    <?php $field_id = 'section_video'; ?>
                                    <?php $temp = (isset($event[0][$field_id]) ? $event[0][$field_id] : ''); ?>
                                    <?php if ($temp != '') { ?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="well well-sm">
                                                <?php if ($event[0]['section_video_source'] == 'youtube') { ?>
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $event[0]['section_video']; ?>"></iframe>
                                                    </div>
                                                <?php } elseif ($event[0]['section_video_source'] == 'vimeo') { ?>
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe src="https://player.vimeo.com/video/<?php echo $event[0]['section_video']; ?>" frameborder="0"></iframe>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <video controls >
                                                            <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $event[0]['section_video']; ?>" type='video/mp4'>
                                                        </video>
                                                    </div>
                                                <?php } ?>    
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group universal-form-style-v2">
                                        <label>Banner Image: </label>

                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_section_image"">No file selected</span>
                                            <input class="customImage" type="file" name="section_image" id="section_image" onchange="check_file('section_image')">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($this->uri->segment(2) == 'edit') {?>
                                <input type="hidden" id="old_upload_image" name="old_upload_image" value="<?php echo $event[0]['section_image'] ?>">
                                <?php } ?>

                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Banner Image status: </label><br>
                                    <label class="control control--radio">
                                        Disable
                                        <input id="disable_banner" class="banner_status" name="banner_status" checked="checked" value="0" type="radio">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio">
                                        Enable
                                        <input id="enable_banner" class="banner_status" name="banner_status" value="1" type="radio" <?php if ($this->uri->segment(2) == 'edit') {?><?= $event[0]['section_image_status'] == '1' ? 'checked="checked"' : '' ?><?php } ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                    </div>
                                </div>

                                <?php if ($this->uri->segment(2) == 'edit') {?>
                                    <?php $field_id = 'section_image'; //echo 'this is '.$event[0]['section_image_status']; die();?>
                                    <?php $sec_img = $temp = (isset($event[0][$field_id]) ? $event[0][$field_id] : ''); ?>
                                    <?php if (!empty($temp)) { ?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-col-100 autoheight">
                                                <div class="well well-sm">
                                                    <?php $img_url = isset($temp) && !empty($temp) ? AWS_S3_BUCKET_URL . $temp : '';  ?>
                                                    <img class="img-responsive" src="<?php echo $img_url; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                                <div class="col-sm-6">
                                    <label>Send notification emails?</label>
                                    <div>
                                        <label class="control control--radio">
                                            <input type="radio" name="send_email" value="yes"> Yes &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <input type="radio" name="send_email" value="no" checked="true"> No &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <br />
                                </div>

                                <div id="upload-doc-div" style="display: none">
<!--                                --><?php //if ($this->uri->segment(2) != 'edit') {?>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group universal-form-style-v2">
                                            <label>Upload Document</label>
                                            <div class="upload-file invoice-fields" style="display: block;">
                                                <span class="selected-file" id="name_document">No file selected</span>
                                                <input class="customImage" type="file" multiple="multiple" name="document_upload[]" id="document" onchange="check_upload_document('document')">
                                                <a href="javascript:;">Choose Document</a>
                                            </div>
    <!--                                        <div id="file-upload-div" class="file-upload-box"></div>-->
    <!--                                        <div class="attached-files" id="uploaded-files" style="display: none;"></div>-->
                                        </div>
                                    </div>
<!--                                --><?php //}?>
                                    <?php if ($this->uri->segment(2) == 'edit' && sizeof($related_documented)>0) {?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight universal-form-style-v2">
                                                <h4><strong>Uploaded Documents</strong></h4>
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr style="background-color: #81bc35;">
                                                            <th>Document</th>
                                                            <th>Type</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($related_documented as $document) { ?>
                                                            <tr data-id="<?=$document['sid'];?>">
                                                                <td><?=$document['document_name'];?></td>
                                                                <td><?=$document['document_type'];?></td>
                                                                <td>
                                                                    <button class="btn btn-success js-view">View</button>
                                                                    <button class="btn btn-danger js-remove">Remove</button>
                                                                </td>
                                                            </tr>
                                                        <?php }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="text-right">
                                        <a href="<?= base_url('announcements')?>" class="submit-btn"> Cancel </a>
                                        <input type="submit" value="<?php if($this->uri->segment(2) == 'add'){ ?>Submit<?php }elseif($this->uri->segment(2) == 'edit'){?>Update<?php } ?>" class="submit-btn" id="add_event_submit">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url('assets/css/chosen.css'); ?>"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets/js/chosen.jquery.js'); ?>"></script>
<script>
    $('#add_event_submit').click(function () {
        var video_url_required;
        var video_url_message;
        var upload_required;
        var image_url_required;

        if ($('#enable_banner').is(':checked')) {
            <?php if ($this->uri->segment(2) == 'edit') {?>
                if ($('#old_upload_image').val()) {
                    image_url_required = false;
                } else {
                    image_url_required = true;
                }
            <?php } else { ?>   
                    image_url_required = true;
            <?php } ?>     
        } else {
            image_url_required = false;
        }

        if ($('#enable_video').is(':checked')) {

            if ($('#youtube').is(':checked')) {
                video_url_required = true;
                video_url_message = 'Please provide Youtube link';
            } else if ($('#vimeo').is(':checked')) {
                video_url_required = true;
                video_url_message = 'Please provide vimeo link';
            } else if ($('#upload_video').is(':checked')) {
                <?php if ($this->uri->segment(2) == 'edit') {?>
                    if ($('#old_upload_video').val()) {
                        upload_required = false;
                    } else {
                        upload_required = true;
                    }
                <?php } else { ?>   
                    upload_required = true;
                <?php } ?>  
            } else {
                upload_required = false;
                video_url_required = false;
            }

        } else {


            video_url_required = false;
            upload_required = false;
        }

        $("#add_new_event").validate({
            ignore: [],
            rules: {
                title: {
                    required: true,
                },
                display_start_date: {
                    required: true,
                }, video_upload: {
                    required: upload_required
                },
                video_url: {
                    required: video_url_required
                },
                section_image: {
                    required: image_url_required
                }
            },
            messages: {
                title: {
                    required: 'Title Is Required',
                },
                display_start_date: {
                    required: 'Display Start Date Is Required',
                },
                video_upload: {
                    required: 'Please upload video',
                },
                video_url: {
                    required: video_url_message,
                },
                section_image: {
                    required: 'Please Upload Image',
                }
            },
            submitHandler: function (form) {
                var type = $('#type').val();
                if (type == 'New Hire') {
                    if ($('#new_hire_name').val() == '') {
                        alertify.alert('New Hire Name Is Required');
                        return false;
                    }
                }

                if ($('input[name="video_source"]:checked').val() == 'youtube') {
                    var flag = 0;
                    if ($('#video_url_youtube').val() != '') {
                        var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                        if (!$('#video_url_youtube').val().match(p)) {
                            alertify.alert('Not a Valid Youtube URL');
                            flag = 1;
                            return false;
                        }
                    }
                } else if ($('input[name="video_source"]:checked').val() == 'vimeo') {
                    if ($('#video_url_vimeo').val() != '') {
                        var flag = 0;
                        var myurl = "<?= base_url() ?>onboarding/validate_vimeo";
                        $.ajax({
                            type: "POST",
                            url: myurl,
                            data: {url: $('#video_url_vimeo').val()},
                            async: false,
                            success: function (data) {
                                console.log(data);
                                if (data == 'false') {
                                    alertify.alert('Not a Valid Vimeo URL');
                                    flag = 1;
                                    return false;
                                }
                            },
                            error: function (data) {

                            }
                        });
                    }
                    if (flag) {
                        return false;
                    }
                }
                $('#my_loader').show();
                form.submit();
            }
        });
    });

    $(document).on('click','.js-remove',function(e) {
        e.preventDefault();
        var id = $(this).closest('tr').data('id');
        alertify.confirm('Confirmation', "Are you sure you want to delete this document?",
            function () {
                $.ajax({
                    url: '<?= base_url('announcements/delete_record_ajax'); ?>',
                    type: 'POST',
                    data: {
                        id: id,
                        action: 'delete'
                    },
                    success: function(data){
//                        alertify.alert('File Deleted Successfully');
                        window.location.href = window.location.href;
                    },
                    error: function(){
                    }
                });
            },
            function () {
//                alertify.error('Canceled');
            });
    });

    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url:'<?= base_url('announcements/ajax_handler');?>',
            success: function(res){
                var response = JSON.parse(res);
                var select = '';
                var team_sid = <?= $this->uri->segment(2) == 'edit' && $event[0]['department_team_sid'] != null && $event[0]['department_team_sid'] != '' ? $event[0]['department_team_sid'] : 0;?>;
                $.each(response,function(index,object1){
                    select += '<optgroup label="' + index + '">';
                    $.each(object1,function(index,object2) {
                        var auto_select = '';
                        if(object2.sid == team_sid){
                            auto_select = 'selected="selected"';
                        }
                        select += '<option value="'+object2.sid+'" '+auto_select+'>'+object2.name.toUpperCase()+'</option>';
                    });
                    select += '</optgroup>';
                });
                if(res.length > 0){
                    $('#upload-doc-div').show();
                }
                $('#dep-team').append(select);
            },
            error: function(){

            }
        });

        $('.video_source').on('click', function () {
            var selected = $(this).val();

            if (selected == 'youtube') {
                $('#yt_video_container input').prop('disabled', false);
                $('#yt_video_container').show();

                $('#vm_video_container input').prop('disabled', true);
                $('#vm_video_container').hide();
                $('#ul_video_container input').prop('disabled', true);
                $('#ul_video_container').hide();
            } else if (selected == 'vimeo') {
                $('#yt_video_container input').prop('disabled', true);
                $('#yt_video_container').hide();
                $('#ul_video_container input').prop('disabled', true);
                $('#ul_video_container').hide();

                $('#vm_video_container input').prop('disabled', false);
                $('#vm_video_container').show();
            } else if (selected == 'upload_video') {
                $('#yt_video_container input').prop('disabled', true);
                $('#yt_video_container').hide();
                $('#vm_video_container input').prop('disabled', true);
                $('#vm_video_container').hide();

                $('#ul_video_container input').prop('disabled', false);
                $('#ul_video_container').show();
            }

        });

        $('.video_source:checked').trigger('click');

        $(".chosen-select").chosen().change(function () {});

        var type = $('#type').val();

        <?php if ($this->uri->segment(2) == 'add') { ?>
            if(type == 'New Hire'){
                $('.new-hire-div').show();
                $('.event-div').hide();
            }else{
                $('.new-hire-div').hide();
                $('.event-div').show();
            }

            $('#type').change(function(){
                var type = $(this).val();
                if(type == 'New Hire'){
                    $('.new-hire-div').show();
                    $('.event-div').hide();
//                    $('#announce-for').addClass('col-lg-6 col-md-6 col-sm-6');
//                    $('#announce-for').removeClass('col-lg-12 col-md-12 col-sm-12');
                }else{
                    $('.new-hire-div').hide();
                    $('.event-div').show();
//                    $('#announce-for').removeClass('col-lg-6 col-md-6 col-sm-6');
//                    $('#announce-for').addClass('col-lg-12 col-md-12 col-sm-12');
                }
            }); 

            $('.datepicker').datepicker({dateFormat: 'mm-dd-yy'}).val();

            $('#display_start_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#display_end_date').datepicker('option', 'minDate', value);
                }
            }).datepicker('option', 'minDate', $('#display_end_date').val());

            $('#display_end_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#display_start_date').datepicker('option', 'maxDate', value);
                }
            }).datepicker('option', 'minDate', $('#display_start_date').val());

            $('#event_start_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#event_end_date').datepicker('option', 'minDate', value);
                }
            }).datepicker('option', 'maxDate', $('#event_end_date').val());

            $('#event_end_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#event_start_date').datepicker('option', 'maxDate', value);
                }
            }).datepicker('option', 'minDate', $('#event_start_date').val());

            $('#new_hire_joining_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
            });                                  
        <?php } elseif ($this->uri->segment(2) == 'edit') { ?>
            if (type == 'New Hire') {
                $('.new-hire-div').show();
                $('.event-div').hide();
//                $('#announce-for').addClass('col-lg-6 col-md-6 col-sm-6');
//                $('#announce-for').removeClass('col-lg-12 col-md-12 col-sm-12');
            } else {
                $('.new-hire-div').hide();
                $('.event-div').show();
//                $('#announce-for').removeClass('col-lg-6 col-md-6 col-sm-6');
//                $('#announce-for').addClass('col-lg-12 col-md-12 col-sm-12');
            }

            $('#type').change(function () {
                var type = $(this).val();
                if (type == 'New Hire') {
                    $('.new-hire-div').show();
                    $('.event-div').hide();
                } else {
                    $('.new-hire-div').hide();
                    $('.event-div').show();
                }
            });  

            $('.datepicker').datepicker({dateFormat: 'mm-dd-yy'}).val();

            $('#display_start_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#display_end_date').datepicker('option', 'minDate', value);
                }
            }).datepicker('option', 'maxDate', $('#display_end_date').val());

            $('#display_end_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#display_start_date').datepicker('option', 'maxDate', value);
                }
            }).datepicker('option', 'minDate', $('#display_start_date').val());

            $('#event_start_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#event_end_date').datepicker('option', 'minDate', value);
                }
            }).datepicker('option', 'maxDate', $('#event_end_date').val());

            $('#event_end_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#event_start_date').datepicker('option', 'maxDate', value);
                }
            }).datepicker('option', 'minDate', $('#event_start_date').val());

            $('#new_hire_joining_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
            });                                
        <?php } ?>
    });

        function check_file(val) {

            var fileName = $("#" + val).val();
            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                if (val == 'pictures') {
                    if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid Image format.");
                        $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                        return false;
                    } else
                        return true;
                }
            } else {
                $('#name_' + val).html('No file selected');
            }
        }

        function check_upload_video(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'video') {
                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid video format.");
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
                alertify.alert("No video selected");
                $('#name_' + val).html('<p class="red">Please select video</p>');

            }
        }

        function check_upload_document(val) {

            if (document.getElementById('document').files.length > 0) {
                // if($('#dep-team').val() == ''){
                //     alertify.alert('Please select a team for documents');
                //     return false;
                // }
                $('#name_' + val).html('');
                $.each(document.getElementById('document').files,function(index,object){
                    var ext = object.name.split('.').pop();
                    var ext = ext.toLowerCase();
                    console.log(ext);
                    if(ext == "mp4" || ext == "m4a" || ext == "m4v" || ext == "f4v" || ext == "f4a" || ext == "m4b" || ext == "m4r" || ext == "f4b" || ext == "mov" || ext == 'mp3'){
                        var file_size = Number((object.size/1024/1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#" + val).val(null);
                            alertify.alert('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                            $('#name_' + val).html('');
                            return false;
                        }
                    }
                    $('#name_' + val).html($('#name_' + val).html()+' '+object.name.substring(0, 45));
                });
            } else {
                $('#name_' + val).html('No Document selected');
                alertify.alert("No Document selected");
                $('#name_' + val).html('<p class="red">Please select document</p>');

            }
        }
</script>

<style>
    .loader{ position: absolute; top: 0; right: 0; bottom: 0; left: 0; width: 100%; background: rgba(255,255,255,.8); }
    .loader i{ text-align: center; top: 50%; left: 50%; font-size: 40px; position: relative; }
</style>

<script>
    
    $(function(){
        var documents = <?=json_encode($related_documented);?>;

        $('.js-view').click(function(e){
            e.preventDefault();
            // Get document
            var doc = getDocument($(this).closest('tr').data('id'));
            //
            if(Object.keys(doc).length == 0){
                alertify.alert('ERROR!', 'Document not found.');
                return;
            }
            //
            loadModal(doc);
        });

        //
        function loadModal(doc){
            var
            modal = '',
            d = '',
            iframeURL = '',
            spinner = '',
            printBtnURL = '',
            downloadBtnURL = '';

            // For video
            if(
                doc['document_type'] == "mp4" || doc['document_type'] == "m4a" || 
                doc['document_type'] == "m4v" || doc['document_type'] == "f4v" || 
                doc['document_type'] == "f4a" || doc['document_type'] == "m4b" || 
                doc['document_type'] == "m4r" || doc['document_type'] == "f4b" || 
                doc['document_type'] == "mov" || doc['document_type'] == 'mp3'){
                d += '<video id="my-video" class="video-js" controls preload="auto" poster="<?=base_url('assets/images/affiliates/affiliate-0.png');?>" data-setup="{}">';
                d += '    <source src="<?=base_url('assets/uploaded_videos').'/';?>'+( doc['document_code'] )+'>';
                d += '    <p class="vjs-no-js">';
                d += '        To view this video please enable JavaScript, and consider upgrading to a web browser that';
                d += '        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>';
                d += '    </p>';
                d += '</video>';
            } else if(
                doc['document_type'] == "png" || doc['document_type'] == "jpg" || 
                doc['document_type'] == "jpeg"){
                d +=' <figure>';
                d +='     <img class="img-responsive" src="<?=AWS_S3_BUCKET_URL;?>'+( doc['document_code'] )+'"/>';
                d +=' </figure>';
            } else if(
                doc['document_type'] == "doc" || doc['document_type'] == "docx" || 
                doc['document_type'] == "xlx" || doc['document_type'] == "xlxs"){
                downloadBtnURL = "<?=base_url('hr_documents_management/download_upload_document');?>/"+doc['document_code'];
                spinner = '<div class="loader"><i class="fa fa-spinner fa-spin"></i></div>';
                iframeURL = "https://view.officeapps.live.com/op/embed.aspx?src="+( encodeURI("<?=AWS_S3_BUCKET_URL;?>"+doc['document_code']) )+"";
                printBtnURL = iframeURL;
                d += '<iframe id="preview_iframe" src="'+( iframeURL )+'" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>';
            } else {
                downloadBtnURL = "<?=base_url('hr_documents_management/download_upload_document');?>/"+doc['document_code'];
                spinner = '<div class="loader"><i class="fa fa-spinner fa-spin"></i></div>';
                iframeURL = "https://docs.google.com/gview?url="+( encodeURI("<?=AWS_S3_BUCKET_URL;?>"+doc['document_code']) )+"&embedded=true";
                printBtnURL = iframeURL;
                d += '<iframe id="preview_iframe" src="'+( iframeURL )+'" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>';
            }

            modal+= '<div class="modal fade" id="modal-id">';
            modal+= '    <div class="modal-dialog modal-lg">';
            modal+= '        <div class="modal-content">';
            modal+= '            <div class="modal-header">';
            modal+= '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            modal+= '                <h4 class="modal-title">'+( doc['document_name'] )+'</h4>';
            modal+= '            </div>';
            modal+= '            <div class="modal-body" style="min-height: 400px;">';
            modal+= spinner                
            modal+= d                
            modal+= '            </div>';
            modal+= '            <div class="modal-footer">';
            if(printBtnURL != '')
            modal+= '                <a href="'+( printBtnURL )+'" target="_blank" class="btn btn-success js-btn" >Print</a>';
            if(downloadBtnURL != '')
            modal+= '                <a href="'+( downloadBtnURL )+'" class="btn btn-success js-btn" >Download</a>';
            modal+= '                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            modal+= '            </div>';
            modal+= '        </div>';
            modal+= '    </div>';
            modal+= '</div>';

            //
            $('#modal-id').remove();
            $('body').append(modal);
            $('#modal-id').modal();
            //
            if(iframeURL != '') loadIframe(iframeURL, '#preview_iframe');
        }

        //
        function loadIframe(url, target){
            try {
                if($(target).contents()[0].body.text == ''){
                    $(target).prop('src', url);
                    setTimeout(function(){
                        loadIframe(url, target);
                    }, 3000);
                }
            } catch(e) {
                $('.loader').remove();
                console.log('Iframe is loaded.');
            }
        }

        //
        function getDocument(sid){
            if(documents.length === 0) return {};
            //
            var i = 0, il = documents.length;
            //
            for (i; i < il; i++) {
                if(documents[i]['sid'] == sid) return documents[i];
            }
            //
            return {};
        }
    })
</script>