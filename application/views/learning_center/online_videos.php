<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
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
                                    <a class="dashboard-link-btn" href="<?php echo base_url('learning_center'); ?>"><i class="fa fa-chevron-left"></i>Learning Center</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if(check_access_permissions_for_view($security_details, 'add_online_videos')) { ?>
                            <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                                <a href="<?php echo base_url('learning_center/add_online_video'); ?>" class="btn btn-success btn-block">Add A Video</a>
                            </div>
                        <?php }?>
                        <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="dashboard-conetnt-wrp">
                                <div class="announcements-listing">
                                <?php   if(!empty($videos)) {
                                           foreach($videos as $key => $video) { ?>
                                            <article class="listing-article">
                                                <figure>
                                <?php               if($video['video_source'] == 'youtube') { ?>
                                                        <img class="thumbnail_video_url" src="https://img.youtube.com/vi/<?php echo $video['video_id']; ?>/hqdefault.jpg"/>
                                <?php               } else if($video['video_source'] == 'vimeo') { 
                                                        $thumbnail_image = vimeo_video_data($video['video_id']); ?>
                                                        <img class="thumbnail_video_url"  src="<?php echo $thumbnail_image;?>"/>
                                <?php               } else { ?>
                                                        <video id="video" class="thumbnail_video_url">
                                                            <source src="<?php echo base_url('assets/uploaded_videos/'.$video['video_id']); ?>" type="video/mp4">
                                                        </video>
                                <?php               } ?>
                                                </figure>
                                                <div class="text">
                                                    <h3><?php echo $video['video_title']; ?></h3>
                                                    <div class="post-options">
                                                        <div class="row">
                                                            <div class="col-lg-8 col-md-6 col-xs-12 col-sm-6">
                                                                <ul>
                                                                    <li><strong>Assigned On: </strong><?=reset_datetime(array( 'datetime' => $video['created_date'], '_this' => $this)); ?></li>
                                                                    <li><strong>Start Date: </strong><?=reset_datetime(array( 'datetime' => $video['video_start_date'], '_this' => $this)); ?></li>
                                                                    <li><strong>Visibility Type: </strong><?=ucwords($video['employees_assigned_to']);?></li>
                                                                    <li><strong>Created By: </strong><?=remakeEmployeeName($video);?></li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                <?php                           if(check_access_permissions_for_view($security_details, 'edit_online_video')) { ?>
                                                                    <a href="<?php echo base_url('learning_center/edit_online_video/' . $video['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit</a>
                                <?php                           } ?>                                                            
                                                            </div>
                                                            <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                 <?php                          if(check_access_permissions_for_view($security_details, 'delete_online_videos')) { ?>
                                                                    <form id="form_delete_video_<?php echo $video['sid']; ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_online_video" />
                                                                        <input type="hidden" id="video_sid" name="video_sid" value="<?php echo $video['sid']; ?>" />
                                                                    </form>
                                                                    <button type="button" onclick="func_delete_video(<?php echo $video['sid']; ?>);" class="btn btn-danger btn-sm btn-block">Delete</button>
                                <?php                           } ?>
                                                            </div>
                                                        </div>                                                                
                                                    </div>
                                                    <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                        <?php echo strlen($video['video_description']) > 100 ? substr($video['video_description'],0,100)." ..." : $video['video_description']; ?>
                                                    </div>
                                                </div>
                                            </article>
                                <?php       } // end of foreach
                                        } //end if
                                        else {
                                            ?>
                                            <article class="listing-article">
                                                <h3 class="alert alert-info text-center">
                                                    You haven't added any videos yet. Click the below button to add videos <br> <br>
                                                    <a href="<?php echo base_url('learning_center/add_online_video'); ?>" class="btn btn-success btn-lg"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add A Video</a>
                                                </h3>
                                            </article>
                                            <?php
                                        } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function func_delete_video(video_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this video?',
            function () {
                $('#form_delete_video_' + video_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }
</script>
