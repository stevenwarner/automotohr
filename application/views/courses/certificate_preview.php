

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

        .s13 {
            color: black;
            font-family: "Times New Roman", serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9.5pt;
        }

        .cursive {
            font-family: "Pinyon Script", cursive;
        }

        .sans {
            font-family: "Open Sans", sans-serif;
        }
         
        .bold {
            font-weight: bolder;
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
            background-color: #0000FF;
        }

        .pm-certificate-container {
            position: relative;
            width: 850px;
            height: 860px;
            background-color: #0000FF;
            padding: 30px;
            color: #333;
            font-family: "Open Sans", sans-serif;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        .outer-border {
            width: 845px;
            height: 850px;
            position: absolute;
            left: 82%;
            margin-left: -695px;
            top: 50%;
            margin-top: -425px;
            border: 2px solid #fff;
        }

        .inner-border {
            width: 815px;
            height: 806px;
            position: absolute;
            left: 81%;
            margin-left: -672px;
            top: 50%;
            margin-top: -403px;
            border: 2px solid #fff;
        }
        
        .pm-certificate-border {
            position: relative;
            width: 805px;
            height: 796px;
            padding: 0;
            border: 1px solid #e1e5f0;
            background-color: #ffffff;
            background-image: none;
            left: 83%;
            margin-left: -667px;
            top: 50%;
            margin-top: -398px;
        }

        .pm-certificate-block {
            width: 760px;
            height: 200px;
            position: relative;
            left: 36%;
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
            font-size: 64px !important;
            font-weight: 900;
            text-align: center;
        }

        .pm-certificate-body {
            /* padding: 20px; */
        }

        .pm-name-text {
            font-size: 32px;
        }

        .pm-course-title-text {
            font-size: 22px;
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
            width: 860px;
            height: 100px;
            position: relative;
            left: 36%;
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

        .pm-completion-date-text {
            font-size: 26px;
            font-weight: 600;
            margin-top: 16px;
            margin-bottom: 8px;
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
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="jsCertificateContainer">
                    <div class="container pm-certificate-container">
                        <div class="outer-border"></div>
                        <div class="inner-border"></div>

                        <div class="pm-certificate-border col-xs-12">
                            <div class="row pm-certificate-header">
                                <div class="pm-certificate-title s13 col-xs-12 text-center">
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
                                                <span class="pm-name-text bold"><?php echo remakeEmployeeName($studentInfo, true, true); ?></span>
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
                                            <div class="col-xs-1"><!-- LEAVE EMPTY --></div>
                                            <div class="pm-course-title underline col-xs-10 text-center">
                                                <span class="pm-course-title-text block bold sans"><?php echo $courseInfo['course_title']; ?> (<?= ucfirst($EmployeeCourseProgress["course_language"]);?>)</span>
                                            </div>
                                            <div class="col-xs-1"><!-- LEAVE EMPTY --></div>
                                        </div>
                                    </div>
                                </div>       
                                
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="pm-certificate-footer">
                                            <?php if ($courseInfo['secondary_logo']) { ?>
                                                <div class="col-xs-4 pm-certified text-center">
                                                    <span class="pm-credits-text block sans">Training provided by</span>
                                                    <img class="image-responsive" src="<?php echo $AHRLogo; ?>" alt="" style="width: 120px">
                                                </div>
                                                <div class="col-xs-4 pm-certified text-center">
                                                    <span class="pm-credits-text block sans">Proudly Sponsored By</span>
                                                    <?php 
                                                        if ($courseInfo['secondary_logo']) {
                                                            //
                                                            $url = AWS_S3_BUCKET_URL.$courseInfo['secondary_logo'];
                                                            //make a curl call to fetch content
                                                            $ch = curl_init();
                                                            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
                                                            curl_setopt($ch, CURLOPT_HEADER, 0);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                            curl_setopt($ch, CURLOPT_URL, $url);
                                                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                                                            $data = curl_exec($ch);
                                                            curl_close($ch);
                                                            //get mime type
                                                            $mime_type = getMimeType($url);
                                                            $str64 = base64_encode($data);
                                                        }
                                                        //
                                                    ?>
                                                    <img class="image-responsive" src="<?php echo "data:" . $mime_type . ";base64," . $str64; ?>" alt="" style="width: 120px">
                                                </div>
                                                <div class="col-xs-4 pm-certified text-center">
                                                    <span class="pm-credits-text block sans">Completion Date</span>
                                                    <span class="pm-completion-date-text block cursive"><?php echo $completedOn; ?></span>
                                                    <span class="pm-empty-space block underline"></span>
                                                    <span class="bold block">Employee Number : <?php echo $AHRStudentID; ?></span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-xs-5 pm-certified text-center">
                                                <span class="pm-credits-text block sans">Training provided by</span>
                                                    <img class="image-responsive" src="<?php echo $AHRLogo; ?>" alt="" style="width: 120px">
                                                </div>
                                                <div class="col-xs-2">
                                                    <!-- LEAVE EMPTY -->
                                                </div>
                                                <div class="col-xs-5 pm-certified text-center">
                                                    <span class="pm-credits-text block sans">Completion Date</span>
                                                    <span class="pm-completion-date-text block cursive"><?php echo $completedOn; ?></span>
                                                    <span class="pm-empty-space block underline"></span>
                                                    <span class="bold block">Employee Number : <?php echo $AHRStudentID; ?></span>
                                                </div>
                                            <?php } ?>    
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