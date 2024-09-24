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

<?php
    //
	$s3_file = isset($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : $document['document_s3_name'];
	//
    $d = get_required_url(
		$s3_file
	);
    //
    if($section == 'both'){
        stream_context_set_default(
            array(
                'http' => array(
                    'method' => 'HEAD'
                )
            )
        );
        //
        $cl = get_headers($d['preview_url'])[6];
        if(!$cl){
            $cl = get_headers($d['preview_url'])[6];
        }
        //
        $iframeSize = 
        ceil(
            trim(
                explode(
                    'content-length: ', strtolower($cl)
                )[1]
            ) / 2
        + 100
        ).'px';
        //
        sleep(4);
    }
    //
    if($type == 'original'){
        $text = preg_replace('/({{(.*)}})/', '_______________',  html_entity_decode($document['document_description']));
    }else{
        //
        $content = html_entity_decode($document['document_description']);
        //
        if($type == 'submitted'){
            $content = str_replace('{{sign_date}}', '<p><strong>'.date_with_time($document['signature_timestamp']).'</strong></p>', $content);
        }
        //
        $text = replace_tags_for_document(
            $company_sid, 
            $document['user_sid'], 
            $document['user_type'], 
            $content, 
            $document['document_sid'],
            0,
            $type != 'submitted' ? 0 : $document['signature_base64']
        );
    }
?>
<div style="margin-top: 30px;">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">

					<div class="panel panel-success">
						<div class="panel-heading hidden-print">
							<h3 class="panel-title"><?=$document['document_title'];?></h3>
						</div>
						<div id="download_generated_document">
                        <?php if($section == 'description' || $section == 'both') { ?>
							<div class="panel-body">
								<?=$text;?>
							</div>	
                        <?php } ?>
                        <?php if($section == 'both') { ?>
                            <iframe src="<?=$d['preview_url'];?>" frameborder="0" style="width: 100%; height: <?=$iframeSize;?>"></iframe>
                        <?php } ?>
						</div>
					</div>
			</div>
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

	if("<?=$action;?>" == 'download') {
        var imgs = $('#download_generated_document').find('img');
        var p = /((http|https):\/\/)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g;
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
            //
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
            // $('body').append('myiframe')
           
            // $('#myiframe').attr("src",data);
            kendo.saveAs({
                dataURI: pdf,
                fileName: '<?= $document['document_title'].'-'.date("m-d-Y").'.pdf'; ?>',
            });

            setTimeout(() => {
            window.close();
            }, 2000);
            
        });
    }
</script>

</body>
</html>