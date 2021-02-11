<div class="dashboard-menu visible-xs visible-lg visible-md visible-sm" style="min-height: inherit;">
    <ul>
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-th"></i>&nbsp;&nbsp;Dashboard</a></li>
        <li><a class="<?php echo ($active_link == 'resource_center' ? 'active' : '');?>" href="<?php echo base_url('resource_center'); ?>"><i class="fa fa-files-o"></i>&nbsp;&nbsp;Resource Center</a></li>
        
        <?php   if($left_navigation == 'parent') {
                    foreach($main_menu_url as $mmu) { ?>
                        <li><a href="<?php echo $mmu['link']; ?>"><i class="fa <?php echo $mmu['fa_icon']; ?>"></i>&nbsp;&nbsp;<?php echo $mmu['name']; ?></a></li>
        <?php       } 
                } else if($left_navigation == 'main') { ?>
                    <li><a class="<?php echo ($active_link == $left_menu_parent['code'] ? 'active' : '');?>" href="<?php echo $left_menu_parent['link']; ?>"><i class="fa <?php echo $left_menu_parent['fa_icon']; ?>"></i>&nbsp;&nbsp;<?php echo $left_menu_parent['name']; ?></a></li>
                    
        <?php       foreach($left_menu as $lm) { ?>
                        <li><a href="<?php echo $lm['link']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lm['name']; ?></a></li>
        <?php       }
                } else if($left_navigation == 'sub') { ?>
                    <li><a href="<?php echo $left_menu_parent['link']; ?>"><i class="fa <?php echo $left_menu_parent['fa_icon']; ?>"></i>&nbsp;&nbsp;<?php echo $left_menu_parent['name']; ?></a></li>
        <?php       foreach($left_menu as $lm) { ?>
                        <li><a class="<?php echo ($active_link == $lm['url_code'] ? 'active' : '');?>" href="<?php echo $lm['link']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lm['name']; ?></a></li>
        <?php       }
                } ?>
        <li><a href="<?php echo base_url('resource_page'); ?>" class="<?php echo ($active_link == 'resource_page' ? 'active' : '');?>"><i class="fa fa-briefcase"></i>&nbsp;&nbsp;Resources</a></li>
    </ul>
</div>
<?php if($active_link != 'resource_center') { ?>
<div class="dash-box service-contacts">
    <div class="admin-info">
        <h2>Ask the Pro</h2>
        <div class="full-width text-center">
            <p><strong>Need some help from one of our HR Pros?
                    Go ahead and ask. They’re standing by.</strong></p>
            <a href="javascript:;" class="site-btn text-capitalize">Ask the Pro</a>
        </div>
    </div>
</div>
<div class="dash-box service-contacts">
    <div class="admin-info">
        <h2>Need Help?</h2>
        <div class="full-width text-center">
            <p><strong>Having trouble finding what you need?
                    Chat live with your HR Concierge.
                    We’re here to help!</strong></p>
            <a href="javascript:;" class="site-btn text-capitalize">Ask the Pro</a>
        </div>
    </div>
</div>
<?php } ?>