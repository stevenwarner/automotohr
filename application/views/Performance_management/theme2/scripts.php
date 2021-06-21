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
<<<<<<< HEAD
        companyName: "<?=$companyDetails['CompanyName'];?>",
        companyLogo: "<?=$companyDetails['Logo'];?>",
=======
>>>>>>> 2798fc44... Added review part of Perfoemance management
        employerId: <?=$employerId;?>,
        employee: {
            id: <?=isset($employeeId) ? $employeeId : 0;?>,
            name: "<?=isset($employeeName) ? $employeeName : '';?>",
            level: <?=$level;?>
        }
<<<<<<< HEAD
    };
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="<?=base_url('assets/select2/select2.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- Common functions -->
<script src="<?=base_url('assets/performance_management/js/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>

<?php 
    if(strpos($this->uri->uri_string(), 'review/create') !== false){
        ?>
        <script type="text/javascript" src="<?=base_url('assets/performance_management/js/theme2/create'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
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
        },
        page: "<?=isset($page) ? $page : ''?>",
        Id: <?=isset($pid) ? $pid : 0; ?>,
        Pem: <?=isset($pem) ? $pem : 0; ?>
=======
>>>>>>> d5bced39... Added creatae review step 1 on blue screen
    };
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="<?=base_url('assets/select2/select2.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- Common functions -->
<script src="<?=base_url('assets/performance_management/js/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<<<<<<< HEAD
<script src="<?=base_url('assets/performance_management/js/goals/create'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
=======

<?php 
    if(strpos($this->uri->uri_string(), 'review/create') !== false){
        ?>
        <script type="text/javascript" src="<?=base_url('assets/performance_management/js/theme2/create'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <?php
    }
?>
<<<<<<< HEAD
>>>>>>> d5bced39... Added creatae review step 1 on blue screen
=======

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
>>>>>>> fee239a4... Added PM report for blue screen
