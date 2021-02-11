<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree" id="mynav">
            <li <?php echo $this->uri->segment(1) == 'dashboard' ? 'class="active"' : ''?>>
                <a href="<?= base_url() ?>dashboard">
                    <i class="fa fa-dashboard"></i> 
                    <span>Dashboard</span>
                </a>
            </li>
            <li <?php echo $this->uri->segment(1) == 'view-profile' ? 'class="active"' : ''?>>
                <a href="<?= base_url('view-profile') ?>">
                    <i class="fa fa-users"></i>
                    <span>View Profile</span>
                </a>
            </li>
            <li <?php echo $this->uri->segment(1) == 'login-credentials' ? 'class="active"' : ''?>>
                <a href="<?= base_url('login-credentials') ?>">
                    <i class="fa fa-key"></i>
                    <span>Login Credentials</span>
                </a>
            </li>
            <?php if(check_access_permissions_for_view($security_details, 'refer_client')) { ?>
                <li <?php echo $this->uri->segment(1) == 'refer-potential-clients' ? 'class="active"' : ''?>>
                    <a href="<?= base_url('refer-potential-clients') ?>">
                        <i class="fa fa-users"></i>
                        <span>Refer Potential Clients</span>
                    </a>
                </li>
            <?php } if(check_access_permissions_for_view($security_details, 'view_referred_clients')) {?>
            <li <?php echo $this->uri->segment(1) == 'view-referral-clients' ? 'class="active"' : ''?>>
                <a href="<?= base_url('view-referral-clients') ?>">
                    <i class="fa fa-list"></i>
                    <span>View Referred Clients</span>
                </a>
            </li>
            <?php } if(check_access_permissions_for_view($security_details, 'paying_clients')) {?>
            <li <?php echo $this->uri->segment(1) == 'my-current-paying-clients' ? 'class="active"' : ''?>>
                <a href="<?= base_url('my-current-paying-clients') ?>">
                    <i class="fa fa-users"></i> 
                    <span>My Current Paying Clients</span>
                </a>
            </li>
            <?php } if(check_access_permissions_for_view($security_details, 'payment_voucher')) {?>
            <li <?php echo $this->uri->segment(1) == 'invoice' ? 'class="active"' : ''?>>
                <a href="<?= base_url('invoice') ?>">
                    <i class="fa fa-money"></i> 
                    <span>Payment Vouchers</span>
                </a>
            </li>
            <?php }?>
<!--            <li --><?php //echo $this->uri->segment(1) == 'affiliate-advertising' ? 'class="active"' : ''?><!-->
<!--                <a href="--><?//= base_url('affiliate-advertising') ?><!--">-->
<!--                    <i class="fa fa-globe"></i> -->
<!--                    <span>Affiliate Advertising</span>-->
<!--                </a>-->
<!--            </li>-->
            <?php if(check_access_permissions_for_view($security_details, 'refer_affiliate')) {?>
            <li <?php echo $this->uri->segment(1) == 'refer-an-affiliate' ? 'class="active"' : ''?>>
                <a href="<?= base_url('refer-an-affiliate') ?>">
                    <i class="fa fa-map-signs"></i>
                    <span>Refer an Affiliate</span>
                </a>
            </li>
            <?php } if(check_access_permissions_for_view($security_details, 'view_referred_affiliates')) {?>
            <li <?php echo $this->uri->segment(1) == 'view-referral-affiliates' ? 'class="active"' : ''?>>
                <a href="<?= base_url('view-referral-affiliates') ?>">
                    <i class="fa fa-list"></i>
                    <span>View Referred Affiliates</span>
                </a>
            </li>
            <?php } if(check_access_permissions_for_view($security_details, 'private_messages')) {?>
            <li <?php echo $this->uri->segment(1) == 'inbox' || $this->uri->segment(1) == 'outbox' || $this->uri->segment(1) == 'compose-messages' || $this->uri->segment(2) == 'view_message' ? 'class="active"' : ''?>>
                <a href="<?= base_url('inbox') ?>">
                    <i class="fa fa-envelope"></i>
                    <span>Private Messages</span>
                </a>
            </li>
        <?php } //if(check_access_permissions_for_view($security_details, 'documents')) {?>
            <li <?php echo $this->uri->segment(1) == 'documents'  ? 'class="active"' : ''?>>
                <a href="<?= base_url('documents') ?>">
                    <i class="fa fa-file"></i>
                    <span>Documents</span>
                </a>
            </li>
            <?php //}?>
        </ul>
    </section>
</aside>