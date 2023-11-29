<?php if (checkIfAppIsEnabled('attendance') && $this->session->userdata('logged_in')) : ?>
    <!-- Attendance -->
    <script>
        apiURL = "<?= getCreds('AHR')->API_BROWSER_URL; ?>";
        apiAccessToken = "<?= getApiAccessToken(
                                $this->session->userdata("logged_in")['employer_detail']['sid'],
                                $this->session->userdata("logged_in")['company_detail']['sid']
                            ); ?>";
    </script>
    <?php // echo bundleJs(["v1/common","v1/attendance/js/timer",], "public/v1/js/attendance/", "timer",  true); ?>
<?php endif; ?>