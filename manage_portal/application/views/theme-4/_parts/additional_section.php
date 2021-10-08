<div class="about-section">
    <?php 
        $class = 'col-lg-6 col-md-6 col-xs-12 col-sm-12';
        if($section['column_type']=='top_down') {
            $class = 'col-lg-12 col-md-12 col-xs-12 col-sm-12';
        }
        //
        if(isset($section['do_show_image']) && $section['do_show_image'] == 'off'){
            $section['show_video_or_image'] = '';
            $class = 'col-lg-12 col-md-12 col-xs-12 col-sm-12';
        }

        if($section['column_type']=='left_right' || $section['column_type']=='top_down') { ?>
            <div class="<?php echo $class; ?>">
                <div class="about-work">
                    <?php
                    if (isset($section['title']) && $section['title'] != '') {
                        $sectionTitle = $section['title'];
                        $sectionTitleArray = explode(' ', $sectionTitle, 2);
                    }
                    ?>
                    <div class="<?php echo $section['column_type']=='top_down' ? 'text-center' : ''?>"><h2 class="section-title"><?php echo isset($sectionTitleArray[0]) ? $sectionTitleArray[0] : ''; ?><span><?php echo isset($sectionTitleArray[1]) ? $sectionTitleArray[1] : ''; ?></span></h2></div>

                    <p><?php echo (isset($section['content']) && $section['content'] != '' ? $section['content'] : '') ?></p>
                </div>
            </div>
            <div class="<?php echo $class; ?> babau ji">
                <?php if($section['show_video_or_image']=='image') { ?>
                    <div class="about-work">
                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL; echo (isset($section['image']) && $section['image'] != '' ? $section['image'] : '') ?>" alt="Tab Image">
                    </div>
                <?php } else if($section['show_video_or_image']=='video') {
                    $url_prams = array();
                    parse_str(parse_url($section['video'], PHP_URL_QUERY), $url_prams);

                    if (isset($url_prams['v'])) {
                        $video_url = $url_prams['v'];
                    } else {
                        $video_url = $section['video'];
                    } ?>
                    <div class="about-work Hassan">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $video_url; ?>"></iframe>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }
        elseif($section['column_type']=='right_left' || $section['column_type']=='top_down'){ ?>
           <?php if($section['show_video_or_image'] != '') { ?> <div class="<?= $class?>"> <?php } ?>
            <?php if($section['show_video_or_image']=='image'){?>
                <div class="about-work">
                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL; echo (isset($section['image']) && $section['image'] != '' ? $section['image'] : '') ?>" alt="No Image Selected">
                </div>
            <?php } else if($section['show_video_or_image']=='video') {
                $url_prams = array();
                parse_str(parse_url($section['video'], PHP_URL_QUERY), $url_prams);

                if (isset($url_prams['v'])) {
                    $video_url = $url_prams['v'];
                } else {
                    $video_url = $section['video'];
                } ?>
                <div class="about-work">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $video_url; ?>"></iframe>
                    </div>
                </div>
                <?php }?>
                <?php if($section['show_video_or_image'] != '') { ?> </div> <?php }?>
            <div class="<?= $class?>">
                <div class="about-work">
                    <?php
                    if (isset($section['title']) && $section['title'] != '') {
                        $sectionTitle = $section['title'];
                        $sectionTitleArray = explode(' ', $sectionTitle, 2);
                    } ?>
                    <div class="<?php echo $section['column_type']=='top_down' ? 'text-center' : ''?>"><h2 class="section-title"><?php echo isset($sectionTitleArray[0]) ? $sectionTitleArray[0] : ''; ?><span><?php echo isset($sectionTitleArray[1]) ? $sectionTitleArray[1] : ''; ?></span></h2></div>
                    <!--            --><?php //echo (isset($section_03_meta['tag_line']) && $section_03_meta['tag_line'] != '' ? '<h4>' . $section_03_meta['tag_line'] . '</h4>' : '') ?>
                    <p><?php echo (isset($section['content']) && $section['content'] != '' ? $section['content'] : '') ?></p>
                </div>
            </div>
        <?php }?>
</div>