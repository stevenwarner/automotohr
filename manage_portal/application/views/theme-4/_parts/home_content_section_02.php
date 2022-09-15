<div class="bottom-btn-row top-aplly-btn">
    <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
    <ul>
        <li><a href="<?php echo base_url(strtolower(str_replace(" ","_",$jobs_page_title))); ?>" class="site-btn bg-color">View Job Opportunities</a></li>
    </ul>
</div>
<div class="about-section">	
    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
        <div class="tab-image">
            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL; echo (isset($section_02_meta['image']) && $section_02_meta['image'] != '' ?  $section_02_meta['image']  : '')?>" alt="Tab Image">
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
        <div class="about-work">
    <?php   if(isset($section_02_meta['title']) && $section_02_meta['title'] != ''){
                    $sectionTitle = $section_02_meta['title'];
                    $sectionTitleArray = explode(' ', $sectionTitle, 2);
            } ?>
            
            <h2 class="section-title"><?php echo isset($sectionTitleArray[0]) ? $sectionTitleArray[0] : ''; ?><span><?php echo isset($sectionTitleArray[1]) ? $sectionTitleArray[1] : ''; ?></span></h2>
            <?php echo (isset($section_02_meta['tag_line']) && $section_02_meta['tag_line'] != '' ? '<h4>' . $section_02_meta['tag_line'] . '</h4>' : '')?>
            <p><?php echo (isset($section_02_meta['content']) && $section_02_meta['content'] != '' ?  $section_02_meta['content']  : '')?></p>
        </div>
    </div>
</div>