<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3" style="margin-top: 18px;">
                        <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block mb-2" style="background: ##3554dc !important;">
                            <i class="fa fa-angle-left"></i> 
                            Back to Incident
                        </a>
                    </div>
                </div>
                <div class="page-header full-width">
                    <h1 class="section-ttile"><?php echo $title; ?></h1>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">    
                <div class="embed-responsive embed-responsive-16by9">
                    <?php $source = $incident_video['video_type']; ?>
                    <?php if ($source == 'youtube') { ?>
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $incident_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                    <?php } else if ($source == 'vimeo') { ?>
                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $incident_video['video_url']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                    <?php } else if ($source == 'upload_video') { ?>
                        <video controls>
                            <source src="<?php echo base_url().'assets/uploaded_videos/incident_videos/'.$incident_video['video_url']; ?>" type='video/mp4'>
                        </video>
                    <?php } else if ($source == 'upload_audio') { ?>
                        <p style="color: #cc0000;">
                            <b>
                                <i>
                                    We suggest that you only use Google Chrome to listen audio.
                                </i>
                            </b>
                        </p>
                        <hr>
                        <audio controls>
                            <source src="<?php echo base_url().'assets/uploaded_videos/incident_videos/'.$incident_video['video_url']; ?>" type="">
                        </audio>
                    <?php } ?>
                </div>
            </div>      
        </div>
    </div>
</div>