<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">              
                <div class="bottom-btn-row top-aplly-btn">
                    <ul>
                        <li>
                            <?php   if($pageData['job_opportunities'] == '1'){ ?>
                                        <a href="<?php echo base_url($jobs_page_title); ?>" class="site-btn bg-color"><?php echo $pageData['job_opportunities_text']; ?></a>
                            <?php   } ?>
                            <?php   if(!empty($job_fairs) && $pageData['job_fair'] == '1') { ?>
                                        <a href="<?php echo base_url('/job_fair/'); ?>" class="site-btn fair_customizations"><?php echo $job_fairs['title']; ?></a>
                            <?php   } ?>
                        </li>                       
                    </ul>
                </div>
                <div class="hr-text-editor">
                    <header class="heading-title border-bottom">
                        <h1 class="section-title color"><?php echo $pageData['page_title'];?> </h1>
                    </header>
                    <?php if($pageData['video_location'] == 'top'){ ?>
                        <?php if($pageData['page_youtube_video_status'] == 1) { ?>
                            <?php if(!empty($pageData['page_youtube_video'])){ ?>
                                <div class="comapny-video">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $pageData['page_youtube_video'];?>"></iframe>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo html_entity_decode($pageData['page_content']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if($pageData['video_location'] == 'bottom'){ ?>
    <?php if($pageData['page_youtube_video_status'] == 1) { ?>
        <?php if(!empty($pageData['page_youtube_video'])){ ?>
            <div class="comapny-video">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $pageData['page_youtube_video'];?>"></iframe>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>