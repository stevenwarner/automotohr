<!-- <html lang="en"> -->
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
                <div class="main-content">
                    <div class="dashboard-wrp">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="panel panel-success">
                                                        <div class="panel-heading">
                                                            <strong><?php echo $document['document_title']; ?></strong>
                                                        </div>
                                                        <div class="panel-body">
                                                            <?php echo html_entity_decode($document['document_description']); ?>
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
            </section>
        </main>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
        <script>
            $(document).ready(function () {
               
                <?php if ($document['user_consent'] == 1 && !empty($document['form_input_data'])) { ?>
                    var form_input_data = <?php echo $form_input_data; ?>;
                    form_input_data = Object.entries(form_input_data);
                
                    $.each(form_input_data, function(key ,input_value) { 
                        var input_field_id = input_value[0]+'_id';  
                        var input_field_val = input_value[1]; 
                        var input_type =  $('#'+input_field_id).attr('data-type');

                        if (input_type == 'text') {
                            $('#'+input_field_id).val(input_field_val);
                            $('#'+input_field_id).prop('disabled', true);
                        } else if (input_type == 'checkbox') {
                            //
                            if ($('#' + input_field_id).attr('data-required') == "yes") {
                                if (input_value[1] == 'yes') {
                                    $(`input[name="${input_value[0]}1"]`).prop('checked', true);
                                } else {
                                    $(`input[name="${input_value[0]}2"]`).prop('checked', true);
                                }
                            }
                            //
                            if (input_field_val == 'yes') {
                                $('#'+input_field_id).prop('checked', true);;
                            }
                            $('#'+input_field_id).prop('disabled', true);
                            
                        } else if (input_type == 'textarea') {
                            $('#'+input_field_id).hide();
                            $('#'+input_field_id+'_sec').show();
                            $('#'+input_field_id+'_sec').html(input_field_val);
                        } 
                    });   
                    
                <?php } ?>

                var imgs = $('#download_generated_document').find('img');

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
                                    download_document();  
                                },
                                error: function(){

                                }
                            });
                        } 
                    });
                }

                var perform_action = '<?php echo $perform_action; ?>';
         
                if(perform_action == 'download') { 
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
                        pdf = data;

                        $('#myiframe').attr("src",data);
                        kendo.saveAs({
                            dataURI: pdf,
                            fileName: '<?php echo $file_name.".pdf"; ?>',
                        });
                        window.close();
                    });
                } else { 
                    window.print();
                    // $(window).on( "load", function() { 
                    //     setTimeout(function(){
                    //         window.print();
                    //         alert('finish')
                    //     }, 2000);  
                    // });

                    window.onafterprint = function(){
                        window.close();
                    }
                }
            });
        </script>
    </body>
</html>                

   
