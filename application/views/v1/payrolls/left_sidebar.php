<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('dashboard')) !== false) {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>
    </ul>
</div>


<!-- -->
<?php
$getCompanyHelpBox = get_company_helpbox_info($company_sid);
if ($getCompanyHelpBox[0]['box_status'] == 1) {
?>
    <div class="dash-box service-contacts hidden-xs">
        <div class="admin-info">
            <h2><?php echo $getCompanyHelpBox[0]['box_title']; ?></h2>
            <div class="profile-pic-area">
                <div class="form-col-100">
                    <ul class="admin-contact-info">
                        <li>
                            <label>Support</label>
                            <?php if ($getCompanyHelpBox[0]['box_support_phone_number']) { ?>
                                <span>
                                    <i class="fa fa-phone"></i>&nbsp;
                                    <?php echo $getCompanyHelpBox[0]['box_support_phone_number']; ?>
                                </span>
                                <br />
                            <?php } ?>
                            <span>
                                <button class="btn btn-orange jsCompanyHelpBoxBtn">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    &nbsp;<?= $getCompanyHelpBox[0]['button_text']; ?>
                                </button>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('company_help_box_script'); ?>
<?php } ?>