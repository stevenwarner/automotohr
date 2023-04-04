<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">              
                <div class="bottom-btn-row top-aplly-btn">
                    <ul>
                        <li>
                <?php       if($pageData['job_opportunities'] == '1') { ?>
                                <a href="<?php echo base_url(str_replace('','_',strtolower($jobs_page_title))); ?>" class="site-btn bg-color"><?php echo $pageData['job_opportunities_text']; ?></a>
                <?php       }

                            if(!empty($job_fairs) && $pageData['job_fair'] == '1') { 
                                $fair_page_url = $pageData['job_fair_page_url'];
                                
                                if($fair_page_url == '' || $fair_page_url == NULL) {
                                    foreach($job_fairs as $fair_value) {
                                        if($fair_value['status'] == 1) {
                                            $fair_page_url = $fair_value['page_url'];
                                        }
                                    }
                                    $button_background_color = $job_fairs[$fair_page_url]['button_background_color'];   
                                    $button_text_color = $job_fairs[$fair_page_url]['button_text_color']; ?>
                                    <a href="<?php echo base_url('/job_fair').'/'.$fair_page_url; ?>" class="site-btn" style="background: <?=$button_background_color?>; color: <?=$button_text_color;?>">
                                        <?php echo $job_fairs[$fair_page_url]['title']; ?>
                                    </a>
                                    <?php
                                } else{
                                    //
                                    $jobFairs = explode(',', $fair_page_url);
                                    //
                                    foreach($jobFairs as $jb){
                                        $button_background_color = $job_fairs[$jb]['button_background_color'];   
                                        $button_text_color = $job_fairs[$jb]['button_text_color']; 
                                        ?>
                                        <a href="<?php echo base_url('/job_fair').'/'.$jb; ?>" class="site-btn" style="background: <?=$button_background_color?>; color: <?=$button_text_color;?>">
                                            <?php echo $job_fairs[$jb]['title']; ?>
                                        </a>
                                        <?php
                                    }
                                }
                                ?>
                

                <?php       } ?>
                        </li>                       
                    </ul>
                </div>
                <div class="hr-text-editor">
                    <header class="heading-title border-bottom">
                        <h1 class="section-title color"><?php echo $pageData['page_title'];?> </h1>
                    </header>
                    <?php if($pageData['video_location'] == 'top') { ?>
                        <?php if($pageData['page_youtube_video_status'] == 1) { ?>
                            <?php if(!empty($pageData['page_youtube_video'])){ ?>
                                <div class="comapny-video <?php if($header_video_overlay == 0) {echo 'no_overlay';} ?>">
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