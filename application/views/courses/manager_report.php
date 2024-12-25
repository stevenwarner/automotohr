<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Company Course(s) Report</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/css/i9-style.css')?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/bootstrap.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/theme-2021.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/responsive.css') ?>">
    </head>
    <!-- Set "A5", "A4" or "A3" for class name -->
    <!-- Set also "landscape" if you need -->
    <body class="A4">
        <div class="wrapper-outer" id="jsCompanyReport">
            <div class="container" style="padding: 12px; border: 3px solid #000;">
                <div class="main-content">
                    <div class="container-fluid">
                        <div class="row">
                            <section class="padding-10mm">
                                <article class="sheet-header">
                                    <div class="header-logo">
                                        <img src="<?php echo $companyLogo; ?>" style="width: 75px; height: 75px;" class="img-rounded"><br>
                                    </div>
                                    <div class="center-col">
                                        <h2><?php echo $companyName; ?></h2>
                                    </div>
                                </article>
                                <?php if ($action == "download") { ?>
                                    <p><strong>Exported By:  </strong> <?php echo getUserNameBySID($employeeId); ?></p>
                                    <p><strong>Exported Date: </strong> <?php echo formatDateToDB( date('Y-m-d H:i:s'), DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></p>
                                <?php } ?>
                                <table class="i9-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="2">
                                                <strong>Visual Information</strong>
                                            </th>
                                        </tr>   
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style=" width: 50%;">
                                                <div id="container1"></div>
                                            </td>
                                            <td style=" width: 50%;">
                                                <div id="container2"></div>
                                            </td>
                                        </tr>    
                                    </tbody>
                                </table>
                                <table class="i9-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="2">
                                                <strong>Basic Employees Overview</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                Number of Employees with Assigned Courses
                                            </td>
                                            <td>
                                            <?php echo $subordinateReport['employee_have_courses']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Number of Employees Without Assigned Courses
                                            </td>
                                            <td>
                                                <?php echo $subordinateReport['employee_not_have_courses']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="i9-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="3">
                                                <strong>Basic Courses Overview</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                Total Assigned Course(s)
                                            </td>
                                            <td>
                                                <?php echo $subordinateReport['total_assigned_courses']; ?>
                                            </td>
                                            <td class="bg-gray">
                                                <strong>Percentage</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Total Completed Course(s)
                                            <td>
                                                <?php echo $subordinateReport['total_completed_courses']; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $completedPercentage = round(($subordinateReport['total_completed_courses'] / $subordinateReport['total_assigned_courses']) * 100, 2);
                                                    echo $completedPercentage.'%';
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Total Inprogress Course(s)
                                            </td>
                                            <td>
                                                <?php echo $subordinateReport['total_inprogress_courses']; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $inprogressPercentage = round(($subordinateReport['total_inprogress_courses'] / $subordinateReport['total_assigned_courses']) * 100, 2);
                                                    echo $inprogressPercentage.'%'; 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Total Ready to Start Course(s)
                                            <td>
                                                <?php echo $subordinateReport['total_rts_courses']; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $rtsPercentage = round(($subordinateReport['total_rts_courses'] / $subordinateReport['total_assigned_courses']) * 100, 2);
                                                    echo $rtsPercentage.'%'; 
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="i9-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="8">
                                                <strong>Detail Information</strong>
                                            </th>
                                        </tr>
                                        <tr class="bg-gray">
                                            <th>
                                                
                                            </th>
                                            <th>
                                                Employee Name
                                            </th>
                                            <th>
                                                Department
                                            </th>
                                            <th>
                                                Assign Course(s)
                                            </th>
                                            <th>
                                                Inprogress Course(s)
                                            </th>
                                            <th>
                                                Ready To Start Course(s)
                                            </th>
                                            <th>
                                                Completed Course(s)
                                            </th>
                                            <th>
                                                Completion Percentage
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($subordinateReport['employees']) { ?>
                                            <?php foreach ($subordinateReport['employees'] as $employee) { ?>
                                                <?php
                                                    $percentage = 0;
                                                    $assignedCourses = 0;
                                                    $inprogressCourses = 0;
                                                    $rtsCourses = 0;
                                                    $completedCourses = 0;
                                                    //
                                                    if (isset($employee['coursesInfo'])) {
                                                        $percentage = $employee['coursesInfo']['percentage'];
                                                        $assignedCourses = $employee['coursesInfo']['total_course'];;
                                                        $inprogressCourses = $employee['coursesInfo']['started'];
                                                        $rtsCourses = $employee['coursesInfo']['ready_to_start'];
                                                        $completedCourses = $employee['coursesInfo']['completed'];
                                                    }
                                                    
                                                    //
                                                    $rowColor = "bg-danger";
                                                    //
                                                    if ($percentage == "100") {
                                                        $rowColor = "bg-success";
                                                    } else if ($percentage < "99" && $percentage > "1") {
                                                        $rowColor = "bg-warning";
                                                    }    
                                                ?>
                                                <tr class="<?php echo $rowColor; ?>">
                                                    <td>
                                                        <img style="width: 55px; height: 55px; border-radius: 50% !important;" src="<?php echo $employee['profile_picture_url'] ?>" alt="" />
                                                    </td>
                                                    <td>
                                                        <?php echo $employee["full_name"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $employee["department_name"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $assignedCourses; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $inprogressCourses; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $rtsCourses; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $completedCourses; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $percentage."%"; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </section>
                        </div>
                    </div>
                </div>            
            </div>
            <div id="jsFileLoader" class="text-center my_loader">
                <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
                <div class="loader-icon-box">
                    <i class="fa fa-refresh fa-spin my_spinner" aria-hidden="true" style="visibility: visible;"></i>
                    <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
                    </div>
                </div>
            </div>
        </div>    
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
        
        <script type="text/javascript">
            $( window ).on( "load", function() {

                $("#container1").kendoChart({
                    title: {
                        text: "Employee(s) Overview",
                        font: "22px Arial" // Title font size
                    },
                    seriesDefaults: {
                        type: "pie",
                        labels: {
                            visible: true,
                            background: "transparent",
                            font: "16px Arial", // Label font size
                            format: "{0}%"
                        }
                    },
                    series: [{
                        data: [
                            {
                                category: 'In Progress',
                                value: <?php echo round(($subordinateReport['employees_with_started_courses'] / $subordinateReport['employee_have_courses']) * 100, 2); ?>,
                                color: '#DDDF0D'
                            },
                            {
                                category: 'Ready To Start',
                                value: <?php echo round(($subordinateReport['employees_with_not_started_courses'] / $subordinateReport['employee_have_courses']) * 100, 2); ?>,
                                color: '#DF5353'
                            },
                            {
                                category: 'Completed',
                                value: <?php echo round(($subordinateReport['employees_with_completed_courses'] / $subordinateReport['employee_have_courses']) * 100, 2); ?>,
                                color: '#00e272'
                            }
                        ],
                        colorField: "color", // Optional if you want to use colorField for custom colors
                    }],
                    tooltip: {
                        visible: true,
                        format: "{0}%",
                        font: "14px Arial" // Font size for the tooltip
                    },
                    legend: {
                        visible: true,
                        position: "bottom",
                        labels: {
                            font: "14px Arial" // Font size for legend labels
                        }
                    },
                    chartArea: {
                        background: "#f9f9f9",
                        width: 600,
                        height: 500
                    }
                });

                // Define data for the Bar Chart
                var chartData = [
                    {
                        category: "Employee(s) with Course Completions",
                        value: <?php echo $subordinateReport['employees_with_completed_courses'] ; ?>,
                        color: '#00e272'
                    },
                    {
                        category: 'Employee(s) with Ongoing Course(s)',
                        value: <?php echo $subordinateReport['employees_with_started_courses']; ?>,
                        color: '#DDDF0D'
                    },
                    {
                        category: 'Employee(s) Yet to Start Course(s)',
                        value: <?php echo $subordinateReport['employees_with_not_started_courses']; ?>,
                        color: '#DF5353'
                    },
                    {
                        category: 'Employees Without Assigned Courses',
                        value: <?php echo $subordinateReport['employee_not_have_courses']; ?>,
                        color: '#544fc5'
                    }
                ];

                // Initialize the Kendo UI Bar Chart
                $("#container2").kendoChart({
                    title: {
                        text: "Employee(s) Overview"
                    },
                    legend: {
                        position: "top"
                    },
                    seriesDefaults: {
                        type: "bar",  // Set chart type to "bar"
                        label: {
                            visible: true,
                            background: "white"
                        }
                    },
                    series: [{
                        data: chartData.map(function(item) { return item.value; }),
                        colorField: "color" 
                    }],
                    categoryAxis: {
                        categories: chartData.map(function(item) { return item.category; }),
                        labels: {
                            rotation: -45
                        }
                    },
                    valueAxis: {
                        labels: {
                            format: "{0}"
                        }
                    },
                    chartArea: {
                        background: "#f9f9f9",
                        width: 500,
                        height: 500
                    }
                });

                if ('<?php echo $action; ?>' == "download") { 
                    setTimeout(() => {
                        var imgs = $('#jsCompanyReport').find('img');
                        var i = 0;
                        //
                        if(imgs.length){
                            $("#jsFileLoader").show();
                            //
                            i = imgs.length;
                            //
                            $(imgs).each(function(ind,v) {
                                var imgSrc = $(this).attr('src');
                                var _this = this;

                                var p = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/gm; 

                                if (imgSrc.match(p)) {
                                    $.ajax({
                                        url: '<?= base_url('hr_documents_management/getbase64/')?>',
                                        data:{
                                            url: imgSrc.trim()
                                        },
                                        type: "GET",
                                        async: false,
                                        success: function (resp){
                                            resp = JSON.parse(resp);
                                            $(_this).attr("src", "data:"+resp.type+";base64,"+resp.string);
                                            --i;
                                            download_document();
                                        },
                                        error: function(){

                                        }
                                    });
                                } else { 
                                    --i; 
                                    download_document();
                                }
                            });
                        } else {
                            download_document();
                        }

                        function download_document(){
                            if(i != 0) return;
                            $("#jsFileLoader").hide();
                            // 
                            var draw = kendo.drawing;
                            draw.drawDOM($("#jsCompanyReport"), {
                                avoidLinks: false,
                                paperSize: "auto",
                                multiPage: true,
                                margin: { bottom: "2cm" },
                                scale: 0.8
                            })
                            .then(function(root) {
                                return draw.exportPDF(root);
                            })
                            .done(function(data) {
                                var pdf;
                                pdf = data;

                                $('#myiframe').attr("src",data);
                                kendo.saveAs({
                                    dataURI: pdf,
                                    fileName: '<?php echo str_replace(' ', '_', $companyName)."_".date('m_d_Y_H_i_s', strtotime('now')).".pdf"; ?>',
                                });
                                window.close(); 
                            });
                        }
                    }, 2000);   
                } else {
                    $("#jsFileLoader").hide();
                }
            });
        </script>
    </body>
</html>
