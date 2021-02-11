<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
        <title>Generated Document</title>
        <style>
            .center-col{
                float: left;
                width: 100%;
                text-align: center;
                margin-top: 14px;
            }
            .center-col h2,
            .center-col p{
                margin: 0 0 5px 0;
            }
            .sheet-header {
                float: left;
                width: 100%;
                padding: 0 0 2px 0;
                margin: 0 0 5px 0;
                border-bottom: 5px solid #000;
            }
            .sheet.padding-10mm { padding: 10mm }
            .header-logo{
                float: left;
                width: 100%;
            }
        </style>
    </head>

    <body cz-shortcut-listen="true">
        <main role="main" class="container">
            <section class="sheet padding-10mm" id="download_generated_document">
                <!-- <article class="sheet-header">
                    <div class="header-logo">
                        <h2 style="margin: 0;"><?php echo $company_name; ?></h2>
                        <small>
                            <?php echo $action_date; ?>: <b><?php echo reset_datetime(array('datetime' => date('Y-m-d'), '_this' => $this)); ?></b><br>
                            <?php echo $action_by; ?>: <b><?php echo $employee_name; ?></b>
                        </small>
                    </div>
                    <div class="center-col">
                        <h2><?php echo $document_title; ?></h2>
                    </div>
                </article> -->
                <?php if ($print == 'submitted') { ?>
                    <canvas id="the-canvas" style="border:1px  solid black"></canvas>
                <?php } else { ?>
                    <?php echo html_entity_decode($original_document_description); ?>
                <?php } ?>
            </section>    
            
        </main>
        <style>
            #download_generated_document ol, #download_generated_document ul { padding-left: 15px !important; }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

        <script id="script">
            <?php if ($print == 'submitted'): ?>
                var document_base64 = '<?php echo $document["submitted_description"]; ?>';  
                var res = document_base64.replace("data:application/pdf;base64,", "");
                var pdfData = atob(res);

                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.worker.min.js';

                pdfjsLib.getDocument({data: pdfData}).then(function getPdfHelloWorld(pdf) {

                    pdf.getPage(1).then(function getPageHelloWorld(page) {
                        var scale = 1.5;
                        var viewport = page.getViewport(scale);

                        // Prepare canvas using PDF page dimensions.
                        var canvas = document.getElementById('the-canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        // Render PDF page into canvas context.
                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });

                });
            <?php endif ?>

            var download = '<?php echo $download; ?>';
            var sub_data = '<?php echo isset($document_file) ? $document_file : "no_pdf"; ?>';
         
            if(download == 'download') {
                var imgs = $('#download_generated_document').find('img');
                var p = /((http|https):\/\/)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g;

                if(imgs.length){
                    $(imgs).each(function(i,v) {
                        var imgSrc = $(this).attr('src').trim();
                        var _this = this;

                        var p = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/gm; 

                        if (p.test(imgSrc)) {
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
                                    download_document();  
                                },
                                error: function(){

                                }
                            });
                        } else {
                            download_document(); 
                        } 
                    });
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
                draw.drawDOM($("#download_generated_document"), {
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
                    var pdf;
                    if(sub_data == 'pdf') {
                        pdf = '<?php echo isset($document["submitted_description"]) ? $document["submitted_description"] : ''; ?>';
                    } else {
                        pdf = data;
                    }
                   
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