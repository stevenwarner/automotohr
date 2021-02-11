<!DOCTYPE html>
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
    <!--  -->
    <?php $this->load->view('timeoff/fmla/preview/'.( $Slug ).'', array('FMLA' => $FMLA, 'pd' => 'print', 'fileName' => $fileName)); ?>
    <!--  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <script id="script">
            if("<?=$pd;?>" == 'download'){
                var imgs = $('#js-preview').find('img');
             
                if(imgs.length){
                    $(imgs).each(function(i,v) {
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
                                },
                                error: function(){

                                }
                            });
                        } else {
                            download_document(); 
                        } 
                    });
                }

                var draw = kendo.drawing;
                draw.drawDOM($("#js-preview"), {
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
                    pdf = data;
                   
                    // $('#myiframe').attr("src",data);
                    kendo.saveAs({
                        dataURI: pdf,
                        fileName: "<?=$fileName;?>",
                    });
                    window.close();
                });
            }else{
                //
                $(window).on( "load", function() { 
                    setTimeout(function(){
                        window.print();
                    }, 2000);  
                });
                //
                window.onafterprint = function(){
                    window.close();
                }
            }
    </script>
</body>
</html>
    


