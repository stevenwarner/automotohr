<?php $session = $this->session->userdata('logged_in'); ?>
<script type="text/javascript" src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('public/v1/plugins/moment/moment-timezone.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url(_m('assets/js/uri', 'js', '1.0.1')); ?>"></script>
<script type="text/javascript" src="<?= base_url(_m('assets/js/common', 'js', '1.0.2')); ?>"></script>
<?php if (isset($add_app) && $this->session->userdata('logged_in')) : ?>
    <script type="text/javascript" src="<?= base_url(_m('assets/portal/app', 'js', '1.0.0')); ?>"></script>
<?php endif; ?>
<?php if ($this->session->userdata('logged_in')) : ?>
    <script>
        companyId = <?= $session['company_detail']['sid'] ?>;
        <?php if ($this->uri->segment(2) == 'create_employee') { ?>
            employeeId = <?= $this->uri->segment(3) ?>;
        <?php } else { ?>
            employeeId = <?= $session['employer_detail']['sid'] ?>;
        <?php } ?>
        /**
         * Set base url
         * @type {string}
         */
        var baseURI = "<?php echo rtrim(base_url(), '/'); ?>/";
        var googleMapKey = '<?php echo GOOGLE_MAP_API_KEY ?>';
    </script>
<?php endif; ?>
<?php if (isset($PageScripts)) {
    echo '<!-- Dynamic Scripts -->';
    echo GetScripts($PageScripts);
} ?>

<?php $this->load->view("v1/attendance/footer_scripts"); ?>
<?= $appJs ?? ''; ?>