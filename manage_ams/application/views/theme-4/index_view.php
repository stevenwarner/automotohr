<div class="main">
    <div class="container-fluid">
        <div class="bottom-btn-row top-aplly-btn">
            <ul>
                <li>
            <?php   if($enable_home_job_button == 1) { ?>
                        <a href="<?php echo base_url($jobs_page_title); ?>" class="site-btn bg-color"><?php echo $home_job_button_text; ?></a>
            <?php   } ?>
            <?php   if($theme4_enable_job_fair_homepage == 1 && !empty($job_fairs)) { ?>
                        <a href="<?php echo base_url('/job_fair/'); ?>" class="site-btn fair_customizations"><?php echo $job_fairs['title']; ?></a>
            <?php   } ?>
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