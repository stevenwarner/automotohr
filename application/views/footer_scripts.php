<script type="text/javascript" src="<?= base_url(_m('assets/js/uri', 'js', '1.0.1')); ?>"></script>
<script type="text/javascript" src="<?= base_url(_m('assets/js/common', 'js', '1.0.1')); ?>"></script>
<?php if(isset($add_app) && $this->session->userdata('logged_in')): ?>
<script type="text/javascript" src="<?= base_url(_m('assets/portal/app', 'js', '1.0.0')); ?>"></script>
<?php endif; ?>
<?php if($this->session->userdata('logged_in')): ?>
<script>
    companyId = <?= $session['company_detail']['sid'] ?>;
    /**
     * Set base url
     * @type {string}
     */
    var baseURI = "<?php echo rtrim(base_url(), '/'); ?>/";
</script>
<?php endif; ?>
<?php if (isset($PageScripts)) {
    echo '<!-- Dynamic Scripts -->';
    echo GetScripts($PageScripts);
} ?>
<?php if (checkIfAppIsEnabled('attendance') && $this->session->userdata('logged_in') && in_array($this->uri->uri_string(), ['payroll', 'dashboard'])) : ?>
    <script src="<?= base_url(_m("assets/payroll/js/payroll_company_onboard", 'js', '1.0.1')); ?>"></script>
    <script src="<?= base_url(_m("assets/payroll/js/employee_onboard", 'js', '1.0.1')); ?>"></script>
<?php endif; ?>
<?php if (checkIfAppIsEnabled('attendance') && $this->session->userdata('logged_in')) : ?>
    <script src="<?= base_url(_m("assets/attendance/js/main", 'js', '1.0.0')); ?>"></script>
<?php endif; ?>