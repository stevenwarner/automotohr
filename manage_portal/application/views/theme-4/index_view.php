<div class="main">
    <div class="container-fluid">
        <div class="bottom-btn-row top-aplly-btn">
            <ul>
                <li>
            <?php   if($enable_home_job_button == 1) { ?>
                        <a href="<?php echo base_url(strtolower(str_replace(" ","_",$jobs_page_title))); ?>" class="site-btn bg-color"><?php echo $home_job_button_text; ?></a>
            <?php   }

                    if($theme4_enable_job_fair_homepage == 1 && !empty($job_fairs)) { 
                        //
                        $jobFairs = explode(',', $job_fair_homepage_page_url);
                        //
                        foreach($jobFairs as $jf){
                            $fair_title = $job_fairs[$jf]['title'];  
                            $button_background_color = $job_fairs[$jf]['button_background_color'];   
                            $button_text_color = $job_fairs[$jf]['button_text_color'];
                        ?>
                            <a href="<?php echo base_url('/job_fair').'/'.$jf; ?>" class="site-btn" style="background: <?=$button_background_color?>; color: <?=$button_text_color;?>">
                                <?php echo $fair_title; ?>
                            </a>
                        <?php
                        }

            } ?>
                </li>
            </ul>
        </div>
        <?php  

        if (isset($section_02_meta['status']) && intval($section_02_meta['status']) == 1) {
            //$this->load->view($theme_name . '/_parts/home_content_section_02');
            $view_data = array();
            $view_data['section'] = $section_02_meta;
            $this->load->view($theme_name . '/_parts/additional_section', $view_data);
        }

        if (isset($section_03_meta['status']) && intval($section_03_meta['status']) == 1) {
            //$this->load->view($theme_name . '/_parts/home_content_section_03');
            $view_data = array();
            $view_data['section'] = $section_03_meta;
            $this->load->view($theme_name . '/_parts/additional_section', $view_data);
        }

        if (sizeof($additional_sections) > 0) {
            foreach ($additional_sections as $add_sec) {
                $view_data = array();
                $view_data['section'] = $add_sec;
                if ($add_sec['status'] == 1) {
                    $this->load->view($theme_name . '/_parts/additional_section', $view_data);
                }
            }
        }
        ?>

        <div class="clearfix"></div>
    </div>
    <?php
    if (isset($section_04_meta['status']) && intval($section_04_meta['status']) == 1) {
        $this->load->view($theme_name . '/_parts/home_content_section_04');
    }

    if (isset($section_05_meta['status']) && intval($section_05_meta['status']) == 1) {
        $this->load->view($theme_name . '/_parts/home_content_section_05');
    }

    if (isset($section_06_meta['status']) && intval($section_06_meta['status']) == 1) {
        $this->load->view($theme_name . '/_parts/home_content_section_06');
    }
    ?>
</div>