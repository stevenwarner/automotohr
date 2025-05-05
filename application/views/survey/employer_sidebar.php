<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('dashboard')) !== false) {
                echo 'class="active"';
            } ?>  href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('survey/templates')) !== false) {
                echo 'class="active"';
            } ?> href="<?php echo base_url('survey/templates') ?>">
                <figure><i class="fa fa-files-o"></i></figure>Templates
            </a>
        </li>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('survey/listing')) !== false) {
                echo 'class="active"';
            } ?> href="<?php echo base_url('survey/listing') ?>">
                <figure><i class="fa fa-file"></i></figure>Surveys
            </a>
        </li>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('survey/report')) !== false) {
                echo 'class="active"';
            } ?> href="<?php echo base_url('survey/report') ?>">
                <figure><i class="fa fa-pie-chart"></i></figure>Report
            </a>
        </li>


    </ul>
</div>
<div class="dash-box service-contacts hidden-xs">
    <div class="admin-info">
        <h2>Need help with your AutomotoHR Platform? <br />Contact one of our Talent Network Partners at</h2>
        <div class="profile-pic-area">
            <div class="form-col-100">
                <ul class="admin-contact-info">
                    <li>
                        <label>Sales Support</label>
                        <?php $data['session'] = $this->session->userdata('logged_in'); ?>
                        <?php $company_sid = $data["session"]["company_detail"]["sid"]; ?>
                        <?php $company_info = get_contact_info($company_sid); ?>
                        <span><i class="fa fa-phone"></i>
                            <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_phone_no'] : TALENT_NETWORK_SALE_CONTACTNO; ?></span>
                        <span><a
                                href="mailto:<?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?>"><i
                                    class="fa fa-envelope-o"></i>
                                <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?></a></span>
                    </li>
                    <li>
                        <label>Technical Support</label>
                        <span><i class="fa fa-phone"></i>
                            <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_phone_no'] : TALENT_NETWORK_SUPPORT_CONTACTNO; ?></span>
                        <span><a
                                href="mailto:<?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?>"><i
                                    class="fa fa-envelope-o"></i>
                                <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- -->
<?php
$getCompanyHelpboxInfo = get_company_helpbox_info($company_sid);
if ($getCompanyHelpboxInfo[0]['box_status'] == 1) {
    ?>
    <div class="dash-box service-contacts hidden-xs">
        <div class="admin-info">
            <h2><?php echo $getCompanyHelpboxInfo[0]['box_title']; ?></h2>
            <div class="profile-pic-area">
                <div class="form-col-100">
                    <ul class="admin-contact-info">
                        <li>
                            <label>Support</label>
                            <?php if ($getCompanyHelpboxInfo[0]['box_support_phone_number']) { ?>
                                <span><i class="fa fa-phone"></i>
                                    <?php echo $getCompanyHelpboxInfo[0]['box_support_phone_number']; ?></span><br>
                            <?php } ?>
                            <span>
                                <button class="btn btn-orange jsCompanyHelpBoxBtn">
                                    <i class="fa fa-envelope-o"
                                        aria-hidden="true"></i>&nbsp;<?= $getCompanyHelpboxInfo[0]['button_text']; ?>
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