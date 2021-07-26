<?php
    $version = time();
    $prefixJS = '';
?>

<!--  -->
<script>
    /**
     * Add performance management object 
     * to window object
     */
    window.pm = {
        urls: {
            base: "<?=rtrim(base_url(), '/');?>/",
            pbase: "<?=rtrim(base_url(), '/');?>/performance-management/",
            handler: "<?=rtrim(base_url(), '/');?>/performance-management/handler/",
            aws: "<?=AWS_S3_BUCKET_URL;?>"
        },
        dateTimeFormats: {
            ymdf: 'MM/DD/YYYY',
            ymd: 'YYYY-MM-DD',
            ymdt: 'YYYY-MM-DD H:i:s',
            md: 'MMM D',
            d: 'dddd',
            mdy: 'MMM DD YYYY, ddd',
            mdyt: 'MMM DD YYYY, ddd hh:mm a'
        },
        companyId: <?=$companyId;?>,
        companyName: "<?=$session['company_detail']['CompanyName'];?>",
        companyLogo: "<?=$session['company_detail']['Logo'];?>",
        employerId: <?=$employerId;?>,
        employee: {
            id: <?=isset($employeeId) ? $employeeId : 0;?>,
            name: "<?=isset($employeeName) ? $employeeName : '';?>",
            level: <?=$level;?>
        }
    };
    
    <?php if(isset($review)):?>
        window.pm.review = <?=json_encode($review);?>;
    <?php endif; ?>
    
    
    <?php if(isset($review) && isset($company_employees)):?>
        // Employees
        window.pm.employees = <?=json_encode($company_employees);?>;
    <?php endif; ?>
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="<?=base_url('assets/select2/select2.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/lodash/loadash.min.js');?>"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- Common functions -->
<script src="<?=base_url('assets/performance_management/js/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<!-- Create Goal -->
<script src="<?=base_url('assets/performance_management/js/goals/create'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<script src="<?=base_url('assets/performance_management/js/goals/events'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>

<?php 
    if(strpos($this->uri->uri_string(), 'review/create') !== false){
        ?>
        <link rel="stylesheet" type="text/css" href="<?=base_url('assets/mFileUploader/index.css');?>" />
        <script type="text/javascript" src="<?=base_url('assets/mFileUploader/index.js');?>"></script>
        <script src="<?=base_url('assets/performance_management/js/video_record'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <script type="text/javascript" src="<?=base_url('assets/performance_management/js/theme2/create'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <?php
    }
    ?>

<!-- Feedback -->
<?php 
    if(
        strpos($this->uri->uri_string(), 'feedback') !== false ||
        strpos($this->uri->uri_string(), 'review') !== false
        ){
            ?>
        <link rel="stylesheet" type="text/css" href="<?=base_url('assets/mFileUploader/index.css');?>" />
        <script type="text/javascript" src="<?=base_url('assets/mFileUploader/index.js');?>"></script>
        <script type="text/javascript" src="<?=base_url('assets/performance_management/js/theme2/review/index'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <?php
    }
    ?>

<!-- Reviews listing -->
<?php 
    if(
        strpos($this->uri->uri_string(), 'reviews') !== false
        ){
            ?>
        <script type="text/javascript" src="<?=base_url('assets/performance_management/js/theme2/reviews/index'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <?php
    }
    ?>

<!-- Single Review listing -->
<?php 
    if(
        strpos($this->uri->uri_string(), 'review') !== false
        ){
            ?>
        <script type="text/javascript" src="<?=base_url('assets/performance_management/js/theme2/single_review/index'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <?php
    }
    ?>

<!-- Single Review listing -->
<?php 
    if(
        strpos($this->uri->uri_string(), 'my-reviews') !== false
        ){
            ?>
        <script type="text/javascript" src="<?=base_url('assets/performance_management/js/theme2/my_review/index'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <?php
    }
    ?>


<!-- Report -->
<?php 
    if(strpos($this->uri->uri_string(), 'report') !== false){
        ?>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script type="text/javascript" src="<?=base_url('assets/performance_management/js/theme2/report'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <?php
    }
    ?>