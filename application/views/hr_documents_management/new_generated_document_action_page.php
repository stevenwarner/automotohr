<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
        <link rel="stylesheet" type="https://printjs-4de6.kxcdn.com/print.min.css" href="print.css">
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
            input[type='checkbox'].user_checkbox {
	margin-top: -30px;
}

input[type='checkbox'].user_checkbox {
	-webkit-font-smoothing: antialiased;
	text-rendering: optimizeSpeed;
	width: 25px;
	height: 25px;
	margin: 0;
	margin-right: 10px !important;
	display: block;
	float: left;
	position: relative;
	cursor: pointer;
}

input[type='checkbox'].user_checkbox:after {
	content: "";
	vertical-align: middle;
	text-align: center;
	line-height: 25px;
	position: absolute;
	cursor: pointer;
	height: 25px;
	width: 25px;
	left: 0;
	top: 0;
	font-size: 14px;
	background: #999999;
}

input[type='checkbox'].user_checkbox:hover:after, input[type='checkbox'].user_checkbox:checked:hover:after {
	background: #999999;
	content: '\2714';
	color: #fff;
}

input[type='checkbox'].user_checkbox:checked:after {
	background: #999999;
	content: '\2714';
	color: #fff;
}
        </style>
    </head>

    <body cz-shortcut-listen="true">
        <main role="main" class="container">
            <section class="sheet padding-10mm" id="download_generated_document">
                <!-- <article class="sheet-header">
                    <div class="header-logo">
                        <h2 style="margin: 0;"><?php //echo $company_name; ?></h2>
                        <small>
                            <?php //echo $action_date; ?>: <b><?php //echo reset_datetime(array('datetime' => date('Y-m-d'), '_this' => $this)); ?></b><br>
                            <?php //echo $action_by; ?>: <b><?php //echo $employee_name; ?></b>
                        </small>
                    </div>
                    <div class="center-col">
                        <h2><?php //echo $document_title; ?></h2>
                    </div>
                </article> -->
                    
                <div class="main-content">
                    <div class="dashboard-wrp">
                        <div class="container-fluid">
                            <div class="row" id="jsContentArea" style="word-break: break-all;">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php if ($request_type == 'submitted' && $is_iframe_preview == 1) { ?>
                                        <canvas id="the-canvas" style="border:1px  solid black"></canvas>
                                    <?php } else { ?>
                                        <?php echo html_entity_decode($document_contant); ?>
                                    <?php } ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>    
            
        </main>

        <!-- Document loader -->
        <div id="document_loader" class="text-center my_loader" style="display: none;">
            <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
            <div class="loader-icon-box">
                <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate document view...
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
        <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>


        

        <script id="script">
            document.onreadystatechange = function () {
                var state = document.readyState
                if (state == 'interactive') {
                    $('#document_loader').show();
                } 
            }

            if($('#jsContentArea').find('select').length >= 0){
                $('#jsContentArea').find('select').map(function(i){
                    //
                    $(this).addClass('js_select_document');
                    $(this).prop('name', 'selectDD'+i);
                });
            }

            $(document).ready(function () {
                var perform_action = '<?php echo $perform_action; ?>';
                var request_type = '<?php echo $request_type; ?>';
                var is_iframe_preview = '<?php echo $is_iframe_preview; ?>';
                var imgs = $('#download_generated_document').find('img');
                var imgs_flag = 0;
                var completion_flag = 0;

                <?php if (isset($document['user_consent'])) { ?>
                    <?php if ($document['user_consent'] == 1 && !empty($document['form_input_data']) && $is_iframe_preview != 1) { ?>
                        var form_input_data = <?php echo $form_input_data; ?>;
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
                                        if (input_field_val == 'yes') {
                                            $('#'+input_field_id).prop('checked', true);;
                                        }
                                        $('#'+input_field_id).prop('disabled', true);
                                        
                                    } else if (input_type == 'textarea') {
                                        $('#'+input_field_id).hide();
                                        $('#'+input_field_id+'_sec').show();
                                        $('#'+input_field_id+'_sec').html(input_field_val);
                                    } else if(input_value[0].match(/select/) !== -1){
                                        if(input_value[1] != null){
                                            let cc =get_select_box_value(input_value[0],input_value[1]);
                                            $(`select.js_select_document[name="${input_value[0]}"]`).html('');  
                                            $(`select.js_select_document[name="${input_value[0]}"]`).hide(0);    
                                            $(`select.js_select_document[name="${input_value[0]}"]`).after(`<strong style="font-size: 20px;">${cc}</strong>`);  
                                        }
                                    }
                                }    
                            }); 
                        }
                    <?php } ?>   
                <?php } ?>

                function get_select_box_value(select_box_name, select_box_val) {
                    var data = select_box_val;
                    let cc = '';

                    if (select_box_val.indexOf(',') > -1) { 
                        data = select_box_val.split(','); 
                    }
                    

                    if($.isArray(data)) {
                        let modify_string = '';
                        $.each(data, function(key ,value) { 
                            if (modify_string == '') {
                                modify_string = ' '+$(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                            } else {
                                modify_string = modify_string + ', ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                            }
                        });
                        cc = modify_string;
                    } else {
                        cc = $(`select.js_select_document[name="${select_box_name}"] option[value="${select_box_val}"]`).text();
                    }
                    
                    return cc;
                }

                if(perform_action == 'download') {
                    if(imgs.length){
                        $(imgs).each(function(i,v) {
                            var imgSrc = $(this).attr('src');
                            var _this = this;
                            // if (imgSrc.indexOf('http') != -1 || imgSrc.indexOf('https') != -1 || imgSrc.indexOf('www') != -1 || imgSrc.indexOf('data:image/png:base64') != -1) { 
                            var p = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/gm; 

                            if (imgSrc.match(p)) { 
                                
                                imgs_flag = imgs_flag + 1;
                                $.ajax({
                                    url: '<?= base_url('hr_documents_management/getbase64/')?>',
                                    data:{
                                        url: imgSrc.trim()
                                    },
                                    type: "GET",
                                    async: false,
                                    success: function (resp){
                                        completion_flag = completion_flag + 1;
                                        resp = JSON.parse(resp);
                                        $(_this).attr("src", "data:"+resp.type+";base64,"+resp.string);
                                    },
                                    error: function(){

                                    }
                                });
                            }
                        });
                    }
                    
                    trigger_action();
                } else {
                    trigger_action();
                }    
                    
                if (request_type == 'submitted' && is_iframe_preview == 1) {
                    var document_base64 = '<?php echo isset($document["submitted_description"]) ? $document["submitted_description"] : ''; ?>';  
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

                    if(perform_action == 'print') {
                        $('#document_loader').hide();

                        printJS({
                            type: 'pdf',
                            base64: true,
                            showModal: true,
                            printable: res,
                            onPrintDialogClose: function () {
                                window.close();
                            }
                        });
                    }   
                }

                function trigger_action () {
                    if (completion_flag == imgs_flag) {
                        $('#document_loader').hide();
                        if(perform_action == 'download') {
                            download_document(); 
                        } else { 
                            if (is_iframe_preview == 0) {
                                print_document();
                            }  
                        }
                    }
                }
                
                function download_document () {
                    var document_type = '<?php echo $is_hybrid; ?>';
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
                        if(request_type == 'submitted' && is_iframe_preview == 1) {
                            pdf = '<?php echo $request_type == "submitted" && $is_iframe_preview == 1 ? $document_contant: ''; ?>';
                        } else {
                            pdf = data;
                        }
                       
                        $('#myiframe').attr("src",data);
                        kendo.saveAs({
                            dataURI: pdf,
                            fileName: '<?php echo $file_name.".pdf"; ?>',
                        });
                        //
                        setTimeout(() => {
                            if (document_type == "yes") {
                                var document_path = '<?php echo $document_path; ?>';
                                window.open(document_path, '_blank');
                                setTimeout(() => {
                                    window.close();
                                },5000)
                            } else {
                                window.close();
                            }
                            
                        }, 5000);
                    });
                }
                
                function print_document () {
                    window.print();
                }

            });

            window.onafterprint = function () {
                window.close();
            }
        </script>

        <style>
            #download_generated_document ul,
            #download_generated_document ol{
                padding-left:20px;
            }
        </style>
    </body>
</html>