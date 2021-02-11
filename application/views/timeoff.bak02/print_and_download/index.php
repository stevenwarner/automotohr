<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/bootstrap.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/style.css'); ?>">
        <title><?php echo $page_title; ?></title>
        <style>
            .content {
                font-size: 100%; 
                line-height: 1.6em; 
                display: block; 
                max-width: 1000px; 
                margin: 0 auto; 
                padding: 0; 
                position:relative;
            }

            .header {
                width:100%; 
                float:left; 
                padding:5px 20px; 
                text-align:center; 
                box-sizing:border-box; 
                background-color:#000;
            }

            .body-content {
                width:100%; 
                float:left; 
                padding:20px 12; 
                margin-top: 90px;
                box-sizing:padding-box;
            }

            .header h2 {
                color:#fff;
            }

            .footer {
                width:100%; 
                float:left; 
                background-color:#000; 
                padding:20px 30px; 
                box-sizing:border-box;
            }

            .footer_contant {
                float:left; 
                width:100%;
            }

            .footer_text {
                color:#fff; 
                float:left; 
                text-align:center; 
                font-style:italic; 
                line-height:normal; 
                font-family: "Open Sans", sans-serif; font-weight:600; 
                font-size:14px;
            }

            .footer_text a {
                color:#fff; 
                text-decoration:none;
            }

            .employee-info figure {
                width: 50px!important;
                height: 50px!important;
            }

            .employee-info figure {
                float: left;
                width: 50px;
                height: 50px;
                border-radius: 100%;
                border: 1px solid #ddd;
            }

            .employee-info figure img {
                width: 100%;
                height: 100%;
                border-radius: 100%;
                border-radius: 3px!important;
            }

            .employee-info .text {
                margin: 0 0 0 60px;
            }

            .employee-info .text h4 {
                font-weight: 600;
                font-size: 18px!important;
                margin: 0;
            }

            #js-data-area .text p {
                color: #000!important;
            }

            .employee-info .text p {
                font-weight: 400;
                font-size: 14px;
                margin: 0;
            }

            .upcoming-time-info .icon-image {
                float: left;
                width: 40px;
                height: 40px;
                display: inline-block;
            }

            .upcoming-time-info .icon-image img {
                width: 100%;
                height: 100%;
            }

            .upcoming-time-info .text {
                margin: 5px 0 0 50px;
            }

            .upcoming-time-info .text h4 {
                font-weight: 600;
                font-size: 16px;
                margin: 0;
            }

            .upcoming-time-info .text p {
                font-weight: 400;
                font-size: 14px;
                margin: 0;
            }

            .upcoming-time-info .text p span {
                font-weight: 700;
            }

            .section_heading {
                font-weight: 700;
            }

            .approvers_panel {
                margin-top: 18px;
            }

            .approver_row:nth-child(odd) {
                background-color: #F5F5F5;
            }

        </style>
    </head>

    <body cz-shortcut-listen="true">
        <div class="content" id="download_timeoff_action">
            <?php $this->load->view('timeoff/print_and_download/header'); ?>
            <div class="body-content">
                <div class="row">
                    <?php foreach ($action_data as $record) { ?>
                        <?php 
                              
                        ?>
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Request <strong style="float: right;">Created Date: <?php echo $record['created_at']?></strong></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <h5 style="font-weight: 700">Name</h5>
                                            <div class="employee-info">
                                                <figure>
                                                    <img src="https://automotohrattachments.s3.amazonaws.com/download-cylw2M.jpg" img_path="download-cylw2M.jpg" class="emp-image" />
                                                </figure>
                                                <div class="text">
                                                    <h4>Paul Adams</h4>
                                                    <p>(IT Manager) [Employee]</p>
                                                    <p>
                                                        <a>
                                                            Id: 172
                                                        </a>
                                                    </p>
                                               </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <h5 style="font-weight: 700">Policy</h5>
                                            <div class="upcoming-time-info">
                                                <div class="icon-image">
                                                    <a href="">
                                                        <img src="<?=base_url('assets');?>/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1">
                                                    </a>
                                                </div>
                                                <div class="text">
                                                    <h4>Dec 31 2020, Thu - Jan 04 2021, Mon</h4>
                                                    <p><span>FMLA PTO</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <h5 style="font-weight: 700">Status</h5>
                                            <p>Pending</p>
                                        </div>
                                        <div class="col-xs-2">
                                            <h5 style="font-weight: 700">Total Time</h5>
                                            <p>16 hours</p>
                                        </div>
                                    </div>
                                    <div class="panel panel-default approvers_panel">
                                        <div class="panel-heading">Approvers</strong></div>
                                        <div class="panel-body"> 
                                            <div class="row approver_row">
                                                <div class="col-xs-4">
                                                    <h5 style="font-weight: 700">Employee</h5>
                                                    <div class="employee-info">
                                                        <figure>
                                                            <img src="https://automotohrattachments.s3.amazonaws.com/images-(1)-nZOpvg.jfif" img_path="images-(1)-nZOpvg.jfif" class="emp-image" />
                                                        </figure>
                                                        <div class="text">
                                                            <h4>Paul Adams</h4>
                                                            <p>(IT Manager) [Employee]</p>
                                                            <p>
                                                                <a>
                                                                    Id: 172
                                                                </a>
                                                            </p>
                                                       </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Status</h5>
                                                    <p>FLMA</p>
                                                </div>
                                                <div class="col-xs-2">
                                                    <h5 style="font-weight: 700">Comment</h5>
                                                    <p>50%</p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Action Taken</h5>
                                                    <p>9 hours</p>
                                                </div>
                                            </div> 
                                            <div class="row approver_row">
                                                <div class="col-xs-4">
                                                    <h5 style="font-weight: 700">Employee</h5>
                                                    <div class="employee-info">
                                                        <figure>
                                                            <img src="https://automotohrattachments.s3.amazonaws.com/HygqIW_auto_careers_TU7.jpg" img_path="HygqIW_auto_careers_TU7.jpg" class="emp-image" />
                                                        </figure>
                                                        <div class="text">
                                                            <h4>Paul Adams</h4>
                                                            <p>(IT Manager) [Employee]</p>
                                                            <p>
                                                                <a>
                                                                    Id: 172
                                                                </a>
                                                            </p>
                                                       </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Status</h5>
                                                    <p>FLMA</p>
                                                </div>
                                                <div class="col-xs-2">
                                                    <h5 style="font-weight: 700">Comment</h5>
                                                    <p>50%</p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Action Taken</h5>
                                                    <p>9 hours</p>
                                                </div>
                                            </div>
                                            <div class="row approver_row">
                                                <div class="col-xs-4">
                                                    <h5 style="font-weight: 700">Employee</h5>
                                                    <div class="employee-info">
                                                        <figure>
                                                            <img src="https://automotohrattachments.s3.amazonaws.com/images-(4)-1pzGew.jfif" img_path="images-(4)-1pzGew.jfif" class="emp-image" />
                                                        </figure>
                                                        <div class="text">
                                                            <h4>Paul Adams</h4>
                                                            <p>(IT Manager) [Employee]</p>
                                                            <p>
                                                                <a>
                                                                    Id: 172
                                                                </a>
                                                            </p>
                                                       </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Status</h5>
                                                    <p>FLMA</p>
                                                </div>
                                                <div class="col-xs-2">
                                                    <h5 style="font-weight: 700">Comment</h5>
                                                    <p>50%</p>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h5 style="font-weight: 700">Action Taken</h5>
                                                    <p>9 hours</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>           
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>    
            <?php $this->load->view('timeoff/print_and_download/footer'); ?>
        </div>    
        
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

        <script id="script">

            var download = '<?php echo $download; ?>';
            var sub_data = '<?php echo isset($document_file) ? $document_file : "no_pdf"; ?>';
         
            if(download == 'yes') {
                var imgs = $('#download_timeoff_action').find('img');
                var p = /((http|https):\/\/)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g;

                if(imgs.length){
                    $(imgs).each(function(i,v) {
                        var imgSrc = $(this).attr('src');
                        var imgPath = $(this).attr('img_path');
                        var _this = this;

                        var p = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/gm; 

                        if (imgSrc.match(p)) {
                            $.ajax({
                                url: '<?= base_url('timeoff/get_image_base64/')?>',
                                data:{
                                    url: imgSrc.trim(),
                                    img_url: imgPath.trim()
                                },
                                type: "GET",
                                async: false,
                                success: function (resp){
                                    console.log(resp);
                                    resp = JSON.parse(resp);
                                    $(_this).attr("src", "data:"+resp.type+";base64,"+resp.string); 
                                },
                                error: function(){

                                }
                            });
                        } 
                    });
                    download_document();
                } else {
                    download_document(); 
                }
               
            } else {
                $(window).on( "load", function() { 
                    setTimeout(function(){
                        window.print();
                    }, 2000);  
                });

                window.onafterprint = function(){
                    window.close();
                }
            }


            function download_document () {
                var draw = kendo.drawing;
                draw.drawDOM($("#download_timeoff_action"), {
                    avoidLinks: false,
                    paperSize: "A4",
                    multiPage: true,
                    margin: { bottom: "2cm" },
                    scale: 0.8
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(data) {
                    var pdf = data;
                   
                    $('#myiframe').attr("src",data);
                    kendo.saveAs({
                        dataURI: pdf,
                        fileName: '<?php echo $file_name.".pdf"; ?>',
                    });
                    window.close();
                });
            }
        </script>
    </body>
</html>