<div class="hr-left-nav">
    <ul>
        <li><a href="<?php echo base_url(); ?>my_settings"> &laquo; Back</a></li>
        <li<?php if ($this->uri->segment(1)=="seo_tags"){echo ' class="active"';}?>><a href="<?php echo base_url(); ?>seo_tags">Seo Tags</a></li>
        <li<?php if ($this->uri->segment(1)=="embedded_code"){echo ' class="active"';}?>><a href="<?php echo base_url(); ?>embedded_code">Embedded Code</a></li>
        <li<?php if ($this->uri->segment(1)=="portal_widget"){echo ' class="active"';}?>><a href="<?php echo base_url(); ?>portal_widget">portal Widget</a></li>
<!--        <li<?php if ($this->uri->segment(1)=="web_services"){echo ' class="active"';}?>><a href="<?php echo base_url(); ?>web_services">Web Services</a></li>-->
        <li<?php if ($this->uri->segment(1)=="domain_management"){echo ' class="active"';}?>><a href="<?php echo base_url(); ?>domain_management">Domain Management</a></li>
        <li<?php if ($this->uri->segment(1)=="social_links"){echo ' class="active"';}?>><a href="<?php echo base_url(); ?>social_links">Social Links</a></li>
    </ul>
</div>