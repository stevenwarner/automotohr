<div class="container-fluid">
    <!-- Our Sponsors -->
    <section class="sponsors text-center">
        <div class="container-fluid">
            <div class="row">
                <?php
                if(isset($section_05_meta['title']) && $section_05_meta['title'] != ''){
                    $sectionTitle = $section_05_meta['title'];

                    $sectionTitleArray = explode(' ', $sectionTitle, 2);
                }
                ?>
                <h2 class="section-title"><?php echo isset($sectionTitleArray[0]) ? $sectionTitleArray[0] : ''; ?> <span><?php echo isset($sectionTitleArray[1]) ? $sectionTitleArray[1] : ''; ?></span></h2>
                <div class="section-details">
                    <?php foreach($partners as $partner){?>
                        <div class="col-sm-3 col-xs-6">
                            <a class="logo-link" href="<?php echo $partner['txt_partner_url']?>" target="_blank">
                            <div class="sponsors-logo flex-center">
                                <img src="<?php echo AWS_S3_BUCKET_URL; echo (isset($partner['file_partner_logo']) && $partner['file_partner_logo'] != '' ?  $partner['file_partner_logo']  : '')?>" alt="Sponsors Logo">
                                
                            </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Our Sponsors -->
</div>