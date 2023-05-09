<?php if($theme_name == 'theme-4'){?>
    <?php if(!($customize_career_site['status'] == 1 && in_array('home',$customize_career_site['inactive_pages']))){  ?>
    <li <?php echo ($pageName == 'home' ? 'class="active"' : '' ); ?>>
        <a href="<?php echo base_url('/'); ?>"><i class="fa fa-home"></i>&nbsp;Home</a>
    </li>
    <?php } ?>
    <li <?php echo ($pageName == 'jobs' ? 'class="active"' : '' ); ?>>
        <a href="<?php echo base_url(strtolower(str_replace(' ', '_', $jobs_page_title))); ?>"><?php echo ucwords((str_replace(['-', '_'], ' ', $jobs_page_title))); ?></a>
    </li>
    <?php   if ($isPaid) {
        foreach ($pages as $page) {
            if ($page['page_status'] == 1 && !($customize_career_site['status'] == 1 && in_array($page['page_name'],$customize_career_site['inactive_pages']))) { ?>
                <li <?php echo($pageName == $page['page_name'] ? 'class="active"' : ''); ?> >
                    <a href="<?php echo base_url($page['page_name']); ?>"><?php echo $page['page_title']; ?></a>
                </li>
            <?php           }
        }
    } ?>
    <?php   if (!empty($dealership_website) && !($customize_career_site['status'] == 1 && in_array('company_website',$customize_career_site['inactive_pages']))) { ?>
        <li><a href="<?php echo $dealership_website; ?>" target="_blank">Company Website</a></li>
    <?php   } ?>
<?php }else if($theme_name == 'theme-3' || $theme_name == 'theme-2'){  ?>

    <?php if(!($customize_career_site['status'] == 1 && in_array('home',$customize_career_site['inactive_pages']))){  ?>
    <li <?php if ($this->uri->segment(1) != 'contact_us') { ?>class="active" <?php } ?>><a href="<?php echo base_url('/'); ?>"><i class="fa fa-home"></i> home</a></li>
    <?php } ?>
    <?php if (!empty($dealership_website) && !($customize_career_site['status'] == 1 && in_array('company_website',$customize_career_site['inactive_pages']))) { ?>
        <li><a href="<?php echo $dealership_website; ?>" target="_blank">Company Website</a></li>
    <?php } ?>
    <?php if ($contact_us_page && !($customize_career_site['status'] == 1 && in_array('contact_us',$customize_career_site['inactive_pages']))) { ?>
        <li <?php if ($this->uri->segment(1) == 'contact_us') { ?>class="active" <?php } ?>><a href="<?php echo base_url('/contact_us'); ?>  "><i class="fa fa-tty"></i>contact</a></li>
    <?php } ?>
<?php }else if($theme_name == 'theme-1'){ ?>
    <?php if(!($customize_career_site['status'] == 1 && in_array('home',$customize_career_site['inactive_pages']))){  ?>
    <li <?php if ($this->uri->segment(1) != 'contact_us') { ?>class="active" <?php } ?>><a href="<?php echo base_url('/'); ?>">home</a></li>
    <?php } ?>
    <?php if (!empty($dealership_website) && !($customize_career_site['status'] == 1 && in_array('company_website',$customize_career_site['inactive_pages']))) { ?>
        <li><a href="<?php echo $dealership_website; ?>" target="_blank">Company Website</a></li>
    <?php } ?>
    <?php if ($contact_us_page && !($customize_career_site['status'] == 1 && in_array('contact_us',$customize_career_site['inactive_pages']))) { ?>
        <li <?php if ($this->uri->segment(1) == 'contact_us') { ?>class="active" <?php } ?>><a href="<?php echo base_url('/contact_us'); ?>"></i>contact</a></li>
    <?php } ?>
<?php } ?>