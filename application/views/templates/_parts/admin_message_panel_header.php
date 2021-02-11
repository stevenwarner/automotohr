<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="add-new-promotions">
    <a class="site-btn" href="<?php echo site_url('manage_admin/private_messages');?>"> Inbox  <span>(<?= $total_messages ?>)</span></a>
    <?php if(in_array('full_access', $security_details) || in_array('private_messages_outbox', $security_details)){ ?>
        <a class="site-btn" href="<?php echo site_url('manage_admin/outbox');?>"> Outbox </a> 
    <?php } ?>
    <?php if(in_array('full_access', $security_details) || in_array('compose_private_message', $security_details)){ ?>
        <a class="site-btn" href="<?php echo site_url('manage_admin/compose_message');?>"> Compose Message </a>
    <?php } ?>
</div>