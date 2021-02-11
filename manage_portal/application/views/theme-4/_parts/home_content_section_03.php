<div class="about-section">
    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
        <div class="about-work">
            <?php   if (isset($section_03_meta['title']) && $section_03_meta['title'] != '') {
                        $sectionTitle = $section_03_meta['title'];
                        $sectionTitleArray = explode(' ', $sectionTitle, 2);
                    } ?>
            <h2 class="section-title"><?php echo isset($sectionTitleArray[0]) ? $sectionTitleArray[0] : ''; ?><span><?php echo isset($sectionTitleArray[1]) ? $sectionTitleArray[1] : ''; ?></span></h2>
                <?php echo (isset($section_03_meta['tag_line']) && $section_03_meta['tag_line'] != '' ? '<h4>' . $section_03_meta['tag_line'] . '</h4>' : '') ?>
            <p><?php echo (isset($section_03_meta['content']) && $section_03_meta['content'] != '' ? $section_03_meta['content'] : '') ?></p>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
        <div class="tab-image">
            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL; echo (isset($section_03_meta['image']) && $section_03_meta['image'] != '' ? $section_03_meta['image'] : '') ?>" alt="Tab Image">
        </div>
    </div>
</div>