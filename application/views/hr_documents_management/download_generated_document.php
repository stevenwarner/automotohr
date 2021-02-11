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
            <section class="sheet padding-10mm" id="original_document_download">
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
                    <iframe src="<?php echo $document['submitted_description']; ?>" id="printf" name="printf" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                <?php } else { ?>
                    <div> 
                        <?php echo html_entity_decode($original_document_description); ?>
                    </div>
                <?php } ?>
            </section>    
        </main>    
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
        <script src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

        <script type="text/javascript">
        	$( window ).on( "load", function() {
                var draw = kendo.drawing;
                draw.drawDOM($("#original_document_download"), {
                    avoidLinks: true,
                    paperSize: "A4",
                    margin: { bottom: "1cm" },
                    scale: 0.6
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(data) {
                    var consent = '<?php echo isset($document['user_consent']) ? $document['user_consent'] : 0; ?>';
                    var pdfdata = "";
                    if(consent == 0) {
                        pdfdata = data;
                    } else {
                        <?php if ($print == 'original'): ?>
                            pdfdata = data
                        <?php else: ?>
                            pdfdata = '<?php echo $document['submitted_description']; ?>';
                        <?php endif ?>
                    }
                    
                    kendo.saveAs({
                        dataURI: pdfdata,
                        fileName: '<?php echo ucwords($document['document_title']).".pdf"; ?>',
                    });

                    window.close();
                }); 
        </script>
    </body>
</html>    
