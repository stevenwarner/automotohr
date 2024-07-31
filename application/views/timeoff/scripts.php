<!--  -->
<script>
let
    baseURL = "<?=rtrim(base_url(), '/');?>/",
    handlerURL = "<?=rtrim(base_url(), '/');?>/timeoff/handler",
    companyId = <?=isset($companyId) ? $companyId : $company_sid;?>,
    employerId = <?=isset($employerId) ? $employerId : $employer_sid;?>,
    level = <?=$level;?>,
    employeeId = <?=isset($employee_sid) ? $employee_sid : 0;?>,
    employeeName = "<?=isset($employee_name) ? $employee_name : '';?>",
    requestId = <?=isset($request_sid) ? $request_sid : 0;?>,
    timeoffDateFormat = 'MMM DD YYYY, ddd',
    timeoffDateFormatD = 'YYYY-MM-DD',
    timeoffDateFormatDWT = 'YYYY-MM-DD H:i:s',
    timeoffDateFormatB = 'MMM D',
    timeoffDateFormatBD = 'dddd',
    timeoffDateFormatWithTime = 'MMM DD YYYY, ddd hh:mm a',
    awsURL = "<?=AWS_S3_BUCKET_URL;?>",
    holidayDates = <?=isset($holidayDates) ? json_encode($holidayDates) : '[]';?>,
    timeOffDays = <?=isset($timeOffDays) ? json_encode($timeOffDays) : '[]';?>,
    page = "<?=isset($page) ? $page : ''?>";
</script>

<script src="<?=base_url('assets/select2/select2.min.js');?>"></script>
<script src="<?=base_url('assets/js/select2.multi-checkboxes.js');?>"></script>
<script src="<?=base_url('assets/js/pagination.min.js');?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>

<?php if (isset($theme) && $theme == 1) { ?>
    <!-- Common functions -->
    <script src="<?=base_url('assets/timeoff/js_theme_1/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <script src="<?=base_url('assets/timeoff/js_theme_1/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>

    <?php if(in_array('policies', $this->uri->segment_array())): ?>
        <!-- For policies -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/policy/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/policy/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/policy/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.1"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/policy/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.1"></script>
    <?php endif; ?>

    <?php if(in_array('types', $this->uri->segment_array())): ?>
        <!-- For types -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/type/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/type/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/type/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/type/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('holidays', $this->uri->segment_array())): ?>
        <!-- For holidays -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/holidays/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/holidays/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/holidays/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/holidays/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('settings', $this->uri->segment_array())): ?>
        <!-- For settings -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/setting/setting'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('approvers', $this->uri->segment_array())): ?>
        <!-- For approvers -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/approvers/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/approvers/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/approvers/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/approvers/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('balance', $this->uri->segment_array())): ?>
        <!-- For balance -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/balances/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/balances/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/mFileUploader/index.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('requests', $this->uri->segment_array())): ?>
        <!-- For requests -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/requests/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/requests/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('lms', $this->uri->segment_array())): ?>
        <!-- For lms -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/lms/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/lms/graph'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/lms/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/lms/policies'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('create_employee', $this->uri->segment_array())): ?>
        <!-- For employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/employee/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/employee/graph'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/employee/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/employee/policies'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('employee_management_system', $this->uri->segment_array())): ?>
        <!-- For ems dashboard -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/dashboard/dashboard'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/breakdown'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('dashboard', $this->uri->segment_array())): ?>
        <!-- For ems employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/dashboard/dashboard'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/breakdown'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>
    
    <?php if(in_array('report', $this->uri->segment_array())): ?>
        <!-- For ems employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/report'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>
<?php } else if (isset($theme) && $theme == 2) { ?>
    <!-- Common functions -->
    <script src="<?=base_url('assets/timeoff/js_theme_2/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <script src="<?=base_url('assets/timeoff/js_theme_2/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>

    <?php if(in_array('policies', $this->uri->segment_array())): ?>
        <!-- For policies -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/policy/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/policy/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/policy/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.1"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/policy/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.1"></script>
    <?php endif; ?>

    <?php if(in_array('types', $this->uri->segment_array())): ?>
        <!-- For types -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/type/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/type/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/type/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/type/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('holidays', $this->uri->segment_array())): ?>
        <!-- For holidays -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/holidays/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/holidays/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/holidays/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/holidays/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('settings', $this->uri->segment_array())): ?>
        <!-- For settings -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/setting/setting'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('approvers', $this->uri->segment_array())): ?>
        <!-- For approvers -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/approvers/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/approvers/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/approvers/add'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/approvers/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('balance', $this->uri->segment_array())): ?>
        <!-- For balance -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/balances/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/balances/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/mFileUploader/index.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('requests', $this->uri->segment_array())): ?>
        <!-- For requests -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/requests/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/requests/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('lms', $this->uri->segment_array())): ?>
        <!-- For lms -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/lms/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/lms/graph'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/lms/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/lms/policies'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('create_employee', $this->uri->segment_array())): ?>
        <!-- For employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/employee/common'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/employee/graph'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/employee/view'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/employee/policies'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/edit'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('employee_management_system', $this->uri->segment_array())): ?>
        <!-- For ems dashboard -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/dashboard/dashboard'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/breakdown'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>

    <?php if(in_array('dashboard', $this->uri->segment_array())): ?>
        <!-- For ems employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/dashboard/dashboard'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/breakdown'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>
    <?php if(in_array('report', $this->uri->segment_array())): ?>
        <!-- For ems employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/report'.( $GLOBALS['minified_version'] ).'.js');?>?v=3.0"></script>
    <?php endif; ?>
<?php } ?>