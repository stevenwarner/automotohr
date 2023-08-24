<!-- jQuery -->
<script src="<?php echo _m(base_url('assets/2022/js/jquery-1.11.3.min'), 'js', '1.11.3') ?>"></script>
<!-- jQuery UI -->
<script src="<?php echo _m(base_url('assets/2022/js/jquery-ui.min'), 'js', '1.0') ?>"></script>
<!-- Bootstrap -->
<script src="<?php echo _m(base_url('assets/2022/js/bootstrap.min', 'js', '1.0')) ?>"></script>
<!-- DateTimePicker -->
<script src="<?php echo _m(base_url('assets/2022/js/jquery.datetimepicker'), 'js', time()) ?>"></script>
<!-- Alertify -->
<script src="<?php echo _m(base_url('assets/2022/js/alertify.min'), 'js', '1.0') ?>"></script>
<!-- Select2 -->
<script src="<?php echo _m(base_url('assets/2022/js/select2'), 'js', time()) ?>"></script>
<!-- Moment -->
<script type="text/javascript" src="<?= _m(base_url('assets/2022/js/moment.min'), 'js', time()); ?>"></script>
<script type="text/javascript" src="<?= base_url(_m('assets/js/uri', 'js', '1.0.1')); ?>"></script>
<script type="text/javascript" src="<?= base_url(_m('assets/js/common', 'js', '1.0.2')); ?>"></script>
<?php if ($this->session->userdata('logged_in')) : ?>
    <script type="text/javascript" src="<?= base_url(_m('assets/portal/app', 'js', '1.0.0')); ?>"></script>
<?php endif; ?>
<!-- Google translator scrips -->
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<!-- App -->
<script type="text/javascript" src="<?= _m(base_url('assets/2022/js/app'), 'js', time()); ?>"></script>
<!--  -->
<link rel="stylesheet" href="<?= base_url("assets/css/SystemModel.css"); ?>">
<script src="<?= base_url("assets/js/SystemModal.js"); ?>"></script>
<!--  -->
<?php if ($this->session->userdata('logged_in')) : ?>
    <script>
        /**
         * Token
         */
        var cToken = <?= $session['company_detail']['sid'] ?? 0 ?>;
        var eToken = <?= $session['employer_detail']['sid'] ?? 0 ?>;
        /**
         * Set base url
         * @type {string}
         */
        var baseURI = "<?php echo rtrim(base_url(), '/'); ?>/";
        var apiURI = "<?php echo getCreds("AHR")->API_BROWSER_URL; ?>";
    </script>
<?php endif; ?>
<?php if (isset($PageScripts)) {
    echo '<!-- Dynamic Scripts -->';
    echo GetScripts($PageScripts);
} ?>
<?= $appJs ?? ''; ?>
<!--  -->
<script src="<?php echo _m(base_url('assets/employee_survey/js/create'), 'js', time()) ?>"></script>