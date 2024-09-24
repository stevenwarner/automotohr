<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo STORE_NAME; ?> : <?php echo $title ?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ?>assets/css/bootstrap.css">
    <link rel="shortcut icon" href="<?php echo  base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>

    <script type="text/javascript" src="<?php echo  base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
</head>
<body>
    <style>
        #download_generated_document ol, #download_generated_document ul { padding-left: 15px !important; }
    </style>
    <div class="row" id="download_generated_document">
        <div class="col-xs-12">
            <div style="padding: 30px 50px; background-color: lightgrey; overflow-x: hidden; overflow-y: scroll;" class="document_body_container">
                <div style="padding: 20px; background-color: white;" class="document_body">
                    <?php echo html_entity_decode(html_entity_decode($document_body)); ?>
                </div>
            </div>
        </div>
    </div>
    <div id="my_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
        <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
        <div class="loader-icon-box">
            <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
            <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;">
            </div>
        </div>
    </div>
    <script>
        <?php if($type == 'submitted'){ ?>
            var form_input_data = <?= unserialize($document['form_input_data']);?>;
            if (form_input_data != null) {
                form_input_data = Object.entries(form_input_data);
        
                $.each(form_input_data, function(key ,input_value) { 
                    if (input_value[0] == 'signature_person_name') {
                        var input_field_id = input_value[0];  
                        var input_field_val = input_value[1];
                        $('#'+input_field_id).val(input_field_val);
                    } else {
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
                    }    
                });
            } 
        <?php } ?>

        $(window).on( "load", function() { 
        
            //
            download_document(); 
            
            // setTimeout(function(){
            //     window.print();
            // }, 2000);  
        });

        window.onafterprint = function(){
            window.location.replace('<?php echo $print_url; ?>')
            // window.close();
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
                $('#my_loader .loader-text').html('Please wait ....');
                $('#my_loader').show();
                $.ajax({
                    'url': '<?php echo base_url('hr_documents_management/ajax_responder'); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'download_hybird_document',
                        'base64': data,
                        's3_path': '<?php echo $s3_path; ?>',
                        'file_name': '<?php echo $title; ?>'
                    }
                }).done(function(response) {
                    $('#my_loader').hide(0);
                    window.location.replace(response)
                    setTimeout(() => {
                        // window.close();
                    }, 2000);
                });
            });
        }
    </script>
</body>
</html>    
