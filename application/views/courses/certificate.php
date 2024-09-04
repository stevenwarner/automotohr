

    <style>
        @font-face {
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: 400;
            font-stretch: normal;
            src: url(https://fonts.gstatic.com/s/opensans/v36/memSYaGs126MiZpBA-UvWbX2vVnXBbObj2OVZyOOSr4dVJWUgsjZ0B4gaVc.ttf) format('truetype');
        }

        @font-face {
            font-family: 'Pinyon Script';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/pinyonscript/v21/6xKpdSJbL9-e9LuoeQiDRQR8WOXaPw.ttf) format('truetype');
        }

        @font-face {
            font-family: 'Rochester';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/rochester/v22/6ae-4KCqVa4Zy6Fif-UC2FHS.ttf) format('truetype');
        }

        .cursive {
            font-family: "Pinyon Script", cursive;
        }

        .sans {
            font-family: "Open Sans", sans-serif;
        }
         
        .bold {
            font-weight: bold;
        }
         
        .block {
            display: block;
        }
         
        .underline {
            border-bottom: 1px solid #777;
            padding: 5px;
            margin-bottom: 15px;
        }
         
        .margin-0 {
            margin: 0;
        }

        .margin-bottom-15 {
            margin: 15px;
        }
         
        .padding-0 {
            padding: 0;
        }
         
        .pm-empty-space {
            height: 16px;
            width: 100%;
        }
         
        body {
            padding: 20px 0;
            background: #ccc;
        }

        .cssMain {
            background-color: #618597;
        }

        .pm-certificate-container {
            position: relative;
            width: 1200px;
            height: 794px;
            background-color: #618597;
            padding: 30px;
            color: #333;
            font-family: "Open Sans", sans-serif;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        .outer-border {
            width: 1190px;
            height: 784px;
            position: absolute;
            left: 50%;
            margin-left: -595px;
            top: 50%;
            margin-top: -392px;
            border: 2px solid #fff;
        }

        .inner-border {
            width: 1144px;
            height: 742px;
            position: absolute;
            left: 50%;
            margin-left: -573px;
            top: 50%;
            margin-top: -370px;
            border: 2px solid #fff;
        }
        
        .pm-certificate-border {
            position: relative;
            width: 1134px;
            height: 732px;
            padding: 0;
            border: 1px solid #e1e5f0;
            background-color: #ffffff;
            background-image: none;
            left: 50%;
            margin-left: -568px;
            top: 50%;
            margin-top: -365px;
        }

        .pm-certificate-block {
            width: 650px;
            height: 200px;
            position: relative;
            left: 50%;
            margin-left: -325px;
            top: 70px;
            margin-top: 0;
        }
         
        .pm-certificate-header {
            margin-bottom: 10px;
        }

        .pm-certificate-title {
            position: relative;
            top: 40px;
        }

        .pm-certificate-title h1 {
            font-size: 44px !important;
            font-weight: 500;
            text-align: center;
        }

        .pm-certificate-body {
            /* padding: 20px; */
        }

        .pm-name-text {
            font-size: 20px;
        }
         
        .pm-credits-text {
            font-size: 15px;
        }

        .pm-certified {
            font-size: 12px;
        }

        .pm-certified .underline {
            margin-bottom: 5px;
        }
        .pm-certificate-footer {
            width: 650px;
            height: 100px;
            position: relative;
            left: 50%;
            margin-left: -325px;
            bottom: -105px;
        }

        .pm-presented-text{
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .pm-entitled-text {
            font-size: 26px;
            font-weight: 600;
            margin-top: 26px;
            margin-bottom: 14px;
        }

        .pm-sub-credit-text {
            font-size: 24px;
            margin-top: 12px
        }

        .csRadius5 {
            border-radius: 5px !important;
            -webkit-border-radius: 5px !important;
            -moz-border-radius: 5px !important;
            -o-border-radius: 5px !important;
        }

        .image-responsive {
            margin: 20px auto;
            max-width: 100%;
            display: block;
        }
    </style>
   
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 margin-bottom-15">
                    <div class="container">
                        <div class="row">
                            <br>
                            <div class="col-xs-12 text-right">
                                <?php if ($type == "subordinate") { ?>
                                    <a href="<?php echo base_url('lms/subordinate/courses/'.$student_sid); ?>" class="btn btn-black csRadius5 csF16"><i class="fa fa-arrow-left csF16"></i> Back to Courses</a>
                                <?php } else { ?> 
                                    <a href="<?php echo base_url('lms/courses/my'); ?>" class="btn btn-black csRadius5 csF16"><i class="fa fa-arrow-left csF16"></i> Back to Courses</a>
                                <?php } ?> 
                             
                                <button type="button" class="btn btn-info btn-orange csRadius5 csF16 jsDownloadCertificate">
                                    <i class="fa fa-download csF16" aria-hidden="true"></i>
                                    Download
                                </button>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="jsCertificateContainer">
                    <div class="container pm-certificate-container">
                        <div class="outer-border"></div>
                        <div class="inner-border"></div>

                        <div class="pm-certificate-border col-xs-12">
                            <div class="row pm-certificate-header">
                                <div class="pm-certificate-title cursive col-xs-12 text-center">
                                    <h1><?php echo $companyName; ?></h1>
                                </div>
                            </div>

                            <div class="row pm-certificate-body">
                            
                                <div class="pm-certificate-block">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                                            <div class="pm-certificate-name underline margin-0 col-xs-8 text-center">
                                                <span class="pm-presented-text padding-0 block cursive">This Certificate is presented to</span>
                                                <span class="pm-name-text bold"><?php echo remakeEmployeeName($employeeName[0], true, true); ?></span>
                                            </div>
                                            <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                                        </div>
                                    </div>          


                                    
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                                            <div class="pm-course-title col-xs-8 text-center">
                                                <span class="pm-entitled-text block cursive">on successfully completing the e-learning training course entitled</span>
                                            </div>
                                            <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                                            <div class="pm-course-title underline col-xs-8 text-center">
                                                <span class="pm-credits-text block bold sans"><?php echo $courseInfo['course_title']; ?></span>
                                            </div>
                                            <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                                        </div>
                                    </div>
                                </div>       
                                
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="pm-certificate-footer">
                                            <div class="col-xs-5 pm-certified text-center">
                                            <span class="pm-credits-text block sans">Training provided by</span>
                                                <img class="image-responsive" src="<?php echo $AHRLogo; ?>" alt="">
                                                <!-- <span class="pm-credits-text block sans">Signature</span>
                                                <span class="pm-entitled-text block cursive"><?php echo $company_info['ContactName']; ?></span>
                                                <span class="pm-empty-space block underline"></span>
                                                <span class="bold block">
                                                <?php //echo ucwords($company_info['Location_Address']) . ', ' . ucwords($company_info['Location_City']) . ', ' . ucwords($company_info['state_name']) . ', ' . ucwords($company_info['Location_ZipCode']) . ', ' . ucwords($company_info['country_name']); ?>
                                                </span> -->
                                            </div>
                                            <div class="col-xs-2">
                                                <!-- LEAVE EMPTY -->
                                            </div>
                                            <div class="col-xs-5 pm-certified text-center">
                                                <span class="pm-credits-text block sans">Completion Date</span>
                                                <span class="pm-entitled-text block cursive"><?php echo $completedOn; ?></span>
                                                <span class="pm-empty-space block underline"></span>
                                                <span class="bold block">Employee Number : <?php echo $AHRStudentID; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>  
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <script type="text/javascript">
        $(document).on('click', '.jsDownloadCertificate', function(event) {
            //
            var draw = kendo.drawing;
            draw.drawDOM($("#jsCertificateContainer"), {
                avoidLinks: false,
                paperSize: "auto",
                multiPage: true,
                margin: { bottom: "1cm" },
                scale: 0.8
            })
            .then(function(root) {
                return draw.exportPDF(root);
            })
            .done(function(data) {
                $('#myiframe').attr("src",data);
                kendo.saveAs({
                    dataURI: data,
                    fileName: '<?php echo str_replace(" ","_",$courseInfo['course_title'])."_certificate.pdf"; ?>',
                });
            });
        });
    </script>  





